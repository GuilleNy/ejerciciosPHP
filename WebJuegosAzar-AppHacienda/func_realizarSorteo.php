<?php 
session_start();
include_once "conexionBaseDeDatos.php";
/*****************************PROGRAMA PRINCIPAL************************* */
if(isset($_POST['generar'])){
    //$_SESSION["numGand"]=generarCombinacion();
    $_SESSION["numGand"]="30-39-5-24-36-43-8";
    header("Location: ./realizarSorteo.php");
    exit();
}else if(isset($_POST['realizarSorteo'])){
    if(!empty($_POST['combGanadora'])){
        insertarCombinacionGand();
        repartirPremio();
                //unset($_SESSION["numGand"]);
                //unset($_SESSION['ganadores']);
        

        header("Location: ./realizarSorteo.php");
        exit();
    }else{
        $_SESSION['mensajeSorteoFail']="Debes generar una Combinacion Ganadora";
        header("Location: ./realizarSorteo.php");
        exit();
    } 
}
else if(isset($_POST['atras']))
{
    unset($_SESSION["numGand"]);
    header("Location: ./inicio.php");
    exit();
}   

//1.- Genero la combinacion ganadora con la funcion generarCombinacion() y lo llamo en el Programa principal
//cuando el usuario presione el boton "Generar".
/**************************************************************************************** */
/**************************GENERA LA COMBINACION GANADORA******************************** */
function generarCombinacion(){
    $numAleatorios=array();
    
    while(count($numAleatorios) < 6){
        $num=rand(1,49);
        if(!in_array($num, $numAleatorios)){
            $numAleatorios[]=$num;
        }
    }

    $numAleatorios[]=rand(0,9);

    $cadenaGan=implode("-", $numAleatorios);
    return $cadenaGan;
}
/********************************************************************************************/
//2.- Una vez que el usuario presione el boton "Generar", en el Programa princial, llamo a la 
//funcion insertarCombinacionGand() para actualizar la tabla sorteo  en el campo  COMBINACION_GANADORA, RECAUDACION_PREMIOS y ACTIVO.
/******************************************************************************************** */
/*FUNCION PARA INSERTAR LA COMBINACION GANADORA Y LA RECAUDACION_PREMIOS A LA TABLA sorteo*/
function insertarCombinacionGand(){
    $combinacion=$_SESSION["numGand"];
    $sorteoSele=$_POST['sorteo'];
    $numeroDelSorteo=recaudacionPremio($sorteoSele);
    //$valido=false;
    $conn=conexionBBDD();
    try{
        $conn->beginTransaction();
        $stmt = $conn->prepare("UPDATE sorteo SET COMBINACION_GANADORA = :combGanadora , RECAUDACION_PREMIOS=:recaud, ACTIVO = 'N'  WHERE NSORTEO = :numSorteo"); 
        $stmt->bindParam(':numSorteo', $sorteoSele);
        $stmt->bindParam(':recaud', $numeroDelSorteo);//actualiza la recaudacion para premios
        $stmt->bindParam(':combGanadora', $combinacion);//agrega la combinacion ganadora
        

        if($stmt->execute()){
            //$valido=true;
            $_SESSION['mensajeSorteo']="Sorteo realizado correctamente.";
        }
        $conn->commit();
    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }
    //return $valido;
}
/*OBTENGO LA RECAUDACION PARA PREMIOS*/
function recaudacionPremio($num){
    $cantidadTotalRec=obtenerRecaudacion($num);
    return ($cantidadTotalRec*0.50);//El 50% recaudación se dedica a premios.
}

/*OBTENGO LA RECAUDACION TOTAL*/
function obtenerRecaudacion($numeroSort){
    $conn=conexionBBDD();
    try{
       
        $stmt=$conn->prepare("SELECT RECAUDACION FROM sorteo WHERE NSORTEO=:numSorteo");
        $stmt->bindParam(':numSorteo', $numeroSort);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $numSorteos=$stmt->fetch();


    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack();  // Solo hacer rollBack si hay transacción activa
            }
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

    return  (float)$numSorteos['RECAUDACION'];

}
/********************************************************************************************************* */
//3.- Despues de llamamos a la funcion repartirPremio() en el Programa principal para gestionar lo que es la reparticion de la cantidad
//ganada por cada apostador y actualizar el saldo de este en su tabla, tambien se actualiza la tabla apuestas en el campo
// de IMPORTE_PREMIO y CATEGORIA_PREMIO.
/*********************************************INICIO***************************************************** */
/*********************************FUNCION PRINCIPAL PARA LA REPARTICION DE LOS PREMIOS A LOS APOSTADORES****************************************** */


function repartirPremio(){
    //premio de 500
    $todosLosGand=$_SESSION['ganadores'];//array(4) { ["6R"]=> array(1) { [0]=> int(1) } [5]=> array(2) { [0]=> int(3) [1]=> int(5) } ["5R"]=> array(1) { [0]=> int(4) } [6]=> array(1) { [0]=> int(6) } }
    $ganadores=calculoPremio(); //array(9) { ["6R"]=> float(126) [6]=> float(125) ["5R"]=> float(26) [5]=> float(25) ["4R"]=> int(0) [4]=> int(0) ["3R"]=> int(0) [3]=> int(0) ["R"]=> int(0) }

    foreach($todosLosGand as $categ=> $subArray){
            foreach($subArray as $indice => $NAPUESTA){
                if(isset($ganadores[$categ])){
                    $premioAsignado = $ganadores[$categ];
                    //echo "Al número de apuesta $NAPUESTA le corresponde un premio de $premioAsignado € en la categoría $categ ";
                    insertarPremio($categ, $NAPUESTA, $premioAsignado);
                }
            }
    }
    //var_dump($todosLosGand);
}

function calculoPremio(){
    $sorteoSele=$_POST['sorteo'];//S001
    $cantidadPremios=recaudacionPremio($sorteoSele);//500
    $premios = [ "6R" => 0, "6" => 0, "5R" => 0, "5" => 0, "4R" => 0, "4" => 0, "3R" => 0, "3" => 0, "R" => 0 ];
    $ganadores=numeroGandPorCatg(); //array(9) { ["6R"]=> int(1) [6]=> int(0) ["5R"]=> int(1) [5]=> int(2) ["4R"]=> int(0) [4]=> int(0) ["3R"]=> int(0) [3]=> int(0) ["R"]=> int(0) }


    foreach($ganadores as $catg => $cant){
        if ($cant != 0){
            if($catg == "6" || $catg == "6R"){
                $premio_base = ($cantidadPremios * 0.50) / ($ganadores["6"] + $ganadores["6R"]);
            } elseif($catg == "5" || $catg == "5R"){
                $premio_base = ($cantidadPremios * 0.15) / ($ganadores["5"] + $ganadores["5R"]);
            } elseif($catg == "4" || $catg == "4R"){
                $premio_base = ($cantidadPremios * 0.10) / ($ganadores["4"] + $ganadores["4R"]);
            } elseif($catg == "3" || $catg == "3R"){
                $premio_base = ($cantidadPremios * 0.05) / ($ganadores["3"] + $ganadores["3R"]);
            } else {
                $premio_base = 0;
            }
    
            // A cada categoría con reintegro le sumas 1 €
            if (str_ends_with($catg, "R")) {
                $premios[$catg] = $premio_base + 1;
            } else {
                $premios[$catg] = $premio_base;
            }
        }
    }
    
    //var_dump($premios);
    return $premios;
}

/*BBDD PARA INSERTAR LA CANTIDAD DE PREMIO GANADA*/
function insertarPremio($catg, $clave, $premio){
    $conn=conexionBBDD();
    try{
        $conn->beginTransaction();
        $stmt = $conn->prepare("UPDATE apuestas SET IMPORTE_PREMIO = :impPremio , CATEGORIA_PREMIO=:catgP WHERE NAPUESTA = :numAp"); 
        $stmt->bindParam(':numAp', $clave);
        $stmt->bindParam(':impPremio', $premio);//actualiza la recaudacion para premios
        $stmt->bindParam(':catgP', $catg);//agrega la combinacion ganadora
        
        if($stmt->execute()){
            actualizarSaldoPremioApos($conn, $clave);
        }
        $conn->commit();
    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }
}

function actualizarSaldoPremioApos($conn, $clave){
   
    try {
        // sin beginTransaction() aqui
        //Busca el premio e identificar el dni del apostante
        $stmt = $conn->prepare("SELECT DNI, IMPORTE_PREMIO 
                                FROM apuestas 
                                WHERE NAPUESTA = :numAp");
        $stmt->bindParam(':numAp', $clave);
        $stmt->execute();
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($datos) {
            $dni = $datos['DNI'];
            $premio = $datos['IMPORTE_PREMIO'];
            
            //Actualizo el saldo sumando el premio
            $stmtUpdate = $conn->prepare("UPDATE apostante 
                                          SET SALDO = SALDO + :premio 
                                          WHERE DNI = :dni");
            $stmtUpdate->bindParam(':premio', $premio);
            $stmtUpdate->bindParam(':dni', $dni);
            $stmtUpdate->execute();
        }

        
    } catch (PDOException $e) {
        throw $e; // Dejo que insertarPremio() se encargue
    }
}
/*************************************************************FIN******************************************************************************* */


function numeroGandPorCatg(){
    //$_SESSION["numGand"] --> "30-39-5-24-36-43-8"
    $premios = [ "6R" => 0, "6" => 0, "5R" => 0, "5" => 0, "4R" => 0, "4" => 0, "3R" => 0, "3" => 0, "R" => 0 ];// es un array que tiene en cada clave el numero de ganadores por categoria.
    $_SESSION['ganadores'] = array();//Es un array que como clave tiene la categoria y dentro de esa clave hay un sub array de el num de clave del apostante.
    $combGand=array_map('intval', explode("-", $_POST['combGanadora'])); //[30, 39, 5, 24, 36, 43, 8]
    $numReintegro=array_pop($combGand); //Combinacion ganadora 8 ; array_pop() se usa para quitar el último elemento de un array y devolverlo. También modifica el array original.

    $numeroAp=obetenerNumeroApost();//devuelve los 7 numeros de la apuesta ["30", "39", "5", "24", "36", "43", "8"] todo tipo string
                                    // el cual lo debo recorrer con un foreach
   

    foreach ($numeroAp as $clave => $subArray)  {
        $subArray=array_map('intval', $subArray);//[30, 39, 5, 24, 36, 43, 8]
        $reintegro=array_pop($subArray);// me deuelve el numero del reintegro del array.
        
        //var_dump($reintegro) ;
        $aciertos = count(array_intersect($subArray, $combGand));
        
        if($aciertos == 6){
            if($reintegro == $numReintegro){
                $_SESSION['ganadores']["6R"][] = $clave;
                $premios["6R"]++;
            }else{
                $_SESSION['ganadores']["6"][] = $clave;
                $premios["6"]++;
            }
        }else if($aciertos == 5){
            if($reintegro == $numReintegro){
                $_SESSION['ganadores']["5R"][] = $clave;
                $premios["5R"]++;
            }else{
                $_SESSION['ganadores']["5"][] = $clave;
                $premios["5"]++;
            }
        }else if($aciertos == 4){  
            if($reintegro == $numReintegro){
                $_SESSION['ganadores']["4R"][] = $clave;
                $premios["4R"]++;
            }else{
                $_SESSION['ganadores']["4"][] = $clave;
                $premios["4"]++;
            }

        }else if($aciertos == 3){
            if($reintegro == $numReintegro){
                $_SESSION['ganadores']["3R"][] = $clave;
                $premios["3R"]++;
            }else{
                $_SESSION['ganadores']["3"][] = $clave;
                $premios["3"]++;
            }

        }else if($aciertos == 0){
            if($reintegro == $numReintegro){
                $_SESSION['ganadores']["R"][] = $clave;
                $premios["R"]++;
            }
        }
    }
    
    //var_dump($_SESSION['ganadores']);
    //var_dump($premios);
    return $premios;
}


function obetenerNumeroApost(){
    $numSort=$_POST['sorteo'];
    $numeros=obetenerApuestas($numSort);
    $arrayApuest=array();
    
    foreach($numeros as $index => $apuesta){
        //lo que hago aqui es que ubico la clave NAPUESTA al nuevo arrayApuest y le asigno el valor con array_slice a partir del
        //1 al total del array para solo obtener los numero del voleto, excluyendo NAPUESTA.
        //Luego con array_values elimino las claves
        $arrayApuest[$apuesta['NAPUESTA']]=array_values(array_slice($apuesta, 1, count($apuesta)-1));//aqui puedo cambiar en count($apuesta) por un numero menos para obetenr solo los digitos sin el reintegro 
    }
    return $arrayApuest;
}

function obetenerApuestas($numSorte){
    $conn=conexionBBDD();
    try{
        $stmt=$conn->prepare("SELECT NAPUESTA ,N1, N2, N3, N4, N5, N6, R FROM apuestas WHERE NSORTEO=:numSort");
        $stmt->bindParam(':numSort', $numSorte);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $numSorteos=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack();  // Solo hacer rollBack si hay transacción activa
            }
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    return $numSorteos;
}


/*********************************************************************************FIN************************************************************* */
/************************************************************************************************************************************************* */




?>
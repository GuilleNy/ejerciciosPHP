<?php 
session_start();
include_once "conexionBaseDeDatos.php";
include_once "BBDD_realizarApuesta.php";

/*****************PROGRAMA PRINCIPAL************************* */
if(isset($_POST['realizarApuesta'])){

    //var_dump(count(recogerNuneros()));
    if(validar() && count(recogerNuneros())== 6){

        if(verificarSaldo()){
            //var_dump(ultimoNAPUESTA());
            //var_dump(recogerNuneros());
            ingresarNumerosApost();
            actualizarRecaudacionTotal();
            $_SESSION['mensajeApuesta']="Apuesta realizada";
            header("Location: ./realizarApuesta.php");
            exit();
            
        }else{
            $_SESSION['mensajeApuestaFail']="No tienes saldo en tu cuenta";
            header("Location: ./realizarApuesta.php");
            exit();
        }
    }else{
        $_SESSION['mensajeLlenarCampo']="Tienes que rellenar todos los campos sin que esten repetidos entre ellos.";
        header("Location: ./realizarApuesta.php");
        exit();
    }
        
    
}else if(isset($_POST['atras']))
{
    //unset($_SESSION["sort"]);
    header("Location: ./inicio.php");
    exit();
}   
/***************************************************************************************************/

function validar(){
    $enviar=true;
    if(empty(trim($_POST['n1']))){
        $enviar=false;
    }else if(empty(trim($_POST['n2']))){
        $enviar=false;
    }else if(empty(trim($_POST['n3']))){
        $enviar=false;
    }else if(empty(trim($_POST['n4']))){
        $enviar=false;
    }else if(empty(trim($_POST['n5']))){
        $enviar=false;
    }else if(empty(trim($_POST['n6']))){
        $enviar=false;
    }
    
    return $enviar;
}

function recogerNuneros(){
    $i=1;

    $numerosSelect=array();
    while(count($numerosSelect) < 6 && $i<=6){
        $num=$_POST["n$i"];
        if(!in_array($num, $numerosSelect)){
            $numerosSelect[]=$num;
        }
        $i++;
    }
    return $numerosSelect;
}
/***************************************************************************************************/

//listo
function verificarSaldo(){
//verifico que tenga saldo y si tiene le resto 1€ de su cuenta
    $correcto=false;
    $conn=conexionBBDD();
    $dniNum=$_SESSION['VstUsuario'];
    
    try{
        //$conn->beginTransaction(); 
        //no es necesario, las transacciones se usan generalmente para operaciones que modifican datos INSERT, UPDATE, DELETE.

        $stmt=$conn->prepare("SELECT SALDO FROM apostante WHERE DNI=:dni");
        $stmt->bindParam(':dni', $dniNum);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);// aqui le cambie el FETCH_ASSOC por FETCH_NUM para acceder por indice numerico
        $haySaldo=$stmt->fetch();

        if($haySaldo && $haySaldo['SALDO'] > 0){
            $correcto=true;
            $saldoTotal=$haySaldo['SALDO'] - 1;
            actualizarSaldoApost($dniNum, $saldoTotal);
        }

    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $correcto;
}
//listo
function actualizarSaldoApost($dniNum, $saldoTotal){
//inserto los datos de la apuesta y genero el numero del reintegro


    $conn=conexionBBDD();
    try{
        $conn->beginTransaction();
        $stmt = $conn->prepare("UPDATE apostante SET SALDO = :saldo WHERE DNI = :dni"); 
        $stmt->bindParam(':saldo', $saldoTotal);
        $stmt->bindParam(':dni', $dniNum);//actualiza la recaudacion para premios
        $stmt->execute();
        $conn->commit();
    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }
}

function ingresarNumerosApost(){
    $conn=conexionBBDD();
    $numApuesta=ultimoNAPUESTA();
    $dniNum=$_SESSION['VstUsuario'];
    $numSorteo=$_POST['sorteo'];
    $fecha=date('Y-m-d');
    $numApos=recogerNuneros();
    $r=rand(0,9);

    try{
        $conn->beginTransaction();
        $stmt = $conn->prepare("INSERT INTO apuestas (NAPUESTA, DNI, NSORTEO, FECHA, N1, N2, N3, N4, N5, N6, R) 
                                    VALUES (:numApuesta, :numdni, :numSorteo, :fecha, :n1, :n2, :n3, :n4, :n5, :n6, :r)"); 
        $stmt->bindParam(':numApuesta', $numApuesta);
        $stmt->bindParam(':numdni', $dniNum);
        $stmt->bindParam(':numSorteo', $numSorteo);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':n1', $numApos[0]);
        $stmt->bindParam(':n2', $numApos[1]);
        $stmt->bindParam(':n3', $numApos[2]);
        $stmt->bindParam(':n4', $numApos[3]);
        $stmt->bindParam(':n5', $numApos[4]);
        $stmt->bindParam(':n6', $numApos[5]);
        $stmt->bindParam(':r', $r);
        $stmt->execute();
            
        
        $conn->commit();
    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }
}

function ultimoNAPUESTA(){
    $conn=conexionBBDD();
    try{
        $stmt=$conn->prepare("SELECT NAPUESTA FROM apuestas ORDER BY NAPUESTA DESC LIMIT 1");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoDigito=$stmt->fetch();

    
        if($ultimoDigito){
            $cod= intval($ultimoDigito['NAPUESTA']);//aqui intval convierte la cadena a entero en este caso de 001 a 1
		    $nuevoNum=$cod+1;
            
        }else{
            $nuevoNum=1; 
        }
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    return $nuevoNum;
}

function actualizarRecaudacionTotal(){
    
    $numSorteo=$_POST['sorteo'];
    $conn=conexionBBDD();
    try {
        $conn->beginTransaction();

        // Obtener la recaudacion actual
        $stmt = $conn->prepare("SELECT RECAUDACION FROM sorteo WHERE NSORTEO = :numSorteo");
        $stmt->bindParam(':numSorteo', $numSorteo);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $nuevaRecaudacion =($resultado['RECAUDACION']) + 1;
            //$recaudacionPremios =  $nuevaRecaudacion * 0.50;
            //echo $recaudacionPremios;
            // Actualizar ambos campos
            $stmt = $conn->prepare("UPDATE sorteo SET RECAUDACION = :recaudacion WHERE NSORTEO = :numSorteo");
            $stmt->bindParam(':recaudacion', $nuevaRecaudacion);
            //$stmt->bindParam(':premios', $recaudacionPremios);
            $stmt->bindParam(':numSorteo', $numSorteo);
            $stmt->execute();
        }

        $conn->commit();
    } catch(PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack(); 
        }
        echo "Error: " . $e->getMessage();
    }
}

?>
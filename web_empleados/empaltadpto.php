
<?php
include "otras_funciones.php";
include_once "db/BBDD_empaltadpto.php";


if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {

        alta_dpto(); 
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['nombre_dpto'])) {
        $mensaje .= "El campo Nombre Departament esta vacio. <br>";
        $enviar = False;  
    }

    echo $mensaje;
    return $enviar;
}

function alta_dpto(){
    //try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombreDpto = depurar($_POST['nombre_dpto']);
        //$cod_dpto = obtenerUltimoCodigo($conn);
        $cod_dpto = "D006";

        $res = insertarDpto($conn, $cod_dpto, $nombreDpto);
       
        if(!$res['resultado']){
            $error = $res['stmt']->errorInfo();

            echo "Código MySQL: " . $error[1] . "<br>"; //Código interno de MySQL (1062)
            echo "Mensaje: " . $error[2] . "<br>"; // Mensaje completo del error

            if ($error[1] == 1062) { 
                echo "Registro duplicado detectado<br>";
            }
        }else{
            echo "Departamento " . $nombreDpto . " esta dado de alta.";
        }
            

        
        $conn->commit();
    /*
    }catch(PDOException $e)
        {
            $conn->rollBack(); 
            
            echo "Error: " . $e->getMessage() . "<br>";

             // Código de error (SQLSTATE)
            echo "Código de error: " . $e->getCode() . "<br>";
        }
    */
}


//Funcion que obtiene el ultimo codigo de departamento y le suma 1 para crear uno nuevo.
function obtenerUltimoCodigo($conn){

    try{    
       
        $ultimoID=consultaUltimoCod($conn);
        $nuevoID = "";

        if($ultimoID != False){
            $cod = intval(substr($ultimoID['ultimo_dpto'], 1));
            $nuevo_Num   = str_pad($cod + 1, 3, '0', STR_PAD_LEFT );
            $nuevoID ="D" . $nuevo_Num  ;
        }else{
            $nuevoID  = "D001";
        }
        return $nuevoID ;
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
}

function consultaUltimoCod($conn){
    $stmt = $conn->prepare("SELECT max(cod_dpto) 'ultimo_dpto' FROM departamento");
    $resultado = $stmt->execute();

   if ($resultado) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoID = $stmt->fetch();  // Asigna el resultado si todo va bien
    } else {
        $error = $stmt->errorInfo();
        echo "Error en consulta: " . $error[2];  // Mensaje del error
    }

    return $ultimoID;
}

function insertarDpto($conn, $cod_dpto, $nombreDpto){
    $stmt = $conn->prepare("INSERT INTO departamento (cod_dpto ,nombre_dpto) VALUES (:id_dpto,:nombre)");
    $stmt->bindParam(':id_dpto', $cod_dpto);
    $stmt->bindParam(':nombre', $nombreDpto);
    $resultado = $stmt->execute(); //ejecuta y guarda el resultado de true o false
    return ['stmt' => $stmt, 'resultado' => $resultado];
}
?>
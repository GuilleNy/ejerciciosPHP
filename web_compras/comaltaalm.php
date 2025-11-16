<?php
include "otras_funciones.php";
include "db/BBDD_empaltadpto.php";


if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {
        
        alta_almacen();
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['localidad'])) {
        $mensaje .= "El campo del Nombre de la Localidad esta vacio. <br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}



function alta_almacen(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $localidad = depurar($_POST['localidad']);
        $num_alma = obtenerUltimoCodigo($conn);

        $stmt = $conn->prepare("INSERT INTO almacen (NUM_ALMACEN , LOCALIDAD) VALUES (:id_almacen, :nombre)");
        $stmt->bindParam(':id_almacen', $num_alma);
        $stmt->bindParam(':nombre', $localidad);
        
        if($stmt->execute()){
            echo "Almacen en la Localidad de  " . $localidad . " esta dado de alta.";
        }

        $conn->commit();//importante para realizar cualquier accion de modificacion.

    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }

}
function obtenerUltimoCodigo($conn){

    try{    
        $stmt = $conn->prepare("SELECT NUM_ALMACEN  FROM almacen ORDER BY NUM_ALMACEN DESC LIMIT 1");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoID=$stmt->fetch();

        if($ultimoID){
            $cod = intval($ultimoID['NUM_ALMACEN']);
            $ultimoID = $cod + 1;
        }else{
            $ultimoID = 1;
        }
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $ultimoID;
}








?>
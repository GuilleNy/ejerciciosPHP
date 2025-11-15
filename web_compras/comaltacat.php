<?php
include "otras_funciones.php";
include "db/BBDD_empaltadpto.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (empty($_POST['nombre_cat'])) {
        echo "El campo está vacío.";
    } else {
    
        /*************************************RECOLECCION DE DATOS********************************************/
        
        /*****************************************************************************************************/
        
        /******************************************FUNCIONES**************************************************/
        alta_categoria();
        
        /*****************************************************************************************************/

    }      
        
}



function alta_categoria(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombreCategoria = depurar($_POST['nombre_cat']);
        $cod_catg = obtenerUltimoCodigo($conn);

        $stmt = $conn->prepare("INSERT INTO categoria (id_categoria ,nombre) VALUES (:id_categoria,:nombre)");
        $stmt->bindParam(':id_categoria', $cod_catg);
        $stmt->bindParam(':nombre', $nombreCategoria);
        
        if($stmt->execute()){
            echo "Categoria " . $nombreCategoria . " esta dado de alta.";
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
        $stmt = $conn->prepare("SELECT id_categoria  FROM categoria ORDER BY id_categoria DESC LIMIT 1");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoID=$stmt->fetch();

        if($ultimoID){
            $cod = intval(substr($ultimoID['id_categoria'], 1));
            $nuevo_Num = str_pad($cod + 1, 3, '0', STR_PAD_LEFT );
            $ultimoID="C" . $nuevo_Num;
        }else{
            $ultimoID = "C001";
        }
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $ultimoID;
}

?>
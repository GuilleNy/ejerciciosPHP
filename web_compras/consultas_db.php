<?php
include "db/BBDD_empaltadpto.php";


function obtenerCategorias(){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT ID_CATEGORIA, NOMBRE  FROM categoria");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_categorias=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $all_categorias;
}


?>
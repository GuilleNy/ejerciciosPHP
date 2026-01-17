<?php
include_once "db/BBDD_pedidos.php";

function comprobarLogin($usuario, $clave){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT customerNumber , contactLastName  
                                FROM customers 
                                WHERE customerNumber = :numCli 
                                AND contactLastName = :apellidoCli");
        $stmt->bindParam(':numCli', $usuario);
        $stmt->bindParam(':apellidoCli', $clave);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datos=$stmt->fetch();
        return $datos;
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        } 
}

function obtenerProductos(){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT productCode , productName , buyPrice
                                FROM products 
                                WHERE quantityInStock > 0");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_productos=$stmt->fetchAll();
        return $all_productos;
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    } 

}




?>


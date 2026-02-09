<?php


function obtenerProductos($conn){

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
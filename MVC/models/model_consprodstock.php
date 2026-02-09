<?php

function obtenerProductosStock($conn){

    try{    
        $stmt = $conn->prepare("SELECT productCode , productName
                                FROM products");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_productos=$stmt->fetchAll();
        return $all_productos;
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    } 

}

function consultarStockProducto($conn , $productCode){
    
    try{  
        $stmt = $conn->prepare("SELECT productName, quantityInStock
                                FROM products
                                WHERE productCode = :productCode");
        $stmt->bindParam(':productCode', $productCode);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stockProducto = $stmt->fetch();
        return $stockProducto;
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    } 
}





?>
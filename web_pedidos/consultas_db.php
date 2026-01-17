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



function consultaUltimaOrder(){
    $conn = conexion_BBDD();

    try{  
        $stmt = $conn->prepare("SELECT max(orderNumber) 'ultima_order' FROM orders");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimaOrder=$stmt->fetch();
        return $ultimaOrder;
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    } 
}

function crearOrders($orderNum , $customerNumber, $orderDate, $requiredDate){
    $conn = conexion_BBDD();
    $status = "Shipped";
    try{    
        $stmt = $conn->prepare("INSERT INTO orders (orderNumber, orderDate, requiredDate, `status` , customerNumber )
                                VALUES (:orderNumber, :orderDate, :requiredDate, :statuss, :customerNumber )");
        $stmt->bindParam(':orderNumber', $orderNum);
        $stmt->bindParam(':orderDate', $orderDate);
        $stmt->bindParam(':requiredDate', $requiredDate);
        $stmt->bindParam(':statuss', $status);
        $stmt->bindParam(':customerNumber', $customerNumber);
        $stmt->execute();

    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

function crearOrderDetails($orderNum , $productCode, $quantityOrdered, $priceEach, $orderLineNumber){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("INSERT INTO orderdetails (orderNumber, productCode, quantityOrdered, priceEach, orderLineNumber )
                                VALUES (:orderNumber, :productCode, :quantityOrdered, :priceEach, :orderLineNumber )");
        $stmt->bindParam(':orderNumber', $orderNum);
        $stmt->bindParam(':productCode', $productCode);
        $stmt->bindParam(':quantityOrdered', $quantityOrdered);
        $stmt->bindParam(':priceEach', $priceEach);
        $stmt->bindParam(':orderLineNumber', $orderLineNumber);
        $stmt->execute();

    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }

}

function actualizarCantidadProd($conn, $idProd, $cant){

    try{  
        $stmt = $conn->prepare("UPDATE products
                                SET quantityInStock = quantityInStock - :cantidad
                                WHERE productCode = :id_producto
                                AND quantityInStock > 0");
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':cantidad', $cant);
        $stmt->execute();
     
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}


?>


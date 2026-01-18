<?php


function comprobarLogin($conn, $usuario, $clave){
  
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



function consultaUltimaOrder($conn){


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

function crearOrders($conn, $orderNum , $customerNumber, $orderDate, $requiredDate){

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

function crearOrderDetails($conn, $orderNum , $productCode, $quantityOrdered, $priceEach, $orderLineNumber){

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

function actualizarCantidadProd($conn,  $idProd, $cant){

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

function crearPayments($conn, $customerNumber,$checkNumber, $paymentDate, $amount){

    try{    
        $stmt = $conn->prepare("INSERT INTO payments (customerNumber, checkNumber, paymentDate, amount )
                                VALUES (:customerNumber, :checkNumber, :paymentDate, :amount)");
        $stmt->bindParam(':customerNumber', $customerNumber);
        $stmt->bindParam(':checkNumber', $checkNumber);
        $stmt->bindParam(':paymentDate', $paymentDate);
        $stmt->bindParam(':amount', $amount);
        $stmt->execute();

    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

?>


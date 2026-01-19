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

function obtenerDatosCli($conn, $usuario){
    
    try{    
        $stmt = $conn->prepare("SELECT customerNumber, contactLastName , contactFirstName, addrebLine1, creditLimit
                                FROM customers 
                                WHERE customerNumber = :numCli");
        $stmt->bindParam(':numCli', $usuario);
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

    $status = "In Process";
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

function consultarClientes($conn, $customerNumber){
    try{  
        $stmt = $conn->prepare("SELECT customerNumber
                                FROM customers
                                WHERE customerNumber = :customerNumber");
        $stmt->bindParam(':customerNumber', $customerNumber);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $clientes=$stmt->fetch();
        return $clientes;
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    } 
}

function consultaOrdernCli($conn){
    $customerNumber = depurar($_POST['numCli']);
    try{  
        $stmt = $conn->prepare("SELECT orderNumber, orderDate, `status`
                                FROM orders
                                WHERE customerNumber = :customerNumber");
        $stmt->bindParam(':customerNumber', $customerNumber);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $pedidos=$stmt->fetchAll();
        return $pedidos;
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    } 
}

function consultaOrdernDetails($conn , $orderNumber){
    
    try{  
        $stmt = $conn->prepare("SELECT o.orderLineNumber, o.orderNumber, p.productName, o.quantityOrdered, o.priceEach
                                FROM orderdetails o, products p, orders d
                                WHERE p.productCode = o.productCode
                                AND o.orderNumber = d.orderNumber
                                AND  o.orderNumber = :orderNumber
                                ORDER BY o.orderLineNumber");
        $stmt->bindParam(':orderNumber', $orderNumber);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $pedidosDetallados = $stmt->fetchAll();
        return $pedidosDetallados;
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    } 
}

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


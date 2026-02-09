<?php

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

function consultaOrdernCli($conn, $customerNumber){
    
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




?>
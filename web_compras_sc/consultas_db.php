<?php

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

function obtenerAlmacenes(){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT NUM_ALMACEN , LOCALIDAD  FROM almacen");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_almacenes=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $all_almacenes;
}

function obtenerProductos(){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT ID_PRODUCTO , NOMBRE, PRECIO  FROM producto");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_productos=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $all_productos;
}

function obtenerClientes(){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT NIF , NOMBRE  FROM cliente");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_clientes=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $all_clientes;
}

function obtenerNIFCli(){
    $conn = conexion_BBDD();

    $nombreCli = $_SESSION['VstUsuario'];
    $clavecli = $_SESSION['VstContraseña'];

    try{    
        $stmt = $conn->prepare("SELECT NIF  
                                FROM cliente 
                                WHERE NOMBRE = :nombre
                                AND CLAVE = :clave");
        $stmt->bindParam(':nombre', $nombreCli);
        $stmt->bindParam(':clave', $clavecli);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $dato=$stmt->fetch();

    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
    return $dato['NIF'];
}


?>
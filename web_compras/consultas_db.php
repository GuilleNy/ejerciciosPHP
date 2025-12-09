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
        $stmt = $conn->prepare("SELECT ID_PRODUCTO , NOMBRE  FROM producto");
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



?>
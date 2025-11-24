<?php

function obtenerDpto(){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT cod_dpto, nombre_dpto  FROM departamento");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_dpto=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $all_dpto;
}

function obtenerEmpleados(){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT dni  FROM empleado");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_emp=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $all_emp;
}



?>
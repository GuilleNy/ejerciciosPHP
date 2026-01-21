<?php

function obtenerDatosCli($conn, $usuario){
  
    try{    
        $stmt = $conn->prepare("SELECT dni, nombre, apellidos, email  
                                FROM clientes 
                                WHERE email = :email");
        $stmt->bindParam(':email', $usuario);
    
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datos=$stmt->fetch();
        return $datos;
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        } 
}


?>
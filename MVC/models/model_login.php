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



?>
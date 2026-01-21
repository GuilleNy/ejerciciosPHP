<?php

function obtenerDatosCli($conn, $usuario, $clave){
  
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

function obtenerVuelos($conn){
  
    try{    
        $stmt = $conn->prepare("SELECT *  
                                FROM vuelos 
                                WHERE asientos_disponibles > 0 ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datos=$stmt->fetchAll();
        return $datos;
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        } 
}


function obtenerIdReserva($conn){
  
    try{    
        $stmt = $conn->prepare("SELECT MAX(id_reserva) as ultimo_id
                                FROM reservas ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datos=$stmt->fetch();
        return $datos;
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        } 
}


function actualizarCantidadAsientos($conn, $idVuelo, $numAsientos){

    try{  
        $stmt = $conn->prepare("UPDATE vuelos
                                SET asientos_disponibles = asientos_disponibles - :asientos
                                WHERE id_vuelo = :id_vuelo");
        $stmt->bindParam(':asientos', $numAsientos);
        $stmt->bindParam(':id_vuelo', $idVuelo);
        $stmt->execute();
     
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

function crearReserva($conn, $idReserva, $idVuelo, $dniCliente, $numAsientos, $precioTotal){

    try{    
        $stmt = $conn->prepare("INSERT INTO reservas (id_reserva, id_vuelo, dni_cliente, fecha_reserva,  num_asientos, preciototal)
                                VALUES (:id_reserva, :id_vuelo, :dni_cliente ,NOW(), :num_asientos, :preciototal)");
        $stmt->bindParam(':id_reserva', $idReserva);
        $stmt->bindParam(':id_vuelo', $idVuelo);
        $stmt->bindParam(':dni_cliente', $dniCliente);
        $stmt->bindParam(':num_asientos', $numAsientos);
        $stmt->bindParam(':preciototal', $precioTotal);
        $stmt->execute();

    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}



function obtenerReserva($conn, $dniCliente){
  
    try{    
        $stmt = $conn->prepare("SELECT id_reserva 
                                FROM reservas 
                                WHERE dni_cliente = :dni_cliente
                                ORDER BY id_reserva ASC");
         $stmt->bindParam(':dni_cliente', $dniCliente);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datos=$stmt->fetchAll();
        return $datos;
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        } 
}

function consulta($conn){
    try {
        $sql = 'SELECT v.id_aerolinea, v.origen, v.destino, v.fechahorasalida, v.fechahorallegada, r.num_asientos 
                FROM reservas r
                JOIN vuelos v ON r.id_vuelo = v.id_vuelo
                WHERE r.id_reserva = :id_reserva';
        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(':id_reserva', $_POST['reserva'], PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        trigger_error("Error en la obtencion de las facturas: " . $e->getMessage(), E_USER_ERROR);
    }
}
?>
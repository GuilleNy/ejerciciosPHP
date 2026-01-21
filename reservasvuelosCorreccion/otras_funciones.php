<?php 


function depurar($cadena){
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena; 
}
 
function low($cadena){
    return strtolower($cadena);
}

function recogerDatos(){
    $nomUsuario=depurar($_POST['usuario']);
    $contrUsuario=depurar($_POST['password']);
    return [$nomUsuario, $contrUsuario];
}


function verifica_campo_login(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['usuario'])) {
        $mensaje .= "El campo Usuario esta vacio.<br>";
        $enviar = False;  
    }

    if (empty($_POST['password'])) {
        $mensaje .= "El campo Clave esta vacio.<br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}

function verifica_campo_compra(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['vuelos'])){
        $mensaje .= "No se ha seleccionado un vuelo.<br>";
        $enviar = False; 
    }
    
    if (empty($_POST['asientos'])){
        $mensaje .= "El numero de asientos esta vacia.<br>";
        $enviar = False; 
    }else{ 
        if(intval(depurar($_POST['asientos'])) < 0 ){
            echo "Los asientos debe ser igual o mayor de 1.<br>";
        }
    }
    echo $mensaje;
    return $enviar;
}




function registrarCompra(){
    $conn = conexion_BBDD();

    try{
        $conn->beginTransaction();

        $idReserva = obtenerSiguienteID($conn);

        $dniCliente =  devolverDni(); //func_sesiones.php
   
        $cestaProductos = devolverCesta();
        foreach ($cestaProductos as $productos => $detalles) {
            $idVuelo = $detalles[0];
            $numAsientos = $detalles[7];
            $precioTotal = $detalles[8]; //3

            
            crearReserva($conn, $idReserva, $idVuelo, $dniCliente,$numAsientos, $precioTotal);
            actualizarCantidadAsientos($conn, $idVuelo, $numAsientos);
        }  

    $conn->commit();
    echo "Compra realizada con éxito.";
    }catch(PDOException $e){
        if ($conn->inTransaction()) {
            $conn->rollBack(); 
        }
        echo "Error: " . $e->getMessage();
    }
}

function obtenerSiguienteID($conn){

    try{    
        $ultimoID=obtenerIdReserva($conn);
        $nuevoID = "";

        if($ultimoID != False){
            $cod = intval(substr($ultimoID['ultimo_id'], 1));
            $nuevo_Num   = str_pad($cod + 1, 4, '0', STR_PAD_LEFT );
            $nuevoID ="R" . $nuevo_Num  ;
        }else{
            $nuevoID  = "R0001";
        }
        return $nuevoID ;
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
}



function verifica_campo_consultaCli(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['reserva'])){
        $mensaje .= "No se ha seleccionado una reserva.<br>";
        $enviar = False; 
    }

    echo $mensaje;
    return $enviar;
}

function consultaVuelo($conn){
    $consulta = consulta($conn);
    
    if($consulta != False)
    {
        echo "<div>";
            print '<table style="border:2px solid black; border-collapse:collapse;">';
                    echo "<tr>
                            <th>Aerolinea</th>
                            <th>origen</th>
                            <th>destino</th>
                            <th>salida</th>
                            <th>llegada</th>
                            <th>asientos</th>
                        </tr>";
                    
            
            foreach ($consulta as $row) {
                
                    echo "<tr>";
								echo "<td>" . htmlspecialchars($row['id_aerolinea']) . "</td>";
								echo "<td>" . htmlspecialchars($row['origen']) . "</td>";
								echo "<td>" . htmlspecialchars($row['destino']) . "</td>";
								echo "<td>" . htmlspecialchars($row['fechahorasalida']) . "</td>";
								echo "<td>" . htmlspecialchars($row['fechahorallegada']) . "</td>";
								echo "<td>" . htmlspecialchars($row['num_asientos']) . "</td>";
							echo "</tr>";

               
            }
             print "</table>";
                echo "<hr>";
            
        echo "</div>";
    }
}










?>
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
    $contrUsuario=depurar($_POST['clave']);
    return [$nomUsuario, $contrUsuario];
}


function registrarCompra(){
    $conn = conexion_BBDD();

    try{
        $conn->beginTransaction();

        $numCli =  devolverNumCli(); //func_sesiones.php
        $date = date("Y-m-d");
        $codigoOrder = obtenerUltimoCodigo(); //otras_funciones.php,   Obtengo el ultimo numero de orden 
        $cestaProductos = devolverCesta();
        $numeroPago = depurar($_POST['pago']);
        $importeTotal=precioTotalCesta();
        $orderLineNumber = 1;

        crearOrders($codigoOrder , $numCli,  $date, $date);
        
        foreach ($cestaProductos as $productos => $detalles) {
            $idProducto = $detalles[0];
            $priceEach = $detalles[2];
            $cantidadProd = $detalles[3]; //3
            
            crearOrderDetails($codigoOrder , $idProducto, $cantidadProd, $priceEach, $orderLineNumber);
            actualizarCantidadProd($idProducto, $cantidadProd);
            $orderLineNumber++;
        }  
        crearPayments($numCli, $numeroPago, $date, $importeTotal);
    $conn->commit();
    echo "Compra realizada con éxito.";
    }catch(PDOException $e){
        if ($conn->inTransaction()) {
            $conn->rollBack(); 
        }
        echo "Error: " . $e->getMessage();
    }
}


function verifica_campo_login(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['usuario'])) {
        $mensaje .= "El campo Usuario esta vacio.<br>";
        $enviar = False;  
    }

    if (empty($_POST['clave'])) {
        $mensaje .= "El campo Clave esta vacio.<br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}

function verifica_campo_altaPedido(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['producto'])){
        $mensaje .= "No se ha seleccionado un Producto.<br>";
        $enviar = False; 
    }
    
    if (empty($_POST['cantidad'])){
        $mensaje .= "La cantidad esta vacia.<br>";
        $enviar = False; 
    }else{ 
        if(intval(depurar($_POST['cantidad'])) < 0 ){
            echo "La cantidad debe ser igual o mayor de 1.<br>";
        }
    }
    echo $mensaje;
    return $enviar;
}

function obtenerUltimoCodigo(){

    $ultimoID=consultaUltimaOrder();
    $nuevoID = "";

    if($ultimoID != False){
        $cod = intval($ultimoID['ultima_order']);
        $nuevoID = $cod + 1;
    }else{
        $nuevoID = 10100;
    }
    return $nuevoID ;
}


function verificarPago(){
    $enviar = True;
    $mensaje = " ";

    $numeroPago = depurar($_POST['pago']);

    if(strlen($numeroPago) != 7){
        $enviar = False;
        $mensaje .= "La informacion de pago debe contener 7 caracteres.</br>";
    }else{
        $indice = 0;
        while($enviar && $indice < 2){
            if(!ctype_upper($numeroPago[$indice])){
                $enviar = False;
                $mensaje .= "Los primeros dos caracteres deben ser letras mayusculas.</br>";
            }
            $indice++;
        }

        while($enviar && $indice < strlen($numeroPago)){
            if(!ctype_digit($numeroPago[$indice])){
                $enviar = False;
                $mensaje .= "Los cincos digitos restantes deben ser numeros.</br>";
            }
            $indice++;
        }
    }
    
    if(!$enviar){
        echo $mensaje;
    }
    return $enviar;
}



?>
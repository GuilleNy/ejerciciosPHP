<?php
function crearSesion($usuario, $contraseña){
    session_start();
    $_SESSION["VstUsuario"]=$usuario;
    $_SESSION["VstContraseña"]=$contraseña;
    
}


function verificarSesion(){
    $sessionCreada=false;
    if(isset($_SESSION["VstUsuario"]) && isset($_SESSION["VstContraseña"])){
        $sessionCreada=true;
    }
    return $sessionCreada;
}

function crearCookie($nombreCookie, $valorCookie){
    setcookie($nombreCookie, $valorCookie, time() + (86400 * 30), "/");
}


function eliminarCookie($nombreCookie){
    setcookie($nombreCookie, "" , time() - (86400 * 30), "/");
}






function annadirCesta($producto, $cantProducto){
    $cadena = $producto . "|" . $cantProducto;
    
    $detallesProductos=explode("|", $cadena);

    if (isset($_SESSION["compra"])) {
        $cestaProducto = $_SESSION["compra"];
        $cestaProducto[] = $detallesProductos;
    } else {
        $cestaProducto = array();
        $cestaProducto[] = $detallesProductos;
    }

    $_SESSION["compra"] = $cestaProducto;
}

function devolverCesta()
{
    $cesta = null;
    if(isset($_SESSION["compra"]))
        $cesta = $_SESSION["compra"];
    return $cesta;
}

function vaciarCesta()
{
    unset($_SESSION["compra"]);

    foreach ($_COOKIE as $nombre => $valor) {
        if ($nombre !== session_name()) { //evito borrar la cookie de sesion
            setcookie($nombre, "", time() - 3600, "/");
        }
    }
}

function devolverNIF(){
    return $_SESSION["VstNIF"];
}

function precioTotalCesta(){
    $cesta = devolverCesta();
    $precioTotal = 0;

    if($cesta != null){
        foreach ($cesta as $productoCesta => $detalles) {
            $precioTotal += $detalles[2] * $detalles[3];
        }
    }
   
    return $precioTotal;
}

?>
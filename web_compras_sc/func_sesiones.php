<?php
function crearSesion($usuario, $contraseña){
    $_SESSION["VstUsuario"]=$usuario;
    $_SESSION["VstContraseña"]=$contraseña;
    
}

function eliminarSesionYRedirigir(){

    session_unset();
    session_destroy();
    
    setcookie("PHPSESSID", "" , time() - (86400 * 30), "/",$_SERVER['HTTP_HOST']);

    header("Location: ./comlogincli.php");
    exit;
}

function eliminarSesion(){
    session_unset();
    session_destroy();
    
    setcookie("PHPSESSID", "" , time() - (86400 * 30), "/",$_SERVER['HTTP_HOST']);
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
    
    $detallesProductos=explode("|", $producto);
    $detallesProductos[] = $cantProducto; // Cantidad del producto
    
    $detallesProductos[] = $detallesProductos[2] * $cantProducto;

    if (isset($_SESSION["compra"])) {
        $cestaProducto = $_SESSION["compra"];
        $encontrado = false;

        foreach ($cestaProducto as $key => $productoCesta) {
            if ($productoCesta[0] == $detallesProductos[0]) {
                // Si el producto ya existe en la cesta se actualiza la cantidad
                
                $cestaProducto[$key][3] += $detallesProductos[3];
                $cestaProducto[$key][4] += $detallesProductos[2] * $detallesProductos[3];
                $encontrado = true;
            }
        }

        if (!$encontrado) {
            // Si el producto no existe en la cesta se le añade
            $cestaProducto[] = $detallesProductos;
        }
        $_SESSION["compra"] = $cestaProducto;
    } else {
       // Si no hay cesta en la sesion, la creamos
        $cestaProducto = array();
        $cestaProducto[] = $detallesProductos;
        $_SESSION["compra"] = $cestaProducto;
    }

    
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
            $precioTotal += $detalles[4];
        }
    }
   
    return $precioTotal;
}

?>
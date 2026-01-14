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
        $encontrado = false;

        foreach ($cestaProducto as $key => $productoCesta) {
            if ($productoCesta[0] == $detallesProductos[0]) {
                // Si el producto ya existe en la cesta se actualiza la cantidad
                
                $cestaProducto[$key][3] += $detallesProductos[3];
                $cestaProducto[$key][2] += $detallesProductos[2] * $detallesProductos[3];
                $encontrado = true;
            }
        }

        if (!$encontrado) {
            // Si el producto no existe en la cesta se le añade
            $cestaProducto[] = $detallesProductos;
        }
        $_SESSION["compra"] = $cestaProducto;
    } else {
        $cestaProducto = array();
        $cestaProducto[] = $detallesProductos;
        for($i = 0; $i < count($cestaProducto); $i++){
            $cestaProducto[$i][2] = $cestaProducto[$i][2] * $cestaProducto[$i][3];
        }

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
            $precioTotal += $detalles[2];
        }
    }
   
    return $precioTotal;
}

?>
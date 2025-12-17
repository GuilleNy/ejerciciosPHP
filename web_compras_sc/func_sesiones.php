<?php
function crearSesion($usuario, $contraseña){
    $_SESSION["VstUsuario"]=$usuario;
    $_SESSION["VstContraseña"]=$contraseña;
    
}

function eliminarSesionYRedirigir(){
    session_destroy();
    session_unset();
    setcookie("PHPSESSID", "" , time() - (86400 * 30), "/",$_SERVER['HTTP_HOST']);
    header("Location: ./comlogincli.php");
}

function verificarSesion(){
    $sessionCreada=false;
    if(isset($_SESSION["VstUsuario"]) && isset($_SESSION["VstContraseña"])){
        $sessionCreada=true;
    }
    return $sessionCreada;
}


function annadirCesta($producto){
    
    $detallesProductos=explode("|", $producto);

    if (isset($_SESSION["compra"]["producto"])) {
        $cestaProducto = $_SESSION["compra"]["producto"];
        $cestaProducto[] = $detallesProductos;
    } else {
        $cestaProducto = array();
        $cestaProducto[] = $detallesProductos;
    }

    $_SESSION["compra"]["producto"] = $cestaProducto;
    

}

function devolverCesta()
{
    $cesta = null;
    if(isset($_SESSION["compra"]["producto"]))
        $cesta = $_SESSION["compra"]["producto"];
    return $cesta;
}

function vaciarCesta()
{
    unset($_SESSION["compra"]["producto"]);
}

?>
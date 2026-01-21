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

function iniciarSesion($conn, $usuario, $contra, $datos){
    //si no esta creada la sesion crearmela
    $fechaSistema = date("Y-m-d");
    var_dump($datos) ;
    if($contra != substr($datos['dni'], 0, 4)) {
                echo "Contraseña incorrecta";
            } else {         
                crearSesion($usuario, $contra);
                $_SESSION['usuario'] = $datos;
                $_SESSION['usuario']['fechaSistema'] = $fechaSistema;
                header("Location: vinicio.php");
                exit();
            }
    //Si la sesion del usario con su contraseña esta creada , esta puede acceder al inicio de la pagina
    if(verificarSesion()){
        header("Location: ./vinicio.php");
        exit(); 
    }
}



function crearCookie($nombreCookie, $valorCookie){
    setcookie($nombreCookie, $valorCookie, time() + (86400 * 30), "/");
}


function eliminarCookie($nombreCookie){
    setcookie($nombreCookie, "" , time() - (86400 * 30), "/");
}






function annadirCesta($vuelo, $numAsientos){
    
    $detallesVuelo=explode("|", $vuelo);
    $detallesVuelo[] = $numAsientos; // Cantidad del producto
    
    $detallesVuelo[] = $detallesVuelo[5] * $numAsientos;

    if (isset($_SESSION["compra"])) {
        $cestaProducto = $_SESSION["compra"];
        $encontrado = false;

        foreach ($cestaProducto as $key => $productoCesta) {
            if ($productoCesta[0] == $detallesVuelo[0]) {
                // Si el producto ya existe en la cesta se actualiza la cantidad
                
                $cestaProducto[$key][7] += $detallesVuelo[7]; //sumo la cantidad de asientos elegidos
                $cestaProducto[$key][8] += $detallesVuelo[5] * $detallesVuelo[7]; //hago el total de asientos elegidos y el precio
                $encontrado = true;
            }
        }

        if (!$encontrado) {
            // Si el producto no existe en la cesta se le añade
            $cestaProducto[] = $detallesVuelo;
        }
        $_SESSION["compra"] = $cestaProducto;
    } else {
       // Si no hay cesta en la sesion, la creamos
        $cestaProducto = array();
        $cestaProducto[] = $detallesVuelo;
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

function devolverDni(){
    return $_SESSION["usuario"]["dni"];
}



function precioTotalCesta(){
    $cesta = devolverCesta();
    $precioTotal = 0;

    if($cesta != null){
        foreach ($cesta as $productoCesta => $detalles) {
            $precioTotal += $detalles[5] * $detalles[7];
        }
    }
   
    return $precioTotal;
}



?>
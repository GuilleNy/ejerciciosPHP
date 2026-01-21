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

function iniciarSesion($usuario, $contra, $datos){
    //si no esta creada la sesion crearmela
    $fechaSistema = date("Y-m-d");
    var_dump($datos) ;
    if($contra != substr($datos['dni'], 0, 4)) {
                echo "Contraseña incorrecta";
            } else {         
                crearSesion($usuario, $contra);
                //Guardo la sesion de usuario sus datos y ademas la fecha actual del sistema
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


?>
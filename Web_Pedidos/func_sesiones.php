<?php
function crearSesion($usuario,$contraseña) //asignarVariablesSession
{
    $_SESSION["usuario"]=$usuario;
    $_SESSION["contraseña"]=$contraseña;
}

function eliminarSesionYRedirigir()
{
    session_destroy();
    session_unset();
    setcookie("PHPSESSID", "" , time() - (86400 * 30), "/",$_SERVER['HTTP_HOST']);
    header("Location: ./login.php");
}

function eliminarSesion()
{
    session_destroy();
    session_unset();
    setcookie("PHPSESSID", "" , time() - (86400 * 30), "/",$_SERVER['HTTP_HOST']);

}
function verificarSesion()
{
    $sessionCreada = false;

    if((isset($_SESSION["usuario"]) && isset($_SESSION["contrasena"])))
        $sessionCreada = true;

 return $sessionCreada;
}
?>
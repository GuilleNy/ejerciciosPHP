<?php
//listo
function crearSesion($usuario,$contraseña) //asignarVariablesSession
{
    $_SESSION["nombreUsu"]=$usuario;
    $_SESSION["contraUsu"]=$contraseña;
}
/*****************************************************************************************************/
//listo
function eliminarSesionYRedirigir()
{
    session_destroy();
    session_unset();
    setcookie("PHPSESSID", "" , time() - (86400 * 30), "/",$_SERVER['HTTP_HOST']);
    header("Location: ./pe_login.php");
}

/*****************************************************************************************************/
//listo
function verificarSesion()
{
    $sessionCreada = false;

    if(isset($_SESSION["nombreUsu"]) && isset($_SESSION["contraUsu"]))
    {
        $sessionCreada = true;
    }
 return $sessionCreada;
}
?>
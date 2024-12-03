<?php

/* Funcion para enviar los datos introducidos de manera segura */
function depurar($cadena)
{
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena;
}

/* Funcion para poder acceder a la base de datos y realizar consultas */
function baseDeDatos()
{
    $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname="usuario";
        $conn = null;

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

        return $conn;
}


/* Funcion para eliminar la cookies una vez que el usuario cierre sesion, de manera que lo redirigiremos a la pagina principal de login. */
function eliminarSesion()
{
    session_destroy();
    session_unset();
    setcookie("PHPSESSID", "" , time() - (86400 * 30), "/",$_SERVER['HTTP_HOST']);
    header("Location: ./login.php");
}

/*Esta funcion nos ayuda a verificar si el usuario tiene una cookies creada en el caso de que no, lo redirigiremos a la 
pagina principal de login */
function verificarSesion()
{
    $sesionCreada = false;
    if((isset( $_SESSION["usuario"]) && isset($_SESSION["contraseña"])))
         $sesionCreada = true;
    return $sesionCreada;
}


function asignarSesion($usuario,$contraseña)
{
    $_SESSION["usuario"] = $usuario;
    $_SESSION["contraseña"] = $contraseña;
}

function iniciarSesion()
{
    session_start();
}


?>
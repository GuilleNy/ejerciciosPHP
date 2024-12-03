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
function eliminarCookie()
{
    setcookie("nombreUsu", "", time() - (86400 * 30), "/");
    setcookie("contraUsu", "", time() - (86400 * 30), "/");
    header("Location: ./login.php");
}

/*Esta funcion nos ayuda a verificar si el usuario tiene una cookies creada en el caso de que no, lo redirigiremos a la 
pagina principal de login */
function verificarCookieExistente()
    {
        $cookiesCreadas = false;
        if((isset($_COOKIE["nombreUsu"]) && isset($_COOKIE["contraUsu"])))
            $cookiesCreadas = true;
        return $cookiesCreadas;
    }

?>
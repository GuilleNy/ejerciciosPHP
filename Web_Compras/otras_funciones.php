<?php


function depurar($cadena)
{
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena;
}


function conexionBBDD()
{   
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname="COMPRASWEB";
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
?>
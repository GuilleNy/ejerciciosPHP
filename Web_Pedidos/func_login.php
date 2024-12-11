<?php

include_once "otras_funciones.php";
include_once "func_sesiones.php";

/************************************************************************************* */
if (!isset($_SESSION['intentos']))
{
    $_SESSION['intentos']=4;
}

if($_SESSION['intentos'] == 1)
{
    die("Has alcanzado el limite de intentos.");
}

/************************************************************************************* */
//listo
function recogerDatos()
{
    $nomUsuario=depurar($_POST['nombreUsu']);
    $contUsuario= depurar($_POST['contraseña']) ;

    return [$nomUsuario, $contUsuario];//enviamos un array con los datos correspondientes
}
/****************************************************************************************** */
//verificamos usuario y contraseña
//listo
function verificarDatos($usuario , $contraseña)
{
    /*Llamamos la funcion baseDeDatos() para poder realizar consultas a la tabla */
    $conn=baseDeDatos();
    
    try
        {
            $stmt = $conn->prepare("SELECT customerNumber, contactLastName from customers where customerNumber = :usuario and contactLastName = :contra");
                $stmt->bindParam(':usuario', $usuario);
                $stmt->bindParam(':contra', $contraseña);
                $stmt -> execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $resultado=$stmt->fetch();

            if($resultado != null)
            {
                if($usuario == $resultado['customerNumber'] && $contraseña == $resultado['contactLastName'])
                {
                
                    iniciarSesion($usuario,$contraseña);
                    $_SESSION['intentos'] =4; 
                       
                    
                }else {
                    
                    $_SESSION['intentos']--; 
                    echo ("<p>Usuario o contraseña invalido</p>");
                    echo ("<p>Itentos restantes" .  $_SESSION['intentos'] ."</p>");
                    
                }      

            }else{
                $_SESSION['intentos']--; 
                echo ("<p>Usuario o contraseña invalido</p>");
                echo ("<p>Itentos restantes" . $_SESSION['intentos'] ."</p>");
            }
            
        }
        catch(PDOException $e)
        {
            $conn -> rollBack();//En el caso de la conexion no se de o haya un fallo , se realizara un rollback para no afectar la tabla .
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

}
/***************************************************************************************************** */
//listo
function iniciarSesion($usuario,$contraseña)
    {
        if (!(isset($_SESSION["nombreUsu"]) && isset($_SESSION["contraUsu"])))
        {
            crearSesion($usuario,$contraseña);

        }
    
        if(verificarSesion())
        {
            header("Location: ./pe_inicio.php");
        }
    }
?>
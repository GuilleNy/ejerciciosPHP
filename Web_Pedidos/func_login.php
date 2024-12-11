<?php

include_once "otras_funciones.php";
include_once "func_sesiones.php";



if (!isset($_SESSION['intentos']))
{
    $_SESSION['intentos']=0;
}

if($_SESSION['intentos'] >= 3)
{
    die("Has alcanzado el limite de intentos.");
}

/************************************************************************************* */

if (isset($_POST['login'])) 
{
    
    list($usuario,$contraseña)=recogerDatos();
    verificarDatos($usuario, $contraseña);
}

function recogerDatos()
{
    $nomUsuario=depurar($_POST['nombreUsu']);
    $contUsuario= depurar($_POST['contraseña']) ;

    return [$nomUsuario, $contUsuario];//enviamos un array con los datos correspondientes.
}
/****************************************************************************************** */
//verificamos que el nombre de usuario y su contraseña esten en la base de datos
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
                if(password_verify($contraseña, $resultado['contactLastName']) )
                {
                    crearCookie($usuario,$contraseña);
                    $_SESSION['intentos'] = 0; 
                    exit;
                }
                else
                {
                    $_SESSION['intentos']++; // Incrementa los intentos
                    
                }
            }else {
                
                $_SESSION['intentos']++; 
                header("Location: ./pe_login.php");
                
            }      
        }
        catch(PDOException $e)
        {
            $conn -> rollBack();//En el caso de la conexion no se de o haya un fallo , se realizara un rollback para no afectar la tabla .
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

}

function crearCookieSesion($usuario,$contraseña)
    {
        if (!(isset($_SESSION["nombreUsu"]) && isset($_SESSION["contraUsu"])))
        {
            session_start();
            crearSesion($usuario,$contraseña);
        }
    
        if(verificarSesion())
        {
            
            header("Location: ./pe_inicio.php");
        }
    }
?>
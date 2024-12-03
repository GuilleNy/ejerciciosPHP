<?php
include_once "otras_funciones.php";

/* En esta funcion recogemos los datos para poder enviarlos limpios y de manera segura,
utilizando la funcion depurar() que tenemos en otro fichero. */
function recogerDatos()
{
    $nomUsuario=depurar($_POST['nombreUsu']);
    $contUsuario= depurar($_POST['contraseña']) ;

    return [$nomUsuario, $contUsuario];//enviamos un array con los datos correspondientes.
}

//verificamos que el nombre de usuario y su contraseña esten en la base de datos
function verificarDatos($usuario , $contraseña)
{
    /*Llamamos la funcion baseDeDatos para poder realizar consultas a la tabla */
    $conn=baseDeDatos();

    try
        {
            $stmt = $conn->prepare("SELECT nomusuario,contraseña from usuarios where nomusuario = :usuario and contraseña = :contra");
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':contra', $contraseña);
            $stmt -> execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $resultado=$stmt->fetchAll();

            /*En el caso de que el usuario exista lo que hacemos es actualizar la fecha deacceso a la de hoy utilizando now()*/
            if($resultado != null)
            {
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->beginTransaction();
                $stmt = $conn->prepare("UPDATE usuarios set fecha_acc = now() where nomusuario = :usuario and contraseña = :contra");
                $stmt->bindParam(':usuario', $usuario);
                $stmt->bindParam(':contra', $contraseña);
                $stmt -> execute();
                $conn -> commit();
                crearCookie($usuario,$contraseña);
            }
            else
            {
                print "<h2>Las claves de acceso son incorrectas</h2>";
            }

        }
        catch(PDOException $e)
        {
            $conn -> rollBack();//En el caso de la conexion no se de o haya un fallo , se realizara un rollback para no afectar la tabla .
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

}

/*Creamos la cookies con los datos del usuario una vez verificado que existe en nuestra bases de datos.  */
function crearCookie($usuario,$contraseña)
    {

        $validoCookies=false;

        if (!(isset($_COOKIE["nombreUsu"]) && isset($_COOKIE["contraUsu"])))
        {
            setcookie("nombreUsu", $usuario, time() + (86400 * 30), "/");
            setcookie("contraUsu", $contraseña, time() + (86400 * 30), "/");
            $validoCookies=true;

        }
        else{
            echo "Cookie ya creada.";
        }

        if($validoCookies)
        {
            //una vez creado la cookies redirigimos al usuario a la pagina de web de aplicacion.
            header("Location: ./aplicacion.php");
        }
        
    }

?>
<?php
include_once "otras_funciones.php";
/* En esta funcion recogemos los datos para poder enviarlos limpios y de manera segura,
utilizando la funcion depurar() que tenemos en otro fichero. */
function recogerDatos()
{
    $usuario=depurar($_POST['nombreUsu']);
    $contraseña= depurar($_POST['contraseña']) ;

    return [$usuario, $contraseña];//enviamos un array con los datos correspondientes.
}

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
                crearSesion($usuario,$contraseña);
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

function crearSesion($usuario,$contraseña)
    {
        if (!(isset($_SESSION["usuario"]) && isset($_SESSION["contraseña"])))
        {
            iniciarSesion($usuario,$contraseña);
            asignarSesion($usuario,$contraseña);
        }

        if(verificarSesion())
        {
            //una vez creado la sesion redirigimos al usuario a la pagina de web de aplicacion.
            header("Location: ./aplicacion.php");
        }
        
    }


?>
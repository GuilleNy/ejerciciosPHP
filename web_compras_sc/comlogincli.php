
<?php
session_start();
include "otras_funciones.php";
include "func_sesiones.php";
include_once "db/BBDD_empaltadpto.php";
include_once "consultas_db.php";

echo '<pre>';
    print_r($_SESSION);
echo '</pre>';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login de Clientes</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Login de Clientes</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">
                    <div class="form-group">
                        <label for="usuario">Usuario:</label>
                        <input type="text" name="usuario" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="clave">Clave:</label>
                        <input type="text" name="clave" class="form-control">
                    </div>
                    <input type="submit" name="login" value="Login" class="btn btn-warning">
                    <input type="submit" name="registrarse" value="Registrarse" class="btn btn-warning">
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
/********************************** PROGRAMA PRINCIPAL ************************************/
if(isset($_POST['login'])){ 
    if (verifica_campo()) {
        list($usuario, $clave)=recogerDatos();
        if(verificarLogin($usuario, $clave)){
            iniciarSesion($usuario, $clave);
        }else{
            echo "Usuario o contraseña incorrecto.";
        }    
    }         
}else if(isset($_POST['registrarse']))
{
    header("Location: ./comregcli.php");
}
/************************************* FUNCIONES ******************************************/

//Funcion para verificar que los campos del formulario no esten vacios
function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['usuario'])) {
        $mensaje .= "El campo Usuario esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['clave'])) {
        $mensaje .= "El campo Clave esta vacio. <br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}

//Funcion para verificar el login del usuario
function verificarLogin($usuario, $clave){
    $enviar = False;
    $dato=datosUsuario($usuario, $clave);

    if((low($dato['NOMBRE']) == low($usuario)) && ($dato['CLAVE'] == $clave)){
        $enviar = True;
    }
    return $enviar;
}

//Funcion para depurar y recoger los datos del formulario
function recogerDatos(){
    $nomUsuario=depurar($_POST['usuario']);
    $contrUsuario=depurar($_POST['clave']);
    return [$nomUsuario, $contrUsuario];
}


//Funcion para obtener los datos del usuario de la BBDD
function datosUsuario($usuario, $clave){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT NOMBRE , CLAVE  
                                FROM cliente 
                                WHERE NOMBRE = :nombreCli 
                                AND CLAVE= :claveCli");
        $stmt->bindParam(':nombreCli', $usuario);
        $stmt->bindParam(':claveCli', $clave);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datos=$stmt->fetch();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $datos;
}

//Funcion para iniciar sesion y redirigir al inicio de la pagina
function iniciarSesion($usu, $contra){
    //si no esta creada la sesion crearmela
    if(!(isset($_SESSION["VstUsuario"]) && isset($_SESSION["VstContraseña"]))){
        crearSesion($usu, $contra);
        $_SESSION['VstNIF'] = obtenerNIFCli();
    }
    //Si la sesion del usario con su contraseña esta creada , esta puede acceder al inicio de la pagina
    if(verificarSesion()){
        header("Location: ./com_inicio_cli.php");
    }
}

?>
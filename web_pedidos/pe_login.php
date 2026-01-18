
<?php
include_once "db/BBDD_pedidos.php";
include "consultas_db.php";
include "func_sesiones.php";
include "otras_funciones.php";

echo '<pre>';
    print_r($_COOKIE);
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
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
if(isset($_POST['login'])){ 
    if (verifica_campo_login()) { //otras_funciones.php
        $conn = conexion_BBDD();
        list($usuario, $clave)=recogerDatos();//otras_funciones.php
        if(comprobarLogin($conn, $usuario, $clave)){ //consulta_db.php
            iniciarSesion($usuario, $clave);//func_sesiones.php
        }else{
            echo "Usuario o contraseña incorrecto.";
        }    
    }         
}




?>
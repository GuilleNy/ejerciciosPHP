<?php
session_start();
include_once "func_sesiones.php";


#Aqui verifico si la session sigue activa, en el caso de que NO esta vuelve a la pagina del login
if(!verificarSesion()) //func_sesiones.php
{
	header("Location: ./pe_login.php");
}

echo '<pre>';
    print_r($_SESSION);
echo '</pre>';
echo '<pre>';
    print_r($_COOKIE);
echo '</pre>';
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

    <form name="inicio"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>"  method="POST" class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <!-- Aplicación -->
            <div class="card border-success mb-3 mx-auto" style="max-width: 30rem;">
                <div class="card-header text-center">
                    <h2><b>Elegir Acción</b></h2>
                </div>
                <div class="card-body text-center">
                    <div>
                        <a href="pe_altaped.php" class="btn btn-primary">Alta Pedidos</a>
                    </div>
                    <hr>
                  
                    <div>
                        <input type="submit" value="Consultar Compras" name="consultarCompras" class="btn btn-primary">
                    </div>
                </div>
                <div class="card-footer text-center">
                    <div>
                        <a href="comlogoutcli.php" class="btn btn-warning">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>



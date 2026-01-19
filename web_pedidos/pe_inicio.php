<?php
session_start();
include_once "func_sesiones.php";
include_once "db/BBDD_pedidos.php";


#Aqui verifico si la session sigue activa, en el caso de que NO esta vuelve a la pagina del login
if(!verificarSesion()) //func_sesiones.php
{
	header("Location: ./pe_login.php");
}

echo '<pre>';
    print_r($_SESSION);
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

    <div class="container">
        <!-- Aplicación -->
        <div class="card border-success mb-3 mx-auto" style="max-width: 30rem;">
            <div class="card-header text-center">
                <h2><b>Elegir Acción</b></h2>
                <B>Numero Cliente:</B>  <?php echo $_SESSION["DatosUsuario"]['customerNumber']; ?>  <BR>
                <B>Nombre Cliente:</B>  <?php echo $_SESSION["DatosUsuario"]['contactFirstName']." ".$_SESSION['DatosUsuario']['contactLastName']; ?>  <BR>
                <B>Credito Limite:</B>  <?php echo $_SESSION["DatosUsuario"]['creditLimit']; ?>  <BR><BR>
            </div>
           
            <div class="card-body text-center">
                <div>
                    <a href="pe_altaped.php" class="btn btn-primary">Alta Pedidos</a>
                    <a href="pe_consped.php" class="btn btn-primary">Consultar Compras</a>
                    <a href="pe_consprodstock.php" class="btn btn-primary">Consultar Stock</a>
                </div>
            
            </div>
            <div class="card-footer text-center">
                <div>
                    <a href="comlogoutcli.php" class="btn btn-warning">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>



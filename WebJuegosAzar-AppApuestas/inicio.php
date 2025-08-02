<?php
session_start();
include_once "func_sesiones.php";
include_once "func_inicio.php";
if(!verificarSesion())
{
	header("Location: ./login.php");
}
print_r($_SESSION);
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

    <form name="inicio" action="" method="POST" class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <!-- Aplicación -->
            <div class="card border-success mb-3 mx-auto" style="max-width: 30rem;">
                <div class="card-header text-center">
                    <h2><b>Elegir Acción</b></h2>
                </div>
                <div class="card-body text-center">
                    <div>
                        <input type="submit" value="Realizar Apuesta" name="realizarApuesta" class="btn btn-primary">
                    </div>
                    <hr>
                    <div>
                        <input type="submit" value="Cargar Saldo" name="cargarSaldo" class="btn btn-primary">
                    </div>
                    <hr>
                    <div>
                        <input type="submit" value="Consultar Apuesta" name="consultarApuesta" class="btn btn-primary">
                    </div>
                </div>
                <div class="card-footer text-center">
                    <div>
                        <input type="submit" value="Cerrar Sesión" name="logout" class="btn btn-warning">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php

    
    
        if(isset($_POST['logout'])){
            if(isset($_SESSION["VstUsuario"]) && isset($_SESSION["VstContraseña"])){
				eliminarSesionYRedirigir();
			}
        }  

    ?>   


</body>
</html>
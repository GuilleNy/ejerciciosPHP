<?php
session_start();
include_once "db/BBDD_pedidos.php";
include_once "consultas_db.php";
include_once "func_sesiones.php";
include "otras_funciones.php";


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
$conn = conexion_BBDD();

?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consultar Pedidos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        <label for="producto">Numero Cliente:</label>
                        <input type="text"  name="numCli"  class="form-control" >
                    </div>

                    <BR>
                    <div class="card-footer text-center">
                        <div>
                            <input type="submit" name="consultar" value="Realizar Consulta"  class="btn btn-warning ">
                            <input type="submit" name="atras" value="Atras" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    
    
</body>
</html>

<?php

if(isset($_POST['consultar'])){
    if(verifica_campo_consultaCli()){
        if(verificarNumCli($conn)){
            mostrarConsultas($conn);
        }else{
            echo "El numero de cliente no existe.";
        }
    }
}else if(isset($_POST['atras'])){
    header("Location: ./pe_inicio.php");
    exit();
}    
?>
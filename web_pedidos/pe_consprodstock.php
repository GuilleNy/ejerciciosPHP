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

$conn = conexion_BBDD();

if(isset($_POST['consultarStock'])){
    if(verificarCampoProducto()){
        
        $productCode = depurar($_POST['producto']);
        mostrarStock($conn, $productCode); //otras_funciones.php
        
    }
}else if(isset($_POST['atras'])){
    header("Location: ./pe_inicio.php");
    exit();
}   



?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consultar Stock</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">
                    <div class="card-header text-center">
                        <B>Numero Cliente:</B>  <?php echo $_SESSION["DatosUsuario"]['customerNumber']; ?>  <BR>
                        <B>Nombre Cliente:</B>  <?php echo $_SESSION["DatosUsuario"]['contactFirstName']." ".$_SESSION['DatosUsuario']['contactLastName']; ?>  <BR>
                        <B>Credito Limite:</B>  <?php echo $_SESSION["DatosUsuario"]['creditLimit']; ?>  <BR><BR>
                    </div>
                    <div class="form-group">
                        <B>Producto:</B>
                        <select name="producto" class="form-control">
                            <option value="" disabled selected>-- Selecciona un Producto --</option>
                            <?php
            
                                $producto= obtenerProductosStock($conn);
                                foreach ($producto as $fila) {
                                    echo "<option value=\"" . $fila['productCode'] . "\">" . $fila['productName'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <BR>
                    <div class="card-footer text-center">
                        <div>
                            <input type="submit" name="consultarStock" value="Consultar Stock"  class="btn btn-warning ">
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


?>
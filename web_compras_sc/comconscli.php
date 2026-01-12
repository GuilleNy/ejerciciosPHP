<?php
session_start();
include "otras_funciones.php";
include_once "db/BBDD_empaltadpto.php";
include_once "consultas_db.php";
include_once "func_sesiones.php";

if(!verificarSesion())
{
	header("Location: ./comlogincli.php");
}
echo "<pre>";
    print_r($_SESSION);
echo "</pre>";


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consulta de Productos</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Consulta de Productos</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        <label for="producto">Desde:</label>
                        <input type="date" name="fechaDesde" class="form-control" >
                    </div>

                    <div class="form-group">
                        <label for="producto">Hasta:</label>
                        <input type="date" name="fechaHasta" class="form-control" >
                    </div>
                    <BR>
                    <div>
                        <input type="submit" name="consultar" value="Consultar" class="btn btn-warning">
                        <input type="submit" name="atras" value="Atras" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
        $cesta = devolverCesta();
        $precioTotal = precioTotalCesta();
        if($cesta != null)
        {
            echo "<div id='cesta'>";
            print '<table class="table table-bordered table-hover table-sm text-nowrap"><tr><th>Id Producto</th><th>Nombre Producto</th><th>Cantidad</th><th>Precio</th></tr>';
            
            foreach ($cesta as $productoCesta => $detalles) {
                print "<tr><td>".$detalles[0]."</td><td>".$detalles[1]."</td><td>".$detalles[3]."</td><td>".$detalles[2]."</td></tr>";
            }
            print "</tr>";
            print "<tr><td colspan='3'><strong>Precio Total:</strong></td><td><strong>" . $precioTotal . " €</strong></td></tr>";
            echo "</div>";
        }
    ?>
</body>


</html>


<?php

if(isset($_POST['consultar'])){
    if(verifica_campo()){
        if(comprobarCantidad()){
            $producto = $_POST['producto'];
            $cantProducto = depurar($_POST['cantidad']);
            annadirCesta($producto, $cantProducto);//func_sesiones.php
            header("Refresh: 0");
        } 
    }
}else if(isset($_POST['atras'])){
    header("Location: ./com_inicio_cli.php");
}

#Funcion que verifica que se haya seleccionado un producto.
function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if(empty($_POST['fechaDesde'])){
        $mensaje .= "No se ha seleccionado la Fecha de Inicio.<br>";
        $enviar = False; 
    }
    if(empty($_POST['fechaHasta'])){
        $mensaje .= "No se ha seleccionado la Fecha de Fin.<br>";
        $enviar = False; 
    } 
    echo $mensaje;
    return $enviar;
}


function consultarProductos(){
    $conn = conexion_BBDD();

    try{
        $conn->beginTransaction();

        $nif =  devolverNIF();
        $fecha = date("Y-m-d");
        

    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }
}




?>
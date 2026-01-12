<?php
session_start();
include "otras_funciones.php";
include_once "db/BBDD_empaltadpto.php";
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
</body>
</html>

<?php

if(isset($_POST['consultar'])){
    if(verifica_campo()){
        visualizarCompras();
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

function visualizarCompras(){
    $nif = devolverNIF();
    $fecha_desde =depurar($_POST['fechaDesde']);   
    $fecha_hasta =depurar($_POST['fechaHasta']);
   
    $all_productos = obtenerCompras($nif , $fecha_desde, $fecha_hasta);

    echo "<table class='table table-striped w-50 mx-auto'>";
        echo "<tr>
                <th>Producto</th>
                <th>Fecha</th>
                <th>Unidades</th>
              </tr>";
        // Recorrer filas
        if(empty($all_productos)){
            echo "No hay datos en esas fechas";
            
        }else{
            foreach ($all_productos as $fila) {
                echo "<tr>";
                echo "<td>" . $fila['nombre'] . "</td>";
                echo "<td>" . $fila['fechaCompra'] . "</td>";
                echo "<td>" . $fila['unidades'] . "</td>";
                echo "</tr>";
            }
        }
}

function obtenerCompras($nif, $fecha_desde, $fecha_hasta){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT p.NOMBRE 'nombre', c.FECHA_COMPRA 'fechaCompra', c.UNIDADES 'unidades'
                                FROM compra c, producto p
                                WHERE c.ID_PRODUCTO = p.ID_PRODUCTO
                                AND  c.NIF = :nif_cliente
                                AND c.FECHA_COMPRA BETWEEN :fecha_desde AND :fecha_hasta");
        $stmt->bindParam(':nif_cliente', $nif);
        $stmt->bindParam(':fecha_desde', $fecha_desde);
        $stmt->bindParam(':fecha_hasta', $fecha_hasta);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_productos=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $all_productos;
}


?>
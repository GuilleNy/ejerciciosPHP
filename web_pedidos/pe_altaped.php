
<?php
session_start();
include_once "consultas_db.php";
include "otras_funciones.php";
include_once "func_sesiones.php";

if(!verificarSesion())
{
	header("Location: ./pe_login.php");
}
echo '<pre>';
    print_r($_SESSION);
echo '</pre>';

echo '<pre>';
    print_r($_COOKIE);
echo '</pre>';


/********************************** PROGRAMA PRINCIPAL ************************************/


if(isset($_POST['añadirCesta'])){
    if(verifica_campo_altaPedido()){
        
        $producto = $_POST['producto'];
        $cantProducto = depurar($_POST['cantidad']);
        annadirCesta($producto, $cantProducto);//func_sesiones.php
        header("Refresh: 0");
        exit();
        
    }
}else if(isset($_POST['pedido'])){
    registrarCompra();
    vaciarCesta();
    

    #$importeTotal=precioTotalCesta();//func_sesiones.php 
}else if(isset($_POST['vaciar'])){
    vaciarCesta();
    header("Refresh: 0");
}else if(isset($_POST['atras'])){
    header("Location: ./pe_inicio.php");
    exit();
}

function registrarCompra(){
    $conn = conexion_BBDD();

    try{
        $conn->beginTransaction();

        $numCli =  devolverNumCli(); //func_sesiones.php
        $date = date("Y-m-d");
        $codigoOrder = obtenerUltimoCodigo(); //otras_funciones.php,   Obtengo el ultimo numero de orden 
        $cestaProductos = devolverCesta();
        $orderLineNumber = 1;

        if($cestaProductos != null){
            crearOrders($codigoOrder , $numCli,  $date, $date);
            
            foreach ($cestaProductos as $productos => $detalles) {
                $idProducto = $detalles[0];
                $priceEach = $detalles[2];
                $cantidadProd = $detalles[3]; //3
                
                crearOrderDetails($codigoOrder , $idProducto, $cantidadProd, $priceEach, $orderLineNumber);
                
                $orderLineNumber++;
            }  
            echo "Compra realizada con éxito.";
        }else{
            echo "La cesta esta vacia, no se puede registrar la compra.<br>";
        }
    $conn->commit();
    }catch(PDOException $e){
        if ($conn->inTransaction()) {
            $conn->rollBack(); 
        }
        echo "Error: " . $e->getMessage();
    }
}


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Compra de Productos</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Compra de Productos</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        <label for="producto">Producto:</label>
                        <select name="producto" class="form-control">
                            <option value="" disabled selected>-- Selecciona un Producto --</option>
                            <?php
                                $producto= obtenerProductos();
                                foreach ($producto as $fila) {
                                    echo "<option value=\"" . $fila['productCode'] ."|". $fila['productName'] ."|". $fila['buyPrice'] . "\">" . $fila['productName'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="producto">Cantidad:</label>
                        <input type="number" name="cantidad" min="1" value="1" class="form-control" >
                    </div>
                    <BR>
                    <div>
                        <input type="submit" name="añadirCesta" value="Añadir a la cesta" class="btn btn-warning">
                        <input type="submit" name="vaciar" value="Vaciar Cesta"  class="btn btn-warning"> 
                        <input type="submit" name="pedido" value="Realizar Pedido"  class="btn btn-warning ">
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
                print "<tr><td>".$detalles[0]."</td><td>".$detalles[1]."</td><td>".$detalles[3]."</td><td>".$detalles[4]."</td></tr>";
            }
            print "</tr>";
            print "<tr><td colspan='3'><strong>Precio Total:</strong></td><td><strong>" . $precioTotal . " €</strong></td></tr>";
            echo "</div>";
        }
    ?>
</body>


</html>


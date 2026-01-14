
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
echo '<pre>';
    print_r($_SESSION);
echo '</pre>';

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
                                    echo "<option value=\"" . $fila['ID_PRODUCTO'] ."|". $fila['NOMBRE'] ."|". $fila['PRECIO'] . "\">" . $fila['NOMBRE'] . "</option>";
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
/********************************** PROGRAMA PRINCIPAL ************************************/
if(isset($_POST['añadirCesta'])){
    if(verifica_campo()){
        /*
        if(comprobarCantidad()){
        */
            $producto = $_POST['producto'];
            $cantProducto = depurar($_POST['cantidad']);
            annadirCesta($producto, $cantProducto);//func_sesiones.php
            header("Refresh: 0");
        /*
        } 
        */
    }
}else if(isset($_POST['pedido'])){
    registrarCompra();
    vaciarCesta();
    #$importeTotal=precioTotalCesta();//func_sesiones.php 
}else if(isset($_POST['vaciar'])){
    vaciarCesta();
    header("Refresh: 0");
}else if(isset($_POST['atras'])){
    header("Location: ./com_inicio_cli.php");
}

/************************************* FUNCIONES ******************************************/

//Funcion que verifica que se haya seleccionado un producto.
function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['producto'])){
        $mensaje .= "No se ha seleccionado un Producto.<br>";
        $enviar = False; 
    }    
    echo $mensaje;
    return $enviar;
}

//Funcion que comprueba si la cantidad solicitada del producto seleccionado esta disponible en los almacenes.
/*
function comprobarCantidad(){
    $valido = True;
    $idProducto = depurar(obtenerCodProd($_POST['producto'])); 
    $cantProducto = depurar($_POST['cantidad']);
    $cantidadTotal = obtenerCantidadTotal($idProducto);
    $stockDisponible = 0;

   // Stock disponible en la cookie
    if (!isset($_COOKIE[$idProducto])) {
        $stockDisponible = $cantidadTotal;
    } else {
        $stockDisponible = $_COOKIE[$idProducto];
    }
    //compara el stock disponible del producto con la cantidad que pide el cliente
    if ($stockDisponible < $cantProducto) {
        echo "No hay suficientes unidades del producto seleccionado en los almacenes.<br>";
        crearCookie($idProducto, 0);
        $valido = False;
    }else{
         $nuevoStock = $stockDisponible - $cantProducto;
        crearCookie($idProducto, $nuevoStock);
    }

    // Actualizar cookie con el stock restante
    return $valido;
}
*/
//Funcion que obtiene la cantidad total disponible de un producto en todos los almacenes.
function obtenerCantidadTotal($idProd){
    $conn = conexion_BBDD();
    
    try{    
        $stmt = $conn->prepare("SELECT sum(CANTIDAD) as cantidadTotal 
                                FROM almacena
                                WHERE ID_PRODUCTO = :id_producto
                                AND CANTIDAD > 0");
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $unidades=$stmt->fetch();
        return intval($unidades['cantidadTotal']);
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

//Funcion que obtiene el codigo del producto a partir de la cadena recibida desde el formulario en donde se selecciona el producto.
function obtenerCodProd($cadena){
    $arrayDatos = explode("|", $cadena);
    return $arrayDatos[0];
}

//Funcion que registra la compra en la tabla compra y actualiza los stocks en la tabla almacena
function registrarCompra(){
    $conn = conexion_BBDD();

    try{
        $conn->beginTransaction();

        $nif =  devolverNIF();
        $fecha = date("Y-m-d");
        $cestaProductos = devolverCesta();

        if($cestaProductos != null){
            foreach ($cestaProductos as $productos => $detalles) {
                $idProducto = $detalles[0];
                $cantidadProd = $detalles[3]; //3
        
                if (!verificarDuplicado($conn, $nif, $idProducto, $fecha)) {
                    //si no existe la compra se hara un insert
                    insertarCompra($conn, $nif, $idProducto, $fecha, $cantidadProd);
                    echo "Compra realizada con éxito.";
                    
                } else {
                    // y si ya existe la compra se actualiza sumando las unidades
                    actualizarCompra($conn, $nif, $idProducto, $fecha, $cantidadProd);
                    echo "Compra realizada con éxito.";
                }

                //actualizar el stock en la tabla almacena
                $almacenes = almacenConStocks($idProducto, $conn);
                actualizarCantidadAlmacena($conn, $almacenes['NUM_ALMACEN'], $idProducto, $cantidadProd);
        
            }
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

//Funcion que inserta una nueva compra en la tabla compra
function insertarCompra($conn, $nifCli , $idProd , $fechaCompr, $unidades){
    try{    
        $stmt = $conn->prepare("INSERT INTO compra (NIF, ID_PRODUCTO, FECHA_COMPRA, UNIDADES)
                                VALUES (:nifCli, :id_producto, :fechaCompra, :unidades)");
        $stmt->bindParam(':nifCli', $nifCli);
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':fechaCompra', $fechaCompr);
        $stmt->bindParam(':unidades', $unidades);
        $stmt->execute();

    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

//Funcion que actualiza la cantidad de unidades en una compra ya existe en la tabla compra
function actualizarCompra( $conn, $nifCli , $idProd , $fechaCompr, $unidades){
    try{    
        $stmt = $conn->prepare("UPDATE compra
                                SET UNIDADES = UNIDADES + :unidades
                                WHERE NIF = :nifCli
                                AND ID_PRODUCTO = :id_producto
                                AND FECHA_COMPRA = :fechaCompra");
        $stmt->bindParam(':nifCli', $nifCli);
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':fechaCompra', $fechaCompr);
        $stmt->bindParam(':unidades', $unidades);
        $stmt->execute();
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

//Funcion que obtiene los almacenes que tienen stock del producto seleccionado
function almacenConStocks($idProducto, $conn ){
     try{    
        $stmt = $conn->prepare("SELECT NUM_ALMACEN, CANTIDAD 
                                FROM almacena 
                                WHERE ID_PRODUCTO = :idProd
                                AND CANTIDAD > 0");
        $stmt->bindParam(':idProd', $idProducto);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datosAlmacen=$stmt->fetch();
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
    return $datosAlmacen;
}

//Funcion que verifica si ya existe una compra realizada por el mismo cliente en el mismo dia con el mismo producto
function verificarDuplicado($conn, $nifCli , $idProd , $fechaCompr){
    $enviar = False;
    try{    
        $stmt = $conn->prepare("SELECT FECHA_COMPRA  
                                FROM compra 
                                WHERE NIF = :nifCli
                                AND ID_PRODUCTO = :id_producto
                                AND FECHA_COMPRA = :fechCompra");
        $stmt->bindParam(':nifCli', $nifCli);
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':fechCompra', $fechaCompr);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $dato=$stmt->fetch();

        if (!empty($dato)) {
            $enviar = True;
        }
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
    return $enviar;
}

//Funcion que actualiza la Cantidad de los productos una vez que se realiza una compra. enla tabla almacena
function actualizarCantidadAlmacena($conn, $num_alm, $idProd, $cant){

    try{  
        $stmt = $conn->prepare("UPDATE almacena
                                SET CANTIDAD = CANTIDAD - :cantidad
                                WHERE NUM_ALMACEN = :num_almacen
                                AND ID_PRODUCTO = :id_producto
                                AND CANTIDAD > 0");
        $stmt->bindParam(':num_almacen', $num_alm);
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':cantidad', $cant);
        $stmt->execute();
     
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

?>
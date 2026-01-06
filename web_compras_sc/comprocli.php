
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
        if($cesta != null)
        {
            echo "<div id='cesta'>";
            print '<table class="table table-bordered table-hover table-sm text-nowrap"><tr><th>Id Producto</th><th>Nombre Producto</th><th>Cantidad</th><th>Precio</th></tr>';
            
            foreach ($cesta as $productoCesta => $detalles) {
                print "<tr><td>".$detalles[0]."</td><td>".$detalles[1]."</td><td>".$detalles[3]."</td><td>".$detalles[2]."</td></tr>";
            }
            print "</tr>";
            echo "</div>";
        }
    ?>
</body>


</html>


<?php


if(isset($_POST['añadirCesta'])){

    if(verifica_campo()){
        if(comprobarCantidad()){
            $producto = $_POST['producto'];
            $cantProducto = depurar($_POST['cantidad']);
            annadirCesta($producto, $cantProducto);//func_sesiones.php
            header("Refresh: 0");
        } 
    }
}else if(isset($_POST['pedido'])){
    if(verifica_campo()){
        registrarCompra();
    }
    #$importeTotal=obtenerImporteTotal();//func_sesiones.php 
    
}else if(isset($_POST['vaciar'])){
    vaciarCesta();
    header("Refresh: 0");


}else if(isset($_POST['atras'])){
    header("Location: ./com_inicio_cli.php");
}

#Funcion que verifica que se haya seleccionado un producto.
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

#Funcion que comprueba si la cantidad solicitada del producto seleccionado esta disponible en los almacenes.
function comprobarCantidad(){
    $valido = True;
    $idProducto = depurar(obtenerCodProd($_POST['producto'])); 
    $cantProducto = depurar($_POST['cantidad']);
    $cantidadTotal = obtenerCantidadTotal($idProducto);

    if($cantProducto > $cantidadTotal){
        echo "No hay suficientes unidades del producto seleccionado en los almacenes.<br>";
        $valido =  False;
    }
    return $valido;
}

#Funcion que obtiene la cantidad total disponible de un producto en todos los almacenes.
function obtenerCantidadTotal($idProd){
    $conn = conexion_BBDD();
    $cantidad = 0;
    try{    
        $stmt = $conn->prepare("SELECT sum(CANTIDAD) as cantidadTotal 
                                FROM almacena
                                WHERE ID_PRODUCTO = :id_producto
                                AND CANTIDAD > :cant");
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':cant', $cantidad);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $unidades=$stmt->fetch();
        return intval($unidades['cantidadTotal']);
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

#Funcion que obtiene el codigo del producto a partir de la cadena recibida desde el formulario en donde se selecciona el producto.
function obtenerCodProd($cadena){
    $arrayDatos = explode("|", $cadena);
    return $arrayDatos[0];
}







//ya hice el añadir cesta ahora falta registrar compra, queda pendiente en hacerlo. pa mañana

function registrarCompra(){
    $conn = conexion_BBDD();

    $nif =  $_SESSION['VstNIF'];
    $idProducto = depurar(obtenerCodProd($_POST['producto'])); # ??
    $cantProducto = depurar($_POST['cantidad']);


    $fecha = date("Y-m-d");
    $numAlm = depurar($_POST['localidad']);#eliminar esto, antes veridficar
    $unidadesProd = intval(verificarCantProd($conn, $idProducto, $numAlm));
    //echo $unidadesProd;

    try{
        $conn->beginTransaction();

        if($unidadesProd > 0){
            if(verificarDuplicado($conn, $nif , $idProducto , $fecha)){ // True si hay duplicado de fecha entonces actualizo la cantidad de Unidades del mismo cliente , del mismo producto de la misma fecha.
                actualizarCantCompra($conn, $nif , $idProducto , $fecha);   // Actualizo la cantidad de unidades en la tabla compra
                actualizarCantidadAlmacena($conn, $numAlm, $idProducto, $unidadesProd); // Actualizo la cantidad de unidades en la tabla almacena
                echo "Se ha completado la compra.";
                
            }else{
                $cantFinal = 1;

                $stmt = $conn->prepare("INSERT INTO compra (NIF, ID_PRODUCTO , FECHA_COMPRA, UNIDADES) VALUES (:nifCli, :id_producto, :fechaCompra, :unidades)");
                $stmt->bindParam(':nifCli', $nif);
                $stmt->bindParam(':id_producto', $idProducto);
                $stmt->bindParam(':fechaCompra', $fecha);
                $stmt->bindParam(':unidades', $cantFinal);
                
                if($stmt->execute()){
                    actualizarCantidadAlmacena($conn, $numAlm, $idProducto, $unidadesProd);
                    echo "Se ha completado la compra.";
                }
            }
            
        }else{
            echo "No hay suficientes unidades del producto seleccionado en el almacén elegido.<br>";
        }


    $conn->commit();//importante para realizar cualquier accion de modificacion.

    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }

}


//Funcion que verifica si ya existe una compra realizada por el mismo cliente en el mismo dia con el mismo producto.
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
function actualizarCantidadAlmacena($conn, $num_alm, $idProd, $cantAntigua){
    $cantNew = $cantAntigua - 1;

    try{  
        $stmt = $conn->prepare("UPDATE almacena
                                SET CANTIDAD = :cantidad
                                WHERE NUM_ALMACEN = :num_almacen
                                AND ID_PRODUCTO = :id_producto");
        $stmt->bindParam(':num_almacen', $num_alm);
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':cantidad', $cantNew);
        
        if($stmt->execute()){
            echo "Cantidad actualizada correctamente.<br>";
        }
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
}

//Funcion que envia la CANTIDAD que esta disponible
function verificarCantProd($conn, $idProducto, $numAlm){
     try{    
        $stmt = $conn->prepare("SELECT CANTIDAD  
                                FROM almacena 
                                WHERE ID_PRODUCTO = :idProd
                                AND NUM_ALMACEN = :numAlm");
        $stmt->bindParam(':idProd', $idProducto);
        $stmt->bindParam(':numAlm', $numAlm);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datos=$stmt->fetch();

        if(empty($datos)){
            $datos = 0;
            echo "No existe este producto en el almacen indicado. ";
        }else{
            $datos = $datos['CANTIDAD'];
        }
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
    return $datos;
}


//Funcion que actualiza el campo UNIDADES de la tabla compra, por si hay una compra realizada por el mismo cliente en el mismo dia con el mismo producto.
function actualizarCantCompra($conn, $nifCli , $idProd , $fechaCompr){

    $ultimaUni = obtenerUltimaUnidad($conn, $nifCli , $idProd , $fechaCompr);
    $unidadesTotal = $ultimaUni + 1;

    try{  
        $stmt = $conn->prepare("UPDATE compra
                                SET UNIDADES = :cantidad
                                WHERE NIF = :nifCli
                                AND ID_PRODUCTO = :id_producto
                                AND FECHA_COMPRA = :fechCompra");
        $stmt->bindParam(':nifCli', $nifCli);
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':fechCompra', $fechaCompr);
        $stmt->bindParam(':cantidad', $unidadesTotal);
        
        if($stmt->execute()){
            echo "Cantidad actualizada correctamente.<br>";
        }
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }

}
//Funcion que obtiene la ultima Unidad de la tabla compra.
function obtenerUltimaUnidad($conn, $nifCli , $idProd , $fechaCompr){
     try{    
        $stmt = $conn->prepare("SELECT UNIDADES  
                                FROM compra 
                                WHERE NIF = :nifCli
                                AND ID_PRODUCTO = :id_producto
                                AND FECHA_COMPRA = :fechCompra");
        $stmt->bindParam(':nifCli', $nifCli);
        $stmt->bindParam(':id_producto', $idProd);
        $stmt->bindParam(':fechCompra', $fechaCompr);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $unidades=$stmt->fetch();
    }catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
    return intval($unidades['UNIDADES']);

}


?>
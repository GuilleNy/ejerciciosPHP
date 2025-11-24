
<?php

include "otras_funciones.php";
include_once "db/BBDD_empaltadpto.php";
include_once "consultas_db.php";
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
                        <label for="cliente">Clientes:</label>
                        <select name="cliente" class="form-control">
                            <option value="" disabled selected>-- Selecciona un NIF --</option>
                            <?php
                                $clientes= obtenerClientes();
                                foreach ($clientes as $fila) {
                                    echo "<option value=\"" . $fila['NIF'] . "\">" . $fila['NIF'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="producto">Producto:</label>
                        <select name="producto" class="form-control">
                            <option value="" disabled selected>-- Selecciona un Producto --</option>
                            <?php
                                $producto= obtenerProductos();
                                foreach ($producto as $fila) {
                                    echo "<option value=\"" . $fila['ID_PRODUCTO'] . "\">" . $fila['NOMBRE'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="localidad">Localidad:</label>
                        <select name="localidad" class="form-control">
                            <option value="" disabled selected>-- Selecciona la Localidad --</option>
                            <?php
                                $almacenes= obtenerAlmacenes();
                                foreach ($almacenes as $fila) {
                                    echo "<option value=\"" . $fila['NUM_ALMACEN'] . "\">" . $fila['LOCALIDAD'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                            
                    <input type="submit" name="submit" value="Comprar" class="btn btn-warning">

                </form>
            </div>
        </div>
    </div>
</body>


</html>


<?php


if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {
        registrarCompra();

    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['cliente'])){
        $mensaje .= "No se ha seleccionado un Cliente. <br>";
        $enviar = False; 
    } 
    if(!isset($_POST['producto'])){
        $mensaje .= "No se ha seleccionado un Producto.<br>";
        $enviar = False; 
    }    

    if(!isset($_POST['localidad'])){
        $mensaje .= "No se ha seleccionado la Localidad.<br>";
        $enviar = False; 
    }  

    echo $mensaje;
    return $enviar;
}

function registrarCompra(){

    try{
        $conn = conexion_BBDD();

        $conn->beginTransaction();

        $nif = depurar($_POST['cliente']);
        $idProducto = depurar($_POST['producto']);
        $fecha = date("Y-m-d");
        $numAlm = depurar($_POST['localidad']);
        $unidadesProd = intval(verificarCantProd($conn, $idProducto, $numAlm));
        //echo $unidadesProd;

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
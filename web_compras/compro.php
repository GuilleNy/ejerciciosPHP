
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
    $conn = conexion_BBDD();

    $nif = depurar($_POST['cliente']);
    $idProducto = depurar($_POST['producto']);
    $fecha = date("Y-m-d");
    $numAlm = depurar($_POST['localidad']);
    $unidadesProd = verificarCantProd($idProducto, $numAlm);

    if(intval($unidadesProd) < 0){
        $cantFinal = $unidadesProd - 1;


        $stmt = $conn->prepare("INSERT INTO compra (NIF, ID_PRODUCTO , FECHA_COMPRA, UNIDADES) VALUES (:nifCli, :id_producto, :fechaCompra, :unidades)");
        $stmt->bindParam(':nifCli', $nif);
        $stmt->bindParam(':id_producto', $idProducto);
        $stmt->bindParam(':fechaCompra', $fecha);
        $stmt->bindParam(':unidades', $cantFinal);
        
        if($stmt->execute()){
            echo "Se ha completado la compra.";
        }
    }else{
         echo "No hay suficientes unidades del producto seleccionado en el almacén elegido.";
    }

   

}

function verificarCantProd($idProducto, $numAlm){

    $conn = conexion_BBDD();
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
            echo "No existe este producto en el almacen indicado. ";
        }

        
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $datos['CANTIDAD'];


}

?>
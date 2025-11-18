
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
    <title>Aprovisionar Productos</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Aprovisionar Productos</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form" action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

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
                        <label for="almacen">Almacen:</label>
                        <select name="almacen" class="form-control">

                            <option value="" disabled selected>-- Selecciona un Almacen --</option>
                            <?php
                                
                                $almacen= obtenerAlmacenes();
                                foreach ($almacen as $fila) {
                                    echo "<option value=\"" . $fila['NUM_ALMACEN'] . "\">" . $fila['LOCALIDAD'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        Cantidad:
                        <input type="number" name="cantidad" placeholder="Cant"  min="0" style="width:70px;" class="form-control" >
                    </div>
                    
                    <input type="submit" name="submit" value="Enviar" class="btn btn-warning">

                </form>
            </div>
        </div>
    </div>
</body>


</html>





<?php


if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {
        
        alta_producto_almacen();
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['producto'])){
        $mensaje .= "No se ha seleccionado un Producto. <br>";
        $enviar = False; 
    }
    if(!isset($_POST['almacen'])){
        $mensaje .= "No se ha seleccionado un Almacen. <br>";
        $enviar = False; 
    }
    if (empty($_POST['cantidad'])) {
        $mensaje .= "El campo de la Cantidad esta vacio. <br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}

//Funcion que da de alta a los productos en los almacenees
function alta_producto_almacen(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $num_alm = depurar($_POST['almacen']);
        $idProd = depurar($_POST['producto']);
        $cantidad = depurar(intval($_POST['cantidad']));
      
        try{    
            $stmt = $conn->prepare("SELECT NUM_ALMACEN, ID_PRODUCTO , CANTIDAD
                                    FROM almacena 
                                    WHERE NUM_ALMACEN = :numAlmacen 
                                    AND ID_PRODUCTO = :idProd");
            $stmt->bindParam(':numAlmacen', $num_alm);
            $stmt->bindParam(':idProd', $idProd);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $all_almacena=$stmt->fetch();

            //si recibo datos de un producto que ya se encuentra en el almacena actualizo su cantidad
            if(!empty($all_almacena)){
                $suma = intval($all_almacena['CANTIDAD']) + $cantidad;
                insertarAlmacena_incrementarCant($conn, $num_alm, $idProd, $suma);

            }else{

                insertarAlmacena($conn, $num_alm, $idProd, $cantidad);
            }

        }catch(PDOException $e)
            {
                echo "Error: " . $e->getMessage();
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

//Funcion que actualiza la cantidad de un producto de un mismo almacen
function insertarAlmacena_incrementarCant($conn, $num_alm, $idProd, $suma){
    
    $stmt = $conn->prepare("UPDATE almacena
                            SET CANTIDAD = :cantidad
                            WHERE NUM_ALMACEN = :num_almacen
                            AND ID_PRODUCTO = :id_producto");
    $stmt->bindParam(':num_almacen', $num_alm);
    $stmt->bindParam(':id_producto', $idProd);
    $stmt->bindParam(':cantidad', $suma);
    
    if($stmt->execute()){
        echo "Cantidad actualizada correctamente.<br>";
        echo "Hay un total de " . $suma . " productos en el Almacen.";  
    }
}

//Funcion que inserta en la tabla almacena el num_almacen , id_producto y la cantidad introducida por el usuario.
function insertarAlmacena($conn, $num_alm, $idProd, $cantidad){
    
    $stmt = $conn->prepare("INSERT INTO almacena (NUM_ALMACEN ,ID_PRODUCTO	, CANTIDAD) VALUES (:num_almacen, :id_producto, :cantidad)");
    $stmt->bindParam(':num_almacen', $num_alm);
    $stmt->bindParam(':id_producto', $idProd);
    $stmt->bindParam(':cantidad', $cantidad);
    
    if($stmt->execute()){
        echo "Se ha almacenado " . $cantidad . " productos en el Almacen.";
    }
}
?>

<?php
include "consultas_db.php";
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consulta de Stock</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Consulta de Stock</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form" name="poker" action="comconstock.php" method="post" class="card-body">

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
                        
                    </div>
                    
                    <input type="submit" name="submit" value="Consultar" class="btn btn-warning">

                </form>
            </div>
        </div>
    </div>
</body>


</html>


<?php
include "otras_funciones.php";
include "db/BBDD_empaltadpto.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {
        
        alta_producto();
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  


    if(!isset($_POST['producto'])){
        $mensaje .= "No se ha seleccionado una Producto.";
        $enviar = False; 
    }
    echo $mensaje;
    return $enviar;
}

function visualizarTabla(){

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr>
                <th>Localidad</th>
                <th>Cantidad</th>
              </tr>";

        // Recorrer filas
        foreach ($all_almacena as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['NUM_ALMACEN'] . "</td>";
            echo "<td>" . $fila['ID_PRODUCTO'] . "</td>";
            echo "<td>" . $fila['CANTIDAD'] . "</td>";
            echo "</tr>";
        }

    echo "</table>";
}



function alta_producto(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombreProd = depurar($_POST['nombre_prod']);
        $cod_prod = obtenerUltimoCodigo($conn);
        $precio = depurar($_POST['precio']);
        $idCategoria = depurar($_POST['categoria']);

        $stmt = $conn->prepare("INSERT INTO producto (ID_PRODUCTO ,NOMBRE, PRECIO, ID_CATEGORIA) VALUES (:id_producto, :nombre, :precio, :id_categoria)");
        $stmt->bindParam(':id_producto', $cod_prod);
        $stmt->bindParam(':nombre', $nombreProd);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':id_categoria', $idCategoria);
        
        if($stmt->execute()){
            echo "Producto " . $nombreProd . " esta dado de alta.";
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


?>
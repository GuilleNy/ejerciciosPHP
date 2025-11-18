
<?php

include "otras_funciones.php";
include_once "db/BBDD_empaltadpto.php";

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consulta de Almacenes</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Consulta de Almacenes</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        <label for="almacen">Almacen:</label>
                        <select name="almacen" class="form-control">

                            <option value="" disabled selected>-- Selecciona un Almacen --</option>
                            <?php
                                include_once "consultas_db.php";
                                $almacenes= obtenerAlmacenes();
                                foreach ($almacenes as $fila) {
                                    echo "<option value=\"" . $fila['NUM_ALMACEN'] . "\">" . $fila['LOCALIDAD'] . "</option>";
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


if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {
        
       visualizarAlmacenes();
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  


    if(!isset($_POST['almacen'])){
        $mensaje .= "No se ha seleccionado una Producto.";
        $enviar = False; 
    }
    echo $mensaje;
    return $enviar;
}

function visualizarAlmacenes(){

    $numAlm = depurar($_POST['almacen']);
    $all_stock = obtenerStockProducto($numAlm);

    echo "<table class='table table-striped w-50 mx-auto'>";
        echo "<tr>
                <th>Producto</th>
                <th>Cantidad</th>
              </tr>";

        // Recorrer filas
        foreach ($all_stock as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['NOMBRE'] . "</td>";
            echo "<td>" . $fila['CANTIDAD'] . "</td>";
            echo "</tr>";
        }

    echo "</table>";
}



function obtenerStockProducto($numAlm){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT NOMBRE , CANTIDAD  
                                FROM producto, almacena
                                WHERE almacena.id_producto=producto.id_producto
                                AND  num_almacen = :numAlm");
        $stmt->bindParam(':numAlm', $numAlm);
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
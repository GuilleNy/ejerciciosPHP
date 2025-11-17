<?php
include "consultas_db.php";
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
                <form id="product-form" name="poker" action="comaprpro.php" method="post" class="card-body">

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
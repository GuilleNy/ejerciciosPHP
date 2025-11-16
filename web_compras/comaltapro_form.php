<?php
include "consultas_db.php";
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alta Productos</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Alta Productos</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form" name="poker" action="comaltapro.php" method="post" class="card-body">

                    <div class="form-group">
                        Producto:
                        <input type="text" name="nombre_prod" placeholder="Nombre de producto" class="form-control">
                    </div>

                    <div class="form-group">
                        Precio:
                        <input type="number" name="precio" placeholder="Precio" class="form-control" min="0" style="width:100px;">
                    </div>

                    <div class="form-group">
                        <label for="categoria">Categoria:</label>
                        <select name="categoria" class="form-control">

                            <option value="" disabled selected>-- Selecciona una categoria --</option>

                            <?php
                                $categorias= obtenerCategorias();
                                foreach ($categorias as $fila) {
                                    echo "<option value=\"" . $fila['ID_CATEGORIA'] . "\">" . $fila['NOMBRE'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    
                    <input type="submit" name="submit" value="Dar Alta" class="btn btn-warning">

                </form>
            </div>
        </div>
    </div>
</body>


</html>
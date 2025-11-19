
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
                        Cantidad:
                        <input type="number" name="cantidad"  class="form-control"  min="0" style="width:70px;">
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
        if(validadNIF()){

            registrarCliente();

        }
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
    if (empty($_POST['cantidad'])) {
        $mensaje .= "El campo Cantidad esta vacio. <br>";
        $enviar = False;  
    }
    
    echo $mensaje;
    return $enviar;
}


?>
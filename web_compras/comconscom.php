
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
    <title>Consulta de Compras</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Consulta de Compras</h1>

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
                                include_once "consultas_db.php";
                                $clientes= obtenerClientes();
                                foreach ($clientes as $fila) {
                                    echo "<option value=\"" . $fila['NIF'] . "\">" . $fila['NIF'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        Desde:
                        <input type="date" name="fecha_desde" class="form-control">
                    </div>

                    <div class="form-group">
                        Hasta:
                        <input type="date" name="fecha_hasta" class="form-control">
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
        
       visualizarCompras();
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  


    if(!isset($_POST['cliente'])){
        $mensaje .= "No se ha seleccionado un Cliente. <br>";
        $enviar = False; 
    }
    

    if (empty($_POST['fecha_desde'])) {
        $mensaje .= "El campo fecha desde esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['fecha_hasta'])) {
        $mensaje .= "El campo fecha hasta esta vacio. <br>";
        $enviar = False;  
    }
    
    echo $mensaje;
    return $enviar;
}

function visualizarCompras(){

    $nif = depurar($_POST['cliente']);
    $fecha_desde =depurar($_POST['fecha_desde']);   
    $fecha_hasta =depurar($_POST['fecha_hasta']);
    $total = totalCompras($nif, $fecha_desde, $fecha_hasta);

   
    $all_productos = obtenerCompras($nif , $fecha_desde, $fecha_hasta);


    echo "<table class='table table-striped w-50 mx-auto'>";
        echo "<tr>
                <th>Producto</th>
                <th>Nombre</th>
                <th>Precio</th>
              </tr>";

        // Recorrer filas
        if(empty($all_productos)){
            echo "No hay datos en esas fechas";
            
        }else{
            foreach ($all_productos as $fila) {
                echo "<tr>";
                echo "<td>" . $fila['categoria'] . "</td>";
                echo "<td>" . $fila['producto'] . "</td>";
                echo "<td>" . $fila['precio'] . "</td>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<th></th>";
                echo "<th>TOTAL</th>";
                echo "<td>" . $total . "</td>";
            echo "</tr>";
            
        }
        

    echo "</table>";
}



function obtenerCompras($nif, $fecha_desde, $fecha_hasta){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT categoria.NOMBRE 'categoria', producto.NOMBRE 'producto', producto.PRECIO 'precio'
                                FROM cliente , compra , producto , categoria
                                WHERE cliente.NIF=compra.NIF
                                AND compra.id_producto=producto.id_producto
                                AND producto.id_categoria=categoria.id_categoria
                                AND  compra.NIF = :nif_cliente
                                AND fecha_compra BETWEEN :fecha_desde AND :fecha_hasta");
        $stmt->bindParam(':nif_cliente', $nif);
        $stmt->bindParam(':fecha_desde', $fecha_desde);
        $stmt->bindParam(':fecha_hasta', $fecha_hasta);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $all_productos=$stmt->fetchAll();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $all_productos;
}

function totalCompras($nif, $fecha_desde, $fecha_hasta){

    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT sum(producto.precio*compra.unidades) 'total' 
                                FROM producto , compra
                                WHERE compra.id_producto=producto.id_producto 
                                AND fecha_compra BETWEEN :fecha_desde AND :fecha_hasta
                                AND compra.nif = :nif_cliente");
        $stmt->bindParam(':nif_cliente', $nif);
        $stmt->bindParam(':fecha_desde', $fecha_desde);
        $stmt->bindParam(':fecha_hasta', $fecha_hasta);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $total=$stmt->fetch();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $total['total'];
}

?>
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
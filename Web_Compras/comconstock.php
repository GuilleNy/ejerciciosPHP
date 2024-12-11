<?php
include_once "otras_funciones.php";
$listaProductos=obtenerNombreProd();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Stock</title>
</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> ">
    
        <label for="productos">Productos:</label>
        <select name="productos" id="productos">
            <option value="" disabled selected>--Selecciona Productos--</option>
            <?php
            foreach ($listaProductos as $valor)
            {
                echo "<option value=\"" . $valor['NOMBRE'] . "\">" . $valor['NOMBRE'] . "</option>";
                
            }
            ?>

        </select>
        <br><br>

        <label for="almacenes">Numero de Almacenes:</label>
        <select name="almacenes" id="almacenes">
            <option value="" disabled selected>--Selecciona el Numero de Almacen--</option>
            <?php
            foreach ($listaNumAlmacenes as $valor)
            {
                echo "<option value=\"" . $valor['NUM_ALMACEN'] . "\">" . $valor['NUM_ALMACEN'] . "</option>";
                
            }
            ?>

        </select>
        <br><br>

        <input type="submit" value="Aprovisionar Producto" />
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            list($cantidad, $codproducto, $numeroAlm)=recogerDatos();
            verificarDatos($cantidad, $codproducto, $numeroAlm);
        }

    ?>
</body>
</html>
<?php

function obtenerNombreProd()
{
    $conn=conexionBBDD();

    try {
        $stmt = $conn -> prepare("SELECT  NOMBRE 
                                FROM producto");
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $productos=$stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error al obtener las categorías: " . $e->getMessage();
    } finally {
        $conn = null; 
    }

    return $productos;
}

?>
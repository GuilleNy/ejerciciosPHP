<?php
include_once "otras_funciones.php";
$listaProductos=obtenerNombreProd();
$listaNumAlmacenes=obtenerNumAlma();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta categoria productos</title>
</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> ">
        <label for="cantProd">Introduce la cantidad de productos:</label>
        <input type="text" id="cantProd" name="cantProd" /><br><br>


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
function recogerDatos()
{
    $cantProd=depurar($_POST['cantProd']);
    $codProduct=obtenerCodProdSeleccionado($_POST['productos']);
    $numAlmacen=$_POST['almacenes'];

    return [$cantProd,$codProduct, $numAlmacen];
}

function obtenerCodProdSeleccionado($categoriaSelecciona)
{
    $conn=conexionBBDD();

    try {
        $stmt = $conn -> prepare("SELECT ID_PRODUCTO   
                                FROM producto
                                WHERE NOMBRE= :nombrecat");
        $stmt -> bindParam(':nombrecat', $categoriaSelecciona);

        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $codProd=$stmt->fetch();
    } catch (PDOException $e) {
        echo "Error al obtener las categorías: " . $e->getMessage();
    } finally {
        $conn = null; 
    }

    return $codProd;

}

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

function obtenerNumAlma()
{
    $conn=conexionBBDD();

    try {
        $stmt = $conn -> prepare("SELECT  NUM_ALMACEN 
                                FROM almacen");
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $numAlmacenes=$stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error al obtener las categorías: " . $e->getMessage();
    } finally {
        $conn = null; 
    }

    return $numAlmacenes;

}

function verificarDatos($cantidad, $codproducto, $numeroAlm)
{
    $conn=conexionBBDD();


    try
        {
        $stmt=$conn->prepare("SELECT  NUM_ALMACEN , ID_PRODUCTO FROM almacena 
                            WHERE NUM_ALMACEN= :numAlm  and ID_PRODUCTO= :idprod"  );
        $stmt->bindParam(':numAlm',$numeroAlm);
        $stmt->bindParam(':idprod', $codproducto['ID_PRODUCTO']);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt -> fetchAll();

        if($resultado == null)
        {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn -> beginTransaction();
            $stmt = $conn -> prepare("INSERT INTO almacena ( NUM_ALMACEN , ID_PRODUCTO, CANTIDAD) 
                                        VALUES (:numAlm, :idprod , :cant)");
            $stmt -> bindParam(':numAlm', $numeroAlm);
            $stmt -> bindParam(':idprod',$codproducto['ID_PRODUCTO']);
            $stmt -> bindParam(':cant', $cantidad);

            $stmt -> execute();
            $conn -> commit();
            echo "<h2>Producto registrada correctamente.</h2>";

        } else
        {
            print "<h2>Producto ya esta registrado.</h2>";
        }

    }
    catch(PDOException $e)
    {
        $conn -> rollBack();//En el caso de la conexion no se de o haya un fallo , se realizara un rollback para no afectar la tabla .
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

}


?>
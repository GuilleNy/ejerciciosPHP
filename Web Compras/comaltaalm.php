<?php
include_once "otras_funciones.php";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta Localidad almacen</title>
</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> ">
        <label for="almacen">Introduce la localidad del almacen:</label>
        <input type="text" id="nomAlmacen" name="nomAlmacen" /><br><br>


        <input type="submit" value="Registrar Almacen" />
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            list($localidad )=recogerDatos();
            verificarDatos($localidad);
        }
    ?>
</body>
</html>
<?php
function verificarDatos($localidad)
{
    $conn=conexionBBDD();
    try
        {
        $stmt=$conn->prepare("SELECT  LOCALIDAD FROM almacen
                            WHERE LOCALIDAD= :nomloc");
        $stmt->bindParam(':nomloc', $localidad);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt -> fetch();

        if($resultado == null)
        {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn -> beginTransaction();
            $stmt = $conn -> prepare("INSERT INTO almacen (LOCALIDAD) 
                                        VALUES (:localidad)");
      
            $stmt -> bindParam(':localidad', $localidad);
            $stmt -> execute();
            $conn -> commit();
            echo "<h2>Localidad del almacen registrado correctamente.</h2>";

        } else
        {
            print "<h2>Localidad del almacen ya esta registrado.</h2>";
        }

    }
    catch(PDOException $e)
    {
        $conn -> rollBack();//En el caso de la conexion no se de o haya un fallo , se realizara un rollback para no afectar la tabla .
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

}

function recogerDatos()
{
    $nomLocalidad=depurar($_POST['nomAlmacen']);
    return[$nomLocalidad];
}

?>
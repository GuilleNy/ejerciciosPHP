<?php
$listaCategorias=obtenerCategorias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta categoria productos</title>
</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> ">
        <label for="nomProd">Introduce el nombre del producto:</label>
        <input type="text" id="nomProd" name="nomProd" /><br><br>

        <label for="precioProd">Introduce el Precio del producto:</label>
        <input type="number" id="precioProd" name="precioProd" step="0.01" required /><br><br>

        <label for="departamento">Categorias:</label>
        <select name="categoria" id="categoria">
            <option value="" disabled selected>--Selecciona Categoria--</option>
            <?php
            foreach ($listaCategorias as $valor)
            {
                echo "<option value=\"" . $valor['NOMBRE'] . "\">" . $valor['NOMBRE'] . "</option>";
                
            }
            ?>

        </select>
        <br><br>
        <input type="submit" value="Registrar Producto" />
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            list($idProducto, $nombreProd, $precioProd, $codCateg)=recogerDatos();
            verificarDatos($idProducto, $nombreProd, $precioProd, $codCateg);
        }

    ?>
</body>
</html>

<?php

function conexionBBDD()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname="COMPRASWEB";
    $conn = null;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }

    return $conn;  
}

function verificarDatos($idProducto, $nombreProd, $precioProd, $codCateg)
{
    $conn=conexionBBDD();


    try
        {
        $stmt=$conn->prepare("SELECT  NOMBRE , PRECIO FROM producto
                            WHERE NOMBRE= :nomProd  and PRECIO= :precioProd"  );
        $stmt->bindParam(':nomProd', $nombreProd);
        $stmt->bindParam(':precioProd', $precioProd);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt -> fetchAll();

        if($resultado == null)
        {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn -> beginTransaction();
            $stmt = $conn -> prepare("INSERT INTO producto(ID_PRODUCTO ,  NOMBRE, PRECIO, ID_CATEGORIA) 
                                        VALUES (:idprod, :nomprod , :preprod , :idcat)");
            $stmt -> bindParam(':idprod', $idProducto);
            $stmt -> bindParam(':nomprod', $nombreProd);
            $stmt -> bindParam(':preprod', $precioProd);
            $stmt -> bindParam(':idcat', $codCateg['ID_CATEGORIA']);

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

function obtenerCategorias()
{
    $conn=conexionBBDD();

    try {
        $stmt = $conn -> prepare("SELECT ID_CATEGORIA  , NOMBRE 
                                FROM categoria");
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $categorias=$stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error al obtener las categorías: " . $e->getMessage();
    } finally {
        $conn = null; 
    }

    return $categorias;
}


function obtenerCodigoProd()
{
    $codigo="";
    $conn=conexionBBDD();

    $stmt = $conn -> prepare("SELECT ID_PRODUCTO FROM producto
                            ORDER BY ID_PRODUCTO DESC  LIMIT 1");
    $stmt -> execute();
    $stmt -> setFetchMode(PDO::FETCH_ASSOC);
    $ultimoCodigo=$stmt->fetch();

    if($ultimoCodigo )
    {
        $cod= intval(substr($ultimoCodigo['ID_PRODUCTO'], 1));
		$nuevoNum=str_pad($cod+1,4,'0',STR_PAD_LEFT);
        $codigo='P' . $nuevoNum;
		
    }else{
        $codigo='P0001';
    }
    return $codigo;

}


function recogerDatos()
{
    $codProducto=obtenerCodigoProd();
    $nombreProducto=depurar($_POST['nomProd']);
    $precioProducto=depurar($_POST['precioProd']);

    $categoriaSelecciona=obtenerCodCatgSeleccionada($_POST['categoria']?? '');
    

    return [$codProducto,$nombreProducto, $precioProducto, $categoriaSelecciona];
}

function depurar($cadena)
{
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena;
}


function obtenerCodCatgSeleccionada($categoriaSelecciona)
{
    $conn=conexionBBDD();

    try {
        $stmt = $conn -> prepare("SELECT ID_CATEGORIA   
                                FROM categoria
                                WHERE NOMBRE= :nombrecat");
        $stmt -> bindParam(':nombrecat', $categoriaSelecciona);

        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $categorias=$stmt->fetch();
    } catch (PDOException $e) {
        echo "Error al obtener las categorías: " . $e->getMessage();
    } finally {
        $conn = null; 
    }

    return $categorias;

}

//$categoriaSelecciona=$_POST['categoria']?? '';
//echo $categoriaSelecciona;




?>
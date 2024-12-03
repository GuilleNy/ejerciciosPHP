<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta categoria categoria</title>
</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> ">
        <label for="categoria">Introduce el nombre de la categoria:</label>
        <input type="text" id="categoria" name="categoria" /><br><br>


        <input type="submit" value="Registrar Categoria" />
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            list($nombre, $categoria)=recogerDatos();
            verificarDatos($nombre, $categoria);
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

function recogerDatos()
{
    $nomCategoria=depurar($_POST['categoria']);
    $codCategoria=obtenerCodigoCatg();
    return [$nomCategoria,$codCategoria];
}

function obtenerCodigoCatg()
{
    $codigo="";
    $conn=conexionBBDD();

    $stmt = $conn -> prepare("SELECT ID_CATEGORIA FROM categoria 
                            ORDER BY ID_CATEGORIA DESC  LIMIT 1");
    $stmt -> execute();
    $stmt -> setFetchMode(PDO::FETCH_ASSOC);
    $ultimoCodigo=$stmt->fetch();

    if($ultimoCodigo )
    {
        $cod= intval(substr($ultimoCodigo['ID_CATEGORIA'], 1));
		$nuevoNum=str_pad($cod+1,3,'0',STR_PAD_LEFT);
        $codigo='C' . $nuevoNum;
		
    }else{
        $codigo='C001';
    }
    return $codigo;

}

function verificarDatos($nombre, $categoria)
{
    $conn=conexionBBDD();


    try
        {
        $stmt=$conn->prepare("SELECT  ID_CATEGORIA ,  NOMBRE FROM categoria
                            WHERE NOMBRE= :nomcat");
        $stmt->bindParam(':nomcat', $nombre);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt -> fetch();

        if($resultado == null)
        {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn -> beginTransaction();
            $stmt = $conn -> prepare("INSERT INTO categoria (ID_CATEGORIA ,  NOMBRE) 
                                        VALUES (:id_cat, :nombre)");
            $stmt -> bindParam(':id_cat', $categoria);
            $stmt -> bindParam(':nombre', $nombre);
            $stmt -> execute();
            $conn -> commit();
            echo "<h2>Categoria registrada correctamente.</h2>";

        } else
        {
            print "<h2>Categoria ya esta registrada.</h2>";
        }

    }
    catch(PDOException $e)
    {
        $conn -> rollBack();//En el caso de la conexion no se de o haya un fallo , se realizara un rollback para no afectar la tabla .
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

}


function depurar($cadena)
{
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena;
}



?>


<?php
session_start();
include_once "otras_funciones.php";
include_once "func_sesiones.php";

if(!verificarSesion())
{
	header("Location: ./pe_login.php");
}
   
if(!isset($_SESSION['cesta']))
{
    $_SESSION['cesta']=[];
}

function obtenerProductos()
{
    $conn=baseDeDatos();
    
    $stmt = $conn->prepare("SELECT productName, productCode from products where quantityInStock > 0");
    $stmt -> execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $resultado=$stmt->fetchAll();
        
return $resultado;
}
$listaProductos=obtenerProductos();

/************************************************************************************************************************** */
/*Listo */
function añadirProductos()
{
    $producto= $_POST['producto']?? null;
    $cantidad = (int) ($_POST['cantidad'] ?? 0);
   
    if (isset($_SESSION['cesta'][$producto]) ) {
        // Si el producto ya existe, incrementa la cantidad
        $_SESSION['cesta'][$producto] = (int) $_SESSION['cesta'][$producto] + $cantidad;
    } else if ($producto != null)
    {
        // Si no existe, crea una nueva entrada
        $_SESSION['cesta'][$producto] = $cantidad;
    } 

    /*
    if(isset($_POST['añadirCesta']) && $cantidad > 0)
    {
        $producto= $_POST['producto']?? null;

        if(!empty($producto))
        {
            if (!in_array($producto, $_SESSION['cesta']))
            {
                array_push($_SESSION['cesta'], $producto);
            }
        }
    }
    */

}


$listaCesta=$_SESSION['cesta'];
añadirProductos();
eliminarProductoCesta();



function eliminarProductoCesta()
{
    if (isset($_POST['eliminarProducto'])) {
        $productoEl = $_POST['cestaArticulos']?? null;
       // echo "<p>" . $productoEl . "</p>";
        if (isset($_SESSION['cesta'][$productoEl])) 
        {
            unset($_SESSION['cesta'][$productoEl]);
            echo "<p>Producto eliminado correctamente.</p>";
        } else {
            echo "<p>No hay productos en la cesta.</p>";
        }
    }
}
/************************************************************************************************************************** */
/*Listo */
function ultimaOrden()
{
    $nuevoNum="";
    $conn=baseDeDatos();
    $stmt = $conn->prepare("SELECT orderNumber 
                            from orders 
                            ORDER BY orderNumber DESC  LIMIT 1");
    $stmt -> execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $ultimoCodigo=$stmt->fetch();

    if($ultimoCodigo)
    {
        $cod= $ultimoCodigo['orderNumber'];
		$nuevoNum=$cod+1;
    }
    return $nuevoNum;
}
function generarTablaOrden($conn, $nombre)
{
    $fechaHoy = date('Y-m-d');
    $fechaEnv= null;
    $numOrden=ultimaOrden();
    $status="In Process";
    $comments = null;

    $stmt= $conn -> prepare("INSERT INTO orders (orderNumber , orderDate, requiredDate, shippedDate, `status`,comments, customerNumber)
                            VALUES (:numeroOrden, :fechaPedido, :fechaSolicitud,:fechaEnvio ,:stat ,:comentario,:cliente)");
    $stmt->bindParam(':numeroOrden', $numOrden);    
    $stmt->bindParam(':fechaPedido', $fechaHoy);
    $stmt->bindParam(':fechaSolicitud',$fechaHoy);
    $stmt->bindParam(':fechaEnvio', $fechaEnv);
    $stmt->bindParam(':stat',$status);
    $stmt->bindParam(':comentario', $comments);
    $stmt->bindParam(':cliente',$nombre);
    

    if($stmt->execute())
    {
        echo "<p>Tabla Order actualizada correctamente.</p>";
        
    }
    else
    {
        echo "<p>Error al actulizar tabla de orden.</p>";
    } 
}
/************************************************************************************************************************** */


/*Listo */
function actualizarStock()
{
    $conn=baseDeDatos();
    $productos=$_SESSION['cesta'];
    foreach ($productos as $producto => $cantidad) 
    {
        $resta=$cantidad;


        if ($resta >0 )
        {
            $stmt = $conn->prepare("UPDATE products 
                                SET quantityInStock= quantityInStock-:rest
                                WHERE productName =:nameProducto");
            $stmt->bindParam(':rest',$resta);
            $stmt->bindParam(':nameProducto',$producto);
            $stmt->execute();
        }
        
    }
   
}
    

/************************************************************************************************************************** */
/*Listo */
/** Afecta a la suma de prodcutos */
function sumaProductos()
{
    $conn=baseDeDatos();
    $total= 0;
    $productos=$_SESSION['cesta'];

    foreach ($productos as $producto => $cantidad) {
       
        $stmt = $conn->prepare("SELECT buyPrice FROM products WHERE productName = :producto");
        $stmt->bindParam(':producto', $producto);
        $stmt -> execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado=$stmt->fetch();

        // Si el producto existe, sumamos su precio al total
        if ($resultado!= null) {
            $total += $resultado['buyPrice']*$cantidad;
        } else {
            echo "<p>El producto '$producto' no existe en la base de datos.</p>";
        }
    }

    return $total;
}

function actulizarAmount($conn, $usuarioCod, $codigoPago)
{
    $sumaTotal=sumaProductos();
    $fechaHoy = date('Y-m-d');
    $stmt = $conn->prepare("UPDATE payments 
                            SET amount= amount+ :suma,
                                paymentDate= :fechahoy
                            WHERE customerNumber =:codigoUsuario AND checkNumber=:codigoPago");
    $stmt->bindParam(':suma', $sumaTotal);
    $stmt->bindParam(':fechahoy', $fechaHoy);
    $stmt->bindParam(':codigoPago', $codigoPago);
    $stmt->bindParam(':codigoUsuario',$usuarioCod);

    $stmt->execute();
}

/************************************************************************************************************************** */

function verificarPago()
{
    $nombre= $_SESSION["nombreUsu"];
    $numPago= $_POST['numeroPago'] ?? null;
    $resultado='';

    if(isset($_POST['validarPago']))
    {
        $conn= baseDeDatos();

        $stmt = $conn->prepare("SELECT customerNumber, checkNumber, amount from payments where checkNumber=:codigo and customerNumber=:usuario");
        $stmt->bindParam(':codigo', $numPago);
        $stmt->bindParam(':usuario', $nombre);
        $stmt -> execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado=$stmt->fetchAll();

        if ($resultado!= null)
        {
            generarTablaOrden($conn, $nombre);
            actualizarStock();
            actulizarAmount($conn, $nombre, $numPago);

        }else{
            echo ("<p>Pago invalidado</p>");
        }
    }

    
return $resultado;
}

/*************************************************************************************************************************************************** */
?>
<html>
	<head>
		<title>Inicio</title>
		<meta charset="utf-8" />
		<meta name="author" content="tu nombre" />
	</head>
	<body>
		
		
        <form method="POST" action="">
            <!-- Elegir los Articulos para la cesta-->
            <label for="product">Elige el Articulo a comprar: </label>
            <select id="productos" name="producto" >
                <option disabled selected>--Selecciona Producto--</option>
                <?php
                foreach ($listaProductos as $row)
                    {
                        echo "<option value=\"" . $row['productName'] . "\">" . $row['productName'] . "</option>";
                    }
                
                ?>
            </select>
            <label for="product">Cantidad: </label>
            <input type="number" name="cantidad" style="width: 40px;" value="0">
            <br>

            <input type="submit" value="Añadir a la cesta" name="añadirCesta"/>

            <br>
            <!-- Articulos en la cesta -->
            <label for="cestaArticulos">Artículos en cesta: </label>
            <select id="cestaArticulos" name="cestaArticulos" >
                <option disabled selected>--Cesta Productos--</option>
                <?php
                    foreach ($listaCesta as $producto => $cantidad)
                    {
                        echo "<option value=\"" . $producto . "\">". $producto ."</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Eliminar producto" name="eliminarProducto">   
            
            <br>

            <fieldset>
                <legend>Método de pago</legend>
                <label for="checkNumber">Número de pago:</label>
                <input type="text" name="numeroPago" />
            </fieldset>
            <input type="submit" value="Validar" name="validarPago">
                    

        </form>
        
        <form action="pe_inicio.php">
        	<input type="submit" value="Volver a Inicio" name="volver">
    	</form>
        
		<form method="POST" action="">
        	<input type="submit" value="Cerrar sesión" name="cerrarSesion">
    	</form>
		<?php
        
        var_dump($_SESSION['cesta']);

        var_dump(verificarPago());

        echo ("<br>");
      

        ?>
		<?php

			if(isset($_POST['cerrarSesion']))
			{
				if(isset($_SESSION["nombreUsu"]) && isset($_SESSION["contraUsu"]))
				{
					eliminarSesionYRedirigir();
				}
			}
    	?>
	</body>
</html>




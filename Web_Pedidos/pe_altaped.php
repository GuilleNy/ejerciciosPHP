<?php
session_start();
include_once "otras_funciones.php";
include_once "func_sesiones.php";

if(!verificarSesion())
{
	header("Location: ./pe_login.php");
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


function añadirProductos()
{
    if(!isset($_SESSION['cesta']))
    {
        $_SESSION['cesta']=[];
    }

    if(isset($_POST['añadirCesta']))
    {
        $producto= $_POST['producto']?? null;

        if($producto)
        {
            if (!in_array($producto, $_SESSION['cesta']))
            {
                array_push($_SESSION['cesta'], $producto);
            }
        }
    }

}
eliminarProductoCesta();
añadirProductos();
$listaCesta=$_SESSION['cesta'];


function eliminarProductoCesta()
{
    if(isset($_POST['eliminarProducto']))
    {
        $producto= $_POST['cesta']?? null;
        

        if($producto)
        {
            $indice= array_search($producto, $_SESSION['cesta']);
            if ($indice !== false) {
                unset($_SESSION['cesta'][$indice]); // Eliminar producto
                
            }
        }
    }    
}
function actualizarFechaDeOrden($conn, $nombre)
{
    $fechaHoy = date('Y-m-d');
    $fechaEnv= null;
    $stmt = $conn->prepare("UPDATE orders 
                        SET orderDate = :fechaPedido, 
                            requiredDate = :fechaSolicitud, 
                            shippedDate = :fechaEnvio 
                        WHERE customerNumber = :cliente");
    $stmt->bindParam(':fechaPedido', $fechaHoy);
    $stmt->bindParam(':fechaSolicitud',$fechaHoy);
    $stmt->bindParam(':cliente',$nombre);
    $stmt->bindParam(':fechaEnvio', $fechaEnv);

    if($stmt->execute())
    {
        echo "<p>Fechas actualizadas correctamente.</p>";
        
    }
    else
    {
        echo "<p>Error al actulizar fechas de orden.</p>";
    } 
}



function actualizarStock($conn)
{
    $indice=0;

    while($indice<count($_SESSION['cesta']))
    {
        $resta=1;
        $stmt = $conn->prepare("UPDATE products 
                                SET quantityInStock= quantityInStock-:rest
                                WHERE productName =:nameProducto");
        $stmt->bindParam(':rest', $resta);
        $stmt->bindParam(':nameProducto',$_SESSION['cesta'][$indice]);
        $stmt->execute();

        $indice++;
    }

}
function sumaProductos()
{
    $conn=baseDeDatos();
    $total= 0;
    $productos=$_SESSION['cesta'];

    foreach ($productos as $producto) {
       
        $stmt = $conn->prepare("SELECT buyPrice FROM products WHERE productName = :producto");
        $stmt->bindParam(':producto', $producto);
        $stmt -> execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado=$stmt->fetch();

        // Si el producto existe, sumamos su precio al total
        if ($resultado!= null) {
            $total += $resultado['buyPrice'];
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

function verificarPago()
{
    $nombre= $_SESSION["nombreUsu"];
    $numPago= $_POST['numeroPago']?? null;
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
            actualizarFechaDeOrden($conn, $nombre);
            actualizarStock($conn);
            actulizarAmount($conn, $nombre, $numPago);

        }else{
            echo ("<p>Pago invalidado</p>");
        }
    }

    
return $resultado;
}


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
            <input type="submit" value="Añadir a la cesta" name="añadirCesta"/>

            <br>
            <!-- Articulos en la cesta -->
            <label for="cestaArticulos">Artículos en cesta: </label>
            <select id="cesta" name="cesta" >
                <option disabled selected>--Cesta Productos--</option>
                <?php
                    foreach ($listaCesta as $row)
                    {
                        echo "<option value=\"" . $row . "\">" . $row . "</option>";
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
        
            
		<form method="POST" action="">
        	<input type="submit" value="Cerrar sesión" name="cerrarSesion">
    	</form>
		<?php
        
        var_dump($_SESSION['cesta']);

        var_dump(verificarPago());

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




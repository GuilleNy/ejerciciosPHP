<?php
session_start();
include_once "otras_funciones.php";
include_once "func_sesiones.php";

if(!verificarSesion())
{
	header("Location: ./pe_login.php");
}




function obtenerUsuarios()
{
    $conn=baseDeDatos();


    $stmt = $conn->prepare("SELECT customerNumber from customers;");
    $stmt -> execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $resultado=$stmt->fetchAll();
        
return $resultado;
}
$listaUsuarios=obtenerUsuarios();
        
function obtenerOrdenes()
{
    $conn=baseDeDatos();
    $numeroUsuarioSelect=$_POST['usuarios']?? null;

    $stmt = $conn->prepare("SELECT orderNumber, orderDate, `status` from orders where customerNumber= :usu");
    $stmt->bindParam(':usu',$numeroUsuarioSelect);
    $stmt -> execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $resultado=$stmt->fetchAll();
        
return $resultado;
}



function visualizarPedidos()
{
    $listaOrdenes=obtenerOrdenes();


    if (isset($_POST['consultar'])) 
    {
        echo ("<table border='1'>");
        echo ("<tr><th>orderNumber</th>
        <th>orderDate</th>
        <th>status</th> </tr>");

        foreach ($listaOrdenes as $row) 
        {
            echo "<tr>";
                echo "<td>" . $row['orderNumber'] . "</td>";  
                echo "<td>" . $row['orderDate'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
            echo "</tr>";
            
        }
        echo ("</table>");
    }
    
    
}

function detallesOrden()
{
    $conn=baseDeDatos();
    $productos=$_SESSION['cesta'];
    $numOrden=ultimaOrden();

   

    if(isset($_POST['validarPago']))
    {

        foreach($productos as $producto => $cantidad)
        {

        }


        $stmt= $conn -> prepare("INSERT INTO orderdetails (orderNumber , productCode, quantityOrdered, priceEach, orderLineNumber)
                            VALUES (:numeroOrden, :codProd, :cantidad,:precioProd,:orderLine)");
        $stmt->bindParam(':numeroOrden', $numOrden);    
        $stmt->bindParam(':codProd', $fechaHoy);
        $stmt->bindParam(':cantidad',$cantidad);
        $stmt->bindParam(':precioProd', $fechaEnv);
        $stmt->bindParam(':orderLine',$status);
     
        

        if($stmt->execute())
        {
            echo "<p>Tabla Order actualizada correctamente.</p>";
            
        }
    }
}

?>
<html>
	<head>
		<title>Inicio</title>
		<meta charset="utf-8" />
		<meta name="author" content="tu nombre" />
	</head>
	<body>
		
			<form action="" method="POST">
				<label for="usuarios">Lista Usuarios</label>
                <select name="usuarios" id="usuarios">
                    <option disabled selected>--Seleccione Usuario--</option>
                    <?php
                    foreach ($listaUsuarios as $row)
                        {
                            echo "<option value=\"" . $row['customerNumber'] . "\">" . $row['customerNumber'] . "</option>";
                        }
                    
                    ?>
                </select>

               
                <?php
                visualizarPedidos();
                        
                ?>       
                

                <input type="submit" value="Consultar Pedidos" name="consultar">         
			</form>
			
		   
			<form action="pe_inicio.php">
        		<input type="submit" value="Volver a Inicio" name="volver">
    		</form>
            
			<form method="POST" action="">
        		<input type="submit" value="Cerrar sesión" name="cerrarSesion">
    		</form>
			<?php

			var_dump($_SESSION["nombreUsu"]);
			var_dump($_SESSION["contraUsu"]);

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

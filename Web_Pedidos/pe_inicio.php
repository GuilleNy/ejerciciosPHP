<?php
session_start();
include_once "otras_funciones.php";
include_once "func_sesiones.php";

if(!verificarSesion())
{
	header("Location: ./pe_login.php");
}
        
?>
<html>
	<head>
		<title>Inicio</title>
		<meta charset="utf-8" />
		<meta name="author" content="tu nombre" />
	</head>
	<body>
		
			<form action="web1.php">
				<input type="submit" value="Ir a Web 1" >
			</form>
			
		   
			<form action="web2.php">
				<input type="submit" value="Ir a Web 2" >
			</form>
			
			<form action="web3.php">
				<input type="submit" value="Ir a Web 3" >
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


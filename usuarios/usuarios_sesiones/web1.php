<?php

include_once "otras_funciones.php";
iniciarSesion();//
//verificamos que haya cookies existente
if(!verificarSesion())
{
    eliminarSesion();//
	header("Location: ./login.php");
}
?>
<html>
	<head>
		<title>Web 1 </title>
		<meta charset="utf-8" />
		<meta name="author" content="tu nombre" />
	<body>
		

			<h1>Esta es la web 1</h1>
			<form action="aplicacion.php">
				<input type="submit" value="Ir a Aplicacion" >
			</form>
            
            <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> method="POST">
            	<input type="submit" value="Cerrar sesion"  >
            </form>
		

		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if(isset($_SESSION["usuario"]) && isset($_SESSION["contrasena"]))
				{
					eliminarSesion();
				}
					
			}
    	?>
	</body>
</html>


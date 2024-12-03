<?php

include_once "otras_funciones.php";
if(!verificarCookieExistente())
        header("Location: ./login.php");
?>
<html>
	<head>
		<title>Web 2 </title>
		<meta charset="utf-8" />
		<meta name="author" content="tu nombre" />
	<body>
		

			<h1>Esta es la web 2</h1>

			<form action="aplicacion.php">
				<input type="submit" value="Ir a Aplicacion" >
			</form>

            <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> method="POST">
            	<input type="submit" value="Cerrar sesion"  >
            </form>
			<?php
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if(isset($_COOKIE["nombreUsu"]) && isset($_COOKIE["contraUsu"]))
				{
					eliminarCookie();
				}
					
			}
    	?>
		
	</body>
</html>


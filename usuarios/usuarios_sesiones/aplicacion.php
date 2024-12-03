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
		<title>Aplicacion </title>
		<meta charset="utf-8" />
		<meta name="author" content="tu nombre" />
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

            
            <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> method="POST">
            	<input type="submit" value="Cerrar sesion"  >
            </form>
		
		<?php
		/*En cuanto se pulse el boton de cerrar sesion, mediante estas condiciones
		verificamos que haya una cookies creada y si ese es el caso procedemos a eliminar la cookies */
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				if(isset($_SESSION["usuario"]) && isset($_SESSION["contraseña"]))
				{
					eliminarSesion();
				}
					
			}
    	?>
	</body>
</html>


<?php
include_once "func_login.php";

?>
<html>
	<head>
		<title>Login-Sesiones </title>
		<meta charset="utf-8" />
		<meta name="author" content="tu nombre" />
	<body>
		<form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> method="POST">

            <label for="nombreUsu"><strong>User: </strong></label>
            <input type="text"  name="nombreUsu" size="25"><br><br>

            <label for="contraseña"><strong>Clave: </strong></label>
            <input type="text" name="contraseña" size="25"><br><br>
			
            <input type="submit" value="Login" name="login" >
			<input type="reset" value="Borrar">
		</form>
		<?php

			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				list($usuario,$contraseña)=recogerDatos();
				verificarDatos($usuario, $contraseña);
			}
		?>


	</body>
</html>
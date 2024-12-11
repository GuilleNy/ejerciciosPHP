<?php
include_once "func_login.php";

?>
<html>
	<head>
		<title>Login</title>
		<meta charset="utf-8" />
		<meta name="author" content="Guillermo" />
	<body>
		<form action="func_login.php"   method="POST">

            <label for="nombreUsu"><strong>User: </strong></label>
            <input type="text"  name="nombreUsu" size="25"><br><br>

            <label for="contraseña"><strong>Clave: </strong></label>
            <input type="text" name="contraseña" size="25"><br><br>


            <input type="checkbox" id="recordarme" name="recordarme">
            <label for="recordarme">Recordarme</label><br>
			
            <input type="submit" value="Login" name="login" >
			<input type="reset" value="Borrar">
		</form>
        
		
	</body>
</html>
<?php



?>
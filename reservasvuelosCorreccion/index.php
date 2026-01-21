<?php
include_once "db/vconfig.php";
include "consultas_db.php";
include "func_sesiones.php";
include "otras_funciones.php";

echo '<pre>';
    print_r($_COOKIE);
echo '</pre>';
?>


<html>
   
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page - PORTAL RESERVAS</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
 </head>
      
<body>
    

    <div class="container ">
        <!--Aplicacion-->
		<div class="card border-success mb-3" style="max-width: 30rem;">
		<div class="card-header">Acceso Reserva Vuelos</div>
		<div class="card-body">
		
		<form id="" name="" action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>"  method="post" class="card-body">
		
		<div class="form-group">
			Usuario <input type="text" name="usuario" placeholder="usuario" class="form-control">
        </div>
		<div class="form-group">
			Password <input type="password" name="password" placeholder="password" class="form-control">
        </div>				
        
		<input type="submit" name="submit" value="Login" class="btn btn-warning disabled">
        </form>
		
	    </div>
    </div>
    </div>
    </div>

</body>
</html>

<?php
if(isset($_POST['submit'])){ 
    if (verifica_campo_login()) { //otras_funciones.php
        $conn = conexion_BBDD();
        list($usuario, $contra)=recogerDatos();//otras_funciones.php
        $datos = obtenerDatosCli($conn, $usuario, $contra);
        if(!empty($datos)){ //consulta_db.php
            iniciarSesion($conn, $usuario, $contra, $datos);//func_sesiones.php
        }else{
            echo "Usuario o contraseña incorrecto.";
        }    
    }         
}




?>
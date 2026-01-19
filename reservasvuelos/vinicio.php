
<?php
session_start();
include "func_sesiones.php";
include "db/vconfig.php";


#Aqui verifico si la session sigue activa, en el caso de que NO esta vuelve a la pagina del login
if(!verificarSesion()) //func_sesiones.php
{
	header("Location: index.php");
}

echo '<pre>';
    print_r($_SESSION);
echo '</pre>';




?>


<html>
   
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>RESERVAS VUELOS</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
 </head>
   
 <body>
   
    <div class="container ">
        <!--Aplicacion-->
		<div class="card border-success mb-3" style="max-width: 30rem;">
		<div class="card-header">Menú Usuario </div>
		<div class="card-body">

		<B>Email Cliente:</B><?php echo $_SESSION["usuario"]['email']; ?> <BR>
		<B>Nombre Cliente:</B>  <?php echo $_SESSION["usuario"]['nombre']." ".$_SESSION['usuario']['apellidos']; ?>  <BR>
		<B>Fecha:</B>  <?php echo date("Y-m-d"); ?>   <BR><BR>
	  
		<!--Formulario con enlaces -->
		<div>
			<input type="submit" value="Reservar Vuelos" name="reservar" onclick="window.location.href='vreservas.php'" class="btn btn-warning disabled">
			<input type="submit" value="Consultar Reserva" name="consultar" onclick="window.location.href='vconsultas.php'" class="btn btn-warning disabled">
			<input type="submit" value="Salir" name="salir" onclick="window.location.href='vlogout.php'" class="btn btn-warning disabled">
		</div>	
		
       
		
		  
	</div>  
	  
	  
     
   </body>
   
</html>



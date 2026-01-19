
<?php
session_start();
include "db/vconfig.php";
include_once "consultas_db.php";
include_once "func_sesiones.php";
include "otras_funciones.php";


#Aqui verifico si la session sigue activa, en el caso de que NO esta vuelve a la pagina del login
if(!verificarSesion()) //func_sesiones.php
{
	header("Location: index.php");
}

echo '<pre>';
    print_r($_SESSION);
echo '</pre>';
echo '<pre>';
    print_r($_COOKIE);
echo '</pre>';
$conn = conexion_BBDD();

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
		<div class="card-header">Consultar Reservas</div>
		<div class="card-body">
	  	  

	<!-- INICIO DEL FORMULARIO -->
	<form action="" method="post">
	
		<B>Email Cliente:</B><?php echo $_SESSION["usuario"]['email']; ?> <BR>
		<B>Nombre Cliente:</B>  <?php echo $_SESSION["usuario"]['nombre']." ".$_SESSION['usuario']['apellidos']; ?>  <BR>
		<B>Fecha:</B>  <?php echo date("Y-m-d"); ?>   <BR><BR>
		
		<B>Numero Reserva</B>
		<select name="reserva" class="form-control">
			<?php
				$reservas = obtenerReserva($conn, $_SESSION["usuario"]['dni']);
				foreach($reservas as $id){
					echo "<option value=". $id['id_reserva'] .">".$id['id_reserva']."</option>";
				}
			?>
		</select>		
		
		
		<BR><BR><BR>
		<div>
			<input type="submit" value="Consultar Reserva" name="consultar" class="btn btn-warning disabled">
			<input type="submit" value="Volver" name="volver" class="btn btn-warning disabled">
		</div>		
	</form>
	
	<!-- FIN DEL FORMULARIO -->
    <a href="vlogout.php">Cerrar Sesion</a>
  </body>
   
</html>

<?php

if(isset($_POST['consultar'])){
    if(verifica_campo_consultaCli()){
        consultaVuelo($conn);
    }
}else if(isset($_POST['volver'])){
    header("Location: vinicio.php");
    exit();
}    
?>
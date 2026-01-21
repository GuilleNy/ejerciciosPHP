

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
	
		<B>Email Cliente:</B><?php //echo $_SESSION["usuario"]['email']; ?> <BR>
		<B>Nombre Cliente:</B>  <?php //echo $_SESSION["usuario"]['nombre']." ".$_SESSION['usuario']['apellidos']; ?>  <BR>
		<B>Fecha:</B>  <?php //echo $_SESSION["usuario"]['fechaSistema']; ?>   <BR><BR>
		
		<B>Numero Reserva</B>
		<select name="reserva" class="form-control">
			
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


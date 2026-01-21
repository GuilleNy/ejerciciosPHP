
<?php
session_start();
include "db/vconfig.php";
include_once "consultas_db.php";
include_once "func_sesiones.php";
include "otras_funciones.php";


if(!verificarSesion())
{
	header("Location: index.php");
}
echo '<pre>';
    print_r($_SESSION);
echo '</pre>';


/********************************** PROGRAMA PRINCIPAL ************************************/


if(isset($_POST['agregar'])){
    if(verifica_campo_compra()){
        $vuelo = $_POST['vuelos'];
        $numAsientos = depurar($_POST['asientos']);
        annadirCesta($vuelo, $numAsientos);//func_sesiones.php
        header("Refresh: 0");
        exit();
    }
}else if(isset($_POST['comprar'])){
    $cesta = devolverCesta();
    if($cesta != null ){
        
		registrarCompra();
		vaciarCesta();
        
    }else{
        echo "Debes seleccionar un producto";
    }
}else if(isset($_POST['vaciar'])){
    vaciarCesta();
    header("Refresh: 0");
}else if(isset($_POST['volver'])){
    header("Location: vinicio.php");
    exit();
}

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
		<div class="card-header">Reservar Vuelos</div>
		<div class="card-body">
	  	  

	<!-- INICIO DEL FORMULARIO -->
	<form action="" method="post">
	
		<B>Email Cliente:</B><?php echo $_SESSION["usuario"]['email']; ?> <BR>
		<B>Nombre Cliente:</B>  <?php echo $_SESSION["usuario"]['nombre']." ".$_SESSION['usuario']['apellidos']; ?>  <BR>
		<B>Fecha:</B>  <?php echo $_SESSION["usuario"]['fechaSistema']; ?>   <BR><BR>
		
		
		<B>Vuelos</B>
		<select name="vuelos" class="form-control">
			<?php
				$conn = conexion_BBDD();
				$vuelos = obtenerVuelos($conn);
				foreach ($vuelos as $fila) {
					$datosVuelos = $fila['id_vuelo'] ."|". $fila['origen'] ."|". $fila['destino'] . "|" . $fila['fechahorasalida'] ."|". $fila['fechahorallegada'] ."|". $fila['precio_asiento'] ."|". $fila['asientos_disponibles'];

					echo "<option value='" . htmlspecialchars($datosVuelos) . "'>" 
						. htmlspecialchars($fila['id_vuelo']) . " | " 
						. htmlspecialchars($fila['origen'])  . " | IDA: " 
						. htmlspecialchars($fila['fechahorasalida'])  . " | VUELTA : " 
						. htmlspecialchars($fila['fechahorallegada'])  . " |PRECIO ASIENTO:  " 
						. htmlspecialchars($fila['precio_asiento'])  . " | ASIENTOS DISPONIBLES: " 
						. htmlspecialchars($fila['asientos_disponibles']) .
						 "</option>";
				}
			?>
		</select>	
		
		<BR> 
		
		<B>Número Asientos</B>
		<input type="number" name="asientos" size="3" min="1" max="100" value="1" class="form-control">
		
		<BR><BR>
		<?php 
		if (!empty($_SESSION['compra'])) {
			echo '<ul>';
			foreach ($_SESSION['compra'] as $vuelo) {
				echo '<li> ' 
				. htmlspecialchars($vuelo[0]) . " | " 
				. htmlspecialchars($vuelo[1]) . " -> " 
				. htmlspecialchars($vuelo[2]) . " | Asientos: " 
				. htmlspecialchars($vuelo[7]) . '</li>';
			}
			echo '</ul>';
		}
		?>

		<BR><BR>

		<b>Precio total: <?php echo precioTotalCesta(); ?>€</b>

		<br>


		<BR><BR><BR><BR><BR>
		<div>
			<input type="submit" value="Agregar a Cesta" name="agregar" class="btn btn-warning disabled">
			<input type="submit" value="Comprar" name="comprar" class="btn btn-warning disabled">
			<input type="submit" value="Vaciar Cesta" name="vaciar" class="btn btn-warning disabled">
			<input type="submit" value="Volver" name="volver" class="btn btn-warning disabled">
		</div>		
	</form>
	
	<!-- FIN DEL FORMULARIO -->
    <a href="vlogout.php">Cerrar Sesion</a>
  </body>
   
</html>


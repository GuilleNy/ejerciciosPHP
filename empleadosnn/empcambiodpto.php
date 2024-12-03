<?php
/*Inserción en tabla Prepared Statement- mysql PDO*/

$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "empleadosnn";

try {
	//Conexion a la base de datos usando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	
	$dniUsu= $_POST['dni']?? '';
	$depart=$_POST['departamento']?? '';
	
	$datos=[$dniUsu, $depart];
	
	
	//obtengo el departamento seleccionado y mediante una select
	//obtener el codigo y asi poder trabajar con el.
	function obtenerCodigo($datos, $conn)
	{
		$stmt = $conn->prepare("SELECT cod_dpto
								FROM dpto
								where nombre=:nombreSeleccion");
								
		$stmt->bindParam(':nombreSeleccion', $datos[1]);
		$stmt->execute();

		 $stmt->setFetchMode(PDO::FETCH_ASSOC);
		 $cod=$stmt->fetch();
	return $cod;	
	}
	
	$seleccionado=obtenerCodigo($datos, $conn);
	
	//funcion para saber si el dni selecionado existe con un departamento asignado
	function encontrarEmple($conn , $datos)
	{
		$stmt = $conn->prepare(" select d.cod_dpto, d.nombre, e.dni 
								from emple_dpto e 
								inner join dpto d on e.cod_dpto= d.cod_dpto");
		$stmt->execute();

		 $stmt->setFetchMode(PDO::FETCH_ASSOC);
		 $datosEmpleDpto=$stmt->fetchAll();
		 $encontrado =false;
		
			for ($i=0 ; $i<count($datosEmpleDpto) ; $i++)
			{ 
				  if ($datos[0] === $datosEmpleDpto[$i]['dni'] )
				{
						
					$encontrado =true;
				}
				 
			} 
	return $encontrado;
	}
	$encontraEmple=encontrarEmple($conn, $datos);
	
	function asignarDpto($datos, $conn, $seleccionado, $encontraEmple)
	{
		// consulta para obtener los departamentos asignados a los empleados
		$stmt = $conn->prepare("SELECT d.cod_dpto, d.nombre, e.dni 
								FROM emple_dpto e 
								INNER JOIN dpto d ON e.cod_dpto = d.cod_dpto");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$datosEmpleDpto = $stmt->fetchAll();

		//para saber si hay datos en la tabla
		if ($datosEmpleDpto) {
			$encontrado = false;

			// verifico si el empleado es encontrado
			if ($encontraEmple) {
				// recorro todos los registro de la tabla emple_dpto 
				for ($i = 0; $i < count($datosEmpleDpto); $i++) {
					if ($datosEmpleDpto[$i]['dni'] == $datos[0]) {
						
						$stmt = $conn->prepare("UPDATE emple_dpto 
												SET cod_dpto = :codigo 
												WHERE dni = :dni");
						$stmt->bindParam(':dni', $datos[0]);
						$stmt->bindParam(':codigo', $seleccionado['cod_dpto']);

						// Ejecutar la actualización
						if ($stmt->execute()) {
							$encontrado = true;
						}
					}
				}

				// verifico si el empleado fue encontrado 
				if (!$encontrado) {
					echo "Empleado no encontrado.";
					
				} 
			}

		} 
	}

	
	asignarDpto($datos, $conn, $seleccionado, $encontraEmple);
		
	
	//listar todos los departamentos
	function listaDepartamento($conn)
	{
	$stmt = $conn->prepare("SELECT nombre FROM dpto");
    $stmt->execute();

     $stmt->setFetchMode(PDO::FETCH_ASSOC);
	 $nombresDpto=$stmt->fetchAll();
		
	return 	$nombresDpto;			
	}
	$departamentos=listaDepartamento($conn);
	
	//listar todos los dni
	function listaDni($conn)
	{
	$stmt = $conn->prepare("SELECT dni FROM emple");
    $stmt->execute();

     $stmt->setFetchMode(PDO::FETCH_ASSOC);
	 $listaDni=$stmt->fetchAll();
		
	return 	$listaDni;			
	}
	$dni=listaDni($conn);
	
	/*
	if (!empty($dniUsu))
	{
		echo "<p>Dni seleccionado: " . $dniUsu . "</p>";
	} else {
        echo "<p>No se seleccionó ningún dni.</p>";
    }
	
	if (!empty($depart)) {
        echo "<p>Departamento seleccionado: " . $depart . "</p>";
    } else {
        echo "<p>No se seleccionó ningún departamento.</p>";
    }
    */
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // poner $encontraEmple en true si el formulario fue enviado
    $encontraEmple = true;
    // este llama a la funcion con los datos del formulario
    asignarDpto($datos, $conn, $seleccionado, $encontraEmple);
	} else {
		//no hace nada si no se ha enviado
		asignarDpto($datos, $conn, $seleccionado, false);
	}
	
	
	
	
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$conn = null; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Empleado</title>
</head>
<body>
    <form method="POST" action="">
        <label for="dni">DNI del Empleado:</label>
		<select id="dni" name="dni" >
			<option >--Selecciona DNI--</option>
            <?php
            foreach ($dni as $row)
				{
					echo "<option value=\"" . $row['dni'] . "\">" . $row['dni'] . "</option>";
                }
            
            ?>
        </select><br>
	
		<br>
        <label for="departamento">Departamento:</label>
        <select id="departamento" name="departamento" >
			<option >--Selecciona Departamento--</option>
				
            <?php
            foreach ($departamentos as $row)
				{
					echo "<option value=\"" . $row['nombre'] . "\">" . $row['nombre'] . "</option>";
                }
            
            ?>
        </select><br>
		<br>
        <input type="submit" value="Registrar Empleado" />
    </form>
</body>
</html>



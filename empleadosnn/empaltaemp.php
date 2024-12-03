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
	$nombreEmp= $_POST['nombreEmple']?? '';
	$salaEmp= $_POST['salario']?? '';
	$fechaNac= $_POST['fechaNac']?? '';
	$depart=$_POST['departamento']?? '';
	
	$datos=[$dniUsu, $nombreEmp, $salaEmp, $fechaNac, $depart];
	
	function validarDatos ($datos)
	{
		
		$fallo=true;
		
			foreach ($datos as $dato)  
			{
				if (empty($dato))
				{
					$fallo=false;
				}
			}
		
		
	return $fallo;
	}
	function obtenerDepartamento($conn, $depart)
	{
		$stmt = $conn -> prepare ("SELECT cod_dpto from dpto where nombre=:depart");
		
		
		$stmt->bindParam(':depart', $depart);
		
		$stmt->execute();
		
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$dpto=$stmt->fetch();
		
		
		
	return $dpto;
	}
	$obtener=obtenerDepartamento($conn, $depart);
	
	function insertarEmp_Dpto($valido, $datos , $conn, $obtener)
	{
		if ($valido )
		{
			$fechaHoy = date('Y-m-d');
			$stmt = $conn->prepare("INSERT INTO emple_dpto (dni, cod_dpto, fecha_ini) 
                                    VALUES (:dni, :cod_dpto, :fecha_ini)");
            $stmt->bindParam(':dni', $datos[0]);
            $stmt->bindParam(':cod_dpto',$obtener['cod_dpto']);
            $stmt->bindParam(':fecha_ini', $fechaHoy);
             
			
            
			 
			 
			if($stmt->execute())
			{
				echo "Datos introducidos correctamente en la tabla emple.";
				echo "<br>";
			}
			else
			{
				echo "Error al registrar al empleado";
			}
			
			 
		}
		else{
			echo "Datos Incompletos";
		}
	}
	
	function insertarEmpleado($valido, $datos , $conn)
	{
		
		
		
		if ($valido)
		{
			$stmt = $conn->prepare("INSERT INTO emple (dni, nombre, salario, fecha_nac) 
                                    VALUES (:dni, :nombre, :salario, :fecha_nac)");
            $stmt->bindParam(':dni', $datos[0]);
            $stmt->bindParam(':nombre', $datos[1]);
            $stmt->bindParam(':salario', $datos[2]);
            $stmt->bindParam(':fecha_nac', $datos[3]);
			
			if($stmt->execute())
			{
				echo "Datos introducidos correctamente en la tabla emple_dpto.";
			}
			else
			{
				echo "Error al registrar al empleado";
			}
			
		}
		
		 
	}
	
	$valido= validarDatos ($datos);
	insertarEmpleado($valido, $datos, $conn);
	insertarEmp_Dpto($valido, $datos , $conn, $obtener);
	
	function listaDepartamento($conn)
	{
	$stmt = $conn->prepare("SELECT nombre FROM dpto");
    $stmt->execute();

     $stmt->setFetchMode(PDO::FETCH_ASSOC);
	 $nombresDpto=$stmt->fetchAll();
		
	return 	$nombresDpto;			
	}
	$departamentos=listaDepartamento($conn);
	
	
	
	
	
	if (!empty($depart)) {
        echo "<p>Departamento seleccionado: " . $depart . "</p>";
    } else {
        echo "<p>No se seleccionó ningún departamento.</p>";
    }
    
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$conn = null; //aqui la conexion se cierra automaticamente
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Empleado</title>
</head>
<body>
    <form method="POST" action="">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" /><br>

        <label for="nombreEmple">Nombre:</label>
        <input type="text" id="nombreEmple" name="nombreEmple"  /><br>

        <label for="salario">Salario:</label>
        <input type="number" id="salario" name="salario" min="0" step="0.01" /><br>

        <label for="fechaNac">Fecha de Nacimiento:</label>
        <input type="date" id="fechaNac" name="fechaNac" /><br>

        <label for="departamento">Departamento:</label>
        <select id="departamento" name="departamento" >
            <?php
            foreach ($departamentos as $departamento)
				{
					echo "<option value=\"" . $departamento['nombre'] . "\">" . $departamento['nombre'] . "</option>";
                }
            
            ?>
        </select><br>

        <input type="submit" value="Registrar Empleado" />
    </form>
</body>
</html>



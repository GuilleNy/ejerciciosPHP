<?php
/*Inserción en tabla Prepared Statement- mysql PDO*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "empleadosnn";

try {
	//Conexion a la base de datos usando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	function obtenerDepartamento($conn)
	{
	$stmt = $conn->prepare("SELECT cod_dpto FROM dpto ORDER BY cod_dpto DESC  LIMIT 1");
    $stmt->execute();

    //obtener los resultados
	/*fetchAll() devuelve un array con todos los resultados de la 
	consulta (en este caso, solo uno, ya que usas LIMIT 1), pero ese 
	array es multidimensional. Esto significa que fetchAll() no solo te 
	da el valor de cod_dpto, sino que lo envuelve en una estructura de array asociativo.*/
	
	//Con fetch(), obtienes solo una fila, que será un array asociativo.
     $stmt->setFetchMode(PDO::FETCH_ASSOC);
	 $ultimoDigito=$stmt->fetch();
	 
	 if($ultimoDigito)
	 {
		$cod= intval(substr($ultimoDigito['cod_dpto'], 1));
		$nuevoNum=str_pad($cod+1,3,'0',STR_PAD_LEFT);
		return 'D' . $nuevoNum;
	 }
	 else
	 {
		 return 'D001';
	 }
	 
	}
	
	/****************************************************************/
	
	function insertarDepartamento($conn, $codDpto, $nombreDpto)
	{
	//prepara la sentencia
    $stmt = $conn->prepare("INSERT INTO dpto(cod_dpto,nombre) 
							VALUES (:cod_dpto,:nombre)"); 
	
	//asigna valores a los marcadores de posicion
    $stmt->bindParam(':cod_dpto', $codDpto);
    $stmt->bindParam(':nombre', $nombreDpto);
	
	return $stmt->execute();
	}
	
	/***********************************************************/
	function visualizarDepartamento($conn)
	{
	$stmt = $conn->prepare("SELECT cod_dpto , nombre FROM dpto ");
    $stmt->execute();

    //obtener los resultados
     $stmt->setFetchMode(PDO::FETCH_ASSOC);
	 return $stmt->fetchAll();
		
	}
	/***************************************************************************/
	/*
    // insert a row
    $cod_dpto = 'D003'; //$_POST["cod_dpto"];
	$nombre = 'MARKETING';
    $stmt->execute();
	*/
	/********************************************************************/
	$codDpto=obtenerDepartamento($conn);
	$nombreDpto=$_POST["nombre"];
	
	if(insertarDepartamento($conn,$codDpto, $nombreDpto))
	{
		echo "Departamento creado correctamente";
		echo "<br>";
		$resultado=visualizarDepartamento($conn);
	 
		foreach($resultado as $row) {
			echo "Codigo dpto: " . $row["cod_dpto"]. " - Nombre: " . $row["nombre"]. "<br>";
		}
		
	}
	else
	{	
		echo "No se pudo agregar el Departamento";
		
	}
	
	 /********************************************************************/
    
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$conn = null; //aqui la conexion se cierra automaticamente
?>



<?php
include "empaltadpto_func.php";



if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(verificarCampos()){
            


            /*************************************RECOLECCION DE DATOS********************************************/
            recogerDatos($arrayJugadores, $repartirPremioJuga);  //Recojo los nombres de los jugadores en un array
            
            /*****************************************************************************************************/
            
            /******************************************FUNCIONES**************************************************/
            
            /*****************************************************************************************************/

            // visualizo array de los jugadores
            /*
            echo "Array jugadasPoker que el jugador ha hecho con su mazo";
            echo "<pre>";
            print_r($jugadasPoker); 
            echo "</pre>";
            */
           
        }
    }



function conexion_BBDD(){
    
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'rootroot');
    define('DB_DATABASE', 'empleados1n');
    
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE."",DB_USERNAME, DB_PASSWORD);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }

    return $conn;
}

function alta_departamento(){

    $conn = conexion_BBDD();

    $stmt = $conn->prepare("INSERT INTO departamento (cod_dpto,nombre_dpto) VALUES (:cod_dpto,:nombre)");
    $stmt->bindParam(':cod_dpto', $cod_dpto);
    $stmt->bindParam(':nombre', $nombre);
  
    // insert a row
    $cod_dpto = 'D002';
	$nombre = 'RRHH';
    $stmt->execute();

}




?>
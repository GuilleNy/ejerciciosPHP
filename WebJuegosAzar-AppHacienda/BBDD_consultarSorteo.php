<?php

include_once "conexionBaseDeDatos.php";
include_once "func_sesiones.php";
/*****************VERIFICAR LA SESION********************** */
if(!verificarSesion())
{
	header("Location: ./login.php");
    exit;// esto  detiene la ejecución del script.
}
/************************ALERTA***************************** */
if(isset( $_SESSION['mensajeSorteoFail'])){
    echo "<div class='alert alert-danger'>" . $_SESSION['mensajeSorteoFail'] . "</div>";
    unset($_SESSION['mensajeSorteoFail']); 
}


$allActivos=todosLosSorteos();
var_dump($allActivos);
function todosLosSorteos(){
    $conn=conexionBBDD();


    try{
        //$conn->beginTransaction(); 
        //no es necesario, las transacciones se usan generalmente para operaciones que modifican datos INSERT, UPDATE, DELETE.

        $stmt=$conn->prepare("SELECT NSORTEO FROM sorteo");
        //$stmt=$conn->prepare("SELECT NSORTEO, FECHA, RECAUDACION, RECAUDACION_PREMIOS, DNI, ACTIVO, COMBINACION_GANADORA  FROM sorteo");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $numSorteos=$stmt->fetchAll();


    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack();  // Solo hacer rollBack si hay transacción activa
            }
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

    return $numSorteos;
}

/*
$cod="S001";
$datos=sorteoSeleccionado($cod);
var_dump($datos);
function sorteoSeleccionado($num){
    $conn=conexionBBDD();


    try{
        //$conn->beginTransaction(); 
        //no es necesario, las transacciones se usan generalmente para operaciones que modifican datos INSERT, UPDATE, DELETE.
        $stmt=$conn->prepare("SELECT NSORTEO, FECHA, RECAUDACION, RECAUDACION_PREMIOS, DNI, ACTIVO, COMBINACION_GANADORA  FROM sorteo WHERE NSORTEO=:numSorteo");
        $stmt->bindParam(':numSorteo', $num);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $numSorteos=$stmt->fetchAll();


    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack();  // Solo hacer rollBack si hay transacción activa
            }
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

    return $numSorteos;

}
*/

?>
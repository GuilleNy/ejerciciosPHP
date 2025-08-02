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
if (isset($_SESSION['mensajeApuesta'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['mensajeApuesta'] . "</div>";
    unset($_SESSION['mensajeApuesta'] ); 


}else if(isset( $_SESSION['mensajeApuestaFail']) ){
    echo "<div class='alert alert-danger'>" . $_SESSION['mensajeApuestaFail'] . "</div>";
    unset($_SESSION['mensajeApuestaFail']); 
}else if(isset( $_SESSION['mensajeLlenarCampo']) ){
    echo "<div class='alert alert-danger'>" . $_SESSION['mensajeLlenarCampo'] . "</div>";
    unset($_SESSION['mensajeLlenarCampo']); 
}


$allActivos=todosLosSorteos();
//var_dump($allActivos);
function todosLosSorteos(){
    $conn=conexionBBDD();


    try{
        //$conn->beginTransaction(); 
        //no es necesario, las transacciones se usan generalmente para operaciones que modifican datos INSERT, UPDATE, DELETE.

        $stmt=$conn->prepare("SELECT NSORTEO FROM sorteo WHERE ACTIVO='A'");
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



?>
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
if (isset($_SESSION['mensajeSorteo'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['mensajeSorteo'] . "</div>";
    unset($_SESSION['mensajeSorteo']); 
}else if(isset( $_SESSION['mensajeSorteoFail'])){
    echo "<div class='alert alert-danger'>" . $_SESSION['mensajeSorteoFail'] . "</div>";
    unset($_SESSION['mensajeSorteoFail']); 
}


$sortActivos=SorteosActivos();
//var_dump($sortActivos);
function SorteosActivos(){
    $conn=conexionBBDD();


    try{
        //$conn->beginTransaction(); 
        //no es necesario, las transacciones se usan generalmente para operaciones que modifican datos INSERT, UPDATE, DELETE.

        $stmt=$conn->prepare("SELECT NSORTEO  FROM sorteo WHERE ACTIVO='A'");
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

/******************************************************************************************************** */
/******************************************************************************************************** */




?>
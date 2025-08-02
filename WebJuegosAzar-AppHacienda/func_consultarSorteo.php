<?php 
session_start();
include_once "conexionBaseDeDatos.php";
include_once "BBDD_realizarSorteo.php";

/*****************PROGRAMA PRINCIPAL************************* */
if(isset($_POST['consultarSorteo'])){
    /*
    if(!empty($_POST['sorteo'])){
    */
        $sorteoSele = $_POST['sorteo'];
        $resultado = sorteoSeleccionado($sorteoSele);
        $_SESSION['sort'] = $resultado[0];
        header("Location: ./consultarSorteo.php");
        exit;
    /*
    }else{
        $_SESSION['mensajeSorteoFail']="No hay sorteos";
        header("Location: ./consultarSorteo.php");
        exit;
    }
        */
    
}else if(isset($_POST['atras']))
{
    unset($_SESSION["sort"]);
    header("Location: ./inicio.php");
    exit;
}   
/************************************************************* */

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



?>
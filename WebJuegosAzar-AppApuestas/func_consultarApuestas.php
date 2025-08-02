<?php 
session_start();
include_once "conexionBaseDeDatos.php";
include_once "func_sesiones.php";
//include_once "BBDD_realizarSorteo.php";

/*****************PROGRAMA PRINCIPAL************************* */
if(isset($_POST['consultarApuestas'])){
    /*
    if(!empty($_POST['sorteo'])){
    */
        $sorteoSele = $_POST['sorteo'];
        $datos = sorteoSeleccionado($sorteoSele);
        $_SESSION['datosApuestas'] = $datos;
        //var_dump($datos);
        header("Location: ./consultarApuestas.php");
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
    unset($_SESSION['datosApuestas']);
    header("Location: ./inicio.php");
    exit;
}   
/************************************************************* */

function sorteoSeleccionado($num){
    $conn=conexionBBDD();
    $dniNum= $_SESSION["VstUsuario"];

    try{
        //$conn->beginTransaction(); 
        //no es necesario, las transacciones se usan generalmente para operaciones que modifican datos INSERT, UPDATE, DELETE.
        $stmt=$conn->prepare("SELECT NAPUESTA, DNI, NSORTEO, FECHA, N1, N2, N3, N4, N5, N6, R, IMPORTE_PREMIO, CATEGORIA_PREMIO  FROM apuestas WHERE NSORTEO=:numSorteo AND DNI=:dni");
        $stmt->bindParam(':numSorteo', $num);
        $stmt->bindParam(':dni', $dniNum);
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
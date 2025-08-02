<?php
session_start();
include_once "conexionBaseDeDatos.php";

/*****************************PROGRAMA PRINCIPAL************************* */
if(isset($_POST['darAlta'])){
    if(darAlta()){
        $_SESSION['mensajeAlta']="Alta Completada";
        header("Location: ./altaSorteo.php");
        exit();
    }/*else{
        $_SESSION['mensajeAlta']="Error";
        header("Location: ./altaSorteo.php");
        exit();
    }*/

}else if(isset($_POST['atras']))
{
    header("Location: ./inicio.php");
}   
/*************************************************************************** */
function localizarUltimoNum(){
    $conn=conexionBBDD();


    try{
        $conn->beginTransaction();

        $stmt=$conn->prepare("SELECT NSORTEO FROM sorteo ORDER BY NSORTEO DESC LIMIT 1");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoDigito=$stmt->fetch();

    
        if($ultimoDigito){
            $cod= intval(substr($ultimoDigito['NSORTEO'], 1));//aqui intval convierte la cadena a entero en este caso de 001 a 1
		    $nuevoNum=str_pad($cod+1,3,'0',STR_PAD_LEFT);
            $ultimoNum="S" . $nuevoNum;
        }else{
            $ultimoNum="S001"; 
        }
            

    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack();  // Solo hacer rollBack si hay transacción activa
            }
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    return $ultimoNum;
}

function darAlta()
{
    $VitnumSorteo=localizarUltimoNum();
    $VstFecha=$_POST['fecha'];
    $VstRecaudacion=0;
    $VstRecaudacion_Premios=0;
    $VstDNI=$_SESSION["VstUsuario"];
    $VstActivo="A";
    $VstCombinacionGanadora=0;
    $valido=false;
    $conn=conexionBBDD();
    try{
        $stmt = $conn->prepare("INSERT INTO sorteo(NSORTEO,FECHA,RECAUDACION,RECAUDACION_PREMIOS,DNI,ACTIVO,COMBINACION_GANADORA) 
							VALUES (:numSorteo,:fech,:recaudacion,:recauPremios,:dni,:activo,:combGanadora)"); 
	
        //asigna valores a los marcadores de posicion
        $stmt->bindParam(':numSorteo', $VitnumSorteo);
        $stmt->bindParam(':fech', $VstFecha);
        $stmt->bindParam(':recaudacion', $VstRecaudacion);
        $stmt->bindParam(':recauPremios', $VstRecaudacion_Premios);
        $stmt->bindParam(':dni', $VstDNI);
        $stmt->bindParam(':activo', $VstActivo);
        $stmt->bindParam(':combGanadora', $VstCombinacionGanadora);

        if($stmt->execute()){
            $valido=true;
        }
    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }
    
	return $valido;
}




?>
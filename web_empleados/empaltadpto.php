
<?php
include "otras_funciones.php";
include_once "db/BBDD_empaltadpto.php";


if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {

        alta_dpto(); 
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['nombre_dpto'])) {
        $mensaje .= "El campo Nombre Departament esta vacio. <br>";
        $enviar = False;  
    }

    echo $mensaje;
    return $enviar;
}

function alta_dpto(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombreDpto = depurar($_POST['nombre_dpto']);
        $cod_dpto = obtenerUltimoCodigo($conn);

        $stmt = $conn->prepare("INSERT INTO departamento (cod_dpto ,nombre_dpto) VALUES (:id_dpto,:nombre)");
        $stmt->bindParam(':id_dpto', $cod_dpto);
        $stmt->bindParam(':nombre', $nombreDpto);
        
        if($stmt->execute()){
            echo "Departamento " . $nombreDpto . " esta dado de alta.";
        }

        $conn->commit();

    }catch(PDOException $e)
        {
            $conn->rollBack(); 
            
            echo "Error: " . $e->getMessage() . "<br>";

             // Código de error (SQLSTATE)
            echo "Código de error: " . $e->getCode() . "<br>";
        }
}


//Funcion que obtiene el ultimo codigo de departamento y le suma 1 para crear uno nuevo.
function obtenerUltimoCodigo($conn){

    try{    
        $stmt = $conn->prepare("SELECT cod_dpto  FROM departamento ORDER BY cod_dpto DESC LIMIT 1");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoID=$stmt->fetch();

        if($ultimoID){
            $cod = intval(substr($ultimoID['cod_dpto'], 1));
            $nuevo_Num = str_pad($cod + 1, 3, '0', STR_PAD_LEFT );
            $ultimoID="D" . $nuevo_Num;
        }else{
            $ultimoID = "D001";
        }
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $ultimoID;
}
?>
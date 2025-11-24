<?php 
include "otras_funciones.php";
include "db/BBDD_empaltadpto.php";
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alta Almacen</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Alta Almacenes</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form" name="poker" action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        Almacen:
                        <input type="text" name="localidad" placeholder="Localidad" class="form-control">
                    </div>
                    
                    <input type="submit" name="submit" value="Dar Alta" class="btn btn-warning">

                </form>
            </div>
        </div>
    </div>
</body>


</html>



<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {
        
        alta_almacen();
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['localidad'])) {
        $mensaje .= "El campo del Nombre de la Localidad esta vacio. <br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}



function alta_almacen(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction(); //A partir de este punto habra operaciones de INSERT, UPDATE , DELETE.

        $localidad = depurar($_POST['localidad']);
        $num_alma = obtenerUltimoCodigo($conn);

        $stmt = $conn->prepare("INSERT INTO almacen (NUM_ALMACEN , LOCALIDAD) VALUES (:id_almacen, :nombre)");
        $stmt->bindParam(':id_almacen', $num_alma);
        $stmt->bindParam(':nombre', $localidad);
        
        if($stmt->execute()){
            echo "Almacen en la Localidad de  " . $localidad . " esta dado de alta.";
        }

        $conn->commit();//importante para realizar cualquier accion de modificacion.

    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }

}
function obtenerUltimoCodigo($conn){

    try{    
        $stmt = $conn->prepare("SELECT NUM_ALMACEN  FROM almacen ORDER BY NUM_ALMACEN DESC LIMIT 1");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoID=$stmt->fetch();

        if($ultimoID){
            $cod = intval($ultimoID['NUM_ALMACEN']);
            $ultimoID = $cod + 1;
        }else{
            $ultimoID = 1;
        }
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $ultimoID;
}








?>
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
    <title>Alta Categoria Productos</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Alta Categoria Productos</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form" name="poker" action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        Categoria:
                        <input type="text" name="nombre_cat" placeholder="Nombre de categoria" class="form-control">
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
       
    if (empty($_POST['nombre_cat'])) {
        echo "El campo está vacío.";
    } else {
  
        alta_categoria();
    }      
        
}



function alta_categoria(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombreCategoria = depurar($_POST['nombre_cat']);
        $cod_catg = obtenerUltimoCodigo($conn);

        $stmt = $conn->prepare("INSERT INTO categoria (id_categoria ,nombre) VALUES (:id_categoria,:nombre)");
        $stmt->bindParam(':id_categoria', $cod_catg);
        $stmt->bindParam(':nombre', $nombreCategoria);
        
        if($stmt->execute()){
            echo "Categoria " . $nombreCategoria . " esta dado de alta.";
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
        $stmt = $conn->prepare("SELECT id_categoria  FROM categoria ORDER BY id_categoria DESC LIMIT 1");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoID=$stmt->fetch();

        if($ultimoID){
            $cod = intval(substr($ultimoID['id_categoria'], 1));
            $nuevo_Num = str_pad($cod + 1, 3, '0', STR_PAD_LEFT );
            $ultimoID="C" . $nuevo_Num;
        }else{
            $ultimoID = "C001";
        }
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $ultimoID;
}

?>
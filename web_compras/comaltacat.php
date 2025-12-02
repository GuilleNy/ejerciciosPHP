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
    $nombreCategoria = depurar($_POST['nombre_cat']);
    $resultado =  insertar_categoria($nombreCategoria);

    if($resultado === True){
        echo "Categoria " . $nombreCategoria . " esta dado de alta.";
    }
}


function insertar_categoria($nombreCategoria){
    $conn = conexion_BBDD();

    $cod_catg = ultimo_id($conn);

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO categoria (id_categoria ,nombre) VALUES (:id_categoria,:nombre)");
        $stmt->bindParam(':id_categoria', $cod_catg);
        $stmt->bindParam(':nombre', $nombreCategoria);
        $stmt->execute();

        $conn->commit();//importante para realizar cualquier accion de modificacion.

        return True;
    }catch(PDOException $e)
    {
        if ($conn->inTransaction()) {
            $conn->rollBack(); 
        }
        echo "Error: " . $e->getMessage();
    }
}

function ultimo_id($conn){

    $ultimoID = obtenerUltimo_id($conn);

        if($ultimoID){
            $cod = intval(substr($ultimoID, 1));
            $nuevo_Num = str_pad($cod + 1, 3, '0', STR_PAD_LEFT );
            $ultimoID="C" . $nuevo_Num;
        }else{
            $ultimoID = "C001";
        }
    
    return $ultimoID;
}

function obtenerUltimo_id($conn){

    $resultado = False;

    try{    
        $stmt = $conn->prepare("SELECT max(id_categoria) 'ultima_id'
                                FROM categoria ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoID=$stmt->fetch();

       if (isset($ultimoID['ultima_id'])) {
            $resultado = $ultimoID['ultima_id'];
        }
    }catch(PDOException $e)
    {
        $resultado = False;
        echo "Error: " . $e->getMessage();
    }
    return $resultado;
}
?>
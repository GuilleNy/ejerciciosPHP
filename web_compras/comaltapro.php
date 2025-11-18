<?php
include "otras_funciones.php";
include_once "db/BBDD_empaltadpto.php";
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alta Productos</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Alta Productos</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        Producto:
                        <input type="text" name="nombre_prod" placeholder="Nombre de producto" class="form-control">
                    </div>

                    <div class="form-group">
                        Precio:
                        <input type="number" name="precio" placeholder="Precio" class="form-control" step="0.01" min="0" style="width:100px;">
                    </div>

                    <div class="form-group">
                        <label for="categoria">Categoria:</label>
                        <select name="categoria" class="form-control">

                            <option value="" disabled selected>-- Selecciona una categoria --</option>

                            <?php
                                include_once "consultas_db.php";
                                $categorias= obtenerCategorias();
                                foreach ($categorias as $fila) {
                                    echo "<option value=\"" . $fila['ID_CATEGORIA'] . "\">" . $fila['NOMBRE'] . "</option>";
                                }
                            ?>
                        </select>
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
        
        alta_producto();
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['nombre_prod'])) {
        $mensaje .= "El campo Producto esta vacio. <br>";
        $enviar = False;  
    }

    if(empty($_POST['precio'])) {
        $mensaje .= "El campo Precio esta vacio. <br>";
        $enviar = False;  
    }

    if(!isset($_POST['categoria'])){
        $mensaje .= "No se ha seleccionado una categoria.";
        $enviar = False; 
    }
    echo $mensaje;
    return $enviar;
}



function alta_producto(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombreProd = depurar($_POST['nombre_prod']);
        $cod_prod = obtenerUltimoCodigo($conn);
        $precio = depurar($_POST['precio']);
        $idCategoria = depurar($_POST['categoria']);

        $stmt = $conn->prepare("INSERT INTO producto (ID_PRODUCTO ,NOMBRE, PRECIO, ID_CATEGORIA) VALUES (:id_producto, :nombre, :precio, :id_categoria)");
        $stmt->bindParam(':id_producto', $cod_prod);
        $stmt->bindParam(':nombre', $nombreProd);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':id_categoria', $idCategoria);
        
        if($stmt->execute()){
            echo "Producto " . $nombreProd . " esta dado de alta.";
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
        $stmt = $conn->prepare("SELECT ID_PRODUCTO  FROM producto ORDER BY ID_PRODUCTO DESC LIMIT 1");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ultimoID=$stmt->fetch();

        if($ultimoID){
            $cod = intval(substr($ultimoID['ID_PRODUCTO'], 1));
            $nuevo_Num = str_pad($cod + 1, 4, '0', STR_PAD_LEFT );
            $ultimoID="P" . $nuevo_Num;
        }else{
            $ultimoID = "P0001";
        }
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $ultimoID;
}


?>
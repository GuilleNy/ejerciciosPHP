
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
    <title>Login de Clientes</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Login de Clientes</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        <label for="usuario">Usuario:</label>
                        <input type="text" name="usuario" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="clave">Clave:</label>
                        <input type="text" name="clave" class="form-control">
                    </div>


                    <input type="submit" name="submit" value="Registrarse" class="btn btn-warning">

                </form>
            </div>
        </div>
    </div>
</body>


</html>


<?php


if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {
        if(verificarLogin()){
            echo "Inicio";
        } 
    }         
}


function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  


    if (empty($_POST['usuario'])) {
        $mensaje .= "El campo Usuario esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['clave'])) {
        $mensaje .= "El campo Clave esta vacio. <br>";
        $enviar = False;  
    }

    
    echo $mensaje;
    return $enviar;
}

function verificarLogin(){
    $enviar = False;

    $usuario = depurar($_POST['usuario']);
    $clave = strrev(depurar($_POST['clave']));

    $dato=obtenerDatosLogin($usuario, $clave);

    echo "<pre>";
        print_r($dato); 
    echo "</pre>";

    if((low($dato['NOMBRE']) == low($usuario)) && ($dato['CLAVE'] == $clave)){
        $enviar = True;
        
    }

    return $enviar;
}

function low($cadena){
    return strtolower($cadena);
}

function obtenerDatosLogin($usuario, $clave){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT NOMBRE , CLAVE  
                                FROM cliente 
                                WHERE NOMBRE = :nombreCli 
                                AND CLAVE= :claveCli");
        $stmt->bindParam(':nombreCli', $usuario);
        $stmt->bindParam(':claveCli', $clave);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $datos=$stmt->fetch();
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }

    return $datos;
}




?>
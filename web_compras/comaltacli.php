
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
    <title>Alta de Clientes</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Alta de Clientes</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" name="apellido" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nif">NIF:</label>
                        <input type="text" name="nif" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="cp">CP:</label>
                        <input type="text" name="cp" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion">Direccion:</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="ciudad">Ciudad:</label>
                        <input type="text" name="ciudad" class="form-control">
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
        if(validadNIF()){

            registrarCliente();

        }
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  


    if (empty($_POST['nombre'])) {
        $mensaje .= "El campo Nombre desde esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['apellido'])) {
        $mensaje .= "El campo Apellido hasta esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['nif'])) {
        $mensaje .= "El campo NIF hasta esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['cp'])) {
        $mensaje .= "El campo CP hasta esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['direccion'])) {
        $mensaje .= "El campo Direccion hasta esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['ciudad'])) {
        $mensaje .= "El campo Ciudad hasta esta vacio. <br>";
        $enviar = False;  
    }
    
    echo $mensaje;
    return $enviar;
}

function validadNIF(){
    $mensaje = ""; 
    $enviar = True; 

    $nif = depurar($_POST['nif']);

    $numero = substr($nif, 0, 8);
    $letra = substr($nif, -1);


    if(strlen($nif) > 9){
        $mensaje .= "El NIF debe componer de 8 digitos. <br>";
        $enviar = False;
    }else{
        if(!is_numeric($numero)){
            $mensaje .= "El NIF debe componer de digitos los primeros 8 numeros. <br>";
            $enviar = False;
        }else{
            if(!is_string($letra)){
                $mensaje .= "El NIF debe finalizar con una Letra. <br>";
                $enviar = False;
            }
 
        }
    }
    echo $mensaje;
    return $enviar;
}

function registrarCliente(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombre = depurar($_POST['nombre']);
        $apellido = depurar($_POST['apellido']);
        $nif = depurar($_POST['nif']);
        $cp = depurar($_POST['cp']);
        $direccion = depurar($_POST['direccion']);
        $ciudad = depurar($_POST['ciudad']);

        $dato=verificarCliente($nif);
        if(empty($dato['NIF'])){
            $stmt = $conn->prepare("INSERT INTO cliente (NIF , NOMBRE, APELLIDO, CP, DIRECCION, CIUDAD) VALUES (:nifCli,:nombreCli, :apellidoCli, :cpCli, :direccionCli, :ciudadCli)");
            $stmt->bindParam(':nifCli', $nif);
            $stmt->bindParam(':nombreCli', $nombre);
            $stmt->bindParam(':apellidoCli', $apellido);
            $stmt->bindParam(':cpCli', $cp);
            $stmt->bindParam(':direccionCli', $direccion);
            $stmt->bindParam(':ciudadCli', $ciudad);
            
            if($stmt->execute()){
                echo "Cliente " . $nombre . " esta dado de alta.";
            }
        }else{
            echo "Cliente ya registrado";
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

function verificarCliente($nifCli){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT NIF  
                                FROM cliente 
                                WHERE NIF = :nifCli");
        $stmt->bindParam(':nifCli', $nifCli);                        
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $cliente=$stmt->fetch();

    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $cliente;
}

?>

<?php

include "otras_funciones.php";
include_once "db/BBDD_empaltadpto.php";
echo '<pre>';
    print_r($_COOKIE);
echo '</pre>';
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

                    <input type="submit" name="registrarse" value="Registrarse" class="btn btn-warning">
                    <input type="submit" name="atras" value="Atras" class="btn btn-warning">
                </form>
            </div>
        </div>
    </div>
</body>


</html>


<?php

/*****************************PROGRAMA PRINCIPAL************************* */
if(isset($_POST['registrarse'])){
    if (verifica_campo()) {
        registrarCliente();
    }         
}else if(isset($_POST['atras'])){
    header("Location: ./comlogincli.php");
}

/*************************************************************************** */

//Funcion para verificar que los campos del formulario no esten vacios
function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  


    if (empty($_POST['nombre'])) {
        $mensaje .= "El campo Nombre esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['apellido'])) {
        $mensaje .= "El campo Apellido esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['nif'])) {
        $mensaje .= "El campo NIF esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['cp'])) {
        $mensaje .= "El campo CP esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['direccion'])) {
        $mensaje .= "El campo Direccion esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['ciudad'])) {
        $mensaje .= "El campo Ciudad esta vacio. <br>";
        $enviar = False;  
    }
    
    echo $mensaje;
    return $enviar;
}

//Funcion que registra el cliente en la base de datos
function registrarCliente(){
    
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombre = depurar($_POST['nombre']);
        $apellido = depurar($_POST['apellido']);
        $nif = depurar($_POST['nif']);
        $cp = depurar($_POST['cp']);
        $direccion = depurar($_POST['direccion']);
        $ciudad = depurar($_POST['ciudad']);
        $clave = strtolower(strrev($apellido));

        $dato=verificarCliente($nif);
        if(empty($dato['NIF'])){
            insertarCliente($conn, $nif, $nombre, $apellido, $cp, $direccion, $ciudad, $clave);
            echo "Cliente " . $nombre . " esta dado de alta.";
        }else{
            echo "Cliente ya esta registrado, Introduce otro DNI.";
        }
        $conn->commit();//importante para realizar cualquier accion de modificacion.
}

//Funcion que inserta el cliente en la tabla cliente
function insertarCliente($conn, $nif, $nombre, $apellido, $cp, $direccion, $ciudad, $clave){
    try{
        $stmt = $conn->prepare("INSERT INTO cliente (NIF , NOMBRE, APELLIDO, CP, DIRECCION, CIUDAD, CLAVE) 
                                VALUES (:nifCli,:nombreCli, :apellidoCli, :cpCli, :direccionCli, :ciudadCli, :claveCli)");
        $stmt->bindParam(':nifCli', $nif);
        $stmt->bindParam(':nombreCli', $nombre);
        $stmt->bindParam(':apellidoCli', $apellido);
        $stmt->bindParam(':cpCli', $cp);
        $stmt->bindParam(':direccionCli', $direccion);
        $stmt->bindParam(':ciudadCli', $ciudad);
        $stmt->bindParam(':claveCli', $clave);
        $stmt->execute();
    }catch(PDOException $e){
        if ($conn->inTransaction()) {
            $conn->rollBack(); 
        }
        echo "Error: " . $e->getMessage();
    }
}

//Funcion que verifica si el cliente ya existe en la base de datos
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
        return $cliente;
    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        } 
}

?>
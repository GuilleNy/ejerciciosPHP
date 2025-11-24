
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
    <title>Alta Empleados</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Alta Empleados</h1>

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
                        <label for="dni">DNI:</label>
                        <input type="text" name="dni" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fech_nac">Fecha Nacimiento:</label>
                        <input type="date" name="fech_nac" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="salario">Salario:</label>
                        <input type="number" name="salario" class="form-control" min="0" step="0.01">
                    </div>
                    
                    <div class="form-group">
                        <label for="departamento">Departamento:</label>
                        <select name="departamento" class="form-control">

                            <option value="" disabled selected>-- Selecciona una Departamento --</option>

                            <?php
                                include_once "consultasDB.php";
                                $allDpto= obtenerDpto();
                                foreach ($allDpto as $fila) {
                                    echo "<option value=\"" . $fila['cod_dpto'] . "\">" . $fila['nombre_dpto'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    
                    

                  

                    <input type="submit" name="submit" value="Dar de Alta" class="btn btn-warning">

                </form>
            </div>
        </div>
    </div>
</body>


</html>


<?php


if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    if (verifica_campo()) {
        registrarEmp();
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

    if (empty($_POST['dni'])) {
        $mensaje .= "El campo DNI hasta esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['fech_nac'])) {
        $mensaje .= "El campo Fecha de Nacimiento hasta esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['salario'])) {
        $mensaje .= "El campo Salario hasta esta vacio. <br>";
        $enviar = False;  
    }

    if(!isset($_POST['departamento'])){
        $mensaje .= "No se ha seleccionado un Departamento. <br>";
        $enviar = False; 
    }
    
    echo $mensaje;
    return $enviar;
}

function registrarEmp(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $nombre = depurar($_POST['nombre']);
        $apellido = depurar($_POST['apellido']);
        $dni = depurar($_POST['dni']);
        $fech_Nac = depurar($_POST['fech_nac']);
        $salario = intval(depurar($_POST['salario']));
        $cod_dpto = depurar($_POST['departamento']);
     

        //$dato=verificarCliente($dni);
        //if(empty($dato['dni'])){
            $stmt = $conn->prepare("INSERT INTO empleado (dni , nombre, apellidos, fecha_nac, salario, cod_dpto) VALUES (:dniEmp,:nombreEmp, :apellidoEmp, :fechaNacEmp, :salarioEmp,  :codDptoEmp)");
            $stmt->bindParam(':dniEmp', $dni);
            $stmt->bindParam(':nombreEmp', $nombre);
            $stmt->bindParam(':apellidoEmp', $apellido);
            $stmt->bindParam(':salarioEmp', $salario);
            $stmt->bindParam(':fechaNacEmp', $fech_Nac);
            $stmt->bindParam(':codDptoEmp', $cod_dpto);
            
            if($stmt->execute()){
                echo "Empleado " . $nombre . " esta dado de alta.";
            }
            /*
        }else{
            echo "Cliente ya registrado";
        }
        */
        $conn->commit();//importante para realizar cualquier accioon de modificacion.

    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();

             // Código de error (SQLSTATE)
            echo "Código de error: " . $e->getCode() . "<br>";
        }
    $conn = null;
}

function verificarCliente($dniEmp){
    $conn = conexion_BBDD();
    try{    
        $stmt = $conn->prepare("SELECT dni  
                                FROM empleado 
                                WHERE dni = :dniEmp");
        $stmt->bindParam(':dniEmp', $dniEmp);                        
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $emp=$stmt->fetch();

    }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return $emp;
}



?>
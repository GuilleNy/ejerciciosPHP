
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
    <title>Cambio Departamento</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Cambio Departamento</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        <label for="empleado">Empleado:</label>
                        <select name="empleado" class="form-control">

                            <option value="" disabled selected>-- Selecciona una Empleado --</option>

                            <?php
                                include_once "consultasDB.php";
                                $allEmp= obtenerEmpleados();
                                foreach ($allEmp as $fila) {
                                    echo "<option value=\"" . $fila['dni'] . "\">" . $fila['dni'] . "</option>";
                                }
                            ?>
                        </select>
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
        cambiarDptoEmp();
    }         
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['empleado'])){
        $mensaje .= "No se ha seleccionado un Empleado. <br>";
        $enviar = False; 
    }

    if(!isset($_POST['departamento'])){
        $mensaje .= "No se ha seleccionado un Departamento. <br>";
        $enviar = False; 
    }
    
    echo $mensaje;
    return $enviar;
}

function cambiarDptoEmp(){
    try{
        $conn = conexion_BBDD();
        $conn->beginTransaction();

        $dniEmp = depurar($_POST['empleado']);
        $cod_dpto = depurar($_POST['departamento']);
        $fecha_ini = date("Y-m-d");
        $fecha_fin = null;
     

        //$dato=verificarCliente($dni);
        //if(empty($dato['dni'])){
            $stmt = $conn->prepare("INSERT INTO emple_depart (dni , cod_dpto, fecha_ini, fecha_fin) VALUES (:dniEmp,:codDptoEmp, :fecha_ini, :fecha_fin)");
            $stmt->bindParam(':dniEmp', $dniEmp);
            $stmt->bindParam(':codDptoEmp', $cod_dpto);
            $stmt->bindParam(':fecha_ini', $fecha_ini);
            $stmt->bindParam(':fecha_fin', $fecha_fin );
                   
            if($stmt->execute()){
                echo "Empleado ha cambiado de Departamento.";
                actualizarfechaFinEmp($conn , $dniEmp , $cod_dpto);
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
            echo "Error: " . $e->getMessage() . "<br>";

             // Código de error (SQLSTATE)
            echo "Código de error: " . $e->getCode() . "<br>";
        }
    $conn = null;
}

//esta mal arreglarlo, me mete la fecha fin en la nuevo cambio de de dpto
function actualizarfechaFinEmp($conn , $dni , $cod_dpto){

    $fecha_fin = date("Y-m-d");
    $stmt = $conn->prepare("UPDATE emple_depart
                            SET fecha_fin = :fecha_fin
                            WHERE dni = :dni
                            AND cod_dpto = :cod_dpto
                            AND fecha_fin IS NULL");
    $stmt->bindParam(':dni', $dni);
    $stmt->bindParam(':cod_dpto', $cod_dpto);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    
    if($stmt->execute()){
        echo "Fecha fin del empleado actualizado.<br>";
    }
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
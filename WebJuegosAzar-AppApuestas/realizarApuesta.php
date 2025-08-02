<?php 
session_start();
include_once "BBDD_realizarApuesta.php";


?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Realizar Apuesta</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

    <form name="inicio" action="func_realizarApuesta.php" method="POST" class="d-flex justify-content-center align-items-center vh-20">
        <div class="container">
            <!-- Aplicación -->
            <div class="card border-success mb-3 mx-auto" style="max-width: 30rem;">
                <div class="card-header text-center">
                    <h2><b>REALIZAR APUESTA</b></h2>
                </div>
                <div class="card-body ">
                    <div class="mb-3">
                        <div class="mb-3 text-center" >
                            <label for="sorteo" class="form-label"><b>SELECCIONA SORTEO:</b></label>
                            <select id="sorteo" name="sorteo" class="custom-select">
                                <option value="" disabled selected>-- Selecciona un sorteo --</option>
                                <?php
                                
                                foreach ($allActivos as $fila)
                                    {
                                        echo "<option value=\"" . $fila['NSORTEO'] . "\">" . $fila['NSORTEO'] . "</option>";
                                    }
                                    //var_dump($_SESSION["datosSorteo"]);
                                ?>
                            </select>
                        </div>
                        
                        <hr>
                        <div class="mb-3 "  >
                            <label for="n1" class="form-label"><b>N1:</b></label>
                            <input type="number" class="form-control" min="1" max="49" name="n1" > 
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="n2" class="form-label"><b>N2:</b></label>
                            <input type="number" class="form-control" min="1" max="49" name="n2" >
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="n3" class="form-label"><b>N3:</b></label>
                            <input type="number" class="form-control" min="1" max="49" name="n3" >
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="n4" class="form-label"><b>N4:</b></label>
                            <input type="number" class="form-control" min="1" max="49" name="n4" >
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="n5" class="form-label"><b>N5:</b></label>
                            <input type="number" class="form-control" min="1" max="49" name="n5" >
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="n6" class="form-label"><b>N6:</b></label>
                            <input type="number" class="form-control" min="1" max="49" name="n6" >
                        </div>
                    </div>
                    <hr>
                    <div>
                        <input type="submit" value="Realizar Apuesta" name="realizarApuesta" class="btn btn-primary">
                        <input type="submit" value="Vover al inicio" name="atras" class="btn btn-warning">
                    </div>
                </div>
            </div>
        </div>
    </form>

   

</body>
</html>
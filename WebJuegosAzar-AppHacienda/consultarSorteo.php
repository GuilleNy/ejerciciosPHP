<?php 
session_start();
include_once "BBDD_consultarSorteo.php";
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consultar Sorteo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

    <form name="inicio" action="func_consultarSorteo.php" method="POST" class="d-flex justify-content-center align-items-center vh-20">
        <div class="container">
            <!-- Aplicación -->
            <div class="card border-success mb-3 mx-auto" style="max-width: 30rem;">
                <div class="card-header text-center">
                    <h2><b>CONSULTAR SORTEO</b></h2>
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
                            <label for="numsorteo" class="form-label"><b>Número del Sorteo:</b></label>
                            <input type="text" class="form-control" name="numsorteo" value="<?= isset($_SESSION['sort']['NSORTEO']) ? $_SESSION['sort']['NSORTEO'] : ''; ?>" readonly> <!-- < ?= ... ?> es una forma corta de escribir: < ?php echo ...; ?> -->
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="fecha" class="form-label"><b>Fecha:</b></label>
                            <input type="text" class="form-control" name="fecha" value="<?= isset($_SESSION['sort']['FECHA']) ? $_SESSION['sort']['FECHA'] : ''; ?>" readonly>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="recaudacion" class="form-label"><b>Recaudación:</b></label>
                            <input type="text" class="form-control" name="recaudacion" value="<?= isset($_SESSION['sort']['RECAUDACION']) ? $_SESSION['sort']['RECAUDACION'] : ''; ?>" readonly>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="recaudacionPremios" class="form-label"><b>Recaudación para Premios:</b></label>
                            <input type="text" class="form-control" name="recaudacionPrem" value="<?= isset($_SESSION['sort']['RECAUDACION_PREMIOS']) ? $_SESSION['sort']['RECAUDACION_PREMIOS'] : ''; ?>" readonly>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="dni" class="form-label"><b>DNI:</b></label>
                            <input type="text" class="form-control" name="dni" value="<?= isset($_SESSION['sort']['DNI']) ? $_SESSION['sort']['DNI'] : ''; ?>" readonly>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="activo" class="form-label"><b>Activo:</b></label>
                            <input type="text" class="form-control" name="activo" value="<?= isset($_SESSION['sort']['ACTIVO']) ? $_SESSION['sort']['ACTIVO'] : ''; ?>" readonly>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="combGanadora" class="form-label"><b>Combinación Ganadora:</b></label>
                            <input type="text" class="form-control" name="combGanadora" value="<?= isset($_SESSION['sort']['COMBINACION_GANADORA']) ? $_SESSION['sort']['COMBINACION_GANADORA'] : ''; ?>" readonly>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <input type="submit" value="Consultar Sorteo" name="consultarSorteo" class="btn btn-primary">
                        <input type="submit" value="Vover al inicio" name="atras" class="btn btn-warning">
                    </div>
                </div>
            </div>
        </div>
    </form>

   

</body>
</html>
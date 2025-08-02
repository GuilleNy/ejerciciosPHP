<?php 
session_start();
include_once "BBDD_realizarSorteo.php";
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Realizar Sorteo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

    <form name="inicio" action="func_realizarSorteo.php" method="POST" class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <!-- Aplicación -->
            <div class="card border-success mb-3 mx-auto" style="max-width: 30rem;">
                <div class="card-header text-center">
                    <h2><b>REALIZAR SORTEO</b></h2>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <label for="sorteo" class="form-label"><b>Sorteos Activos:</b></label>
                        <select id="sorteo" name="sorteo" class="custom-select">
                            <option value="" disabled selected>-- Selecciona un sorteo --</option>
                            <?php
                            
                            foreach ($sortActivos as $fila)
                                {
                                    echo "<option value=\"" . $fila['NSORTEO'] . "\">" . $fila['NSORTEO'] . "</option>";
                                }
                                
                            ?>
                        </select>
                        <hr>
                        <div class="mb-3 text-center">
                            <input type="submit" value="Generar" name="generar" class="btn btn-primary">
                            <input type="text"  name="combGanadora" value="<?= isset($_SESSION["numGand"]) ? htmlspecialchars($_SESSION["numGand"]) : '' ?>" readonly>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <input type="submit" value="Realizar Sorteo" name="realizarSorteo" class="btn btn-primary">
                        <input type="submit" value="Vover al inicio" name="atras" class="btn btn-warning">
                    </div>
                </div>
            </div>
        </div>
    </form>

   

</body>
</html>
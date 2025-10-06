<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calculadora</title>
</head>
<body>
    <h1> Calculadora </h1>
    <form action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="num1">Operando 1:</label>
        <input type="number" name="num1">
        <br>
        <br>
        <label for="num2">Operando 2:</label>
        <input type="number" name="num2">
        <br>
        <br>

        <label for="sele">Selecciona operación: </label><br>
        <input type="radio" name="operacion" value="suma" checked> Suma<br>
        <input type="radio" name="operacion" value="resta"> Resta<br>
        <input type="radio" name="operacion" value="multiplicacion"> Multiplicación<br>
        <input type="radio" name="operacion" value="division"> División<br>
        <button type="submit" name="enviar">Enviar</button>
        <button type="reset">Borrar</button>
        
    </form>
</body>
</html>
<?php

include "otrasFunciones.php";

resultado();

/*******************************FUNCIONES************************************ */

function resultado(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $numero_1=(int) depurar($_POST['num1']);
        $numero_2=(int) depurar($_POST['num2']);
        $operacion= $_POST["operacion"];

        switch ($operacion) {
            case 'suma':
                echo "<p>Resultado operación: " . $numero_1 . " + " . $numero_2 . " = " . ($numero_1 + $numero_2) . "</p>";
                break;
            case 'resta':
                echo "<p>Resultado operación: " . $numero_1 . " - " . $numero_2 . " = " . ($numero_1 - $numero_2) . "</p>";
                break;
            case 'multiplicacion':
                echo "<p>Resultado operación: " . $numero_1 . " * " . $numero_2 . " = " . ($numero_1 * $numero_2) . "</p>";
                break;
            case 'division':
                echo "<p>Resultado operación: " . $numero_1 . " / " . $numero_2 . " = " . ($numero_1 / $numero_2) . "</p>";
                break;
        }
    }
}







?>

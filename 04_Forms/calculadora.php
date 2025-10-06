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
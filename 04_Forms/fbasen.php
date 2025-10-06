<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambio de base</title>
</head>
<body>
    <h1> Cambio de Base </h1>
    <form action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>"  method="POST">
        <label for="num">Numero: </label>
        <input type="text" name="numero">
        <br>
        <br>
        <label for="new">Nueva Base: </label>
        <input type="number" name="base">

        <br>
        <br>
        <button type="submit">Cambio Base</button>
        <button type="reset">Borrar</button>
        <br>
        <br>
        
    </form>
</body>
</html>

<?php
include "otrasFunciones.php";

resultado();

/*******************************FUNCIONES************************************ */

function resultado(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $numero=depurar($_POST['numero']);
        $num_array=explode("/", $numero);
        $base=(int) depurar($_POST['base']);
        
        echo "Numero " . $num_array[0] . " en base " . $num_array[1] . " = " . base_convert($num_array[0] , $num_array[1] , $base) . " en base " . $base;
    }
}


?>
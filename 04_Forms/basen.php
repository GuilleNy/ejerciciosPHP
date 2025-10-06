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


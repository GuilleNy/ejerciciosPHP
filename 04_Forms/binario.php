<?php
include "otrasFunciones.php";

$decimal=depurar($_POST['decimal']);


convertirBinario($decimal);

/*******************************FUNCIONES************************************ */

function convertirBinario($decimal){
    $ipBinario=str_pad(sprintf("%b",$decimal),8,0,STR_PAD_LEFT);

    //aqui para visualizar le numero binario

    return $ipBinario;
}
?>
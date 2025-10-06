<?php
include "otrasFunciones.php";

resultado();

/*******************************FUNCIONES************************************ */
function resultado(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $decimal=(int) depurar($_POST['decimal']);    
        $dec_bin=convertirBinario($decimal);

        echo "<h1>CONVERSOR BINARIO</h1>";
        echo "<label>Numero Decimal:</label>";
        echo "<input value='$decimal'></br></br>";

        echo "<label>Numero Binario:</label>";
        echo "<input value='$dec_bin'>";
    }
}

function convertirBinario($decimal){
    $ipBinario=str_pad(sprintf("%b",$decimal),8,0,STR_PAD_LEFT);
    return $ipBinario;
}
?>
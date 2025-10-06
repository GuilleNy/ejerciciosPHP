<?php
include "otrasFunciones.php";



convertirBinario($decimal);

/*******************************FUNCIONES************************************ */
function resultado(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $decimal=depurar($_POST['decimal']);     

        echo "<h1>CONVERSOR BINARIO</h1>";
        echo "<label>Numero Decimal:</label>";
        echo "<input>" . $decimal . "</input>";
        
    }
    
        


}


function convertirBinario($decimal){
    $ipBinario=str_pad(sprintf("%b",$decimal),8,0,STR_PAD_LEFT);
    return $ipBinario;
}
?>
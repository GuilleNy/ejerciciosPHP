<?php
include "otrasFunciones.php";

resultado();

/*******************************FUNCIONES************************************ */
function resultado(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $ip = depurar($_POST['ip']);    
        $conv_bin = convertirBinario($ip);

        echo "<h1>IPs</h1>";
        echo "<label>Numero Decimal:</label>";
        echo "<input value='$ip'></br></br>";

        echo "<label>Numero Binario:</label>";
        echo "<input value='$conv_bin' size='35'>";
    }
}

function convertirBinario($ip){
    $ip_array = explode(".", $ip);
    $redCadena=array();

    for ($i=0; $i < count($ip_array); $i++) { 
        $redCadena[]=str_pad(sprintf("%b",$ip_array[$i]),8,0,STR_PAD_LEFT); 
    }
  
    //lo convertimos en una cadena con los puntos
    $binarioCompleto = implode(".", $redCadena);
    return $binarioCompleto;
}
?>


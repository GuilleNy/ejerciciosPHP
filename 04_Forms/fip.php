<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>IP Binario</title>
</head>
<body>
    <h1> IPs </h1>
    <form action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="ip">IP notación decimal: </label>
        <input type="text" name="ip" value="<?php if(isset($_POST['ip'])) echo htmlspecialchars($_POST['ip']); ?>">
        <br>
        <br>
        <?php
        include "otrasFunciones.php";
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            resultado();
        }
        ?>
        <br>
        <br>
        <button type="submit">Notacion decimal</button>
        <button type="reset">Borrar</button>
        
    </form>
</body>
</html>

<?php

/*******************************FUNCIONES************************************ */
function resultado(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $ip = depurar($_POST['ip']);    
        $conv_bin = convertirBinario($ip);

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


<HTML>
<HEAD><TITLE> EJ3-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
$ip="192.168.16.100/21";

//print_r($ipArray);

function convertirBinario($ip){
    $ipBinario="";
    $array1=explode("/", $ip);// obtengo en el indice 1 la cadena ip y en el indice 2 la mascara.
    $ipArray=explode(".", $array1[0]);//obtengo en una array la ip separado

    for ($i=0; $i < count($ipArray) ; $i++) {
        $ipBinario.=str_pad(sprintf("%b",$ipArray[$i]),8,0,STR_PAD_LEFT);
        if($i < count($ipArray) - 1){
            $ipBinario.=".";
        }else{
            $ipBinario.=" ";
        }
    }
    return $ipBinario;
}
echo convertirBinario($ip);
echo "<br/>";

//obtengo un string con la ip en binario.
function obtenerMascara($cadenaIp){

    $array1=explode("/", $cadenaIp);// obtengo en el indice 1 la cadena ip y en el indice 2 la mascara.
    
    $ipBinario=convertirBinario($array1[0]);//aqui obtengo la ip en binario  "11000000.10101000.00010000.01100100"
    $ipBinArray=explode(".",$ipBinario);//separo en cada casilla los bits
    $mascara=$array1[1];
    $mascaraBin=array();
    $cont=0;

    for ($i=0; $i < count($ipBinArray); $i++) { 
    
        if($mascara >= 8){
            $mascaraBin[$i]="11111111";
            $mascara-=8;
        }else{
            $mascaraBin[$i]=str_repeat("1", $mascara);
            $mascara-=$mascara;
            $mascaraBin[$i]=str_pad($mascaraBin[$i],8,0,STR_PAD_RIGHT);
        }
    }
    $mascaraCadena=implode(".",$mascaraBin);
    return $mascaraCadena;
}
$mascara=obtenerMascara($ip);

echo obtenerMascara($ip);

function convertirDecimal($ipBinArray){
    $ipDecimal="";
    $ipBinArray=explode(".",$ipBinArray);

    for ($i=0; $i < count($ipBinArray) ; $i++) {
        $ipDecimal.=bindec($ipBinArray[$i]);
        if($i < count($ipBinArray) - 1){
            $ipDecimal.=".";
        }else{
            $ipDecimal.=" ";
        }
    }
    return $ipDecimal;
}

echo "<br/>";
echo convertirDecimal($mascara);


function obtenerbroadcast($ip){
    $ipBinario=explode(".", convertirBinario($ip)); //"11000000.10101000.00010000.01100100"
    $mascara=explode(".", obtenerMascara($ip)); //    "11111111.11111111.11111000.00000000"
    $broadcast=array();

    for ($i=0; $i < count($ipBinario); $i++) { 
        if($ipBinario[$i] == 1 && $mascara[$i] == 1){

            $broadcast[$i].=1;
        }

    }    
}


if($i < count($ipArray) - 1){
            $ipBinario.=".";
        }else{
            $ipBinario.=" ";
        }


?>
</BODY>
</HTML>
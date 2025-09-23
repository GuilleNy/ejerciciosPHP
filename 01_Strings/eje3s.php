<HTML>
<HEAD><TITLE> EJ2-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
$ip="192.168.16.100/16";


$array1=explode("/", $ip);
$array2=explode(".", $array1[0]);
$ipArray=array_merge($array2, [$array1[1]]);

print_r($ipArray);

function convertirBinario($ip){
    $ipBinario="";
    $array=explode('.', $ip);

    for ($i=0; $i < count($array) ; $i++) {
        $ipBinario.=str_pad(sprintf("%b",$array[$i]),8,0,STR_PAD_LEFT);
        if($i < count($array) - 1){
            $ipBinario.=".";
        }else{
            $ipBinario.=" ";
        }
    }
    return $ipBinario;
}






?>
</BODY>
</HTML>
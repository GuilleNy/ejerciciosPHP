<HTML>
<HEAD><TITLE> EJ3-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
$ip="192.168.16.100/16";

/**En la variable array2 separo la ip en cada casilla.
 */
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
echo "<br/>";

//obtengo un string con la ip en binario.
$ipBinario=convertirBinario($array1[0]);
$ipBinArray=explode(".",$ipBinario);

$mascaraBin=array();
$cont=0;
for ($i=0; $i < count($ipBinArray); $i++) { 
   
    if($ipBinario >= 8){
        $mascaraBin[$i]="11111111";
        $ipBinario-=8;
    }else{

        $mascaraBin[$i]=str_repeat("1", $ipBinario);
    }







    $mascaraBin[$i]="";
    while ($array1[1] <= $cont){
        if($cont < $array1[1]){
            $mascaraBin[$i].="1";
            $cont+=1;
        }else{
            $mascaraBin[$i].=str_pad($mascaraBin[$i],8,0,STR_PAD_RIGHT);

        }
    }
}


print_r ($mascaraBin);

?>
</BODY>
</HTML>
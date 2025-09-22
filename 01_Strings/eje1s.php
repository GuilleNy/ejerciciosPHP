<?php
/* Convertir la IP a su representación en binario haciendo uso 
de la función printfo sprintf.Únicamente se podrán utilizar 
funciones de cadenas de caracteres. */
/*IP 192.18.16.204 en binario es 11000000.00010010.00010000.11001100 
IP 10.33.161.2 en binario es 00001010.00100001.10100001.00000010
*/

?>

<HTML>
<HEAD><TITLE> EJ1-Conversion IP Decimal a Binario </TITLE></HEAD>
<BODY>
<?php
$ip="192.18.16.204";
$ip2="10.33.161.2";


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

echo "IP $ip en binario es " . convertirBinario($ip) . "<br/>";
echo "IP $ip2 en binario es " . convertirBinario($ip2) . "<br/>";


/**************************************************************************** */



?>
</BODY>
</HTML>
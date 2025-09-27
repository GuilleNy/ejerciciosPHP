<HTML>
<HEAD><TITLE> EJ3-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
$ip="192.168.16.100/16";

$num_masc=explode("/", $ip);
$ip_dec=$num_masc[0];

$ipBinario=convertirIpBinario($ip_dec);
$mascara=obtenerMascara($num_masc[1], $ipBinario);

print_r (convertirIpBinario($ip)) ;
echo "<br/>";

print_r(obtenerMascara($num_masc[1], $ipBinario));

echo "<br/>";
echo obtenerDireccionRed($mascara, $ipBinario);

function convertirIpBinario($ip){ //devuelve un array
    $ipBinario="";
    $ipArray=explode(".", $ip);//obtengo en una array la ip separado

    for ($i=0; $i < count($ipArray) ; $i++) {
        $ipBinario.=str_pad(sprintf("%b",$ipArray[$i]),8,0,STR_PAD_LEFT);
        if($i < count($ipArray) - 1){
            $ipBinario.=".";
        }else{
            $ipBinario.=" ";
        }
    }
    $ip_arr=explode(".", $ipBinario );
    return $ip_arr;
}


//obtengo un string con la ip en binario.
function obtenerMascara($masc, $ipBinario){     //devuelve un array
    $mascara=$masc;
    $mascaraBin=array();

    for ($i=0; $i < count($ipBinario); $i++) { 
    
        if($mascara >= 8){
            $mascaraBin[$i]="11111111";
            $mascara-=8;
        }else{
            $mascaraBin[$i]=str_repeat("1", $mascara);
            $mascara-=$mascara;
            $mascaraBin[$i]=str_pad($mascaraBin[$i],8,0,STR_PAD_RIGHT);
        }
    }
    return $mascaraBin; //devuelve una cadena 
}



function obtenerDireccionRed($mascaraBin , $ipBin){
    //$mascaraBin
    //$ipBin
    $red=array();

    for ($i=0; $i < count($mascaraBin) ; $i++) { 
        $redBinario="";
        for ($j=0; $j < 8; $j++) { 
            if($mascaraBin[$i][$j]== "1" && $ipBin[$i][$j] =="1"){
                $redBinario.= "1";
            }else{
                $redBinario.= "0";
            }
        }
        $red[]=$redBinario;
    }

    $ipDec="";
    for ($i=0; $i < count($red) ; $i++) {
        $ipDec.=bindec($red[$i]);
        if($i < count($red) - 1){
            $ipDec.=".";
        }else{
            $ipDec.=" ";
        }
    }
    return $ipDec;
}



/*

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

*/

?>
</BODY>
</HTML>
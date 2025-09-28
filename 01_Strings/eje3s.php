<HTML>
<HEAD><TITLE> EJ3-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
$ip="192.168.16.100/21";

$num_masc=explode("/", $ip);
$ip_dec=$num_masc[0];

$ipBinario=convertirIpBinario($ip_dec);
$mascara=obtenerMascara($num_masc[1], $ipBinario);

echo "IP " . $ip . "<br/>";

echo "Mascara " . $num_masc[1] . "<br/>";

echo "Dirección Red: " . obtenerDireccionRed($mascara, $ipBinario) . "<br/>";

echo "Dirección Broadcast: " . (obtenerbroadcast($ipBinario, $mascara, $num_masc[1])) . "<br/>";



/*
print_r (convertirIpBinario($ip)) ;
echo "<br/>";

print_r(obtenerMascara($num_masc[1], $ipBinario));
*/

function convertirIpBinario($ip){ //devuelve un array
    $ipArray=explode(".", $ip);//obtengo en una array la ip separado
    $ipBinario=array();

    for ($i=0; $i < count($ipArray) ; $i++) {
        $ipBinario[]=str_pad(sprintf("%b",$ipArray[$i]),8,0,STR_PAD_LEFT);  
    }
    return $ipBinario;
}


//obtengo un string con la ip en binario.
function obtenerMascara($masc, $ipBinario){     //devuelve un array
    $mascara=$masc;
    $mascaraBin=array();

    for ($i=0; $i < count($ipBinario); $i++) { 
        if($mascara >= 8){
            $mascaraBin[$i]="11111111";
            $mascara-=8;
        }else if($mascara >= 0){
            $mascaraBin[$i]=str_pad(str_repeat("1", $mascara), 8, "0", STR_PAD_RIGHT);
            $mascara=0;  
        }
    }
    return $mascaraBin; //devuelve un array 
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
    return $ipDec;//envia una array
}


function obtenerbroadcast($ip, $mascara, $num_masc){
    $red=obtenerDireccionRed($ip, $mascara);
    $redArray=explode(".", $red);
    $redCadena=array();

    for ($i=0; $i < count($redArray); $i++) { 
        $redCadena[]=str_pad(sprintf("%b",$redArray[$i]),8,0,STR_PAD_LEFT); 
    }
  
    //lo convertimos en una cadena sin los puntos
    $binarioCompleto = implode("", $redCadena);

   
    for ($i = $num_masc; $i < 32; $i++) {
        $binarioCompleto[$i] = "1";
    }
    
    //separaramos otra vez en octetos
    $broadcastBin = str_split($binarioCompleto, 8);

    //convertimos a decimal
    $broadcastDec = array();
    foreach ($broadcastBin as $octeto) {
        $broadcastDec[] = bindec($octeto);
    }
    $broadcastDecimal=implode(".", $broadcastDec);

    return $broadcastDecimal;  //devuelve una cadena
}



?>
</BODY>
</HTML>
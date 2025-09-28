
<HTML>
<HEAD><TITLE> EJ1B – Conversor decimal a binario</TITLE></HEAD>
<BODY>
<?php
/** 
 * Transformar un número decimal a binario usando bucles (no se podrá utiliza la
 * función decbin)
*/

$num_1="168";
$num_2="128";
$num_3="127";
$num_4="1";
$num_5="2";

echo "Numero " . $num_1 . " en binario = " . convertirBinario($num_1) . "<br/>";
echo "Numero " . $num_2 . " en binario = " . convertirBinario($num_2) . "<br/>";
echo "Numero " . $num_3 . " en binario = " . convertirBinario($num_3) . "<br/>";
echo "Numero " . $num_4 . " en binario = " . convertirBinario($num_4) . "<br/>";
echo "Numero " . $num_5 . " en binario = " . convertirBinario($num_5) . "<br/>";


function convertirBinario($numero){
    $num_int=(int)$numero;
    $num_binario="";
    $cont=0;
    while ($num_int > 0) {
        $resto=$num_int % 2;
        $num_int= intdiv($num_int, 2);
        $num_binario= $resto . $num_binario;
        $cont++;
    }
    
    if($cont <= 2){
        $num_binario=(int)$num_binario;
    }else{
        $num_binario=str_pad($num_binario, 8, "0", STR_PAD_LEFT);
        
    }
    
    return $num_binario;
}


?>
</BODY>
</HTML>
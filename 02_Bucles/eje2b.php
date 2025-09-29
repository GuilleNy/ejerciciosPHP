<HTML>
<HEAD><TITLE> EJ2B – Conversor Decimal a base n </TITLE></HEAD>
<BODY>
<?php

$num_1="48";
$base_1="8";

$num_2="48";
$base_2="2";

$num_3="48";
$base_3="4";

$num_4="48";
$base_4="6";


echo "Numero " . $num_1 . " en base " . $base_1 . " = " . convertirCualquierbase($num_1, $base_1) . "<br/>";
echo "Numero " . $num_2 . " en base " . $base_2 . " = " . convertirCualquierbase($num_2, $base_2) . "<br/>";
echo "Numero " . $num_3 . " en base " . $base_3 . " = " . convertirCualquierbase($num_3, $base_3) . "<br/>";
echo "Numero " . $num_4 . " en base " . $base_4 . " = " . convertirCualquierbase($num_4, $base_4) . "<br/>";


function convertirCualquierbase($numero, $base){
    $num_int=(int)$numero;
    $num_conv="";
    $cont=0;
    while ($num_int > 0) {
        $resto=$num_int % $base;
        $num_int= intdiv($num_int, $base);
        $num_conv= $resto . $num_conv;
        $cont++;
    }
    if($base == 2)
    {
        $num_conv=str_pad($num_conv, 8, "0", STR_PAD_LEFT);
    }
    return $num_conv;
}












?>
</BODY>
</HTML>
<HTML>
<HEAD><TITLE> EJ3B – Conversor Decimal a base 16</TITLE></HEAD>
<BODY>
<?php
$num_1="15";
 $base_1="16";


echo "Numero " . $num_1 . " en base " . $base_1 . " = " . convertirHexadecimal($num_1, $base_1) . "<br/>";

function convertirHexadecimal($numero, $base){

    $hexadecimal=["0","1","2","3","4","5","6","7","8","9","A","B ","C","D","E","F"];

    $num_int=(int)$numero;
    $num_conv="";
    while ($num_int > 0) {
        $resto=$num_int % $base;
        $num_int= intdiv($num_int, $base);
        $num_conv= $hexadecimal[$resto] . $num_conv;
    }
    return $num_conv;
}


?>
</BODY>
</HTML>
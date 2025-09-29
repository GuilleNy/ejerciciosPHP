<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

echo "<table table border='1' style='border-collapse: collapse; width: 200px;'>";

echo "<tr>";
    echo "<th>Indice</th>";
    echo "<th>Binario</th>";
    echo "<th>Octal</th>";
echo "</tr>";

$binario=array();
$cont=0;
while($cont < 20){
    $binario[]=sprintf("%b",$cont);
    $cont++;
}
$inverso_Bin=array_reverse($binario, true);

foreach ($inverso_Bin as $indice => $value) {
    echo "<tr>";
    echo "<td>" . $indice .  "</td>";
    echo "<td>" . $value .  "</td>";
    echo "<td>" . sprintf("%o",$indice) .  "</td>";
    echo "</tr>";
}






echo "</table>";
?>
</BODY>
</HTML>

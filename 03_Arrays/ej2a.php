<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

echo "<table table border='1' style='border-collapse: collapse; width: 200px;'>";

$impares=array();
$cont=0;
$i=1;
while($cont <= 20){
    if(($i % 2) != 0){
        $impares[]=$i;
        $cont++;
    }
    $i++;
}
echo "<tr>";
    echo "<th>Indice</th>";
    echo "<th>Valor</th>";
    echo "<th>Suma</th>";
echo "</tr>";

$suma=0;
for ($i=0; $i < count($impares); $i++) { 
    $suma+=$impares[$i];
    echo "<tr>";
    echo "<td>" . $i .  "</td>";
    echo "<td>" . $impares[$i] .  "</td>";
    echo "<td>" . $suma .  "</td>";
    echo "</tr>";
}
echo "</table>";
?>
</BODY>
</HTML>

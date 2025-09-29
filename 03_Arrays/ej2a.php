<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

echo "<table table border='1' style='border-collapse: collapse; width: 200px;'>";

$impares=array();
$cont=0;
$i=1;
while($cont < 20){
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
$suma_pares=0;
$suma_impares=0;
foreach ($impares as $indice => $value) { 
    $suma+=$value;

    if($indice % 2 == 0 ){
        $suma_pares+=$value;
    }else{
        $suma_impares+=$value;
    }

    echo "<tr>";
    echo "<td>" . $indice .  "</td>";
    echo "<td>" . $value .  "</td>";
    echo "<td>" . $suma .  "</td>";
    echo "</tr>";
}
echo "</table>";

$media_pares=$suma_pares / (count($impares) / 2);
$media_impares=$suma_impares / (count($impares) / 2);

echo "Media pares= " . $media_pares . "</br>";
echo "Media impares= " . $media_impares;
?>
</BODY>
</HTML>

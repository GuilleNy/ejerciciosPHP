<HTML>
<HEAD><TITLE> Ejercicios Arrays Multidimensionales</TITLE></HEAD>
<BODY>
<?php

/* definir una matriz de 3x3 con números aleatorios. Generar un array que contenga
los valores máximos de cada fila y otro que contenga los promedios de la mismas. Mostrar el contenido
de ambos arrays por pantalla.
*/

$matriz = array();

for ($i=0; $i < 3; $i++) { 
    for ($j=0; $j < 3; $j++) { 
        $matriz[$i][$j]=rand(1,100);
    }
}

echo "<table border='1' style='border-collapse: collapse; width:200px;'>";
for ($i = 0; $i < count($matriz); $i++) { // Columnas
    echo "<tr>";
    for ($j = 0; $j < count($matriz[$i]); $j++) { // Filas
        echo "<td> " . $matriz[$i][$j] ."</td>";
    }
    echo "</tr>";
}
echo "</table>";

$maximo_fila=array();
$promedio=array();

for ($i=0; $i < count($matriz); $i++) { 
    $max=0;
    $suma=0;
    for ($j=0; $j < count($matriz[$i]); $j++) { 
        if($max < $matriz[$i][$j]){
            $max=$matriz[$i][$j];
        }   
        $suma+=$matriz[$i][$j];
    }
    $maximo_fila[]=$max;
    $promedio[]= (int) ($suma / count($matriz[$i]));
}
print_r($maximo_fila);
echo "</br>";
print_r($promedio);



?>
</BODY>
</HTML>

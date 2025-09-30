<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

/* definir una matriz de 5x3 tal que en cada posición contenga el número
    que resulta de sumar el número que identifica la columna con el número que identifica la fila del mismo,
    imprimir los elementos de la matriz por columnas.
*/

$matriz = array();

for ($i=0; $i < 5; $i++) { 
    for ($j=0; $j < 3; $j++) { 
        $matriz[$i][$j]=($i+1) + ($j+1);
    }
}

echo "<table border='1' style='border-collapse: collapse; width:200px;'>";
for ($j = 0; $j < 3; $j++) { // Columnas
    echo "<tr>";
    for ($i = 0; $i < 5; $i++) { // Filas
        echo "<td> " . $matriz[$i][$j] ."</td>";
    }
    echo "</tr>";
}
echo "</table>";

?>
</BODY>
</HTML>

<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

/**Programa ej1am.phpcrear una matriz de 3x3 con los sucesivos múltiplos de 2. Mostrar el contenido de la
matriz por filas tal y como se indica en la figura. */



echo "<table style='border-collapse: collapse; text-align: center;'>";
echo "<tr>";
    echo "<td style='border: none;'></td>";
    for ($i=0; $i < 3 ; $i++) { 
        echo "<td style='border: none; '>Col " . ($i+1) . "</td>";
    }  
echo "</tr>";

$multiplo=2;
$cont=1;
for ($i=0; $i < 3; $i++) { 
    echo "<tr>";
    echo "<td style='border: none; '>Fila " . ($i+1) . "</td>";
    for ($j=0; $j < 3 ; $j++) { 
        $multiplo=2*$cont;
        echo "<td style='border: 1px solid black; width: 90px;'>" . $multiplo .  "</td>";
        $cont++;
    }
    echo "</tr>";
}
echo "</table>";



?>
</BODY>
</HTML>

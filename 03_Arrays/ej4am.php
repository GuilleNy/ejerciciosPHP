<HTML>
<HEAD><TITLE> Ejercicios Arrays Multidimensionales</TITLE></HEAD>
<BODY>
<?php

/*Programa ej4am.phpa partir de la matriz del ejercicio anterior mostrar la fila y columna del elemento
mayor.
 */

$matriz = array(array(2,4,6,9,7),array(8,10,12,1,12), array(14,16,88,3,15));



echo "<table border='1' style='border-collapse: collapse; width:200px;'>";

   $cont=1;
    for ($i=0; $i < 3; $i++) { 
        echo "<tr>";
        //echo "<td> " . $i ."</td>";
        for ($j=0; $j < 5; $j++) { 
            echo "<td> " . $matriz[$i][$j] ."</td>";
            $cont++;
        }
        echo "</tr>";
    }

echo "</table>";

$maximo=0;
$columna=0;
$fila=0;
for ($i=0; $i < 3 ; $i++) { 
    for ($j=0; $j < 5; $j++) { 
        if($maximo < $matriz[$i][$j]){
            $maximo=$matriz[$i][$j];
            $columna=$j+1;
            $fila=$i+1;
        }
    }
}
echo "Elemento Mayor " . $maximo . " - " . " fila " . $fila . " columna " . $columna;

?>
</BODY>
</HTML>

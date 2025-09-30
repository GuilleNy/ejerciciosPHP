<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

/**crear una matriz de 3x5 mostrarla por pantalla imprimiendo los elementos por fila
en primer lugar y a continuación por columna
 */

$matriz = array(array(2,4,6,9,7),array(8,10,12,1,12), array(14,16,88,3,15));


/*
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
*/
echo "Elementos por fila: </br>";
for ($i=0; $i < 3; $i++) { 
    for ($j=0; $j < 5; $j++) { 
        echo " ( " . ($i+1) . " , " . ($j+1) . " )= " . $matriz[$i][$j] ;
        if (!($i == 2 && $j == 4)) {
            echo " - ";
        }
    }
}
 echo "</br>";

echo "Elementos por columna: </br>";
for ($j=0; $j < 5; $j++) { 
    for ($i=0; $i < 3; $i++) { 
        echo " ( " . ($i+1) . " , " . ($j+1) . " )= " . $matriz[$i][$j] ;
        if (!($i == 2 && $j == 4)) {
            echo " - ";
        }
    }
    
}





?>
</BODY>
</HTML>

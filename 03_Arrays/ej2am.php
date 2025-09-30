<HTML>
<HEAD><TITLE> Ejercicios Arrays Multidimensionales</TITLE></HEAD>
<BODY>
<?php

/**modificar el ejercicio anterior para mostrar la suma de los elementos por filas y por
columnas. Los valores se almacenarán en dos arrays.
 */

$multiplo=2;
$cont=1;

$sumaFilas=array();
$sumaColum=array_fill(0,3,0); //inicializamos este array con tres elementos inicializado a 0.

for ($i=0; $i < 3; $i++) { 
    $suma_filas=0;
    for ($j=0; $j < 3 ; $j++) { 
        $multiplo=2*$cont;
        $cont++;

        $suma_filas+=$multiplo;
        $sumaColum[$j]+=$multiplo;
    }
    $sumaFilas[]=$suma_filas; //la suma total de la fila se guarda en el array de filas.
}

/*
print_r($sumaFilas);
echo "</br>";
print_r($sumaColum);
*/
//Suma por filasç
echo "SUMA POR FILAS: ";

echo "<table border='1' style='border-collapse: collapse; width:40px;'>";

foreach ($sumaFilas as $indice => $value) {
    echo "<tr>"; 
    echo "<td> " . $value . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "SUMA POR COLUMNAS: ";

echo "<table border='1' style='border-collapse: collapse; width:150px;'>";
echo "<tr>"; 
foreach ($sumaColum as $indice => $value) {
    
    echo "<td> " . $value . "</td>";
    
}
echo "</tr>";
echo "</table>";



?>
</BODY>
</HTML>

<?php

echo "<table border='1 solid'>";

echo "<tr>";
    echo "<th>titulo</th>";
    echo "<td>fila uno</td>";
    echo "<td>fila uno</td>";
echo "</tr>";
echo "<tr>";
    echo "<th>Tema</th>";
    echo "<td>fila dos</td>";
    echo "<td>fila dos</td>";
echo "</tr>";
echo "<tr>";
    echo "<th>Introduccion</th>";
    echo "<td>fila tres</td>";
    echo "<td>fila tres</td>";
echo "</tr>";

echo "</table>";

$a=1;
$b=2;

function suma(&$a)
{
    $a=$a+5;
}

suma($a);

print($a);


?>
<?php
//strlen()
$cadena="Maria";
echo strlen($cadena);
//

echo "<br>";
//range() , me devuelve un array de 1 al 60.
$cadena2=range(1,60);
//
echo "<br>";

//ejemplo 1
/*shuffle
shuffle($cadena2);
 foreach ($cadena2 as $valor)
 {
    echo "Valor: " . $valor;
    echo "<br>";
 }
*/


//ejemplo 2 
$numeros=[];
$bolas=range(1,3);

foreach($bolas as $valor)
{
    $numeros[$valor]=rand(1,20);
}

var_dump($numeros);
echo "<br>";

$color1=array("rojo", "verde", "azul");
$medidas=array(12,34,23,4323,65,76,33);

$colores=array('rojo'=>101,'azul'=>104, 'verde'=>23);




var_dump($colores);

echo "<br>";
var_dump($medidas);

echo "<br>";

echo $colores['azul'];

echo "<br>";
echo $medidas[2];
echo "<br>";

foreach($colores as $valor) 
{
    echo "Valor: " . $valor;
    echo "<br>";
}

echo "<br>";

foreach ($colores as $clave => $valor)
{
    echo "Clave: " . $clave . " ; " . "Valor: " . $valor;
    echo "<br>";
}







?>
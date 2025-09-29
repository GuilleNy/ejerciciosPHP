<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

$array_1=array("Bases Datos", "Entorno Desarrollo", "Programación");
$array_2=array("Sistemas Informáticos", "FOL", "Mecanizado");
$array_3=array("Desarrollo Web ES", "Desarrollo Web EC", "Despliegue", "Desarrollo Interfaces", "Ingles");


$array_4=array();

foreach ($array_1 as $indice => $value) {
    $array_4[]=$value;
}
foreach ($array_2 as $indice => $value) {
    $array_4[]=$value;
}
foreach ($array_3 as $indice => $value) {
    $array_4[]=$value;
}
/*
print_r($array_4);
echo "</br>";
echo "</br>";
*/

//Con array_merge()

echo "Union con la funcion array_merge() : </br>";
$union_merge=array_merge($array_1,$array_2,$array_3);
/*
print_r($union_merge);
echo "</br>";
echo "</br>";
*/


//Con array_push()
$union_push=array();

foreach ($array_1 as $indice => $value) {
    array_push($union_push, $value);
}
foreach ($array_2 as $indice => $value) {
    array_push($union_push, $value);
}
foreach ($array_3 as $indice => $value) {
    array_push($union_push, $value);
}

//print_r($union_push);




?>
</BODY>
</HTML>

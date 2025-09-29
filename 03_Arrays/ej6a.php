<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

$array_1=array("Bases Datos", "Entorno Desarrollo", "Programación");
$array_2=array("Sistemas Informáticos", "FOL", "Mecanizado");
$array_3=array("Desarrollo Web ES", "Desarrollo Web EC", "Despliegue", "Desarrollo Interfaces", "Ingles");

//Eliminar del array la cadena "Mecanizado" .

$array_2=array_diff($array_2, ["Mecanizado"]);

//Unir sin utilizar funciones.
echo "Union sin funciones de array : </br>";

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

$inverso_1=array_reverse($array_4);
print_r($inverso_1);
echo "</br>";
echo "</br>";


//Con array_merge()

echo "Union con la funcion array_merge() : </br>";
$union_merge=array_merge($array_1,$array_2,$array_3);
$inverso_2=array_reverse($union_merge);
print_r($inverso_2);
echo "</br>";
echo "</br>";

//Con array_push()

echo "Union con la funcion array_push() : </br>";
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
$inverso_3=array_reverse($union_push);

print_r($inverso_3);




?>
</BODY>
</HTML>

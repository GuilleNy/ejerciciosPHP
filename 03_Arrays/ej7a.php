<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

$estudiantes=["Alejandro"=> 20 , "Roberto" => 24, "Julia"=>19, "Mario"=> 27, "Ana"=> 23 ];

//Mostrar el contenido del array utilizando bucles.
foreach ($estudiantes as $indice => $value) {
    echo $indice . " tiene " . $value . " años.</br>";
}

//Sitúa el puntero en la segunda posición del array y muestra su valor
next($estudiantes);
echo current($estudiantes) . "</br>";

//Avanza una posición y muestra el valor
next($estudiantes);
echo current($estudiantes) . "</br>";

//Coloca el puntero en la última posición y muestra el valor
end($estudiantes);
echo current($estudiantes) . "</br>";

//Ordena el array por orden de edad (de menor a mayor) y muestra la primera posición del
//array y la última.
sort($estudiantes);
//print_r($estudiantes);

$primera=reset($estudiantes);
$ultima=end($estudiantes);

echo "Primera posición: " . $primera . "</br>";
echo "Primera posición: " . $ultima;





?>
</BODY>
</HTML>

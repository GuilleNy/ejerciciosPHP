<HTML>
<HEAD><TITLE> Ejercicios Arrays Unidimensionales</TITLE></HEAD>
<BODY>
<?php

$notas_BD=["Alejandro"=> 6 , "Roberto" => 7, "Julia"=>9, "Mario"=> 5, "Ana"=> 7 ];

//Mostrar el alumno con mayor nota.
$maximo=max($notas_BD);
echo "Mayor: " . $maximo . "</br>";

//Mostrar el alumno con menor nota.
$minimo=min($notas_BD);
echo "Menor: " . $minimo . "</br>";

//Media notas obtenidas por los alumnos
$suma=array_sum($notas_BD);
$num_alumnos=count($notas_BD);

$media=$suma / $num_alumnos;

echo "Media: " . $media;


?>
</BODY>
</HTML>

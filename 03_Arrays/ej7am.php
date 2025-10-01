<HTML>
<HEAD><TITLE> Ejercicios Arrays Multidimensionales</TITLE></HEAD>
<BODY>
<?php

/* definir una matriz que permita almacenar la nota de 10 alumnos en 4 asignaturas
diferentes. Se pide:
a. Mostrar por pantalla el alumno con mayor nota en una asignatura determinada.
b. Mostrar por pantalla el alumno con menor nota en una asignatura determinada.
c. Para un alumno, mostrar en que materia tiene su nota más baja.
d. Para un alumno, mostrar en que materia tiene su nota más alta.
e. Mostrar la media por materia de todos los alumnos.
f. Mostrar la media por alumno para todas las materias

*/


$notas= ["Fred" => ["Programacion"=> 5, "BBDD"=> 8, "Java"=> 5, "FOL"=> 6],
        "Alejandro" => ["Programacion"=> 8, "BBDD"=> 6, "Java"=> 5, "FOL"=> 7],
        "Maria" => ["Programacion"=> 5, "BBDD"=> 7, "Java"=> 8, "FOL"=> 7],
        "Julia" => ["Programacion"=> 4, "BBDD"=> 6, "Java"=> 8, "FOL"=> 8],
        "Leo" => ["Programacion"=> 9, "BBDD"=> 6, "Java"=> 6, "FOL"=> 7],
        "Mateo" => ["Programacion"=> 5, "BBDD"=> 6, "Java"=> 5, "FOL"=> 8],
        "Yulli" => ["Programacion"=> 6, "BBDD"=> 4, "Java"=> 7, "FOL"=> 5],
        "Dani" => ["Programacion"=> 6, "BBDD"=> 5, "Java"=> 8, "FOL"=> 5],
    ];


//a. Mostrar por pantalla el alumno con mayor nota en una asignatura determinada.
$mayor=array();
$mayor_nota=0;
foreach ($notas as $alumno => $asignatura) {
    if(($asignatura["Programacion"] > $mayor_nota) ){
        $mayor_nota=$asignatura["Programacion"];
        $mayor=[$mayor_nota, $alumno];
    }
}
echo "El alumno con mayor nota en Programación es: " . $mayor[1] . "</br>";
echo "Nota: " . $mayor[0];

var_dump($mayor);

//var_dump($notas);


//b. Mostrar por pantalla el alumno con menor nota en una asignatura determinada
$menor=array();
$menor_nota=10;
foreach ($notas as $alumno => $asignatura) {
    if(($asignatura["Programacion"] < $menor_nota) ){
        $menor_nota=$asignatura["Programacion"];
        $menor=[$menor_nota, $alumno];
    }
}
echo "El alumno con menor nota en Programación es: " . $menor[1] . "</br>";
echo "Nota: " . $menor[0];

var_dump($menor);

//c. Para un alumno, mostrar en que materia tiene su nota más baja
$alumno="Mateo";

$menor=array();
$menor_nota=10;
foreach ($notas as $alumno => $asignatura) {
    if(($asignatura["Programacion"] < $menor_nota) ){
        $menor_nota=$asignatura["Programacion"];
        $menor=[$menor_nota, $alumno];
    }
}
echo "El alumno con menor nota en Programación es: " . $menor[1] . "</br>";
echo "Nota: " . $menor[0];

var_dump($menor);






?>
</BODY>
</HTML>

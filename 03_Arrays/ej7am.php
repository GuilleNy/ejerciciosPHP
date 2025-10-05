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
        "Maria" => ["Programacion"=> 9, "BBDD"=> 7, "Java"=> 8, "FOL"=> 7],
        "Julia" => ["Programacion"=> 4, "BBDD"=> 6, "Java"=> 8, "FOL"=> 8],
        "Leo" => ["Programacion"=> 9, "BBDD"=> 6, "Java"=> 6, "FOL"=> 7],
        "Mateo" => ["Programacion"=> 5, "BBDD"=> 6, "Java"=> 3, "FOL"=> 8],
        "Yulli" => ["Programacion"=> 6, "BBDD"=> 4, "Java"=> 7, "FOL"=> 5],
        "Dani" => ["Programacion"=> 6, "BBDD"=> 7, "Java"=> 8, "FOL"=> 5],
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
echo "Nota: " . $mayor[0] . "</br>";

//var_dump($mayor);

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
echo "Nota: " . $menor[0] . "</br>";

//var_dump($menor);

//c. Para un alumno, mostrar en que materia tiene su nota más baja
$nom_alumno="Mateo";

$menor_alu=array();
$menor_nota=10;
foreach ($notas as $alumno => $asignatura) {
    foreach ($asignatura as $nombre_asg => $nota) {
        if(($alumno == $nom_alumno) && ($nota < $menor_nota)){
            
            $menor_nota=$nota;
            $menor_alu=[$nombre_asg, $nota];
        }
    }
}
echo "La materia con la nota mas baja del alumno " . $nom_alumno . " es: " .  $menor_alu[0] . "</br>";
echo "Nota: " . $menor_alu[1] . "</br>";

//d. Para un alumno, mostrar en que materia tiene su nota más alta.
$nom_alumno="Mateo";

$mayor_alu=array();
$mayor_nota2=0;
foreach ($notas as $alumno => $asignatura) {
    foreach ($asignatura as $nombre_asg => $nota) {
        if(($alumno == $nom_alumno) && ($nota > $mayor_nota2)){
            
            $mayor_nota2=$nota;
            $mayor_alu=[$nombre_asg, $nota];
        }
    }
}
echo "La materia con la nota mas alta del alumno " . $nom_alumno . " es: " .  $mayor_alu[0] . "</br>";
echo "Nota: " . $mayor_alu[1] . "</br>";

//e. Mostrar la media por materia de todos los alumnos.
$materia=array();

foreach ($notas as $alumno => $asignatura) {
    foreach ($asignatura as $nombre_asg => $nota) {
        $materia[$nombre_asg][] = $nota;
    }
}

foreach ($materia as $nombre_asg => $nota) {
    $media = array_sum($nota) / count($nota);
    echo "Media de $nombre_asg: " . round($media, 2) . "<br>";
}

echo "-------------------------------------------------------- </br>";
//f. Mostrar la media por alumno para todas las materias

foreach ($notas as $alumno => $asignatura) {
    $suma=array_sum($asignatura);
    $media=$suma / count($asignatura);

    echo "Media de $alumno: " . round($media, 2) . "<br>";
}


?>
</BODY>
</HTML>

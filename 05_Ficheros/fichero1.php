<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficheros</title>
</head>
<body>
    <h1> Alumno 1</h1>
    <form action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="nombre">Nombre: </label>
        <input type="text" name="nombre" >

        <br>
        <br>
        <label for="apellido1">Apellido1: </label>
        <input type="text" name="apellido1">
        <br>
        <br>

        <label for="apellido2">Apellido2: </label>
        <input type="text" name="apellido2" >
        <br>
        <br>

        <label for="fechNac">Fecha Nacimiento: </label>
        <input type="date" name="fechaNacimiento" >
        <br>
        <br>

        <label for="localidad">Localidad: </label>
        <input type="text" name="localidad">
        <br>
        <br>
        <button type="submit">Enviar</button>
        <button type="reset">Borrar</button>
        
    </form>
</body>
</html>


<?php
/*
formulario que recoja los datos de alumnos y los almacene un fichero con
nombre alumnos1.txt (una fila por alumno). Los campos del fichero estarán separados por posiciones:
    Nombre: posición 1 a 40
    Apellido1: posición 41 a 81
    Apellido2: posición 82 a 123
    Fecha Nacimiento: posición 124 a 133
    Localidad: posición 134 a 160
Las posiciones no ocupadas se completarán con espacios. 

 */
include "otrasFunciones.php";



resultado();

/*******************************FUNCIONES************************************ */
function resultado(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        recogerDatos();
    }
}

function recogerDatos(){
    $nombre = depurar($_POST['nombre']);    
    $apellido_1 = depurar($_POST['apellido1']);
    $apellido_2 = depurar($_POST['apellido2']);
    $fecha_Nac = depurar($_POST['fechaNacimiento']);
    $fecha_Nac = date("d/m/Y", strtotime($fecha_Nac));
    $localid = depurar($_POST['localidad']);

    crearFichero($nombre, $apellido_1, $apellido_2, $fecha_Nac, $localid);
}


function crearFichero($nombre, $apellido_1, $apellido_2, $fecha_Nac, $localid){

    $fichero = fopen("alumnos1.txt", "a+" );

    fwrite($fichero, str_pad($nombre,40," ",STR_PAD_RIGHT));
    fwrite($fichero, str_pad($apellido_1,(81-41)," ",STR_PAD_RIGHT));
    fwrite($fichero, str_pad($apellido_2,(123-82)," ",STR_PAD_RIGHT));
    fwrite($fichero, str_pad($fecha_Nac,(124-133)," ",STR_PAD_RIGHT));
    fwrite($fichero, str_pad($localid,(160-134)," ",STR_PAD_RIGHT));
    fwrite($fichero, "\n");

    fclose($fichero);
}







?>
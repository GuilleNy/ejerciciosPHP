<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficheros</title>
</head>
<body>
    <h1> Alumno 2</h1>
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
nombre alumnos2.txt (una fila por alumno). Los campos del fichero estarán separados utilizando como
caracteres delimitadores ##
    Nombre##Apellido1##Apellido2##Fecha Nacimiento##Localidad
No se completarán con espacios los campos puesto que se separan por caracteres delimitadores. 
 */
include "otrasFunciones.php";



resultado();

/*******************************FUNCIONES************************************ */
function resultado(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (!file_exists("alumnos2.txt") || filesize("alumnos2.txt") == 0) {
            crearFichero();
        }
        recogerDatos();
    }
}


function crearFichero(){
    $fichero = fopen("alumnos2.txt", "w" );
    fwrite($fichero, str_pad("Nombre",8,"#",STR_PAD_RIGHT));
    fwrite($fichero, str_pad("Apellido1",11,"#",STR_PAD_RIGHT));
    fwrite($fichero, str_pad("Apellido2",11,"#",STR_PAD_RIGHT));
    fwrite($fichero, str_pad("Fecha Nacimiento",18,"#",STR_PAD_RIGHT));
    fwrite($fichero,"Localidad");
    fwrite($fichero, "\n");
    fclose($fichero);
}

function recogerDatos(){
    $nombre = depurar($_POST['nombre']);    
    $apellido_1 = depurar($_POST['apellido1']);
    $apellido_2 = depurar($_POST['apellido2']);
    $fecha_Nac = depurar($_POST['fechaNacimiento']);
    $fecha_Nac = date("d/m/Y", strtotime($fecha_Nac));
    $localid = depurar($_POST['localidad']);

    agregarDatos($nombre, $apellido_1, $apellido_2, $fecha_Nac, $localid);
}


function agregarDatos($nombre, $apellido_1, $apellido_2, $fecha_Nac, $localid){

    $fichero = fopen("alumnos2.txt", "a+" );

    fwrite($fichero, str_pad($nombre,8,"#",STR_PAD_RIGHT));
    fwrite($fichero, str_pad($apellido_1,11,"#",STR_PAD_RIGHT));
    fwrite($fichero, str_pad($apellido_2,11,"#",STR_PAD_RIGHT));
    fwrite($fichero, str_pad($fecha_Nac,18,"#",STR_PAD_RIGHT));
    fwrite($fichero, $localid);
    fwrite($fichero, "\n");

    fclose($fichero);
}

?>
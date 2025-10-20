<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>IP Binario</title>
</head>
<body>
    <h1> IPs </h1>
    <form action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="nombre">Nombre: </label>
        <input type="text" name="ip" value="nombre">

        <br>
        <br>
        <label for="apellido1">Apellido1: </label>
        <input type="text" name="apellido1" value="apellido1">
        <br>
        <br>

        <label for="apellido2">Apellido2: </label>
        <input type="text" name="apellido2" value="apellido2">
        <br>
        <br>

        <label for="fechNac">Fecha Nacimiento: </label>
        <input type="text" name="fechaNacimiento" value="fechaNacimiento">
        <br>
        <br>

        <label for="localidad">Localidad: </label>
        <input type="text" name="localidad" value="localidad">
        
        <button type="submit">Notacion decimal</button>
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







?>
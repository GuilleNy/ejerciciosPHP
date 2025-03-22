<?php
    if (isset($_REQUEST['registrar'])) {
        $nombre = $_REQUEST['nombre'];
        $apellido = $_REQUEST['apellido'];
        $correo = $_REQUEST['correo'];

        $linea = $nombre . "," . $apellido . "," . $correo . PHP_EOL; // PHP_EOL es una forma de manejar saltos de línea.
        file_put_contents('usuarios.txt', $linea, FILE_APPEND);

        //FILE_APPEND es muy útil cuando necesitas agregar información a un archivo sin perder los datos previos. 
        //Solo recuerda incluir PHP_EOL si quieres que cada entrada quede en una nueva línea.

        echo "<p>Usuario registrado correctamente.</p>";
    }
    ?>
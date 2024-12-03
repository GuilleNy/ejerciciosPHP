<?php
    if (isset($_REQUEST['registrar'])) {
        $nombre = $_REQUEST['nombre'];
        $apellido = $_REQUEST['apellido'];
        $correo = $_REQUEST['correo'];

        $linea = $nombre . "," . $apellido . "," . $correo . PHP_EOL;
        file_put_contents('usuarios.txt', $linea, FILE_APPEND);

        echo "<p>Usuario registrado correctamente.</p>";
    }
    ?>
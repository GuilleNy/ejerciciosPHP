<?php
    require_once ("controller_session.php");
    require_once ("controller_comunes.php");

    sessionStart(); // si ponemos una url a este controlador sin iniciar sesión, se redirige al login, pero no se porque se crea una sesión vacía yredirije al login.

 

    if(!verificarSesion()) //func_sesiones.php
    {
        header("Location: ../index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['altaped'])) {
            // el controlador decide
            header("Location: controller_altaped.php");
            exit;
        }

        if (isset($_POST['consped'])) {
            // el controlador decide
            header("Location: controller_consped.php");
            exit;
        }

        if (isset($_POST['consprodstock'])) {
            // el controlador decide
            header("Location: controller_consprodstock.php");
            exit;
        }

        if (isset($_POST['cerrarsesion'])) {
            // el controlador decide
            header("Location: controller_logout.php");
            exit;
        }
    }


    require_once ("../views/view_inicio.php");
?>
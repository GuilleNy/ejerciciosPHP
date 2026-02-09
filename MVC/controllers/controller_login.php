<?php
    require_once ("controller_session.php");
    require_once ("controller_comunes.php");



    alertalogin();  
    if(verificarSesion()){
        header("Location: controllers/controller_inicio.php");
        exit();
    }else if(isset($_POST['login']))
    {
        list($usuario, $clave)=recogerDatos();
        require_once ("models/model_login.php");
        $resultado = comprobarLogin($conn, $usuario, $clave);
        if($resultado == null){
            noLogin();
        }else
        {
            $usuarioLogin = $resultado["customerNumber"];
            $contraLogin= $resultado["contactLastName"];
            iniciarSesion($conn, $usuario, $clave);
            header("Location: controllers/controller_inicio.php");
            exit(); 
        }
    }


    require_once ("views/view_login.php");

?>
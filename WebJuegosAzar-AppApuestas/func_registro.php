<?php
session_start();
include_once "conexionBaseDeDatos.php";
include_once "otrasFunciones.php";



/*****************************PROGRAMA PRINCIPAL************************* */
if(isset($_POST['registro'])){
    if(validar()){// si validar es true realiza la recoleccion de datos
        list($dni, $nombre, $apellido, $email)=recogerDatosRegistro();
        ingresarRegistro($dni, $nombre, $apellido, $email);
    }else{//si validar es false entonces manda un mensaje de error.
        $_SESSION['mensajeRegistroWarning'] = "Completa Todos los campos.";
        header("Location: registro.php");
        exit();
    }
}else if(isset($_POST['login']))
{
    header("Location: ./login.php");
}   
/*************************************************************************** */


function validar(){
    $enviar=true;
    if(empty(trim($_POST['dni']))){
        $enviar=false;
    }else if(empty(trim($_POST['nombre']))){
        $enviar=false;
    }else if(empty(trim($_POST['apellido']))){
        $enviar=false;
    }else if(empty(trim($_POST['email']))){
        $enviar=false;
    }
    return $enviar;
}


function recogerDatosRegistro(){
    $VstDni=depurar($_POST['dni']);
    $VstNombre=depurar($_POST['nombre']);
    $VstApellido=depurar($_POST['apellido']);
    $VstEmail=depurar($_POST['email']);
    return [$VstDni, $VstNombre, $VstApellido, $VstEmail];
}

function ingresarRegistro($dni, $nombre, $apellido, $email){
    $conn=conexionBBDD();

    $stmt=$conn->prepare("INSERT INTO apostante(DNI, NOMBRE, APELLIDO, EMAIL)
                        VALUES (:VstDni, :VstNombre, :VstApellido, :VstEmail)");
    $stmt->bindParam(':VstDni', $dni);
    $stmt->bindParam(':VstNombre', $nombre);
    $stmt->bindParam(':VstApellido', $apellido);
    $stmt->bindParam(':VstEmail', $email);

    
    if($stmt->execute()){
        $_SESSION['mensajeRegistro'] = "Registro completado";
        header("Location: registro.php");
        exit();
        
    }
}

?>
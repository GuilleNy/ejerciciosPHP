<?php
session_start();
include_once "func_sesiones.php";
include_once "conexionBaseDeDatos.php";
include_once "otrasFunciones.php";


/*****************************PROGRAMA PRINCIPAL************************* */
if(isset($_POST['login'])){
    list($usuario, $contraseña)=recogerDatos();
    verificarDatos($usuario, $contraseña); //las variables ($usuario, $contraseña) las tengo que poner tal cual en la funcion de verificarDatos($usuario, $contraseña)
}else if(isset($_POST['registrarse']))
{
    header("Location: ./registro.php");
}
/*************************************************************************** */



function recogerDatos(){
    $nomUsuario=depurar($_POST['usuario']);
    $contrUsuario=depurar($_POST['contra']);
    return [$nomUsuario, $contrUsuario];
}
//Verificar que las variables que esten dentro de verificarDatos sean las mismas de la que
//se utiliza cuando se llama a la funcion.
function verificarDatos($usuario, $contraseña) 
{
    $conn=conexionBBDD();
    

    try{
        $conn->beginTransaction();

        $stmt=$conn->prepare("SELECT DNI, APELLIDO FROM empleado WHERE DNI = :usu AND APELLIDO = :contra");
        $stmt->bindParam(':usu', $usuario);
        $stmt->bindParam(':contra', $contraseña);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado=$stmt->fetch();

    
        if($resultado != null){

            if($usuario == $resultado['DNI'] && $contraseña == $resultado['APELLIDO']){
                iniciarSesion($usuario, $contraseña);

            }
        }else{
            $_SESSION['mensajeLogin'] = "Usuario no existente";
            header("Location: ./login.php");
            exit();
        }
            

    }catch(PDOException $e)
        {
            if ($conn->inTransaction()) {
                $conn->rollBack(); 
            }
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
}

function iniciarSesion($usu, $contra){
    //si no esta creada la sesion crearmela
    if(!(isset($_SESSION["VstUsuario"]) && isset($_SESSION["VstContraseña"]))){
        crearSesion($usu, $contra);
    }

    if(verificarSesion()){
        header("Location: ./inicio.php");
    }
}

?>
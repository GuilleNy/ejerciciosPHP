<?php
include "otrasFunciones.php";


resultado();

/*******************************FUNCIONES************************************ */
function resultado(){
    $name = $email = $genero = "";
    $enviar=true;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty($_POST['nombre'])){
            echo "<p>El campo 'Nombre' es obligatorio.</p>";
            $enviar=false;
        }else{
            $name=depurar($_POST['nombre']);
        }

        if(empty($_POST['apellido1'])){
            $apellido1="";
        }else{
            $apellido1=depurar($_POST['apellido1']);
        }

        if(empty($_POST['apellido2'])){
            $apellido2="";
        }else{
            $apellido2=depurar($_POST['apellido2']);
        }

        if(empty($_POST['email'])){
            echo "<p>El campo 'Email' es obligatorio.</p>";
            $enviar=false;
        }else{
            $email=depurar($_POST['email']);
        }

        if(empty($_POST['sexo'])){
            echo "<p> Marcar un genero es obligatorio.</p>";
            $enviar=false;
        }else{
            $genero=depurar($_POST['sexo']);
        }
    }

    if($enviar){
        //Nombramos el fichero.
        $archivo = "datos.txt";
        //Variable para guardar el contenido.
        $contenido = $name . "\n" . $apellido1 . "\n" . $apellido2 . "\n" . $email . "\n" . $genero;
        //Abrimos el fichero. "a"= append para añadir al final.
        $fichero = fopen($archivo, "a");
        //Escribimos el contenido.
        fwrite($fichero, $contenido);
        fclose($fichero);

        echo "<h1>Datos Alumnos</h1></br>";
        echo "<table border='1'>";
        echo "<tr><td>Nombre</td><td>Apellidos</td><td>Email</td><td>Sexo</td></tr>";
        echo "<tr><td>" . $name . "</td><td>" . $apellido1 . " " . $apellido2 . "</td><td>" . $email . "</td><td>" . $genero . "</td></tr>";
        echo "</table>";
    }
}

?>


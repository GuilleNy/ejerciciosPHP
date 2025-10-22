<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficheros</title>
</head>
<body>
    <h1> Operaciones Ficheros</h1>
    <form action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="fichero">Fichero (Path/nombre): </label>
        <input type="text" name="fichero" required>
        <br>
        <br>
        <label for="operaciones">Operaciones: </label><br>
        <input type="radio" name="operacion" value="mostrarFichero" > Mostrar Fichero<br>
        <input type="radio" name="operacion" value="mostrarFicheroLinea"> Mostrar linea
        <input type="number" name="linea" style="width: 30px;" min="1" > fichero<br>
        <input type="radio" name="operacion" value="mostrarFichLineas"> Mostrar
        <input type="number" name="lineas" style="width: 30px;" min="1"> primeras filas<br><br>
        <button type="submit">Enviar</button>
        <button type="reset">Borrar</button>
        
    </form>
</body>
</html>


<?php
/*
formulario similar al de la imagen. El programa php deberá mostrar por pantalla
el resultado de la operación seleccionada. El programa controlará si el fichero existe o no devolviendo el
correspondiente mensaje de error
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
   $nombreFichero=depurar($_POST['fichero']);
   $opcionSeleccionada=$_POST['operacion'];

    switch ($opcionSeleccionada) {
        case 'mostrarFichero':
            //funcion para mostrar el fichero
            mostrarFichero($nombreFichero);
        case 'mostrarFicheroLinea':
            $num_Linea=depurar($_POST['linea']);
            //funcion para mostrar el contenido de la linea introducida
            mostrarLinea($num_Linea, $nombreFichero);
        case 'mostrarFichLineas':
            $num_Lineas=depurar($_POST['lineas']);
            //funcion para mostrar el contenido de la primeras filas introducidas
            mostrarPrimerasLineas($num_Lineas, $nombreFichero);
    }
}

function mostrarFichero($name_fichero){
    //el @ hace que no salga el warning en el navegador
    $lineas = @file($name_fichero) or die ("No se encuentra el archivo");
    foreach ($lineas as $valor ) {
        echo $valor . "<br>";
    }
}

//utilizar file() en lugar de fget, fgets, fgetc, con file() se puede utilizar explode() e implode().
function mostrarLinea($numLinea, $nombreFichero){
    $fichero=fopen($nombreFichero, "r") or die ("No se encuentra el archivo");
    $cont=1;
    while (!feof($fichero)) {
        $z=fgets($fichero);
        if($cont == $numLinea){
            echo $z ;
        }
        $cont++;
    }
    fclose($fichero);
}

function mostrarPrimerasLineas($numLinea, $nombreFichero){
    $fichero=fopen($nombreFichero, "r") or die ("No se encuentra el archivo");
    $cont=1;
    while (!feof($fichero)) {
        $z=fgets($fichero);
        if($cont <= $numLinea){
            echo $z . "<br>";
        }
        $cont++;
    }
    fclose($fichero);
}

?>
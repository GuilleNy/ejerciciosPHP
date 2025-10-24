
<?php
/*
que lea los datos del fichero y lo muestre por pantalla (no es necesario mostrar el
campo hora).
 */

mostrarFichero();

/*******************************FUNCIONES************************************ */

function mostrarFichero(){
    //el @ hace que no salga el warning en el navegador

    $lineas=file("ibex35.txt") or die ("No se encuentra el archivo");
    /*
    foreach ($lineas as $valor ) {
        echo $valor . "<br>";
    }
    */
    echo "<pre>";
    print_r($lineas);
    echo "</pre>";

}

?>

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
    /*
    echo "<pre>";
    print_r($lineas);
    echo "</pre>";
    */
    /*
    echo "<table>";
    echo "<tr>";
        echo "<td>Valor</td>";
        echo "<td>Ultimo</td>";
        echo "<td>Var. %</td>";
        echo "<td>Var.</td>";
        echo "<td>Ac.% año</td>";
        echo "<td>MAx.</td>";
        echo "<td>MIn.</td>";
        echo "<td>Vol.</td>";
        echo "<td>Capit.</td>";
    echo "</tr>";

    echo "</table>";
    */

    echo $lineas[5];
    $cadena=explode(" ",$lineas[5]);

    echo "<pre>";
    print_r($cadena);
    echo "</pre>";

}

?>
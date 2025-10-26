
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

    //vamos a utilizar el explode
    //echo $lineas[5];
    $cadena=explode(" ",$lineas[5]);
    print_r($cadena);
    $indice=0;
    echo "<table table border='1' style='border-collapse: collapse; width: 200px;'>";
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

    echo "<tr>";

    $cade_2=implode("#", $cadena);
    $cede_3=explode("##",$cade_2);

    foreach ($cadena as $valor) {
        if($valor != " "){
            echo "<td>" . $valor . " </td>";
        }
        
    }
            
    echo "</tr>";

    echo "</table>";
    echo $cade_2;
    
    echo "<pre>";
    print_r($cede_3);
    echo "</pre>";
    
}

?>
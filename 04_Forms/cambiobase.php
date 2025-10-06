<?php
include "otrasFunciones.php";

resultado();

/*******************************FUNCIONES************************************ */
function resultado(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $decimal=(int) depurar($_POST['decimal']);  
        $conversion= $_POST["base"];
        
        echo "<h1>CONVERSOR BINARIO</h1>";
        echo "<label>Numero Decimal:</label>";
        echo "<input value='$decimal'></br></br>";

        switch ($conversion) {
            case 'binario':
                convertirBinario($decimal);
                break;
            case 'octal':
                convertirOctal($decimal);
                break;
            case 'hexadecimal':
                convertirHexadecimal($decimal);
                break;
            case 'todos':
                convertirTodos($decimal);
                break;
        }
    }
}

function convertirBinario($decimal){
    $binario=str_pad(sprintf("%b",$decimal),8,0,STR_PAD_LEFT);
    
    echo "<table border='1'>";
    echo "<tr>";
    echo "<td>Binario</td>";
    echo "<td>" . $binario . "</td>";
    echo "</tr>";

    echo "</table>";
}

function convertirOctal($decimal){
    $octal=decoct($decimal);
    
    echo "<table border='1'>";
    echo "<tr>";
    echo "<td>Octal</td>";
    echo "<td>" . $octal . "</td>";
    echo "</tr>";

    echo "</table>";
}

function convertirHexadecimal($decimal){
    $hexadecimal=dechex($decimal);
    echo "<table border='1'>";
    echo "<tr>";
    echo "<td>Hexadecimal</td>";
    echo "<td>" . $hexadecimal . "</td>";
    echo "</tr>";

    echo "</table>";
}

function convertirTodos($decimal){
    $binario=str_pad(sprintf("%b",$decimal),8,0,STR_PAD_LEFT);

    echo "<table border='1'>";
    echo "<tr>";
    echo "<td>Binario</td>";
    echo "<td>" . $binario . "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>Octal</td>";
    echo "<td>" . decoct($decimal) . "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>Hexadecimal</td>";
    echo "<td>" . strtoupper(dechex($decimal) ). "</td>";
    echo "</tr>";

    echo "</table>";
}
?>
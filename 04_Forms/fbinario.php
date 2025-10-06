


<h1> CONVERSOR BINARIO </h1>
    <form action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="dec">Numero Decimal:</label>
        <input type="number" name="decimal">
        <br>
        <br>
        <?php
        include "otrasFunciones.php";
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            resultado();
        }
        ?>
        <br>
        <br>
        <button type="submit">Enviar</button>
        <button type="reset">Borrar</button>
        
    </form>

<?php
/*******************************FUNCIONES************************************ */
function resultado(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $decimal=(int) depurar($_POST['decimal']);    
        $dec_bin=convertirBinario($decimal);

        echo "<label>Numero Binario:</label>";
        echo "<input value='$dec_bin'>";
    }
}

function convertirBinario($decimal){
    $ipBinario=str_pad(sprintf("%b",$decimal),8,0,STR_PAD_LEFT);
    return $ipBinario;
}



?>
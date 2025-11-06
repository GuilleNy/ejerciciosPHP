<?php

function depurar($cadena){
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena; 
}
 
function verificarCampos(){

    $mensaje="";
    $enviar=true;

    if(empty($_POST['jug1'])){
        $mensaje.="<p>El campo 'Jugador 1' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['jug2'])){
        $mensaje.="<p>El campo 'Jugador 2' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['jug3'])){
        $mensaje.="<p>El campo 'Jugador 3' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['jug4'])){
        $mensaje.="<p>El campo 'Jugador 4' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['numdados'])){
        $mensaje.="<p>El campo de 'Numero Dados' es obligatorio.</p>";
        $enviar=false;
    }
    echo $mensaje;

    return $enviar;
}

//Funcion para recoger los datos de los jugadores y almacenarlos en un array asociativo, 
//donde la clave es el nombre del jugador y el valor es un array vacio para almacenar los numeros de dados.
//De paso inicializo el array de la BANCA
function recogerDatos(&$arrayJugadores ,  &$arrrayBanca){

    for ($i=0; $i < 4; $i++) { 
        $jugador=depurar($_POST["jug".($i+1)]);
        $arrayJugadores[$jugador]=array(); //me creo un array para almacenar las cartas
    }
    $arrrayBanca['BANCA']=array();
}

//Funcion para verificar el numero maximo de dados introducido por el usuario y con ellos validar la inicializacion del juego
function numMaximoDados()
{
    $enviar=true;
    $numDados=$_POST['numdados'];
    if($numDados < 2 || $numDados > 10){
        $enviar=false;
    }
    return $enviar;
}

//funcion para visualizar la tabla de los jugadores y sus respectivos dados
function visualizarTabla($arrayTodosJugaBanca){
    echo "<h2>RESULTADO JUEGO DADOS</h2>";
    echo "<table border='1'>";
    foreach($arrayTodosJugaBanca as $nombre => $dato){
        echo "<tr>";
        echo "<td>" . $nombre . "</td>";
        foreach ($dato as $indice) {
            echo "<td> <img src='images/" . $indice . ".PNG'width='100' ></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<br><br>";
}

//Funcion para rellenar a cda jugador sus dados correspondientes
function llenarDadosJugador(&$arrayJugadores, $num_dados){

    foreach ($arrayJugadores as $nombre => &$datos) {
        for ($i=0; $i < $num_dados; $i++) { 
            $datos[]=rand(1,6);
            //$datos[]=5;
        }
    }
}

//Funcion para rellenar los dados para la BANCA 
function llenarDadosBanca(&$arrrayBanca, $num_dados){
    
    foreach ($arrrayBanca as $indice => &$datos) {
        for ($i=0; $i < $num_dados; $i++) { 
            $datos[]=rand(1,6);
            //$datos[]=3;
        }
    }
}

//Funcion para juntar el array de jugadores y el array de la BANCA
function juntarArrayjugadoresYbanca(&$arrayTodosJugaBanca, $arrayJugadores, $arrrayBanca){
    $arrayTodosJugaBanca=array_merge($arrayJugadores, $arrrayBanca);
}

//Funcion para sumar todos los dados tirados por cada jugador
function sumarDadosjugadores($arrayJugadores, &$arraySumaPuntos, $num_dados){

    foreach ($arrayJugadores as $nombre => $dados) {
        if(count(array_unique($dados)) == 1)
        {
            $arraySumaPuntos[$nombre]=array_sum($dados) * $num_dados;
        }else{
            $arraySumaPuntos[$nombre]=array_sum($dados);
        } 
    }
}

//Funcion para sumar los dados tirados por la BANCA
function sumarDadosBanca($arrrayBanca, &$arraySumaPuntos){

    foreach ($arrrayBanca as $nombre => $dados) {
        if(count(array_unique($dados)) == 1)
        {
            $arraySumaPuntos[$nombre]=100;
        }else{
            $arraySumaPuntos[$nombre]=array_sum($dados);
        }
    }
}

//Funcion para visualizar la suma total de puntos de cada jugador
function visualizarPuntos($arraySumaPuntos){

    foreach ($arraySumaPuntos as $nombre => $value) {
        echo  $nombre . " = " . $value . " <br>";
    }
    echo "<br><br>";
}

//Funcion para visualizar los ganadores y el total de ellos
function visualizarGanadores($arraySumaPuntos){

    $mayor= max($arraySumaPuntos);

    $ganadores=array_keys($arraySumaPuntos,$mayor);


    foreach ($ganadores as $indice => $value) {
        echo "Ganador: " . $value . " <br>";
    }

    echo "Total jugadores ganadores: " . count($ganadores);

    /*
    echo "<pre>";
    print_r($ganadores); 
    echo "</pre>";
    */
}

//Funcion para crear el fichero correspondiente de todos los jugadores con sus dados.
function crearFichero($arrayTodosJugaBanca){


    $fichero=fopen("resultados.txt" , "w") or die ("No se encuentra el archivo");;
   
    foreach ($arrayTodosJugaBanca as $nombre => $dato) {
        escribir($fichero, $nombre);
        for ($i=0; $i < count($dato) ; $i++) { 
            if($i < (count($dato) - 1)){
                escribir($fichero, $dato[$i]);
            }else{
                fwrite($fichero, $dato[$i]);
            }
            
        }
        fwrite($fichero, "\n");
    }
    
    
    fclose($fichero);
}

//Funcion para escribir sobre el fichero e ingresar cada dato.
function escribir($fichero, $dato){
    fwrite($fichero, $dato);
    fwrite($fichero, "#");
}

?>
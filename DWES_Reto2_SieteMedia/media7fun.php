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

    if(empty($_POST['nombre1'])){
        $mensaje.="<p>El campo 'Jugador 1' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['nombre2'])){
        $mensaje.="<p>El campo 'Jugador 2' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['nombre3'])){
        $mensaje.="<p>El campo 'Jugador 3' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['nombre4'])){
        $mensaje.="<p>El campo 'Jugador 4' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['numcartas'])){
        $mensaje.="<p>El campo de 'Cartas a repartir' es obligatorio.</p>";
        $enviar=false;
    }

    if(empty($_POST['apuesta'])){
        $mensaje.="<p>El campo de 'Cantidad Apostada' es obligatorio.</p>";
        $enviar=false;
    }
    echo $mensaje;

    return $enviar;
}

function recogerDatos(&$arrayJugadores){

    for ($i=0; $i < 4; $i++) { 
        $jugador=depurar($_POST["nombre".($i+1)]);
        $arrayJugadores[$jugador]['cartas']=array(); //me creo un array para almacenar las cartas
        $arrayJugadores[$jugador]['datos']=array("puntos"=>0, "premio"=>0); // me creo otro array de los datos que tendra los puntos y premio.
    }
}

function visualizarTabla($arrayJugadores){
    echo "<table border='1' style='border-collapse: collapse; width:40px;'>";
    foreach ($arrayJugadores as $nombre => $dato) {
        echo "<tr>"; 
        echo "<td > " . $nombre . "</td>";
        foreach ($dato['cartas'] as $indice) {
           
            echo "<td> <img src='images/" . $indice . ".PNG'width='100' ></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function repartirCartas(&$arrayJugadores, &$arrayCartas, $num_cartas){


    foreach ($arrayJugadores as $nombre => $dato) {
        for ($i=0; $i < $num_cartas; $i++) { 
            $indice=array_rand($arrayCartas); // me devuelve el indice random para luego por medio del indice extraer un valor
            $valor_carta=$arrayCartas[$indice];
            
            $arrayJugadores[$nombre]['cartas'][]=$valor_carta;

            //elimino la carta del array
            unset($arrayCartas[$indice]);
        }  
    }
    $arrayCartas = array_values($arrayCartas); //reindexo el arraycartas para que el indice se restablezca ya que al usar unset(se elimina el indice y el contenido.) 
}

function calcularPuntuacion(&$arrayJugadores){
    $cartNum=array("1","2","3","4","5","6","7");
    
    foreach ($arrayJugadores as $nombre => &$dato) {
        foreach ($dato['cartas'] as $indice => $value) {

            if(in_array($value[0],$cartNum)){ // extraigo el primer caracter de la cadena que seria el numero, y si ese numero se encuentra dentro del array $cartNum
                $dato['datos']['puntos']+=intval($value[0]);
            }else{
                $dato['datos']['puntos']+=0.5;
            }

            //echo $value[0] . "";
            //echo $dato['datos']['puntos'] . "\n";
        }
    }
}

function calcularGanadores(&$arrayJugadores, &$ganadores,  $cant_apost, &$premioPorPersona, &$bote){

    $arrayGanadores=array();
    foreach ($arrayJugadores as $nombre => &$dato) {
        $punto=$dato['datos']['puntos'];
        if($punto <= 7.5){
            $arrayGanadores[$nombre]=$punto;
        }
    }

    if(!empty($arrayGanadores)){
        
        $mayor=max($arrayGanadores); //busco el puntaje mayor del arrayGanadores
        $ganadores=array_keys($arrayGanadores, $mayor); // luego array_keys le paso el array de ganadores y
                                                        // tambien el numero mayor para que me devuelva el indice que
                                                        // en este caso es el nombre del ganador o ganadores que en su 
                                                        // valor tenga en puntaje maximo.
        
        foreach ($ganadores as $nombre ) {
            echo  $nombre . " ha ganado la partida con una puntuacion de ". $mayor . "<br>";
            
            if($mayor == 7.5){
                $arrayJugadores[$nombre]['datos']['premio']=( $cant_apost * 0.80) / count($ganadores);
                $premioPorPersona= $arrayJugadores[$nombre]['datos']['premio'];
            }else{
                $arrayJugadores[$nombre]['datos']['premio']=( $cant_apost * 0.50) / count($ganadores);
                $premioPorPersona= $arrayJugadores[$nombre]['datos']['premio'];
            }
        }

        echo "Los ganadores han obtenido " . $premioPorPersona . " € de premio";
        crearFichero($arrayJugadores, $ganadores);
    }else{
        $bote=$cant_apost;
        crearFichero($arrayJugadores, $ganadores);
        echo "NO hay ganadores el bote acumulado es de "  .  $bote . ".<br>";
    }

    echo "<pre>";
    print_r($arrayGanadores); // visualizo los jugadores
    echo "</pre>";
}

function crearFichero($arrayJugadores, $ganadores){

    $dia=date("d");
    $mes=date("m");
    $año=date("Y");
    $hora=date("H");
    $minutos=date("i");
    $segundos=date("s");


    $cadena="apuestas_" . $dia . $mes . $año . $hora . $minutos . $segundos . ".txt";
    
    //echo $cadena;
    $fichero=fopen($cadena , "a+") or die ("No se encuentra el archivo");;

    foreach ($arrayJugadores as $nombre => $dato) {
        $separar=explode(" ", $nombre);
        $inicial_nom=substr($separar[0],0,1); //extraigo el primer caracter del nombre
        $inicial_apell=substr($separar[1],0,1);//extraigo el primer caracter del apellido
        $iniciales=$inicial_nom . $inicial_apell; //los concateno
        $puntaje=$dato['datos']['puntos'];
        $premio=$dato['datos']['premio'];

        //echo $inicial_nom . $inicial_apell . "<br>";

        fwrite($fichero, str_pad($iniciales,(strlen($iniciales)+1),"#"));
        fwrite($fichero, str_pad($puntaje, (strlen($puntaje)+1),"#"));
        fwrite($fichero, $premio);
        fwrite($fichero, "\n");
    }
    $cant_gand=count($ganadores);
    $totalPremio=sumaPremios($arrayJugadores);

    fwrite($fichero, str_pad("TOTALPREMIOS",13,"#"));
    fwrite($fichero, str_pad($cant_gand,(strlen($cant_gand)+1),"#"));
    fwrite($fichero,$totalPremio);

    fclose($fichero);

}

function sumaPremios($arrayJugadores){
    $premio=0;
    foreach ($arrayJugadores as $nombre => &$dato) {
        $premio+=$dato['datos']['premio'];
    }
    return $premio;
}

?>
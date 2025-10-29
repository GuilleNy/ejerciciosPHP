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

function barajearCartas(&$arrayCartas){
    shuffle($arrayCartas);
}

function recogerDatos(&$arrayJugadores, &$arrayJuga_datos){

    for ($i=0; $i < 4; $i++) { 
        $jugador=depurar($_POST["nombre".($i+1)]);
        $arrayJugadores[$jugador]=array(); //me creo un array para almacenar las cartas
        $arrayJuga_datos[$jugador]=array("puntos"=>0, "premio"=>0); // me creo otro array de los datos que tendra los puntos y premio.
    }
}

function visualizarTabla($arrayJugadores){
    echo "<table border='1' style='width:40px;'>";
    foreach ($arrayJugadores as $nombre => $dato) {
        echo "<tr>"; 
        echo "<td > " . $nombre . "</td>";
        foreach ($dato as $indice) {
           
            echo "<td> <img src='images/" . $indice . ".PNG'width='100' ></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function repartirCartas(&$arrayJugadores, &$arrayCartas, $num_cartas){

    $cont=0;
    foreach ($arrayJugadores as $nombre => $dato) {
        for ($i=0; $i < $num_cartas; $i++) { 
            /*
            $indice=array_rand($arrayCartas); // me devuelve el indice random para luego por medio del indice extraer un valor
            $valor_carta=$arrayCartas[$indice];
            */
            $arrayJugadores[$nombre][]=$arrayCartas[$cont];
            //unset($arrayCartas[$cont]); //elimino la carta del array
            $cont++;
        }  
    }
    //$arrayCartas = array_values($arrayCartas); //reindexo el arraycartas para que el indice se restablezca ya que al usar unset(se elimina el indice y el contenido.) 
}

function calcularPuntuacion(&$arrayJugadores, &$arrayJuga_datos){
    $cartNum=array("1","2","3","4","5","6","7");
    
    foreach ($arrayJugadores as $nombre => &$dato) {
        foreach ($dato as $indice => $value) {

            if(in_array($value[0],$cartNum)){ // extraigo el primer caracter de la cadena que seria el numero, y si ese numero se encuentra dentro del array $cartNum
                $arrayJuga_datos[$nombre]['puntos']+=intval($value[0]);
            }else{
                $arrayJuga_datos[$nombre]['puntos']+=0.5;
            }

            //echo $value[0] . "";
            //echo $dato['datos']['puntos'] . "\n";
        }
    }
}

function calcularGanadores(&$arrayJuga_datos,  $cant_apost, &$premioPorPersona, &$bote){

    $array_jugadoresEnRango=array();
    $nombreGanadores=array();

    foreach ($arrayJuga_datos as $nombre => &$dato) {
        $punto=$dato['puntos'];
        if($punto <= 7.5){
            $array_jugadoresEnRango[$nombre]=$punto;
        }
    }

    if(!empty($array_jugadoresEnRango)){
        
        $mayor=max($array_jugadoresEnRango); //busco el puntaje mayor del array_jugadoresEnRango
        $nombreGanadores=array_keys($array_jugadoresEnRango, $mayor); // luego array_keys le paso el array de nombreGanadores y
                                                        // tambien el numero mayor para que me devuelva el indice que
                                                        // en este caso es el nombre del ganador o ganadore que en su 
                                                        // valor tenga en puntaje maximo.
        
        foreach ($nombreGanadores as $nombre ) {
            echo  $nombre . " ha ganado la partida con una puntuacion de ". $mayor . "<br>";
            
            if($mayor == 7.5){
                $arrayJuga_datos[$nombre]['premio']=( $cant_apost * 0.80) / count($nombreGanadores);
                $premioPorPersona= $arrayJuga_datos[$nombre]['premio'];
            }else{
                $arrayJuga_datos[$nombre]['premio']=( $cant_apost * 0.50) / count($nombreGanadores);
                $premioPorPersona= $arrayJuga_datos[$nombre]['premio'];
            }
        }

        echo "Los ganadores han obtenido " . $premioPorPersona . " € de premio";
        crearFichero($arrayJuga_datos, $nombreGanadores, $arrayJuga_datos);
    }else{
        $bote=$cant_apost;
        crearFichero($arrayJuga_datos, $nombreGanadores);
        echo "NO hay ganadores el bote acumulado es de "  .  $bote . ".<br>";
    }

    echo "<pre>";
    print_r($array_jugadoresEnRango); // visualizo los jugadores
    echo "</pre>";

    echo "<pre>";
    print_r($nombreGanadores); // visualizo los jugadores
    echo "</pre>";
}

function crearFichero($arrayJuga_datos, $nombreGanadores){

    $dia=date("d");
    $mes=date("m");
    $año=date("Y");
    $hora=date("H");
    $minutos=date("i");
    $segundos=date("s");

    $cadena="apuestas_" . $dia . $mes . $año . $hora . $minutos . $segundos . ".txt";
    
    //echo $cadena;
    $fichero=fopen($cadena , "a+") or die ("No se encuentra el archivo");;

    foreach ($arrayJuga_datos as $nombre => $dato) {
        $separar=explode(" ", $nombre);
        $inicial_nom=substr($separar[0],0,1); //extraigo el primer caracter del nombre
        $inicial_apell=substr($separar[1],0,1);//extraigo el primer caracter del apellido
        $iniciales=$inicial_nom . $inicial_apell; //los concateno
        $puntaje=$dato['puntos'];
        $premio=$dato['premio'];

        //echo $inicial_nom . $inicial_apell . "<br>";

        escribir($fichero, $iniciales);
        escribir($fichero, $puntaje);
        fwrite($fichero, $premio);
        fwrite($fichero, "\n");

        /*
        fwrite($fichero, str_pad($iniciales,(strlen($iniciales)+1),"#"));
        fwrite($fichero, str_pad($puntaje, (strlen($puntaje)+1),"#"));
        fwrite($fichero, $premio);
        fwrite($fichero, "\n");
        */
    }
    $z="TOTALPREMIOS";
    $cant_gand=count($nombreGanadores);
    $totalPremio=sumaPremios($arrayJuga_datos);

    
    escribir($fichero, $z);
    escribir($fichero, $cant_gand);
    fwrite($fichero,$totalPremio);


    /*
    fwrite($fichero, str_pad("TOTALPREMIOS",(strlen($z)+1),"#"));
    fwrite($fichero, str_pad($cant_gand,(strlen($cant_gand)+1),"#"));
    fwrite($fichero,$totalPremio);
    */

    fclose($fichero);

}

function escribir($fichero, $cadena){
    fwrite($fichero, $cadena);
    fwrite($fichero, "#");
}

//recorro el array de jugadores para poder sumar el total de los premios obtenidos.
function sumaPremios($arrayJuga_datos){
    $premio=0;
    foreach ($arrayJuga_datos as $nombre => &$dato) {
        $premio+=$dato['premio'];
    }
    return $premio;
}

?>
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

    if(empty($_POST['bote'])){
        $mensaje.="<p>El campo de 'Bote' es obligatorio.</p>";
        $enviar=false;
    }
    echo $mensaje;

    return $enviar;
}


function recogerDatos(&$arrayJugadores, &$arrayJuga_datos){

    for ($i=0; $i < 4; $i++) { 
        $jugador=depurar($_POST["nombre".($i+1)]);
        $arrayJugadores[$jugador]=array(); //me creo un array para almacenar las cartas
        $arrayJuga_datos[$jugador]=array("puntos"=>0, "premio"=>0); // me creo otro array de los datos que tendra los puntos y premio.
    }
}

function barajearCartas(&$arrayCartas){
    shuffle($arrayCartas);
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

?>
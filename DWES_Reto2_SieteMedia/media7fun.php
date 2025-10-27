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
        $arrayJugadores[$jugador]=array();
    }
}


function visualizarTabla($arrayJugadores){
    echo "<table border='1' style='border-collapse: collapse; width:40px;'>";
    foreach ($arrayJugadores as $nombre => $dato) {
        echo "<tr>"; 
        echo "<td > " . $nombre . "</td>";
        foreach ($dato as $indice => $carta) {
            echo "<td> <img src='images/" . $carta . ".PNG' ></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function repartirCartas(&$arrayJugadores, &$arrayCartas, $num_cartas){
    foreach ($arrayJugadores as $nombre => $dato) {
        for ($i=0; $i < $num_cartas; $i++) { 
            $indice=array_rand($arrayCartas);
            $valor_carta=$arrayCartas[$indice];
            $arrayJugadores[$nombre][]=$valor_carta;

            //elimino la carta del array
            unset($arrayCartas[$indice]);
        }  
    }
    $arrayCartas = array_values($arrayCartas); //reindexo el arraycartas para que el indice se restablezca ya que al usar unset(se elimina el indice y el contenido.) 
}



?>
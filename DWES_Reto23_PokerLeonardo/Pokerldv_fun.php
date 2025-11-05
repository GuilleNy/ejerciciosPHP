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

//Funcion para recoger los datos de los jugadores y almacenarlos en un array asociativo, 
//donde la clave es el nombre del jugador y el valor es un array vacio para almacenar las cartas.
//Tambien creo otro array asociativo para almacenar el premio que le corresponde a cada jugador.
function recogerDatos(&$arrayJugadores , &$repartirPremioJuga){

    for ($i=0; $i < 4; $i++) { 
        $jugador=depurar($_POST["nombre".($i+1)]);
        $arrayJugadores[$jugador]=array(); //me creo un array para almacenar las cartas
        $repartirPremioJuga[$jugador]=0;
    }
}

//Funcion para barajear las cartas
function barajearCartas(&$arrayCartas){
    shuffle($arrayCartas);
}

//Funcion para repartir las cartas a los jugadores, donde el numero de cartas a repartir es pasado por parametro
// y se reparte de forma equitativa entre los jugadores.
function repartirCartas(&$arrayJugadores, &$arrayCartas, $num_cartas){

    $cont=0;
    foreach ($arrayJugadores as $nombre => $dato) {
        for ($i=0; $i < $num_cartas; $i++) { 
            $arrayJugadores[$nombre][]=$arrayCartas[$cont];
            $cont++;
        }  
    }
}

//Funcion para verificar las combinaciones de las cartas de cada jugador
// y almacenar la jugada en un array asociativo donde la clave es el nombre del jugador y el valor es la jugada.
function clasificarJugadasPoker($arrayJugadores, &$jugadasPoker){
    $valorFacial=verificarCartas($arrayJugadores);

    foreach ($valorFacial as $nombre => $arrayValor) {
    
        $repetidos=array_count_values($arrayValor); //devuelve un array asociativo, donde las claves son los valores del array original y los valores son el numero de veces que se repite.
        $num=0;
        $jugadasPoker[$nombre] = "No hay combinacion"; // valor por defecto
        foreach ($repetidos as $clave => $valor) {
            if(($valor == 2) && ($num == 2)){
                $jugadasPoker[$nombre]="Doble Pareja";
            }elseif($valor == 2){
                $jugadasPoker[$nombre]="Pareja";
                $num=$valor;
            }elseif($valor == 3 ){
                $jugadasPoker[$nombre]="Trio";
            }elseif($valor == 4){
                $jugadasPoker[$nombre]="Poker";
            }
        }
    }
    
    echo "Array valor Facial";
    echo "<pre>";
    print_r($valorFacial); 
    echo "</pre>";
    
}

//Funcion para verificar las cartas de cada jugador y devolver un array asociativo
// donde la clave es el nombre del jugador y el valor es un array con los valores faciales de sus cartas.
function verificarCartas($arrayJugadores){
    $valorFacial=array();
    foreach ($arrayJugadores as $nombre => $cartas) {
        
        foreach ($cartas as $indice => $valor) {
            $valorFacial[$nombre][]=$valor[0];
        }
    }
    return $valorFacial;
}

//Funcion para determinar los ganadores y repartir el premio segun la jugada realizada
// el premio se reparte en funcion de la jugada realizada y el numero de ganadores.
function determinarYRepartirPremios($jugadasPoker, &$repartirPremioJuga, $cant_apost){
    
    $orden=array("Pareja" => 0 ,"Doble Pareja"  => 1,"Trio"  => 2 ,"Poker"  => 3);
    $invertirOrden=array_keys($orden); //array para invertir el orden y obtener el nombre de la jugada a partir del numero.
    $arrayJuga=array(); //array temporal para almacenar las jugadas con su numero de jugada.

    //lo que hago aqui es clasificar las jugadas ubicando su numero de jugada y luego agregar a los jugadores
    //en lugar de que la clave sea el nombre de la jugada lo sustituyo por un numero del array $orden
    foreach ($jugadasPoker as $nombre => $jugada) {
        if($jugada != "No hay combinacion"){
            $arrayJuga[$nombre]=$orden[$jugada];
        }  
    }

    $mayor=max($arrayJuga); //obtengo el mayor valor de jugada realizada 
    $ganadores=array_keys($arrayJuga,$mayor); //obtengo los nombres de los jugadores que han realizado la jugada mayor 


    foreach ($ganadores as $indice => $nombre) {
        if($invertirOrden[$mayor]  == "Poker"){
            $repartirPremioJuga[$nombre]=  $cant_apost / count($ganadores); 
        }elseif($invertirOrden[$mayor]  == "Trio"){
            $repartirPremioJuga[$nombre]= ($cant_apost * 0.70) /count($ganadores) ;
        }
        elseif($invertirOrden[$mayor]  == "Doble Pareja"){
            $repartirPremioJuga[$nombre]= ($cant_apost * 0.50) / count($ganadores) ;
        }
        elseif($invertirOrden[$mayor]  == "Pareja"){
            $repartirPremioJuga[$nombre]= 0;
        } 
    }
    /*
    echo "Gnadores <br>";
    //echo "El mayor es : " . $mayor;
    
    echo "<pre>";
    print_r($ganadores); 
    echo "</pre>";
    */
}

//Funcion para visualizar la tabla de jugadores con sus cartas y la jugada realizada
// se muestra en una tabla HTML con las cartas en imagenes.
function visualizarTabla($arrayJugadores , $jugadasPoker){
    echo "<table border='1' style='width:40px;'>";
    foreach ($arrayJugadores as $nombre => $dato) {
        echo "<tr>"; 
        echo "<td > " . $nombre . "</td>";
        foreach ($dato as $indice) {
            echo "<td> <img src='images/" . $indice . ".PNG'width='100' ></td>";
        }
        echo "<td>" . $jugadasPoker[$nombre]  . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

//Funcion para visualizar los ganadores y la cantidad que han ganado 
// si no hay ganadores se indica.
function visualizarGandores($repartirPremioJuga){
    $cont=0;
    foreach ($repartirPremioJuga as $nombre => $premio) {
        if($premio > 0){
            echo $nombre . " gana con una cantidad de: " . $premio . "<br>";
        }else{
            $cont++;
        }
    }

    if($cont == 4){
        echo "No hay ganadores";
    }
}


?>
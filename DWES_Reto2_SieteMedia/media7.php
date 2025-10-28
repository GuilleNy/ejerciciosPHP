<?php

include "media7fun.php";
// El objetivo del juego es lograr la máxima puntuación, lo más cercana a 7.5 sin pasarse.

resultado();

function resultado(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(verificarCampos()){
            $arrayJugadores=array();
            $arrayCartas=array("1C","1D","1P","1T","2C","2D","2P","2T", "3C","3D","3P","3T", "4C","4D","4P","4T", "5C","5D","5P","5T", "6C","6D","6P","6T", "7C","7D","7P","7T", "JC","JD","JP","JT", "KC","KD","KP","KT", "QC","QD","QP","QT");
            $ganadores=array();
            $bote=0;
            $premioPorPersona=0 + $bote; // variaable que recibe su valor por referncia en la funcion calcularGanadores();
            
            /*************************************RECOLECCION DE DATOS********************************************/
            recogerDatos($arrayJugadores);  //Recojo los nombres de los jugadores en un array
            $num_cartas=intval(depurar($_POST['numcartas'])); //numero de cartas
            $cant_apost=intval(depurar($_POST['apuesta']));    //cantidad para apostar
            /*****************************************************************************************************/
            

            /******************************************FUNCIONES**************************************************/
            barajearCartas($arrayCartas);
            repartirCartas($arrayJugadores, $arrayCartas, $num_cartas);
            calcularPuntuacion($arrayJugadores);
            calcularGanadores($arrayJugadores, $ganadores, $cant_apost, $premioPorPersona, $bote);
            visualizarTabla($arrayJugadores);
            /*****************************************************************************************************/




            // visualizo array de los jugadores
            echo "<pre>";
            print_r($arrayJugadores); 
            echo "</pre>";

            /*
            echo "<pre>";
            print_r($arrayCartas); 
            echo "</pre>";
            
            echo "<pre>";
            print_r($ganadores); // visualizo los jugadores
            echo "</pre>";
            */  
        }
    }
}








?>
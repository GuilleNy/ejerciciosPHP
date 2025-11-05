<?php

    include "pokerldv_fun.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(verificarCampos()){
            $arrayJugadores=array(); //array asociativo para almacenar los jugadores y sus cartas
            $jugadasPoker=array(); //array asociativo para almacenar la jugada(poker, trio, doble pareja, pareja) de cada jugador .
            $repartirPremioJuga=array(); //array asociativo para almacenar el premio que le corresponde a cada jugador.
            $arrayCartas=array("1C1","1C2","1D1","1D2","1P1","1P2","1T1","1T2", "JC1","JC2","JD1","JD2", "JP1","JP2","JT1","JT2", "KC1","KC2","KD1","KD2", "KP1","KP2","KT1","KT2", "QC1","QC2","QD1","QD2", "QP1","QP2","QT1","QT2");

            /*************************************RECOLECCION DE DATOS********************************************/
            recogerDatos($arrayJugadores, $repartirPremioJuga);  //Recojo los nombres de los jugadores en un array
            $num_cartas=4; //numero de cartas
            $cant_apost=intval(depurar($_POST['bote']));    //cantidad para apostar
            /*****************************************************************************************************/
            
            /******************************************FUNCIONES**************************************************/
            
            barajearCartas($arrayCartas);
            repartirCartas($arrayJugadores, $arrayCartas, $num_cartas);
            clasificarJugadasPoker($arrayJugadores, $jugadasPoker);
            determinarYRepartirPremios($jugadasPoker,$repartirPremioJuga, $cant_apost);
            visualizarTabla($arrayJugadores, $jugadasPoker);
            visualizarGandores($repartirPremioJuga);
            /*****************************************************************************************************/

            // visualizo array de los jugadores
            /*
            echo "<pre>";
            print_r($arrayJugadores); 
            echo "</pre>";
            
            echo "Array jugadasPoker que el jugador ha hecho con su mazo";
            echo "<pre>";
            print_r($jugadasPoker); 
            echo "</pre>";
            */
            echo "<pre>";
            print_r($jugadasPoker); 
            echo "</pre>";
            
        }
    }






?>
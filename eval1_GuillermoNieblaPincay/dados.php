<?php

    include "dadosfunc.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(verificarCampos()){
            if(numMaximoDados()){
                $arrayJugadores=array(); 
                $arrrayBanca=array();
                $arrayTodosJugaBanca=array();
                $arraySumaPuntos=array();

                $jugadasPoker=array();  
                $repartirPremioPartida=array();

                /*************************************RECOLECCION DE DATOS********************************************/
                recogerDatos($arrayJugadores, $arrrayBanca);  
                $num_dados=intval(depurar($_POST['numdados']));   
                /*****************************************************************************************************/
                
                /******************************************FUNCIONES**************************************************/
                llenarDadosjugador($arrayJugadores, $num_dados);
                llenarDadosBanca($arrrayBanca, $num_dados);
                juntarArrayjugadoresYbanca($arrayTodosJugaBanca, $arrayJugadores, $arrrayBanca);
                visualizarTabla($arrayTodosJugaBanca);

                sumarDadosjugadores($arrayJugadores, $arraySumaPuntos, $num_dados);
                sumarDadosBanca($arrrayBanca, $arraySumaPuntos);

                visualizarPuntos($arraySumaPuntos);
                visualizarGanadores($arraySumaPuntos);

                crearFichero($arrayTodosJugaBanca);

                /*****************************************************************************************************/

                // visualizo array de los jugadores
                /*
                echo "array suma puntos";
                echo "<pre>";
                print_r($arraySumaPuntos); 
                echo "</pre>";
                
                echo "array arrayTodosJugaBanca";
                echo "<pre>";
                print_r($arrayTodosJugaBanca); 
                echo "</pre>";
                */
        
            }else{
                echo "<p>El numero de dados debe ser al menos 2 y como maximo 10.";
            }
            
        }
    }






?>
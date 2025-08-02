<?php

    try {


    $jugador1 = $_REQUEST['jug1'];
    $jugador2 = $_REQUEST['jug2'];
    $jugador3 = $_REQUEST['jug3'];
    $jugador4 = $_REQUEST['jug4'];
    $numdados = $_REQUEST['numdados']; 

    

    //array_filter elimina los valores vacíos o falsos (null, "", false, 0) del array.
    //Si un jugador no ingresó un nombre, su variable estará vacía ("") y array_filter la eliminará del array.

    $nombresJugadores = array_filter([$jugador1, $jugador2, $jugador3, $jugador4]); 

    //Cuento el numero de jugadores para poder realzar la excepcion
    $numJugadores = count($nombresJugadores);
    echo "Hay solo " . $numJugadores . " jugadores. <br>";

    // Comprobar el número de jugadores
    if ($numJugadores < 2 || $numJugadores > 4) {
        throw new Exception("El número de jugadores debe ser mínimo 2 y máximo 4.");
    }

    



    //Comprobar el numeros de dados
    if (($numdados < 1 || $numdados > 10)) 
    {
        throw new Exception("El numero de dados debe de ser minimo de 1 y como maximo de 10.");
    }

    // Inicializar el array con valores vacios.
    $jugadores = [];

    foreach ($nombresJugadores as $nombre) {
        //está creando un array asociativo donde las claves son los nombres de los jugadores y los valores son arrays vacíos.
        $jugadores[$nombre] = []; 
    }

    print_r($jugadores);
    echo "<br>";


    // Funcion para llenar el array de cada jugador con 6 numeros aleatorios.
    //En esta funcion el & es necesario para que la función modifique directamente el array que se le pasa
    // como argumento, en lugar de trabajar con una copia del array.
    function llenarDados(&$jugador, $numdados)
    {
        for ($i = 0; $i < $numdados; $i++) 
        {
            
            $jugador[] = rand(1, 6);
        }
    }
    


    // Llenar los dados para cada jugador donde utilizo la funcion llenarDados
    foreach ($jugadores as &$jugador) //&$jugador hace que PHP trabaje directamente con los valores originales de $jugadores, sin crear copias.
    {
        //No, no es necesario poner el & en la llamada a la función llenarDados($jugador, $numdados); 
        //si ya has utilizado la referencia & en el foreach.
        llenarDados($jugador, $numdados); //esta funcion modifica directamente los valores en $jugadores, es decir le 
                                          // agrega campo a cada nombre del jugador.
    }

    print_r($jugadores);
    //print_r(array_filter($jugadores));
    /************************************************************************** */
   
    function visualizarTabla( $jugadores)
    {
        // Utilizo empty() para determinar si una variable esta vacia


       
        foreach ($jugadores as $nombre => $dados) 
        {
            if (!empty($nombre) ) 
            {
                echo "<tr>";
                echo "<td> $nombre</td> ";
                foreach ($dados as $dado)
                {
                    echo "<td>";
                    echo "<img src='images/$dado.png' width='100px' height='100px'>";

                    echo "</td>";
                }
                echo "</tr>";
            }
        }
       
    }
    



    echo "<h1>RESULTADO JUEGO DADOS</h1>";
    echo "<table border='1'>";
    
    //Visualizo la tabla

    visualizarTabla($jugadores);
    echo "</table>";
    echo "<br><br>";

    /************************************************************************** */

    // Funcion para asignar los puntos
    /* En la condicion utilizo el count() para saber cuantos dados tengo pasaando como parametro el $dado,
    luego seguido de un and , utilizo la funcion de array_unique() esto lo hago para saber si hay numeros repetidos en mi array jugadores
    siendo asi me elimine los duplicados, todo esto para que me quede solo un dato y poder asi utilziar la funcion count() para que me 
    haga la comparacion de que sea igual a 1   */
    function calcularPuntos($dados) {
        if (count($dados) > 2 && count(array_unique($dados)) == 1) 
        {
            return 100; 
        }
        return array_sum($dados);
    }

    foreach ($jugadores as $nombre => $dados) {
        echo "$nombre = " . calcularPuntos($dados) . "<br>";
    }

    /************************************************************************** */
    $valoresPuntos = [];
    
    
    foreach ($jugadores as $nombre => $dados) 
    {
        $valoresPuntos[]=calcularPuntos($dados);

    }
    $maximo = max($valoresPuntos);

    //var_dump($valo);

    /************************************************************************** */
    //Me creo un array de ganadores
    $ganadores= array();

    foreach ($jugadores as $nombre => $dados) 
    {
        if (calcularPuntos($dados) == $maximo) 
        {
           $ganadores[]=$nombre;
        }
    }

   //Visualizo el array de ganadores 

    foreach($ganadores as $nombre )
    {
        echo "GANADOR: " .  $nombre . "<br>";
    }

    echo "NUMERO DE GANADORES: " . count($ganadores) . "<br>";
    } 
    
    catch (Exception $e) 
    {
        echo "Error: " . $e->getMessage();
    }
   





?>
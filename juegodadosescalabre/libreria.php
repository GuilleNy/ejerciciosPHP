<?php

    try {

        $usuarios = fopen("usuarios.txt", "r");

        if ($usuarios) {
            $nombresJugadores =array();
            
            
            while (($linea = fgets($usuarios)) !== false) {
                list($nombre) = explode(",",$linea); 
                $nombresJugadores[] = $nombre;
            }
    
            
            fclose($usuarios);
        } else {
            throw new Exception("Error al abrir el archivo de usuarios.");
        }
    $nombresJugadores = array_filter($nombresJugadores); // Filtrar nombres válidos
        

    $numdados = $_REQUEST['numdados']; 

    


    //Comprobar el numeros de dados
    if (($numdados < 1 || $numdados > 10)) 
    {
        throw new Exception("El numero de dados debe de ser minimo de 1 y como maximo de 10.");
    }
    // Inicializar el array 
    $jugadores = array();
    foreach ($nombresJugadores as $nombre) {
        $jugadores[$nombre] = array(); 
    }
    // Funcion para llenar el array de cada jugador con 6 numeros aleatorios
    function llenarDados(&$jugador, $numdados)
    {
        for ($i = 0; $i < $numdados; $i++) 
        {
            
            $jugador[] = rand(1, 6);
        }
    }
    // Llenar los dados para cada jugador donde utilizo la funcion llenarDados
    foreach ($jugadores as &$jugador) 
    {
        
        llenarDados($jugador, $numdados);
    }
    
    //print_r(array_filter($jugadores));
    /************************************************************************** */
   
    function visualizarTabla( $jugadores)
    {
        // Utilizo empty() para determinar si una ariable esta vacia
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
    $valoresPuntos = array();
    
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
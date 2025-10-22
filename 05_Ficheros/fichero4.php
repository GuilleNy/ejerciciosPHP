
<?php
/*
programa php que muestre por pantalla el contenido del fichero alumnos2.txt
como una tabla HTML. Se mostrará, después de los datos de los alumnos, un mensaje con el número de
filas que se han leído del fichero.
 */
include "otrasFunciones.php";

//no esta hecho falta hace cambios
resultado();

/*******************************FUNCIONES************************************ */
function resultado(){
    $f1 = fopen("alumnos2.txt","r");
    $cont=0;
    echo "<table table border='1' style='border-collapse: collapse; width: 700px;'>";
    encabezado();
    while (!feof($f1)){  
        $datos=array();
        extraerDatos($datos, $f1);
        visualizarTabla($datos); 
        $cont++;
        
        echo "<pre>";
        print_r($datos);
        echo "</pre>";
        
    }
    fclose($f1);   
    echo "</table>";
    echo "Numero de filas leidas son de: " . $cont ;
}

function encabezado(){
    
    echo "<tr>";
        echo "<th>Nombre</th>";
        echo "<th>Apellido1</th>";
        echo "<th>Apellido2</th>";
        echo "<th>Fecha de Nacimiento</th>";
        echo "<th>Localidad</th>";
    echo "</tr>";
}

function visualizarTabla($datos){
    echo "<tr>";
    foreach ($datos as $dato) {
        echo "<td>$dato</td>";
    }
    echo "</tr>";
}

function extraerDatos(&$datos, $f1){
    $f1=fgets($f1);

     if($f1 != ""){
        $indice = 0;
        $datos[0] = depurar(substr($f1,0,strpos($f1,"##")));
        $indice = strpos($f1,"##",$indice)+2;
        $datos[1] = depurar(substr($f1,$indice,strpos($f1,"##",$indice)-$indice));
        $indice = strpos($f1,"##",$indice)+2;
        $datos[2] = depurar(substr($f1,$indice,strpos($f1,"##",$indice)-$indice));
        $indice = strpos($f1,"##",$indice)+2;
        $datos[3] = depurar(substr($f1,$indice,strpos($f1,"##",$indice)-$indice));
        $indice = strpos($f1,"##",$indice)+2;
        $datos[4] = depurar(substr($f1, $indice));
    }
}


?>
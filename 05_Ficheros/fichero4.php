
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
    $cadena_fila=fgets($f1); //me da la primera fila de datos
    $columnas=5;
    $i=0;
    $inicio=0;
    
    while($i< strlen($cadena_fila)){
        while( $cadena_fila[$i] != "#"){
            $i++;
        }
        
        $cad=substr($cadena_fila,$inicio, $i);

        
        $datos[5-$columnas]=$cad;
        

       
        if($i < strlen($cadena_fila) ){
            echo "i longitud de la cadena: " . $i . "</br>";
            $i=$i+2;
            $inicio=$i;
            $columnas--;
            echo "i incrementado: " . $i . "</br>";
            echo "inicio: " . $inicio . "</br>";
            echo "Columnas: " . $columnas . "</br>";
        } 
        echo "++++++++++++++++++++++++++++++++++++++++++++++++++++</br> ";
    }
    
   
    
    
    
  
}
?>
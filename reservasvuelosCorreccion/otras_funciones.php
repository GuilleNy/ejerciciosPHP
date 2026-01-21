<?php 


function depurar($cadena){
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena; 
}
function recogerDatos(){
    $nomUsuario=depurar($_POST['usuario']);
    $contrUsuario=depurar($_POST['password']);
    return [$nomUsuario, $contrUsuario];
}


function verifica_campo_login(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['usuario'])) {
        $mensaje .= "El campo Usuario esta vacio.<br>";
        $enviar = False;  
    }

    if (empty($_POST['password'])) {
        $mensaje .= "El campo Clave esta vacio.<br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}

function verifica_campo_compra(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['vuelos'])){
        $mensaje .= "No se ha seleccionado un vuelo.<br>";
        $enviar = False; 
    }
    
    if (empty($_POST['asientos'])){
        $mensaje .= "El numero de asientos esta vacia.<br>";
        $enviar = False; 
    }else{ 
        if(intval(depurar($_POST['asientos'])) < 0 ){
            echo "Los asientos debe ser igual o mayor de 1.<br>";
        }
    }
    echo $mensaje;
    return $enviar;
}


?>
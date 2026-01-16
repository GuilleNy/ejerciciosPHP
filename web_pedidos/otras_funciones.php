<?php 


function depurar($cadena){
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena; 
}
 
function low($cadena){
    return strtolower($cadena);
}

function recogerDatos(){
    $nomUsuario=depurar($_POST['usuario']);
    $contrUsuario=depurar($_POST['clave']);
    return [$nomUsuario, $contrUsuario];
}

function verifica_campo(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['usuario'])) {
        $mensaje .= "El campo Usuario esta vacio. <br>";
        $enviar = False;  
    }

    if (empty($_POST['clave'])) {
        $mensaje .= "El campo Clave esta vacio. <br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}






?>
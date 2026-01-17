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

function verifica_campo_login(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['usuario'])) {
        $mensaje .= "El campo Usuario esta vacio.<br>";
        $enviar = False;  
    }

    if (empty($_POST['clave'])) {
        $mensaje .= "El campo Clave esta vacio.<br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}

function verifica_campo_altaPedido(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['producto'])){
        $mensaje .= "No se ha seleccionado un Producto.<br>";
        $enviar = False; 
    }
    
    if (empty($_POST['cantidad'])){
        $mensaje .= "La cantidad esta vacia.<br>";
        $enviar = False; 
    }else{ 
        if(intval(depurar($_POST['cantidad'])) < 0 ){
            echo "La cantidad debe ser igual o mayor de 1.<br>";
        }
    }
    echo $mensaje;
    return $enviar;
}

function obtenerUltimoCodigo(){

    $ultimoID=consultaUltimaOrder();
    $nuevoID = "";

    if($ultimoID != False){
        $cod = intval($ultimoID['ultima_order']);
        $nuevoID = $cod + 1;
    }else{
        $nuevoID = 10100;
    }
    return $nuevoID ;
}



?>
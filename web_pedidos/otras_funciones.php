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




?>
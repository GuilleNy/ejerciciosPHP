<?php


if (isset($_POST['realizarApuesta'])){
    header("Location: ./realizarApuesta.php");
}else if(isset($_POST['cargarSaldo'])){
    header("Location: ./realizarSorteo.php");
}else if(isset($_POST['consultarApuesta'])){
    header("Location: ./consultarApuestas.php");
}

?>
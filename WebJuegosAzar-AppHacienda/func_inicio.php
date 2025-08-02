<?php


if (isset($_POST['altaSort'])){
    header("Location: ./altaSorteo.php");
}else if(isset($_POST['realizarSort'])){
    header("Location: ./realizarSorteo.php");
}else if(isset($_POST['consultarSort'])){
    header("Location: ./consultarSorteo.php");
}

?>
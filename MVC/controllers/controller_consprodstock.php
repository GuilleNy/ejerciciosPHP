<?php
    require_once ("controller_session.php");
    require_once ("controller_comunes.php");

    sessionStart();
 

    if(!verificarSesion()) //controller_session.php
    {
        header("Location: ../index.php");
        exit();
    }

    require_once ("../db/BBDD_pedidos.php");
    require_once ("../models/model_consprodstock.php");
    $producto= obtenerProductosStock($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if(isset($_POST['consultarStock'])){
            if(verificarCampoProducto()){
                
                $productCode = depurar($_POST['producto']);

                $productos = consultarStockProducto($conn , $productCode);
                //mostrarStock($conn, $productCode); //otras_funciones.php
                
            }
        }else if(isset($_POST['atras'])){
            header("Location: controller_inicio.php");
            exit();
        }   
    }

    require_once ("../views/view_consprodstock.php");
?>
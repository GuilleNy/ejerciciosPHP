<?php
    require_once ("controller_session.php");
    require_once ("controller_comunes.php");

    sessionStart();
 

    if(!verificarSesion()) //func_sesiones.php
    {
        header("Location: ../index.php");
        exit();
    }

    require_once ("../db/BBDD_pedidos.php");
    require_once("../models/model_consultarProd.php");
    $producto= obtenerProductos($conn);
    
    //controller_session.php
    $cesta = devolverCesta();
    $precioTotal = precioTotalCesta();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['añadirCesta'])){
            if(verifica_campo_altaPedido()){
                $producto = $_POST['producto'];
                $cantProducto = depurar($_POST['cantidad']);
                annadirCesta($producto, $cantProducto);//func_sesiones.php
                header("Refresh: 0");
                exit();
            }
        }else if(isset($_POST['pedido'])){
            if($cesta != null ){
                if(verificarPago()){
                    require_once("../models/model_altaped.php");
                    registrarCompra($conn);
                    vaciarCesta();
                }
            }else{
                echo "Debes seleccionar un producto";
            }
        }else if(isset($_POST['vaciar'])){
            vaciarCesta();
            header("Refresh: 0");
        }else if(isset($_POST['atras'])){
            header("Location: controller_inicio.php");
            exit();
        }
    }

    


    require_once ("../views/view_altaped.php");
?>
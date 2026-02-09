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
    require_once ("../models/model_consPed.php");
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if(isset($_POST['consultar'])){
            
            if(verifica_campo_consultaCli()){
                
                if(verificarNumCli($conn)){
                    $customerNumber = depurar($_POST['numCli']);
                    $consulta = consultaOrdernCli($conn, $customerNumber);
                }else{
                    echo "El numero de cliente no existe.";
                }
            }
        }else if(isset($_POST['atras'])){
            header("Location: controller_inicio.php");
            exit();
        }    
    }

    


    require_once ("../views/view_consped.php");
?>
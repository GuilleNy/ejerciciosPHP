<pre>
<?php print_r($_SESSION); ?>
</pre>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consultar Pedidos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 30rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">

                    <div class="form-group">
                        <B>Numero Cliente:</B>
                        <input type="text"  name="numCli"  class="form-control" >
                    </div>
                    <?php
                        if(!empty($consulta))
                        {
                            echo "<div>";
                                foreach ($consulta as $row => $valor) {
                                    $orderNumber = $valor['orderNumber'];
                                    //$orderDetails = consultaOrdernDetails($conn, $orderNumber); //consultas_db.php   
                                    print '<table class="table table-bordered table-hover table-sm text-nowrap">';
                                        echo "<tr>
                                                <th>Order Number</th>
                                                <th>Order Date</th>
                                                <th>Status</th>
                                            </tr>";
                                        echo "<tr>
                                                <td>" . $valor['orderNumber'] . "</td>
                                                <td>" . $valor['orderDate'] . "</td>
                                                <td>" . $valor['status'] . "</td>
                                            </tr>";

                                    print "</table>";
                                    echo "<hr>";
                                }
                                
                            echo "</div>";
                        }
                    ?>
                    <BR>
                    <div class="card-footer text-center">
                        <div>
                            <input type="submit" name="consultar" value="Realizar Consulta"  class="btn btn-warning ">
                            <input type="submit" name="atras" value="Atras" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    
    
</body>
</html>
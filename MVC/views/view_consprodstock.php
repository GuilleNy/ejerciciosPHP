<pre>
<?php print_r($_SESSION); ?>
</pre>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consultar Stock</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 40rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">
                    <div class="card-header text-center">
                        <B>Numero Cliente:</B>  <?php //echo $_SESSION["DatosUsuario"]['customerNumber']; ?>  <BR>
                        <B>Nombre Cliente:</B>  <?php //echo $_SESSION["DatosUsuario"]['contactFirstName']." ".$_SESSION['DatosUsuario']['contactLastName']; ?>  <BR>
                        <B>Credito Limite:</B>  <?php //echo $_SESSION["DatosUsuario"]['creditLimit']; ?>  <BR><BR>
                    </div>
                    <div class="form-group">
                        <B>Producto:</B>
                        <select name="producto" class="form-control">
                            <option value="" disabled selected>-- Selecciona un Producto --</option>
                            <?php
            
                                
                                foreach ($producto as $fila) {
                                    echo "<option value=\"" . $fila['productCode'] . "\">" . $fila['productName'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <?php
                        if(!empty($productos))
                        {
                            echo "<div id='cesta'>";
                            print '<table class="table table-bordered table-hover table-sm text-nowrap">
                                <tr>
                                    <th>Nombre Producto</th>
                                    <th>Cantidad</th>
                                </tr>';
                            
                            print "<tr><td>" . $productos['productName'] . "</td><td>" . $productos['quantityInStock'] . "</td></tr>";
                            
                            print "</tr>";
                            echo "</div>";
                        }
                    ?>
                    <BR>
                    <div class="card-footer text-center">
                        <div>
                            <input type="submit" name="consultarStock" value="Consultar Stock"  class="btn btn-warning ">
                            <input type="submit" name="atras" value="Atras" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    
    
</body>
</html>
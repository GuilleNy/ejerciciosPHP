<pre>
<?php print_r($_SESSION); ?>
</pre>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>

    <div class="container">
        <!-- Aplicación -->
        <div class="card border-success mb-3 mx-auto" style="max-width: 30rem;">
            
            <div class="card-header text-center">
                <h2><b>Elegir Acción</b></h2>
                <B>Numero Cliente:</B>  <?php //echo $_SESSION["DatosUsuario"]['customerNumber']; ?>  <BR>
                <B>Nombre Cliente:</B>  <?php //echo $_SESSION["DatosUsuario"]['contactFirstName']." ".$_SESSION['DatosUsuario']['contactLastName']; ?>  <BR>
                <B>Credito Limite:</B>  <?php //echo $_SESSION["DatosUsuario"]['creditLimit']; ?>  <BR><BR>
            </div>
           
            <form action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" >
                <div class="card-body text-center">
                    <input type="submit" name="altaped" value="Alta Pedido" class="btn btn-primary mb-2">
                    <input type="submit" name="consped" value="Consultar Compras" class="btn btn-primary mb-2">
                    <input type="submit" name="consprodstock" value="Consultar Stock" class="btn btn-primary mb-2">
                </div>

                <div class="card-footer text-center">
                    <input type="submit" name="cerrarsesion" value="Cerrar Sesión" class="btn btn-warning">
                </div>
            </form>
        </div>
    </div>

</body>
</html>

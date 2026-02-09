<pre>
<?php print_r($_COOKIE); ?>
</pre>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login de Clientes</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Login de Clientes</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form"  action="<?php htmlspecialchars ($_SERVER["PHP_SELF"]); ?>" method="post" class="card-body">
                    <div class="form-group">
                        <B>Usuario:</B>
                        <input type="text" name="usuario" class="form-control">
                    </div>

                    <div class="form-group">
                        <B>Clave:</B>
                        <input type="text" name="clave" class="form-control">
                    </div>
                    <input type="submit" name="login" value="Login" class="btn btn-warning">
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alta Almacen</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">Alta Almacenes</h1>

    <div class="container">
        <!--Aplicacion-->
        <div class="card border-success mb-3 mx-auto" style="max-width: 20rem;">
            <div class="card-body">
                <form id="product-form" name="poker" action="comaltaalm.php" method="post" class="card-body">

                    <div class="form-group">
                        Almacen:
                        <input type="text" name="localidad" placeholder="Localidad" class="form-control">
                    </div>
                    
                    <input type="submit" name="submit" value="Dar Alta" class="btn btn-warning">

                </form>
            </div>
        </div>
    </div>
</body>


</html>
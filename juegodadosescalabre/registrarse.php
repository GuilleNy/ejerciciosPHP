<!DOCTYPE html>
<html lang="es">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Juego de Dados - Práctica Obligatoria</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
</head>

<body>
  

  <form name="juegodados" action="registro.php" method="post">

    <div class="container">
      <!-- Aplicación -->
      <div class="card border-success mb-3" style="max-width: 30rem;">
        <div class="card-header">
          <b>JUEGO DADOS</b>
        </div>
        <div class="card-body">
          <b>Nombre:</b> <input type="text" name="nombre" size="25"><br><br>
          <b>Apellido:</b> <input type="text" name="apellido" size="25"><br><br>
          <b>Correo Electrónico:</b> <input type="text" name="correo" size="25"><br><br>


          <!-- boton -->
          <div>
            <input type="submit" value="Registrar" name="registrar" class="btn btn-warning disabled">
          </div>

        </div>
      </div>        
    </div>
   
  </form>

</body>
</html>

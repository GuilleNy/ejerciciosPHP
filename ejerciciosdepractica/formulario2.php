<html>
    <body>
       <?php
            $cadena= $_REQUEST['cadena'];
            echo "Cadena introducida: " . $cadena;
            echo "<br>";

            $sexo=$_REQUEST['sexo'];
            echo "Usted a seleccionado: " . $sexo;

            echo "<br>";
            $extras=$_REQUEST['extras'];
            echo "Usted ha seleccionado: ";
            foreach($extras as $valor)
            {
                echo $valor . " \n";
                
            }
            
            echo "<br>";

            $color=$_REQUEST['color'];

            echo "Usted ha seleccionado el color: " . $color;


            echo "<br>";
            $idioma=$_REQUEST['idiomas'];
            echo "Usted ha seleccionado estos idiomas: ";
            foreach($idioma as $valor)
            {
                echo $valor . " \n";
            }
            echo "<br>";

            $comentario=$_REQUEST['comentario'];
            echo "Has comentado lo siguiente: " . $comentario;

            echo "<br>";
           
            $actualizar=$_REQUEST['enviar'];

            if($actualizar)
            {
                echo "Usted ha presionado el boton Actualizar datos";
            }
            echo "<br>";

            if(isset($actualizar))
            {
                echo "Datos enviados correctamente";
            }

            

        ?>
    </body>
</html>
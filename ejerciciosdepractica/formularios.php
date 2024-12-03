<html>
    <body>
        <form action="formulario2.php" method="POST">
            <!-- Tipo texto-->
            <label>Introduzca la cadena a buscar: </label>
            <input type="text" name="cadena" value="">
            <br>
            <br>
            <!-- Tipo radio -->
            <label>Sexo: </label>
            <label>Mujer</label>
            <input type="radio" name="sexo" value="Mujer">
            <label>Hombre</label>
            <input type="radio" name="sexo" value="Hombre">
            <br>
            <br>
            <!-- Tipo checkbox -->
            <label>Garaje </label>
            <input type="checkbox" name="extras[]" value="garaje">
            <label>Piscina </label>
            <input type="checkbox" name="extras[]" value="piscina">
            <label>Jardin </label>
            <input type="checkbox" name="extras[]" value="jardin">
            <br>
            <br>
            
            <!-- Select simple -->
            <label for="color">Color:</label>
            <select name="color">
                <option value="rojo">Rojo</option>
                <option value="verde">Verde</option>
                <option value="azul">Azul</option>
            </select>    
            <br>
            <br>     
            <!-- Select multiple -->
             <label for="idiom">Idiomas</label>
             <select name="idiomas[]" multiple >
                <option value="ingles">Ingles</option>
                <option value="frances">Frances</option>
                <option value="aleman">Aleman</option>
                <option value="holandes">Holandes</option>
             </select>
            <br>
            <br> 
            <!-- Textarea -->
             <label for="coment">Comentario: </label>
            <textarea name="comentario" ></textarea>
            <br>
            <br>
            <!-- Tipo submit -->
            <input type="submit" name="enviar" value="Enviar datos">
            
            
        </form>
        <form action="subir.php" method="POST" enctype="multipart/form-data">
            
            Selecciona una imagen:
            <input type="file" name="imagen">
            <br>
            <input type="submit" value="Subir Imagen">
        </form>
    </body>
</html>

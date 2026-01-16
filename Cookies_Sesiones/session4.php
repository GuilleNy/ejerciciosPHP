<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// elimina variables de sesión
session_unset();

// elimina la sesión
session_destroy();

//Elimina la Cookie local que contiene el id de la sesión
setcookie("PHPSESSID", "", time() - 3600,"/");

echo "Variables eliminadas y sesi&oacuten destruida"
?>

</body>
</html>
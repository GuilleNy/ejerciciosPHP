<HTML>
<HEAD><TITLE> EJ5B – Tabla multiplicar con TD</TITLE></HEAD>
<BODY>
<?php
$num="8";

echo "<table table border='1' style='border-collapse: collapse; text-align: center; width: 120px;'>";
for ($i=1; $i < 13; $i++) { 
    echo "<tr><td>" . $num  . " x " . $i .  "</td><td>" . ($num*$i) . "</td></tr><br/>";
    
}

echo "</table>";
?>
</BODY>
</HTML>
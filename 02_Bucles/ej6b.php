<HTML>
<HEAD><TITLE> EJ6B – Factorial</TITLE></HEAD>
<BODY>
<?php
$num="4";
$fact=1;

echo $num . "!=";
for ($i=(int)$num; $i > 0 ; $i--) { 
    $fact*=$i;
    if($i > 1){
        echo $i . "x ";  
    }else{
        echo $i . "=";
    }
}
echo $fact;



?>
</BODY>
</HTML>

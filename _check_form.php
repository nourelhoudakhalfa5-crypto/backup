<?php
$c = file_get_contents('login.php');
echo substr($c, strpos($c, '<!-- Password -->'), 1000);
?>

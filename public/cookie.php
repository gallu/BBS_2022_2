<?php  // cookie.php

ob_start();

$i = random_int(1, 100);

echo "test <br>";

setcookie("test", "value");
setcookie("count", strval(@$_COOKIE["count"] + $i));

var_dump($i);
var_dump($_COOKIE);

ob_end_flush();

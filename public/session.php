<?php  // session.php

ob_start();
session_start();
$i = random_int(1, 100);

echo "test <br>";

$_SESSION["test"] = "value";
$_SESSION["count"] = @$_SESSION["count"] + $i;

var_dump($i);
var_dump($_SESSION);

ob_end_flush();

<?php   // twig_test.php
//  php  twig_test.php
require_once(__DIR__ . '/vendor/autoload.php');

$loader = new \Twig\Loader\ArrayLoader([
    'index' => "Hello {{ name }}! \n",
]);
$twig = new \Twig\Environment($loader);

echo $twig->render('index', ['name' => 'おいちゃん']);

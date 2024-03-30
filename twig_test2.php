<?php   // twig_test2.php
// php  twig_test2.php
require_once(__DIR__ . '/vendor/autoload.php');

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);
/*
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);
*/

echo $twig->render('test2.twig', ['name' => '<a>', 'address' => 'hoge']);

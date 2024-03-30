<?php  // index.php
// https://dev2.m-fr.net/アカウント名/BBS/
ob_start();
session_start();

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../libs/Bbs.php');

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// flashセッションのデータを取得
$flash = $_SESSION["flash"] ?? [];
unset($_SESSION["flash"]);

// ページ番号の取得
$page_num = intval($_GET["p"] ?? 1);
if (0 >= $page_num) {
    $page_num = 1;
}
//var_dump($page_num);

// 掲示板の書き込み内容を取得
$bbs_data = Bbs::getList($page_num);
//var_dump($bbs_data);

// 「older_page_num」の有無の確認とbbs_dataのデータ件数の調整
if (count($bbs_data) <= Bbs::PAR_PAGE) {
    // older_page_numがないので、フラグをfalseにする
    $older_page_flg = false;
} else {
    // older_page_numがあるので、データ件数の調整
    $older_page_flg = true;
    array_pop($bbs_data);
}

// twigに渡すデータの作成
$context = [
    "bbs_data" => $bbs_data ,
    "flash" => $flash,
    "older_page_num" => $page_num + 1,
    "newer_page_num" => $page_num - 1, 
    "older_page_flg" => $older_page_flg,
    "now_page_num" => $page_num,
];

// twigの準備
$loader = new FilesystemLoader(__DIR__ . "/../templates");
$twig = new Environment($loader);

// 出力
echo $twig->render("index.twig", $context);

<?php  // delete.php
//
ob_start();
session_start();

require_once(__DIR__ . '/../libs/Bbs.php');

// 削除コードを把握
$delete_code = @strval($_POST["delete_code"] ?? "");
if ("" === $delete_code) {
    header("Location: ./index.php");
    exit;
}
var_dump($delete_code);

// 書き込みを把握
$bbs_id = @strval($_POST["bbs_id"] ?? "");
if ("" === $bbs_id) {
    header("Location: ./index.php");
    exit;
}
var_dump($bbs_id);

// 「書き込みの削除コード」と「入力された削除コード」を比較
$bbs = Bbs::find($bbs_id);
if (false === $bbs) {
    header("Location: ./index.php");
    exit;
}
//var_dump($bbs);
if ($delete_code !== $bbs["delete_code"]) {
    //var_dump($delete_code, $bbs["delete_code"]);
    $_SESSION["flash"]["error"]["delete"] = true;
    header("Location: ./index.php");
    exit;
}

// 書き込みを削除
Bbs::delete($bbs_id);

//
$_SESSION["flash"]["message"]["delete_success"] = true;
header("Location: ./index.php");

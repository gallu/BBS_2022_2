<?php  // write.php
//
ob_start();
session_start();

// クラスの読み込み
require_once(__DIR__ . '/../libs/db_handle.php');

// データを受け取る
/*
$title = strval($_POST["title"] ?? "");
$name = strval($_POST["name"] ?? "");
$body = strval($_POST["body"] ?? "");
var_dump($title, $name, $body);
*/
$params = ["title", "name", "body", "delete_code"];
$datum = [];
foreach($params as $p) {
    $datum[$p] = strval($_POST[$p] ?? "");
}
//var_dump($datum);

// validate
$error = [];
//
if ("" === $datum["body"]) {
    $error["body_must"] = true;
}
//
if ([] !== $error) {
    // なんかエラーだったぽい
    //var_dump($error);
    $_SESSION["flash"]["error"] = $error;
    $_SESSION["flash"]["datum"] = $datum;
    header("Location: ./index.php");
    exit;
}

//echo "ok";

// 追加情報を入れる
$datum["user_agent"] = $_SERVER["HTTP_USER_AGENT"];
$datum["from_ip"] = $_SERVER["REMOTE_ADDR"];
//var_dump($datum);
//$datum['name'] = null;
try {
    /* DBに書き込む */
    // DBハンドルを取得
    $dbh = Db::getHandle();
    //var_dump($dbh);

    // プリペアドステートメントを作成
    $sql = 'INSERT INTO bbses(name, title, body, delete_code, user_agent, from_ip, created_at)
        VALUES(:name, :title, :body, :delete_code, :user_agent, :from_ip, :created_at);';
    $pre = $dbh->prepare($sql);
    //var_dump($pre);

    // プレースホルダに値をバインド
    foreach($datum as $k => $v) {
        $pre->bindValue(":{$k}", $v, PDO::PARAM_STR);
    }
    $pre->bindValue(":created_at", date(DATE_ATOM), PDO::PARAM_STR);

    // 実行
    $r = $pre->execute();
    var_dump($r);
} catch(PDOException $e) {
    $_SESSION["flash"]["error"]["post"] = true;
    header("Location: ./index.php");
    exit;
}

// top pageに移動する
$_SESSION["flash"]["message"]["post_success"] = true;
header("Location: ./index.php");
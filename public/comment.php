<?php  // comment.php
ob_start();
session_start();

require_once(__DIR__ . '/../libs/Bbs.php');

// 入力情報を取得
$datum = [];
$datum["bbs_id"] = (string)($_POST["bbs_id"] ?? "");
$datum["comment_body"] = (string)($_POST["comment"] ?? "");
$page_num = $_POST["p"];
var_dump($datum, $page_num);

// validate
$error = false;
// 入力が空ならエラー
if (("" === $datum["comment_body"])||("" === $datum["bbs_id"])) {
    $error = true;
}
// bbs_idが存在しないidならエラー
$bbs = Bbs::find($datum["bbs_id"]);
if (false === $bbs) {
    $error = true;
}
if (true === $error) {
    header("Location: ./index.php?p=" . rawurlencode($page_num));
    exit;
}

// $_SERVERからの情報追加
$datum["user_agent"] = $_SERVER["HTTP_USER_AGENT"];
$datum["from_ip"] = $_SERVER["REMOTE_ADDR"];
var_dump($datum);

try {
    /* DBに書き込む */
    // DBハンドルを取得
    $dbh = Db::getHandle();

    // oneline_commentsにinsert
    // プリペアドステートメントを作成
    $sql = 'INSERT INTO oneline_comments(bbs_id, comment_body, user_agent, from_ip, created_at)
        VALUES(:bbs_id, :comment_body, :user_agent, :from_ip, :created_at);';
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
// var_dump($e->getMessage());
// exit;
    header("Location: ./index.php?p=" . rawurlencode($page_num));
    exit;
}

// index.phpに移動
header("Location: ./index.php?p=" . rawurlencode($page_num) . "#bbs_" . rawurlencode($datum["bbs_id"]));

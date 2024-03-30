<?php  // Bbs.php

// クラスの読み込み
require_once(__DIR__ . '/db_handle.php');

class Bbs {
    // １ページあたりの投稿表示数
    public const PAR_PAGE = 5;

    // 掲示板の書き込み内容を取得
    public static function getList($page_num) {
        // DBハンドルを取得
        $dbh = Db::getHandle();
//var_dump($dbh);

        // プリペアドステートメントを作成
        $sql = 'SELECT * FROM bbses ORDER BY bbs_id DESC LIMIT :limit OFFSET :offset;';
        $pre = $dbh->prepare($sql);
//var_dump($pre);

        // プレースホルダに値をバインド
        $offset = ($page_num - 1) * self::PAR_PAGE;
        $pre->bindValue(":limit", self::PAR_PAGE + 1, PDO::PARAM_INT);
        $pre->bindValue(":offset", $offset, PDO::PARAM_INT);

        // 実行
        $r = $pre->execute();
//var_dump($r);
        
        // データをフェッチ
        $data = $pre->fetchAll();

        // １行コメントを追加
        foreach($data as $k => $datum) {
            // プリペアドステートメントを作成
            $sql = 'SELECT * FROM oneline_comments WHERE bbs_id = :bbs_id ORDER BY created_at DESC ;';
            $pre = $dbh->prepare($sql);
            // プレースホルダに値をバインド
            $pre->bindValue(":bbs_id", $datum["bbs_id"], PDO::PARAM_INT);
            // 実行
            $r = $pre->execute();

            // データ取得
            $data[$k]["oneline_comment"] = $pre->fetchAll();
        }
//var_dump($data);
        return $data;
    }

    /**
     * 「１つの書きこみ」の取得
     */
    public static function find($bbs_id) {
        // DBハンドルを取得
        $dbh = Db::getHandle();
//var_dump($dbh);

        // プリペアドステートメントを作成
        $sql = 'SELECT * FROM bbses WHERE bbs_id = :bbs_id;';
        $pre = $dbh->prepare($sql);
//var_dump($pre);

        // プレースホルダに値をバインド
        $pre->bindValue(":bbs_id", $bbs_id, PDO::PARAM_STR);

        // 実行
        $r = $pre->execute();
//var_dump($r);
        
        // データをフェッチ
        $datum = $pre->fetch();
        return $datum;
    }

    /**
     * 削除
     */
    public static function delete($bbs_id) {
        // DBハンドルを取得
        $dbh = Db::getHandle();
//var_dump($dbh);

        try {
            // トランザクション開始
            $dbh->beginTransaction();

            /* oneline_commentsの対象レコードを削除 */
            $sql = 'DELETE FROM oneline_comments WHERE bbs_id = :bbs_id';
            $pre = $dbh->prepare($sql);
    //var_dump($pre);
            // プレースホルダに値をバインド
            $pre->bindValue(":bbs_id", $bbs_id, PDO::PARAM_STR);
            // 実行
            $r = $pre->execute();

            /* bbsesの対象レコードを削除 */
            // プリペアドステートメントを作成
            $sql = 'DELETE FROM bbses WHERE bbs_id = :bbs_id;';
            $pre = $dbh->prepare($sql);
    //var_dump($pre);
            // プレースホルダに値をバインド
            $pre->bindValue(":bbs_id", $bbs_id, PDO::PARAM_STR);
            // 実行
            $r = $pre->execute();
    //var_dump($r);

            // トランザクション終了
            $dbh->commit();
        } catch(PDOException $e) {
            // トランザクション異常終了
            if (true === $dbh->inTransaction()) {
                $dbh->rollback();
            }
            return ;
        }
    }
}

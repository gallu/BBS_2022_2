DROP TABLE bbses;
CREATE TABLE bbses (
    bbs_id SERIAL ,
    name VARCHAR(128) NOT NULL COMMENT '投稿者名',
    title VARCHAR(128) NOT NULL COMMENT 'タイトル',
    body TEXT COMMENT '本文',
    -- 
    user_agent TEXT COMMENT 'ブラウザ名',
    from_ip VARBINARY(128) NOT NULL COMMENT '接続元IP',
    -- 
    created_at DATETIME NOT NULL COMMENT '書き込み日時',
    -- updated_at DATETIME NOT NULL COMMENT '',
    -- 
    PRIMARY KEY(bbs_id)
)CHARACTER SET 'utf8mb4', COMMENT='掲示板テーブル';
-- 
ALTER TABLE bbses ADD delete_code VARBINARY(64) NOT NULL DEFAULT '' COMMENT '削除コード';

DROP TABLE oneline_comments;
CREATE TABLE oneline_comments (
    oneline_comment_id SERIAL ,
    bbs_id  BIGINT UNSIGNED NOT NULL COMMENT '紐づく掲示板id',
    comment_body TEXT COMMENT 'コメント本文',
    -- 
    user_agent TEXT COMMENT 'ブラウザ名',
    from_ip VARBINARY(128) NOT NULL COMMENT '接続元IP',
    -- 
    created_at DATETIME NOT NULL COMMENT '',
    -- updated_at DATETIME NOT NULL COMMENT '',
    -- 外部キー制約
    CONSTRAINT fk_comments_bbs_id FOREIGN KEY (bbs_id) REFERENCES bbses (bbs_id),
    -- 
    PRIMARY KEY(oneline_comment_id)
)CHARACTER SET 'utf8mb4', COMMENT='掲示板のコメントテーブル';
 



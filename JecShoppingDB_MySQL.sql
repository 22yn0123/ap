DROP TABLE IF EXISTS SaleDetail;
DROP TABLE IF EXISTS Sale;
DROP TABLE IF EXISTS Cart;
DROP TABLE IF EXISTS Member;
DROP TABLE IF EXISTS Goods;
DROP TABLE IF EXISTS GoodsGroup;

/* 商品分類テーブル */
CREATE TABLE GoodsGroup(
    groupcode   INT      NOT NULL  PRIMARY KEY,  /* 商品分類コード */
    groupname   TEXT     NOT NULL                /* 分類名 */
);

/* 商品テーブル */
CREATE TABLE Goods(
    goodscode   CHAR(5)  NOT NULL  PRIMARY KEY,        /* 商品コード */
    goodsname   TEXT     NOT NULL,                     /* 商品名 */
    price       INT      NOT NULL  CHECK(price >= 0),  /* 商品単価 */
    detail      TEXT,                                  /* 詳細情報 */
    groupcode   INT      NOT NULL,                     /* 商品分類コード */
    recommend   BIT      NOT NULL,                     /* おすすめ商品:1, 通常商品:0 */
    goodsimage  TEXT,                                  /* 商品イメージ(ファイル名) */
    FOREIGN KEY(groupcode) REFERENCES GoodsGroup(groupcode)
);

/* 会員テーブル */
CREATE TABLE Member(
    memberid    INT      NOT NULL  AUTO_INCREMENT PRIMARY KEY,  /* 会員ID */
    email       TEXT     NOT NULL  UNIQUE,             /* メールアドレス */
    membername  TEXT     NOT NULL,                     /* 会員名 */
    zipcode     TEXT     NOT NULL,                     /* 郵便番号 */
    address     TEXT     NOT NULL,                     /* 会員住所 */
    tel         TEXT,                                  /* 電話番号 */
    password    TEXT     NOT NULL                      /* パスワード */
);

/* カートテーブル */
CREATE TABLE Cart(
    memberid    INT      NOT NULL  REFERENCES Member(memberid), /* 会員ID */
    goodscode   CHAR(5)  NOT NULL  REFERENCES Goods(goodscode), /* 商品コード */
    num         INT      NOT NULL  CHECK(num > 0),              /* 商品個数 */
    PRIMARY KEY(memberid, goodscode),
    FOREIGN KEY(memberid)  REFERENCES Member(memberid),
    FOREIGN KEY(goodscode) REFERENCES Goods(goodscode)
);

/* 売上テーブル */
CREATE TABLE Sale(
    saleno      INT      NOT NULL  AUTO_INCREMENT PRIMARY KEY,  /* 売上番号 */
    saledate    DATETIME NOT NULL,                              /* 売上日時 */
    memberid    INT      NOT NULL,                              /* 会員ID */
    FOREIGN KEY(memberid) REFERENCES Member(memberid)
);

/* 売上詳細テーブル */
CREATE TABLE SaleDetail(
    saleno      INT      NOT NULL,                              /* 売上番号 */
    goodscode   CHAR(5)  NOT NULL,                              /* 商品コード */
    num         INT      NOT NULL  CHECK(num > 0),              /* 販売個数 */
    PRIMARY KEY(saleno, goodscode),
    FOREIGN KEY(saleno)    REFERENCES Sale(saleno),
    FOREIGN KEY(goodscode) REFERENCES Goods(goodscode)
);

/* 商品分類テーブルのデータ */
INSERT INTO GoodsGroup
    (groupcode, groupname)
VALUES
    (1, 'Tシャツ'),
    (2, 'ズボン'),
    (3, '靴'),
    (4, '椅子');

/* 商品テーブルのデータ */
INSERT INTO Goods
    (goodscode, goodsname,     price,  detail,    groupcode, recommend, goodsimage)
VALUES
    ('A0001', 'Tシャツ(赤)',    1000, 'JEC特製のＴシャツです。', 1, 0, 'A0001.jpg'),
    ('A0002', 'Tシャツ(緑)',    1000, 'JEC特製のＴシャツです。', 1, 0, 'A0002.jpg'),
    ('A0003', 'Tシャツ(黄)',    1000, 'JEC特製のＴシャツです。', 1, 0, 'A0003.jpg'),
    ('A0004', 'Tシャツ(白)',     800, 'JEC特製のＴシャツです。', 1, 0, 'A0004.jpg'),
    ('A0005', 'Tシャツ(紅白)',  1500, 'JEC特製のＴシャツです。', 1, 1, 'A0005.jpg'),
    ('A0006', 'Tシャツ(黒)',    1000, 'Ｔシャツです。',          1, 0, 'A0006.jpg'),
    ('A0007', 'Tシャツ(青)',    1000, 'Ｔシャツです。',          1, 0, 'A0007.jpg'),
    ('A0008', 'Tシャツ(特製)',  2000, 'Ｔシャツです。',          1, 1, 'A0008.jpg'),
    ('B0001', 'ズボン(白)',     2000, '特製ズボンです。',        2, 0, 'B0001.jpg'),
    ('B0002', 'ズボン(黒)',     1000, '特製ズボンです。',        2, 0, 'B0002.jpg'),
    ('B0003', 'ズボン(青)',     2000, '特製ズボンです。',        2, 0, 'B0003.jpg'),
    ('B0004', 'ズボン(緑)',     2000, '特製ズボンです。',        2, 1, 'B0004.jpg'),
    ('C0001', '靴(黒)',         2000, '素敵な通勤をあなたに',    3, 0, 'C0001.jpg'),
    ('C0002', '靴(茶)',         1000, '素敵な散歩をあなたに',    3, 0, 'C0002.jpg'),
    ('C0003', '靴(白)',         2000, '素敵なデートをあなたに',  3, 0, 'C0003.jpg'),
    ('C0004', '靴(紅白)',       2000, '素敵なお笑いをあなたに',  3, 1, 'C0004.jpg'),
    ('D0001', '椅子(木)',       2000, '特製の椅子です。',        4, 0, 'D0001.jpg'),
    ('D0002', '椅子(スチール)', 1000, '特製の椅子です。',        4, 1, 'D0002.jpg'),
    ('D0003', '椅子(子供用)',   2000, '特製の椅子です。',        4, 0, 'D0003.jpg'),
    ('D0004', '椅子(赤ん坊用)', 2000, '特製の椅子です。',        4, 0, 'D0004.jpg');

/* 会員テーブルのデータ */
INSERT INTO Member
    (email,             membername, zipcode,    address,                    tel,            password)
VALUES
    ('jec01@jec.ac.jp', '電子太郎', '169-8522', '東京都新宿区百人町1-25-4', '03-3369-9333', '$2y$10$tYsLkR3UWWGverRUhP8omeBUAz203Nm9MIsHI6bfJ3ipKyfwfpzOW'), /* 会員ID:1, パスワード:jec01 */
    ('jec02@jec.ac.jp', '電子花子', '350-0841', '埼玉県川越市御成町1-2-3',  '049-222-3344', '$2y$10$HWyQ8/WFtartktF7yIHIdu41Z3..989lYevPVm0LBeSgvU9bep5P6'); /* 会員ID:2, パスワード:jec02 */

/* カートテーブルのデータ */
INSERT INTO Cart
    (memberid, goodscode, num)
VALUES
    (1, 'A0005', 10),
    (1, 'C0004', 20),
    (2, 'D0001', 30);

/* 売上テーブルのデータ */
INSERT INTO Sale
    (saledate, memberid)
VALUES
    ('2020-04-01 00:00:00', 1);

/* 売上詳細テーブルのデータ */
INSERT INTO SaleDetail
    (saleno, goodscode, num)
VALUES
    (1, 'A0001', 10),
    (1, 'A0002', 20);

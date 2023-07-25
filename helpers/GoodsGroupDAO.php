<?php
require_once 'DAO.php';

class GoodsGroup
{
    public int $groupcode; //商品分類コード
    public String $groupname; //商品分類名
}

class GoodsGroupDAO
{
    public function get_goodsgroup()
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM GoodsGroup";
        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetchObject('GoodsGroup')) {
            $data[] = $row;
        }

        return $data;
    }
}

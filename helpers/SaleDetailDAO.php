<?php
require_once 'DAO.php';

class SaleDetail
{
    public int $saleno;
    public string $goodscode;
    public int $num;
}

class SaleDetailDAO
{
    public function insert(SaleDetail $detail, PDO $dbh)
    {
        $sql = "INSERT INTO SaleDetail(saleno,goodscode,num) VALUES(:saleno,:goodscode,:num)";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':saleno', $detail->saleno, PDO::PARAM_INT);
        $stmt->bindValue(':goodscode', $detail->goodscode, PDO::PARAM_STR);
        $stmt->bindValue(':num', $detail->num, PDO::PARAM_INT);

        $stmt->execute();
    }
}

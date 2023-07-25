<?php
require_once 'DAO.php';
require_once 'CartDAO.php';
require_once 'saleDetailDAO.php';

class saleDAO
{
    private function get_saleno()
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT IDENT_CURRENT('Sale') AS saleno";

        $stmt = $dbh->query($sql);

        $row = $stmt->fetchObject();
        return $row->saleno;
    }

    public function insert(int $memberid, array $cart_list)
    {
        $ret = false;
        $dbh = DAO::get_db_connect();

        try {
            $dbh->beginTransaction();

            $sql = "SELECT * FROM Sale WITH(TABLOCK,HOLDLOCK)";
            $dbh->query($sql);

            $sql = "INSERT INTO sale(saledate,memberid) VALUES(:saledate,:memberid)";

            $stmt = $dbh->prepare($sql);

            $saledate = date('Y-m-d H:i:s');

            $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
            $stmt->bindValue(':saledate', $saledate, PDO::PARAM_STR);

            $stmt->execute();

            $saleno = $this->get_saleno();


            $saleDetailDAO = new SaleDetailDAO();

            foreach ($cart_list as $cart) {

                $saleDetail = new SaleDetail();

                $saleDetail->saleno = $saleno;
                $saleDetail->goodscode = $cart->goodscode;
                $saleDetail->num = $cart->num;

                $saleDetailDAO->insert($saleDetail, $dbh);
            }
            $dbh->commit();
            $ret = true;
        } catch (PDOException $e) {
            $dbh->rollBack();
            $ret = false;
        }
        return $ret;
    }
}

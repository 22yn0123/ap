<?php
require_once './helpers/CartDAO.php';
require_once './helpers/MemberDAO.php';

session_start();

if (empty($_SESSION['member'])) {
    header('Location:Login.php');
    exit;
}

$member = $_SESSION['member'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $goodscode = $_POST['goodscode'];
        $num = $_POST['num'];

        $cartDAO = new CartDAO();
        $cartDAO->insert($member->memberid, $goodscode, $num);
    } elseif (isset($_POST['chenge'])) {
        $goodscode = $_POST['goodscode'];
        $num = $_POST['num'];

        $cartDAO = new CartDAO;
        $cartDAO->update($member->memberid, $goodscode, $num);
    } elseif (isset($_POST['delete'])) {
        $goodscode = $_POST['goodscode'];

        $cartDAO = new CartDAO();
        $cartDAO->delete($member->memberid, $goodscode);
    }

    header("Location:" . $_SERVER['PHP_SELF']);
    exit;
}

$cartDAO = new CartDAO();
$cart_list = $cartDAO->get_cart_by_memberid($member->memberid);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>ショッピングカート</title>
</head>

<body>
    <?php include "header.php" ?>

    <?php if (empty($cart_list)) : ?>
        <p>カートに商品はありません。</p>
        <a href="index.php">トップページへ</a>
    <?php else : ?>
        <?php foreach ($cart_list as $cart) : ?>
            <table>
                <tr>
                    <td rowspan="4">
                        <img src="images/goods/<?= $cart->goodsimage ?>">
                    </td>
                    <td>
                        <?= $cart->goodsname ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $cart->detail ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $cart->price ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form action="" method="POST">
                            数量
                            <input type="number" name="num" value="<?= $cart->num ?>" min="1" max="99">
                            <input type="hidden" name="goodscode" value="<?= $cart->goodscode ?>">
                            <input type="submit" name="chenge" value="変更">
                            <input type="submit" name="delete" value="削除">
                        </form>
                    </td>
                </tr>
            </table>
            <hr>
        <?php endforeach; ?>

        <form action="buy.php" method="POST">
            <input type="submit" value="商品を購入する">
        </form>

    <?php endif; ?>

</body>

</html>
<?php
require_once './helpers/MemberDAO.php';
require_once './helpers/CartDAO.php';
require_once './helpers/SaleDAO.php';

session_start();

if (empty($_SESSION['member'])) {
    header('Location:index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:cart.php');
    exit;
}

$member = $_SESSION['member'];

$cartDAO = new CArtDAO();
$cart_list = $cartDAO->get_cart_by_memberid($member->memberid);

$saleDAO = new SaleDAO();
$ret = $saleDAO->insert($member->memberid, $cart_list);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/SMTP.php';
require_once './helpers/CartDAO.php';
require_once './helpers/MemberDAO.php';

mb_language('uni');
mb_internal_encoding('UTF-8');

$mail = new PHPMailer(true);

$mail->CharSet = 'utf-8';

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = '22yn0123@jec.ac.jp';
    $mail->Password   = 'typMJJX93ffs';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;


    $mail->addAddress("{$member->email}", '受信者名');

    $mail->Subject = 'JecShopping購入完了';

    $mail->isHTML(true);

    $mail->Body = "
    <p>{$member->membername}　さん。</p>
    <p>購入ありがとうございました。</p>";

    foreach ($cart_list as $cart) {
        $mail->AddEmbeddedImage("./images/goods/{$cart->goodsimage}", $cart->goodsimage);
        $mail->Body .= "
        <table>
            <tr>
                <td><img src='cid:{$cart->goodsimage}'></td>
            </tr>
            <tr>
                <td>{$cart->goodsname}</td>
            </tr>
            <tr>
                <td>{$cart->price}</td>
            </tr>
            <tr>
                <td>{$cart->num}</td>
            </tr>
        </table>
        <hr>
    ";
    }

    $mail->send();
} catch (Exception $e) {
}


if ($ret === true) {
    $cartDAO->delete_by_memberid($member->memberid);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>購入完了</title>
</head>

<body>
    <?php include "header2.php" ?>

    <?php if ($ret === true) : ?>
        <p>購入が完了しました。</p>
        <a href="index.php">トップページへ</a>
    <?php else : ?>
        <p>購入処理でエラーが発生しました。カートページへ戻りもう一度やり直して下さい</p>
        <p><a href="cart.php">カートページへ</a></p>
    <?php endif; ?>
</body>

</html>
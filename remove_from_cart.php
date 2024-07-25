<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/transaksi.inc.php';

$config = new Config();
$db = $config->getConnection();
$transaksi = new Transaksi($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_transaksi'])) {
    $id_transaksi = $_POST['id_transaksi'];
    // Hapus pesanan dari keranjang
    $transaksi->removeFromCart($id_transaksi);

    // Redirect ke halaman keranjang.php
    header("Location: keranjang.php");
    exit;
}
?>

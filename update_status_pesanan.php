<?php
include_once 'includes/config.php';
include_once 'includes/transaksi.inc.php';
session_start(); // Pastikan sesi dimulai

$config = new Config();
$db = $config->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_transaksi = $_POST['id_transaksi'];
    $status_pesanan = $_POST['status_pesanan'];

    $pro = new Transaksi($db);

    // Validasi input
    if (!in_array($status_pesanan, ['menunggu pembayaran', 'diproses', 'dikirim', 'diterima', 'ditinjau'])) {
        $_SESSION['message'] = "Status pesanan tidak valid.";
        header('Location: transaksi.php');
        exit;
    }

    // Update status pesanan
    try {
        if ($pro->updateStatus($id_transaksi, $status_pesanan)) {
            $_SESSION['message'] = "Status pesanan berhasil diperbarui.";
        } else {
            $_SESSION['message'] = "Gagal memperbarui status pesanan.";
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Terjadi kesalahan: " . $e->getMessage();
    }

    header('Location: transaksi.php'); // Redirect kembali ke halaman transaksi
}
?>

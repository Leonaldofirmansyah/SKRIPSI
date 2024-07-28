<?php
session_start();
include_once '../includes/Config.php'; // Sesuaikan path jika perlu

$config = new Config();
$pdo = $config->getConnection(); // Mendapatkan koneksi database

if (isset($_GET['kode_item'])) {
    $kode_item = $_GET['kode_item'];

    try {
        // Update status pesanan menjadi 'Dalam Produksi'
        $stmt = $pdo->prepare("UPDATE transaksi SET status_pesanan = 'Dalam Produksi' WHERE kode_item = :kode_item");
        $stmt->bindParam(':kode_item', $kode_item);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Pesanan berhasil dikirim ke produksi.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal mengirim pesanan ke produksi.';
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: produk_siap_produksi.php');
        exit;
    } catch (PDOException $e) {
        // Handle error
        echo 'Error: ' . $e->getMessage();
    }
} else {
    $_SESSION['message'] = 'Kode item tidak ditemukan.';
    $_SESSION['message_type'] = 'danger';
    header('Location: produk_siap_produksi.php');
    exit;
}
?>

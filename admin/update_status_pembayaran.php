<?php
include_once '../includes/db_connect.php';
include_once '../includes/config.php';
session_start();

if (!isset($_SESSION['nama_lengkap']) || $_SESSION['role'] != 'Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Hak akses tidak valid.']);
    exit;
}

$config = new Config();
$db = $config->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $status_pembayaran = $_POST['status_pembayaran'];

    // Validasi input
    if (!in_array($status_pembayaran, ['Pending', 'Diterima', 'Ditolak'])) {
        echo json_encode(['status' => 'error', 'message' => 'Status pembayaran tidak valid.']);
        exit;
    }

    $query = "UPDATE transaksi SET status_pembayaran = :status_pembayaran WHERE id_transaksi = :id_transaksi";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status_pembayaran', $status_pembayaran);
    $stmt->bindParam(':id_transaksi', $id_transaksi);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Status pembayaran berhasil diubah.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah status pembayaran.']);
    }
}
?>

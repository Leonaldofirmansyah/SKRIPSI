<?php
include_once '../includes/db_connect.php';
include_once '../includes/config.php';

session_start();

if (!isset($_SESSION['nama_lengkap']) || $_SESSION['role'] != 'Admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
    exit;
}

$config = new Config();
$db = $config->getConnection();

include_once '../includes/transaksi.inc.php';
$pro = new Transaksi($db);

function log_message($message) {
    file_put_contents('log.txt', date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transaksi = filter_input(INPUT_POST, 'id_transaksi', FILTER_SANITIZE_STRING);
    $status_pembayaran = filter_input(INPUT_POST, 'status_pembayaran', FILTER_SANITIZE_STRING);
    $keterangan = filter_input(INPUT_POST, 'keterangan', FILTER_SANITIZE_STRING);

    log_message("Received: id_transaksi={$id_transaksi}, status_pembayaran={$status_pembayaran}, keterangan={$keterangan}");

    // Validasi status pembayaran
    $valid_statuses = ['Diterima', 'Ditolak', 'Menunggu Dikonfirmasi']; // Sesuaikan dengan nilai yang valid
    if ($id_transaksi && in_array($status_pembayaran, $valid_statuses)) {
        $stmt = $db->prepare("UPDATE transaksi SET status_pembayaran = :status_pembayaran, keterangan = :keterangan WHERE id_transaksi = :id_transaksi");
        $stmt->bindParam(':status_pembayaran', $status_pembayaran);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':id_transaksi', $id_transaksi);

        if ($stmt->execute()) {
            log_message("Query executed successfully");
            echo json_encode(['status' => 'success', 'message' => 'Status pembayaran berhasil diperbarui.']);
        } else {
            $errorInfo = $stmt->errorInfo();
            log_message("Query execution error: " . implode(", ", $errorInfo));
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat memperbarui status pembayaran.']);
        }
    } else {
        log_message("Incomplete or invalid data: id_transaksi={$id_transaksi}, status_pembayaran={$status_pembayaran}, keterangan={$keterangan}");
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap atau status pembayaran tidak valid.']);
    }
}
?>

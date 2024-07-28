<?php
include_once '../includes/config.php';
include_once '../includes/status_pembayaran.inc.php';

$config = new Config();
$db = $config->getConnection();
$statusPembayaran = new StatusPembayaran($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $status = $_POST['status_pembayaran'];

    if ($statusPembayaran->updateStatus($id_transaksi, $status)) {
        $_SESSION['message'] = 'Status pembayaran berhasil diperbarui.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Gagal memperbarui status pembayaran.';
        $_SESSION['message_type'] = 'danger';
    }

    header('Location: transaksi.php');
    exit();
}
?>

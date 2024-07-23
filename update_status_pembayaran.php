<?php
include_once 'includes/db_connect.php';
include "includes/config.php";

$config = new Config();
$db = $config->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_transaksi = $_POST['id_transaksi'];
    $status_pembayaran = $_POST['status_pembayaran'];

    $query = "UPDATE transaksi SET status_pembayaran = :status_pembayaran WHERE id_transaksi = :id_transaksi";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':status_pembayaran', $status_pembayaran);
    $stmt->bindParam(':id_transaksi', $id_transaksi);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Status pembayaran berhasil diperbarui.';
    } else {
        $_SESSION['message'] = 'Terjadi kesalahan saat memperbarui status pembayaran.';
    }
    echo $_SESSION['message']; // Debugging line
    header("Location: transaksi.php");
    exit();
}
?>

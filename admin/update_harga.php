<?php
include_once '../includes/db_connect.php';
include "../includes/config.php";

$config = new Config();
$db = $config->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $harga_item = $_POST['harga_item'];

    $query = "UPDATE transaksi SET harga_item = :harga_item WHERE id_transaksi = :id_transaksi";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':harga_item', $harga_item);
    $stmt->bindParam(':id_transaksi', $id_transaksi);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Harga berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui harga']);
    }
}
?>
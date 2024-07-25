<?php
include_once 'includes/db_connect.php';
include "includes/config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $keterangan = $_POST['keterangan'];

    $config = new Config();
    $db = $config->getConnection();
    
    $query = "UPDATE transaksi SET keterangan = :keterangan WHERE id_transaksi = :id_transaksi";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':id_transaksi', $id_transaksi);
    $stmt->bindParam(':keterangan', $keterangan);

    if ($stmt->execute()) {
        echo "Keterangan berhasil diperbarui";
    } else {
        echo "Gagal memperbarui keterangan";
    }
}
?>

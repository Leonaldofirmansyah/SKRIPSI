<?php
include "../includes/config.php";
session_start();

if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='login.php'</script>";
    exit();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    echo "<div class='container'><div class='text-center'>Halaman ini hanya untuk hak akses Admin saja!</div></div>";
    exit();
}

if (isset($_GET['id'])) {
    $kode_item = base64_decode($_GET['id']); // Decode ID
    $config = new Config();
    $db = $config->getConnection();
    
    // Prepare and execute the delete query
    $query = "DELETE FROM item WHERE kode_item = :kode_item";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':kode_item', $kode_item);

    if ($stmt->execute()) {
        echo "<script>alert('Data barang berhasil dihapus'); window.location.href='barang.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data barang'); window.location.href='barang.php';</script>";
    }
} else {
    echo "<script>alert('ID barang tidak valid'); window.location.href='barang.php';</script>";
}
?>

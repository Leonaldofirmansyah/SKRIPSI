<?php
include "includes/config.php";
session_start();
if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='login.php'</script>";
    exit();
}

$config = new Config();
$db = $config->getConnection();

if (isset($_SESSION['role']) && $_SESSION['role'] == base64_encode('Admin')) {
    if (isset($_GET['id'])) {
        $kode_item = base64_decode($_GET['id']);

        include_once 'includes/barang.inc.php';
        $barang = new Barang($db);
        $barang->kode_item = $kode_item;

        try {
            if ($barang->delete()) {
                echo "<script>alert('Data barang berhasil dihapus'); window.location.href='barang.php';</script>";
            } else {
                echo "<script>alert('Gagal menghapus data barang'); window.location.href='barang.php';</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='barang.php';</script>";
        }
    } else {
        echo "<script>alert('Kode item tidak ditemukan'); window.location.href='barang.php';</script>";
    }
} else {
    echo "<script>alert('Anda tidak memiliki akses untuk melakukan aksi ini.'); window.location.href='barang.php';</script>";
}
?>

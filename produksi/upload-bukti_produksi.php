<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/transaksi.inc.php';

// Pastikan pengguna sudah login dan memiliki role produksi
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'produksi') {
    $_SESSION['message'] = 'Anda harus login sebagai produksi untuk mengakses halaman ini.';
    $_SESSION['message_type'] = 'danger';
    header("Location: login.php");
    exit;
}

$config = new Config();
$db = $config->getConnection();
$transaksi = new Transaksi($db);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['bukti']) && isset($_POST['id_transaksi'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $bukti = $_FILES['bukti'];

    // Validasi file upload
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'pdf');
    $file_extension = pathinfo($bukti['name'], PATHINFO_EXTENSION);

    if (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['message'] = 'Format file tidak valid. Hanya jpg, jpeg, png, dan pdf yang diizinkan.';
        $_SESSION['message_type'] = 'danger';
        header("Location: produksi.php");
        exit;
    }

    $target_directory = "../uploads/bukti_produksi/";
    $target_file = $target_directory . basename($bukti['name']);

    if (move_uploaded_file($bukti['tmp_name'], $target_file)) {
        if ($transaksi->updateProductionProof($id_transaksi, $target_file)) {
            $_SESSION['message'] = 'Bukti produksi berhasil diupload.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal mengupdate data produksi.';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Gagal mengupload bukti produksi.';
        $_SESSION['message_type'] = 'danger';
    }

    header("Location: produksi.php");
    exit;
} else {
    $_SESSION['message'] = 'Invalid request.';
    $_SESSION['message_type'] = 'danger';
    header("Location: produksi.php");
    exit;
}
?>

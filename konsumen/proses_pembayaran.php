<?php
// Pastikan data ada di POST
if (isset($_POST['selected_items']) && isset($_POST['opsi_pembayaran']) && isset($_FILES['bukti_pembayaran'])) {
    $selected_items = $_POST['selected_items'];
    $opsi_pembayaran = $_POST['opsi_pembayaran'];
    $bukti_pembayaran = '';

    // Pastikan file berhasil diupload
    if ($_FILES['bukti_pembayaran']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/payment/";
        $target_file = $target_dir . basename($_FILES['bukti_pembayaran']['name']);
        if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $target_file)) {
            $bukti_pembayaran = $target_file;
        } else {
            echo "Gagal mengupload file.";
            exit;
        }
    } else {
        echo "Error: " . $_FILES['bukti_pembayaran']['error'];
        exit;
    }

    include_once '../includes/config.php';
    include_once '../includes/pembayaran.inc.php';

    $config = new Config();
    $db = $config->getConnection();
    $pembayaran = new Pembayaran($db);

    $selected_items_array = explode(',', $selected_items);
    foreach ($selected_items_array as $id_transaksi) {
        if (!$pembayaran->simpanPembayaran($id_transaksi, $opsi_pembayaran, $bukti_pembayaran)) {
            echo "Gagal menyimpan pembayaran untuk transaksi ID $id_transaksi.";
            exit;
        }
    }

    echo "Pembayaran berhasil disimpan.";
} else {
    echo "Data tidak lengkap.";
}
?>
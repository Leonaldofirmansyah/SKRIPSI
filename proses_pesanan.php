<?php
include_once 'header.php';
include_once 'includes/barang.inc.php';
include_once 'includes/transaksi.inc.php';

$barang = new barang($db);
$transaksi = new transaksi($db);

// Ambil data dari form
$kode_item = $_POST['kode_item'];
$nama_item = $_POST['nama_item'];
$harga_item = $_POST['harga_item'];
$jumlah = $_POST['jumlah'];
$total = $_POST['total'];
$pembeli = $_SESSION['username']; // Ambil nama pembeli dari session

// Simpan data pesanan ke dalam database
$transaksi->id_transaksi = null; // Auto increment ID
$transaksi->kode_item = $kode_item;
$transaksi->nama_item = $nama_item;
$transaksi->jumlah_transaksi = $jumlah;
$transaksi->total_transaksi = str_replace(["Rp", "."], ["", ""], $total); // Menghapus format mata uang
$transaksi->tgl_transaksi = date('Y-m-d');
$transaksi->pembeli = $pembeli;
$transaksi->status_pesanan = 'Pending';

if ($transaksi->insert()) {
    // Kurangi stok barang
    $barang->ki = $kode_item;
    $barang->readOne();
    $barang->si -= $jumlah;
    $barang->update();

    echo "<div class='container'><div class='text-center'>Pesanan berhasil dikirim!</div></div>";
} else {
    echo "<div class='container'><div class='text-center'>Gagal mengirim pesanan. Silakan coba lagi!</div></div>";
}

?>

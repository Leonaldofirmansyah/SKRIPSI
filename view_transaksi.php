<?php
include_once 'header.php';
include_once 'includes/transaksi.inc.php';

if (!isset($_GET['id'])) {
    echo "<div class='container'><div class='text-center'>ID Transaksi tidak ditemukan.</div></div>";
    exit;
}

$id_transaksi = $_GET['id'];
$transaksi = new transaksi($db);
$dataTransaksi = $transaksi->readById($id_transaksi);

if (!$dataTransaksi) {
    echo "<div class='container'><div class='text-center'>Data Transaksi tidak ditemukan.</div></div>";
    exit;
}
?>

<div class="container">
    <h3>Detail Transaksi</h3>
    <table class="table table-bordered">
        <tr>
            <th>Kode Transaksi</th>
            <td><?php echo htmlspecialchars($dataTransaksi['id_transaksi']); ?></td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td><?php echo htmlspecialchars($dataTransaksi['kode_item']); ?></td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td><?php echo htmlspecialchars($dataTransaksi['jumlah_transaksi']); ?></td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td><?php echo htmlspecialchars($dataTransaksi['tgl_transaksi']); ?></td>
        </tr>
        <tr>
            <th>Pembeli</th>
            <td><?php echo htmlspecialchars($dataTransaksi['id_pengguna']); ?></td>
        </tr>
        <tr>
            <th>Status Pemesanan</th>
            <td><?php echo htmlspecialchars($dataTransaksi['status_pesanan']); ?></td>
        </tr>
        <tr>
            <th>Gambar</th>
            <td>
                <?php if ($dataTransaksi['gambar']) { ?>
                    <img src="<?= $dataTransaksi['gambar'] ?>" alt="Gambar Pesanan" style="width: 300px; height: auto;">
                <?php } ?>
            </td>
        </tr>
    </table>
    <a href="transaksi.php" class="btn btn-default">Kembali</a>
</div>

<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/transaksi.inc.php';

$config = new Config();
$db = $config->getConnection();
$transaksi = new Transaksi($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_items'])) {
    foreach ($_POST['selected_items'] as $id_transaksi) {
        // Proses pesanan (misalnya, ubah status pesanan atau pindahkan ke tabel lain)
        // Hapus pesanan dari keranjang
        $transaksi->removeFromCart($id_transaksi);
    }
}

// Dapatkan pesanan yang sudah dibayar
$transaksi->id_pengguna = $_SESSION['id_pengguna'];
$paidOrders = $transaksi->getPaidOrdersByUserId();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - Surya Teknik Utama</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f4f4;
        }
        .card {
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: #343a40;
            color: #fff;
        }
        .btn-custom {
            background-color: #343a40;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #495057;
            color: #fff;
        }
        .navbar-custom {
            background-color: black;
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff;
        }
        .navbar-custom .nav-link:hover {
            color: #d1d1d1;
        }
        .navbar-custom .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            max-height: 50px;
            width: auto;
            margin-right: 10px;
        }
        .navbar-custom .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255, 255, 255, 0.5)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="dashboard.php">
        <img src="../images/logo.png" alt="Company Logo">
        CV.Surya Teknik Utama
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="produk.php">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="keranjang.php">Keranjang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_pesanan.php">Pengajuan Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Pesanan Anda</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Item</th>
                                <th>Nama Item</th>
                                <th>Jumlah</th>
                                <th>Tanggal Pesan</th>
                                <th>Status</th>
                                <th>Harga</th>
                                <th>Gambar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($paidOrders): ?>
                                <?php foreach ($paidOrders as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['kode_item']); ?></td>
                                        <td><?php echo htmlspecialchars($item['nama_item']); ?></td>
                                        <td><?php echo htmlspecialchars($item['jumlah_transaksi']); ?></td>
                                        <td><?php echo htmlspecialchars($item['tgl_transaksi']); ?></td>
                                        <td><?php echo htmlspecialchars($item['status_pesanan']); ?></td>
                                        <td><?php echo htmlspecialchars($item['harga_item']); ?></td>
                                        <td>
                                            <?php if ($item['gambar']): ?>
                                                <img src="<?php echo htmlspecialchars($item['gambar']); ?>" alt="Gambar Pesanan" width="100">
                                            <?php else: ?>
                                                No Image
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Tidak ada pesanan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
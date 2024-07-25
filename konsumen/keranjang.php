<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/transaksi.inc.php';

$config = new Config();
$db = $config->getConnection();
$transaksi = new Transaksi($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_items'])) {
    foreach ($_POST['selected_items'] as $id_transaksi) {
        // Pindahkan pesanan ke tabel pesanan
        $transaksi->moveToOrders($id_transaksi);
        // Hapus pesanan dari keranjang
        $transaksi->removeFromCart($id_transaksi);
    }
    // Redirect ke halaman view_pesanan.php
    header("Location: view_pesanan.php");
    exit;
}

// Dapatkan pesanan dari keranjang
$transaksi->id_pengguna = $_SESSION['id_pengguna'];
$pesanan = $transaksi->getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Surya Teknik Utama</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .table th, .table td {
            text-align: center;
        }
        .action-btns {
            display: flex;
            justify-content: center;
        }
        .action-btns button {
            margin: 0 5px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">
        <img src="../images/logo.png" alt="Company Logo">
        CV.Surya Teknik Utama
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard_konsumen.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="produk.php">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_pesanan.php">Pengajuan Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Keranjang Pesanan</div>
                <div class="card-body">
                    <form action="keranjang.php" method="POST">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Pilih</th>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Pesan</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($pesanan): ?>
                                    <?php foreach ($pesanan as $item): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_items[]" value="<?php echo htmlspecialchars($item['id_transaksi']); ?>">
                                            </td>
                                            <td><?php echo htmlspecialchars($item['kode_item']); ?></td>
                                            <td><?php echo htmlspecialchars($item['nama_item']); ?></td>
                                            <td><?php echo htmlspecialchars($item['jumlah_transaksi']); ?></td>
                                            <td><?php echo htmlspecialchars($item['tgl_transaksi']); ?></td>
                                            <td>
                                                <?php if ($item['gambar']): ?>
                                                    <img src="<?php echo htmlspecialchars($item['gambar']); ?>" alt="Gambar Pesanan" width="100">
                                                <?php else: ?>
                                                    No Image
                                                <?php endif; ?>
                                            </td>
                                            <td class="action-btns">
                                                <button type="button" class="btn btn-danger btn-sm remove-btn" data-id="<?php echo htmlspecialchars($item['id_transaksi']); ?>">Hapus dari Keranjang</button>
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
                        <button type="submit" class="btn btn-custom">Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.remove-btn').on('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                var idTransaksi = $(this).data('id');
                $('<form>', {
                    method: 'POST',
                    action: 'remove_from_cart.php'
                }).append($('<input>', {
                    type: 'hidden',
                    name: 'id_transaksi',
                    value: idTransaksi
                })).appendTo('body').submit();
            }
        });
    });
</script>

</body>
</html>
<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/transaksi.inc.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo "<div class='container'><div class='text-center'>Anda harus login terlebih dahulu untuk melakukan pembayaran.</div></div>";
    exit;
}

$config = new Config();
$db = $config->getConnection();
$transaksi = new Transaksi($db);

$id_pengguna = $_SESSION['id_pengguna'];
$selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];

if (is_array($selected_items)) {
    $selected_items = implode(',', $selected_items); // Jika array, ubah menjadi string
}

if (empty($selected_items)) {
    echo "<div class='container'><div class='text-center'>Tidak ada pesanan yang dipilih untuk pembayaran.</div></div>";
    exit;
}

$selected_items_array = explode(',', $selected_items);

$items = [];
$total_harga = 0;

foreach ($selected_items_array as $id_transaksi) {
    $item = $transaksi->getItemByIdTransaksi($id_transaksi, $id_pengguna);
    if ($item) {
        $harga_item = isset($item['harga_item']) ? (float)$item['harga_item'] : 0;
        $jumlah_transaksi = isset($item['jumlah_transaksi']) ? (int)$item['jumlah_transaksi'] : 0;
        $subtotal = $harga_item * $jumlah_transaksi;

        $total_harga += $subtotal;
        
        $item['subtotal'] = $subtotal; // Tambahkan subtotal ke array item
        $items[] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
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
        .container {
            margin-top: 10px; /* Sesuaikan dengan tinggi navbar */
        }
        .alert {
            margin: 0;
            position: sticky;
            top: 10px; /* Sesuaikan dengan tinggi navbar */
            width: 100%;
            z-index: 1000;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .form-container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control-file {
            margin-bottom: 15px;
        }
        .radio-group {
            display: flex;
            flex-direction: column;
        }
        .radio-group label {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">
        <img src="images/logo.png" alt="Company Logo">
        CV.Surya Teknik Utama
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="produk.php">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="keranjang.php">Keranjang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pembayaran.php">Pembayaran</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 class="text-center">Pembayaran</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Item</th>
                <th>Jumlah</th>
                <th>Tanggal Pesan</th>
                <th>Gambar</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['id_transaksi']); ?></td>
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
                        <td><?php echo number_format($item['harga_item'], 2); ?></td>
                        <td><?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="6" class="text-right"><strong>Total Harga Yang Harus Di Bayar:</strong></td>
                    <td><?php echo number_format($total_harga, 2); ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada pesanan yang dipilih.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Form untuk upload bukti pembayaran dan opsi pembayaran -->
    <div class="form-container">
        <form action="proses_pembayaran.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="bukti_pembayaran">Upload Bukti Pembayaran:</label>
                <input type="file" class="form-control-file" id="bukti_pembayaran" name="bukti_pembayaran" required>
            </div>
            <div class="form-group radio-group">
                <label>Opsi Pembayaran:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="dp" name="opsi_pembayaran" value="DP" required>
                    <label class="form-check-label" for="dp">
                        DP(minimal 50%)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="lunas" name="opsi_pembayaran" value="Lunas" required>
                    <label class="form-check-label" for="lunas">
                        Lunas
                    </label>
                </div>
            </div>
            <input type="hidden" name="selected_items" value="<?php echo htmlspecialchars(implode(',', $selected_items_array)); ?>">
            <button type="submit" class="btn btn-custom">Kirim Pembayaran</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

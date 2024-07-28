<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/transaksi.inc.php';

// Pastikan pengguna sudah login dan memiliki role produksi
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'Produksi') {
    $_SESSION['message'] = 'Anda harus login sebagai produksi untuk mengakses halaman ini.';
    $_SESSION['message_type'] = 'danger';
    header("Location: login.php");
    exit;
}

$config = new Config();
$db = $config->getConnection();
$transaksi = new Transaksi($db);

// Ambil data pesanan yang perlu diproses oleh bagian produksi
$stmt = $transaksi->readAllForProduction();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produksi - Pesanan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">CV.Surya Teknik Utama</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> text-center">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <h2>Pesanan untuk Diproduksi</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Item</th>
                <th>Jumlah</th>
                <th>Tanggal Pesan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($stmt->rowCount() > 0): ?>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_transaksi']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_item']); ?></td>
                        <td><?php echo htmlspecialchars($row['jumlah_transaksi']); ?></td>
                        <td><?php echo htmlspecialchars($row['tgl_transaksi']); ?></td>
                        <td>
                            <form action="upload_bukti_produksi.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($row['id_transaksi']); ?>">
                                <input type="file" name="bukti" required>
                                <button type="submit" class="btn btn-success btn-sm">Upload Bukti</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada pesanan untuk produksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

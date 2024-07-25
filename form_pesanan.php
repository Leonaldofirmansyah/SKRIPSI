<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/barang.inc.php';
include_once 'includes/transaksi.inc.php';

$config = new Config();
$db = $config->getConnection();
$barang = new Barang($db);
$transaksi = new Transaksi($db);

$alert_display = false;
if (!isset($_GET['kode']) || empty($_GET['kode'])) {
    $alert_message = "Kode item tidak valid.";
    $alert_type = "danger";
    $alert_display = true;
    $kode_item = null;
} else {
    $kode_item = base64_decode($_GET['kode']);
    $barang->kode_item = $kode_item;
    if (!$barang->readOne()) {
        $alert_message = "Barang tidak ditemukan.";
        $alert_type = "danger";
        $alert_display = true;
    }
}

// Menangani pengiriman formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jumlah = $_POST['jumlah'];
    $gambar = null;

    // Menangani upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $validExtensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $validExtensions)) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
                $gambar = $targetFile;
            } else {
                $alert_message = "Gagal mengunggah gambar.";
                $alert_type = "danger";
                $alert_display = true;
            }
        } else {
            $alert_message = "Format file tidak valid. Hanya jpg, jpeg, png, gif yang diperbolehkan.";
            $alert_type = "danger";
            $alert_display = true;
        }
    }

    // Jika tidak ada error, simpan pesanan ke database
    if (!$alert_display) {
        // Mengasumsikan bahwa fungsi 'insert' dalam kelas 'Transaksi' sudah diperbarui
        $transaksi->kode_item = $kode_item;
        $transaksi->nama_item = $barang->nama_item;
        $transaksi->jumlah_transaksi = $jumlah;
        $transaksi->tgl_transaksi = date('Y-m-d H:i:s');
        $transaksi->id_pengguna = $_SESSION['id_pengguna'];
        $transaksi->gambar = $gambar;

        $db->beginTransaction();
        try {
            if ($transaksi->insertToCart()) {  // Menggunakan metode yang disesuaikan untuk keranjang
                $db->commit();
                $alert_message = "Pesanan berhasil ditambahkan ke keranjang.";
                $alert_type = "success";
                $alert_display = true;
            } else {
                throw new Exception("Gagal menambahkan pesanan ke keranjang.");
            }
        } catch (Exception $e) {
            $db->rollback();
            $alert_message = "Gagal membuat pesanan: " . $e->getMessage();
            $alert_type = "danger";
            $alert_display = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pesanan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #343a40;
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
        .navbar-custom .navbar-brand img {
            max-height: 40px;
        }
        .main-content {
            padding: 20px;
            padding-top: 60px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e3e3e3;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .alert-custom {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
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
                <a class="nav-link" href="index.php">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="keranjang.php">Keranjang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_pesanan.php">Pesanan</a>
            </li>
            <li class="nav-item active">
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
<div class="container main-content">
    <?php if ($alert_display): ?>
        <div class="alert alert-<?php echo $alert_type; ?> alert-custom" role="alert">
            <?php echo $alert_message; ?>
        </div>
    <?php endif; ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h2>Form Pesanan</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="Masukkan Jumlah Pesanan" required />
                    </div>
                    <div class="form-group">
                        <label>Upload File / Gambar Pesanan</label>
                        <input type="file" name="gambar" class="form-control" />
                        <small class="form-text text-muted">
                            * Gambar harus berukuran maksimum 5MB, berformat JPEG, PNG, atau GIF. 
                            * Resolusi gambar tidak boleh lebih dari 1920x1080 piksel.
                            * Gambar memiliki detail ukuran
                        </small>
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Pesan</button>
                        <a href="produk.php" class="btn btn-secondary">Kembali ke Halaman Produk</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
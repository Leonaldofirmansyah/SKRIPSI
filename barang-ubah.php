<?php
include_once 'includes/db_connect.php';
include_once 'includes/config.php';
include_once 'includes/barang.inc.php';

$config = new Config();
$db = $config->getConnection();
$barang = new Barang($db);

// Ambil id dari URL dan dekode base64
$id = isset($_GET['id']) ? base64_decode($_GET['id']) : null;

if (!$id) {
    die('ERROR: Kode barang tidak ditemukan.');
}

$barang->kode_item = $id; // Sesuaikan dengan nama properti di kelas Barang
$barang->readOne();

// Proses pembaruan barang
$updateSuccess = false;
if ($_POST) {
    $barang->nama_item = htmlspecialchars($_POST['nama_barang']); // Sesuaikan dengan nama properti di kelas Barang

    // Upload file gambar jika ada
    if (!empty($_FILES["gambar_barang"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["gambar_barang"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["gambar_barang"]["tmp_name"]);

        if ($check !== false) {
            // Hapus gambar lama jika ada
            if ($barang->gambar && file_exists("uploads/" . $barang->gambar)) {
                unlink("uploads/" . $barang->gambar);
            }

            // Upload gambar baru
            move_uploaded_file($_FILES["gambar_barang"]["tmp_name"], $target_file);
            $barang->gambar = basename($_FILES["gambar_barang"]["name"]);
        } else {
            echo "<div class='alert alert-danger'>File yang diunggah bukan gambar.</div>";
        }
    } else {
        // Jika tidak ada gambar baru, tetap gunakan gambar lama
        $barang->gambar = $barang->gambar;
    }

    if ($barang->update()) {
        $updateSuccess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
        }

        /* CSS tambahan untuk navbar */
        .navbar-custom {
            background-color: #343a40; /* Warna navbar di transaksi.php */
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #f8f9fa; /* Warna teks navbar */
        }

        .navbar-custom .navbar-toggler-icon {
            background-color: #f8f9fa; /* Warna ikon navbar toggler */
        }

        .navbar-custom .navbar-nav .nav-link:hover {
            color: #ffc107; /* Warna teks saat hover */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <a class="navbar-brand" href="dashboard.php">
            <img src="images/logo.png" alt="Company Logo" style="height: 40px;">
            Surya Teknik Utama
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="barang.php">Data Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transaksi.php">Data Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_pengiriman.php">Pengiriman</a>
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
    <div class="container">
        <div class="page-header">
            <h1>Edit Barang</h1>
        </div>

        <?php if ($updateSuccess): ?>
            <div class="alert alert-success">Barang berhasil diperbarui.</div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . base64_encode($barang->kode_item)); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?php echo htmlspecialchars($barang->nama_item, ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="gambar_barang">Gambar Barang</label>
                <input type="file" name="gambar_barang" id="gambar_barang" class="form-control">
                <?php if ($barang->gambar): ?>
                    <img src="uploads/<?php echo htmlspecialchars($barang->gambar, ENT_QUOTES); ?>" alt="Gambar Barang" class="img-thumbnail mt-2" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
        <a href="barang.php" class="btn btn-secondary mt-3">Kembali ke Daftar Barang</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

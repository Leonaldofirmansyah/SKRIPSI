<?php
include "includes/db_connect.php";
include "includes/config.php";
session_start();
if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='login.php'</script>";
}

$config = new Config();
$db = $config->getConnection();

if (isset($_SESSION['role']) && $_SESSION['role'] == base64_encode('Admin')) {
    include_once 'includes/barang.inc.php';
    $barang = new Barang($db);

    if ($_POST) {
        // Tentukan path untuk menyimpan gambar
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Debug untuk cek path dan file
        echo "Target file: " . $target_file . "<br>";

        // Cek apakah file gambar adalah gambar asli atau tidak
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".<br>";

            // Cek apakah file sudah ada
            if (file_exists($target_file)) {
                echo "<script>alert('Sorry, file already exists.');</script>";
            } else {
                // Cek ukuran file
                if ($_FILES["gambar"]["size"] > 10 * 1024 * 1024) {
                    echo "<script>alert('Sorry, your file is too large.');</script>";
                } else {
                    // Hanya izinkan format gambar tertentu
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
                    } else {
                        // Upload file
                        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                            $barang->kode_item = $_POST['kode_item'];
                            $barang->nama_item = $_POST['nama_item'];
                            $barang->gambar = $target_file;

                            if ($barang->insert()) {
                                echo "<script>alert('Data barang berhasil ditambahkan'); window.location.href='barang.php';</script>";
                            } else {
                                echo "<script>alert('Gagal menambahkan data barang');</script>";
                            }
                        } else {
                            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                        }
                    }
                }
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tambah Barang Baru</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome for Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            body {
                background: #f4f4f4;
                height: 100vh;
                display: flex;
                flex-direction: column;
            }
            .navbar-custom {
                background-color: #343a40;
            }
            .navbar-custom .navbar-brand,
            .navbar-custom .nav-link {
                color: #fff;
            }
            .navbar-custom .nav-link:hover {
                color: #d1d1d1;
            }
            .container {
                margin-top: 50px; /* Adjusted margin-top to create space between navbar and content */
            }
            .btn-primary {
                background-color: #343a40;
                border: none;
            }
            .btn-primary:hover {
                background-color: #495057;
            }
        </style>
    </head>
    <body>
        <!-- Navigation Bar -->
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
            <div class="row">
                <div class="col-md-12">
                    <h3>Tambah Barang Baru</h3>
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="kode_item">Kode Item</label>
                            <input type="text" class="form-control" id="kode_item" name="kode_item" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_item">Nama Item</label>
                            <input type="text" class="form-control" id="nama_item" name="nama_item" required>
                        </div>
                        <div class="form-group">
                            <label for="gambar">Upload Gambar</label>
                            <input type="file" class="form-control-file" id="gambar" name="gambar" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
} else {
    ?>
    <div class="container">
        <div class="text-center">Halaman ini hanya untuk hak akses Admin saja!</div>
    </div>
    <?php
}
?>

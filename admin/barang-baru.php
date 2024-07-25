<?php
include "../includes/db_connect.php";
include "../includes/config.php";
session_start();

if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='login.php'</script>";
}

$config = new Config();
$db = $config->getConnection();

if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    include_once '../includes/barang.inc.php';
    $barang = new Barang($db);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Tentukan path untuk menyimpan gambar
        $target_dir = "../uploads/products/"; // Perbaiki path folder uploads
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Debug untuk cek path dan file
        echo "Target file: " . htmlspecialchars($target_file) . "<br>";

        // Cek apakah file gambar adalah gambar asli atau tidak
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . htmlspecialchars($check["mime"]) . ".<br>";

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
                            $barang->gambar = basename($_FILES["gambar"]["name"]); // Simpan nama file saja

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
    <title>Dashboard Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
    background: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.navbar-custom {
    background-color: #343a40;
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1050; /* Higher than sidebar */
}

.navbar-custom .navbar-brand,
.navbar-custom .nav-link {
    color: #fff;
}

.navbar-custom .nav-link:hover {
    color: #d1d1d1;
}

.sidebar {
    width: 250px;
    background: #343a40;
    color: #fff;
    position: fixed;
    top: 56px; /* Adjusted to account for navbar height */
    bottom: 0;
    left: 0;
    overflow-y: auto;
    padding-top: 1rem;
    z-index: 1000; /* Lower than navbar */
}

.sidebar .nav-link {
    color: #fff;
}

.sidebar .nav-link.active {
    background: #495057;
}

.sidebar .nav-link:hover {
    background: #495057;
}

.sidebar .dropdown-menu {
    background: #343a40;
    border: none;
    position: absolute; /* Ensure it doesn't affect layout */
    z-index: 1060; /* Higher than sidebar */
}

.sidebar .dropdown-item {
    color: #fff;
}

.sidebar .dropdown-item:hover {
    background: #495057;
}

.content {
    margin-left: 250px;
    margin-top: 56px; /* Adjusted to account for navbar height */
    padding: 20px;
    flex: 1;
    overflow-y: auto;
    height: calc(100vh - 56px);
}

.btn-primary {
    background-color: #343a40;
    border: none;
}

.btn-primary:hover {
    background-color: #495057;
}

.card {
    margin-bottom: 20px;
}

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <a class="navbar-brand" href="dashboard.php">
            <img src="../images/logo.png" alt="Company Logo" style="height: 40px;">
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
                    <a class="nav-link" href="pengiriman.php">Pengiriman</a>
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
    
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3">
            <h4>Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transaksi.php">Pesanan</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($activeMenu == 'produk' ? 'active' : ''); ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Produk
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item <?php echo ($currentPage == 'barang.php' ? 'active' : ''); ?>" href="barang.php">List Produk</a></li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'barang-baru.php' ? 'active' : ''); ?>" href="barang-baru.php">Tambah Produk</a></li>
                    </ul>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownStock" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Manajemen Stok
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownStock">
                        <li><a class="dropdown-item" href="manage_inventory.php">Stok Bahan</a></li>
                        <li><a class="dropdown-item" href="add_inventory.php">Tambah Bahan Baku</a></li>
                        <li><a class="dropdown-item" href="request_stock.php">Permintaan Bahan Baku</a></li>
                        <li><a class="dropdown-item" href="manage_requests.php">Cetak</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownExpenses" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pengeluaran
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownExpenses">
                        <li><a class="dropdown-item" href="input_expense.php">Tambah Pengeluaran</a></li>
                        <li><a class="dropdown-item" href="manage_expenses.php">Laporan Pengeluaran</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownOrders" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pesanan Konsumen
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownOrders">
                        <li><a class="dropdown-item" href="manage_orders.php">Konsumen Perorangan</a></li>
                        <li><a class="dropdown-item" href="manage_company_orders.php">Konsumen Perusahaan</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_returns.php">Pengembalian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_salaries.php">Penggajian</a>
                </li>
                </li>
                <!-- Menu lainnya -->
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
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
      <!-- Bootstrap Bundle with Popper -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
?>

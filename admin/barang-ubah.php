<?php
include_once '../includes/db_connect.php';
include_once '../includes/config.php';
include_once '../includes/barang.inc.php';

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
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["gambar_barang"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["gambar_barang"]["tmp_name"]);

        if ($check !== false) {
            // Hapus gambar lama jika ada
            if ($barang->gambar && file_exists("../uploads/" . $barang->gambar)) {
                unlink("../uploads/" . $barang->gambar);
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
    <title>Produk</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            z-index: 1000; /* Ensure navbar is on top */
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
            top: 56px; /* Navbar height */
            bottom: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 1rem;
            z-index: 1000; /* Ensure sidebar is on top */
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
        }
        .sidebar .dropdown-item {
            color: #fff;
        }
        .sidebar .dropdown-item:hover {
            background: #495057;
        }
        .content {
            margin-left: 250px; /* Adjust according to sidebar width */
            margin-top: 40px; /* Navbar height */
            padding: 20px;
            flex: 1;
            overflow-y: auto;
            height: calc(100vh - 56px); /* Adjust the height to account for the navbar */
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
        }
        .btn-primary:hover {
            background-color: #495057;
        }
        .table-wrapper {
            overflow-x: auto; /* Memungkinkan scroll horizontal jika diperlukan */
        }
        .table {
            margin: 0; /* Memastikan tabel mengambil lebar penuh */
        }
        .bukti-img {
            max-width: 150px; /* Menyesuaikan ukuran gambar */
            max-height: 150px; /* Menyesuaikan ukuran gambar */
            object-fit: cover;
        }
        .text-left {
            text-align: left;
        }
        .form-control-sm {
            font-size: 0.875rem;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .alert-container {
            margin-bottom: 1rem;
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
        <div class="page-header">
            <h1>Edit Produk</h1>
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
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

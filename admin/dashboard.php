<?php
include_once '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='login.php'</script>";
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    // Memastikan jalur relatif ini benar sesuai struktur direktori Anda
    include_once '../includes/pesanan_admin.inc.php';
    include_once '../includes/produk.inc.php';

    $database = new Database();
    $db = $database->getConnection();

    $pesanan = new Pesanan($db);
    $totalPesanan = $pesanan->countAll();

    $produk = new Produk($db);
    $totalProduk = $produk->countAll();
} else {
    echo "<script>location.href='login.php'</script>";
    exit;
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
                    <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transaksi.php">Pesanan</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Produk
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="barang.php">List Produk</a></li>
                        <li><a class="dropdown-item" href="barang-baru.php">Tambah Produk</a></li>
                    </ul>
                </li>
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
            </ul>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Pesanan</h5>
                            <p class="card-text"><?php echo $totalPesanan; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Produk</h5>
                            <p class="card-text"><?php echo $totalProduk; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include "../includes/config.php";
session_start();
if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='login.php'</script>";
}

$config = new Config();
$db = $config->getConnection();

// Tentukan halaman aktif
$currentPage = basename($_SERVER['PHP_SELF']);
$activeMenu = 'produk'; // Menandai menu Produk sebagai aktif

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != 'Admin') { ?>
        <div class="container">
            <div class="text-center">Halaman ini hanya untuk hak akses Admin saja!</div>
        </div>
    <?php 
    } else {
        include_once '../includes/barang.inc.php';
        $pro = new Barang($db);
        $stmt = $pro->readAll(); // Ambil data barang
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
            <div class="row mb-3">
                <div class="col-md-6 text-left">
                </div>
                <div class="col-md-6 text-right">
                </div>
            </div>
            <table width="100%" class="table table-striped table-bordered" id="tabeldata">
                <thead>
                    <tr>
                        <th width="30px">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Gambar</th>
                        <th width="100px" id="bg-hasil">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo htmlspecialchars($row['kode_item']) ?></td>
                            <td><?php echo htmlspecialchars($row['nama_item']) ?></td>
                            <td><img src="../uploads/products/<?php echo htmlspecialchars($row['gambar']) ?>" alt="Gambar Barang" style="width: 100px; height: 100px;"></td>
                            <td class="text-center">
                                <a href="barang-ubah.php?id=<?php echo base64_encode($row['kode_item']) ?>" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                                <a href="barang-hapus.php?id=<?php echo base64_encode($row['kode_item']) ?>" onclick="return confirm('Yakin ingin menghapus data')" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
      <!-- Bootstrap Bundle with Popper -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
    }
}
?>

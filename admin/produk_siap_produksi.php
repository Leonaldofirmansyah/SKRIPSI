<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Siap Produksi</title>
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
            margin-top: 0; /* Navbar height */
            padding: 5px;
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
            margin-top: 0px;
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
                    <a class="nav-link active" aria-current="page" href="transaksi.php">Pesanan</a>
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Manajemen Stok
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="manage_inventory.php">Stok Bahan</a></li>
                        <li><a class="dropdown-item" href="add_inventory.php">Tambah Bahan Baku</a></li>
                        <li><a class="dropdown-item" href="request_stock.php">Permintaan Bahan Baku</a></li>
                        <li><a class="dropdown-item" href="manage_requests.php">Cetak</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pengeluaran
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="input_expense.php">Tambah Pengeluaran</a></li>
                        <li><a class="dropdown-item" href="manage_expenses.php">Laporan Pengeluaran</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pesanan Konsumen
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
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
    <div class="container mt-4">
        <h2>Produk Siap Produksi</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sample data, replace with your data fetching logic
                $items = [
                    ['kode_item' => 'ITEM001', 'nama_item' => 'Item 1', 'jumlah' => 10, 'gambar' => 'path/to/image1.jpg'],
                    ['kode_item' => 'ITEM002', 'nama_item' => 'Item 2', 'jumlah' => 5, 'gambar' => 'path/to/image2.jpg'],
                ];

                foreach ($items as $item) {
                    echo "<tr>";
                    echo "<td>{$item['kode_item']}</td>";
                    echo "<td>{$item['nama_item']}</td>";
                    echo "<td>{$item['jumlah']}</td>";
                    echo "<td><img src='{$item['gambar']}' class='bukti-img' alt='Gambar'></td>";
                    echo "<td><a href='kirim_produksi.php?kode_item={$item['kode_item']}' class='btn btn-primary btn-sm'>Kirim</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
session_start();

include "../includes/config.php";
include_once '../includes/barang.inc.php';
include_once '../includes/transaksi.inc.php';

// Buat objek untuk barang dan transaksi
$config = new Config();
$db = $config->getConnection();
$barang = new Barang($db);
$transaksi = new Transaksi($db);

// Ambil data barang
$stmtBarang = $barang->readAll();

// Ambil data transaksi
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stmtDataTrans = $transaksi->readAll($search);

if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='login.php'</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUK</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style>
        /* Navbar */
        .navbar-custom {
            background-color: black; /* Dark background color for navbar */
            position: sticky;
            top: 0; /* Stick the navbar to the top */
            z-index: 1000; /* Ensure it stays on top of other content */
            width: 100%; /* Full width */
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
            max-height: 50px; /* Maximum height of the logo in navbar */
            width: auto; /* Maintain aspect ratio */
            margin-right: 10px;
        }
        /* Navbar Toggler */
        .navbar-custom .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1); /* Toggler border color */
        }
        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255, 255, 255, 0.5)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        /* Custom styles for index page */
        .thumbnail {
            background: #ffffff url(images/back1.jpg) left bottom fixed;
            border-color: #2c3e50;
            border-radius: 1.5px;
        }
        .thumbnail img {
            width: 100%; /* Full width for images */
            height: auto; /* Maintain aspect ratio */
        }
        .caption h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .caption p {
            margin-bottom: 10px;
        }
        .caption .btn-primary {
            margin-top: 10px;
        }
        /* Custom styles for product cards */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease-in-out;
        }
        .card-header {
            background-color: #343a40;
            color: #fff;
        }
        .card-header-custom {
        margin-bottom: 20px; /* Mengatur jarak bawah dari card-header */
    }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            height: 200px;
            object-fit: cover;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-text {
            color: #555;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 0.3rem;
            padding: 10px 15px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body style="background: #ffffff url(images/back1.jpg) left bottom fixed;">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">
        <img src="../images/logo.png" alt="Company Logo">
        CV.Surya Teknik Utama
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard_konsumen.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="keranjang.php">keranjang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_pesanan.php">Pengajuan Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pengiriman_konsumen.php">Pengiriman</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <?php if (isset($_SESSION['role'])) { ?>
                    <a class="nav-link" href="logout.php">LOGOUT</a>
                <?php } ?>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <?php if (isset($_GET['dashboard'])) {
        if ($_SESSION['role'] != ('Admin')) { ?>
            <div class="text-center">Halaman ini hanya untuk hak akses Admin saja!</div>
        <?php } else { ?>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="page-header">
                    <h5>Daftar Transaksi</h5>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!-- Tampilkan tabel data transaksi -->
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                    <th>Pembeli</th>
                                    <th>Status Pemesanan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $no = 1;
                            while ($rowTransaksi = $stmtDataTrans->fetch(PDO::FETCH_ASSOC)) { ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $rowTransaksi['id_transaksi'] ?></td>
                                    <td><?php echo $rowTransaksi['nama_item'] ?></td>
                                    <td><?php echo $rowTransaksi['jumlah_transaksi'] ?></td>
                                    <td><?php echo $rowTransaksi['total_transaksi'] ?></td>
                                    <td><?php echo $rowTransaksi['tgl_transaksi'] ?></td>
                                    <td><?php echo $rowTransaksi['pembeli'] ?></td>
                                    <td><?php echo $rowTransaksi['status_pesanan'] ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php }
    } else { ?>
<!-- Product Section -->
<section id="products" class="my-5">
    <div class="card-header card-header-custom">Produk</div>
    <div class="row">
        <?php while ($rowBarang = $stmtBarang->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card">
                    <?php 
                    $imagePath = '../uploads/products/' . $rowBarang['gambar']; // Tambahkan jalur direktori di sini
                    ?>
                    <img class="card-img-top" src="<?= $imagePath ?>" alt="<?= $rowBarang['nama_item'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $rowBarang['nama_item'] ?></h5>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean laoreet lacus nec tempus sodales.</p>
                        <a href="form_pesanan.php?kode=<?= base64_encode($rowBarang['kode_item']) ?>" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

    <?php } ?>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('table').DataTable();
    });
</script>
</body>
</html>
<?php
session_start();
include_once '../includes/config.php';

$config = new Config();
$db = $config->getConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Konsumen</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #f4f4f4;
        }
        .carousel-item img {
            width: 100%;
            height: 200px;
        }
        .card {
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: #343a40;
            color: #fff;
        }
        .btn-custom {
            background-color: #343a40;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #495057;
            color: #fff;
        }
        .navbar-custom {
            background-color: black;
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
        .navbar-custom .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            max-height: 50px;
            width: auto;
            margin-right: 10px;
        }
        .navbar-custom .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255, 255, 255, 0.5)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="dashboard.php">
        <img src="../images/logo.png" alt="Company Logo">
        CV.Surya Teknik Utama
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="produk.php">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="keranjang.php">Keranjang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_pesanan.php">Pengajuan Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">Pesanan</a>
            </li>
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <!-- Carousel -->
    <div id="carouselExampleControls" class="carousel slide mb-4" data-ride="carousel" data-interval="2000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../images/Picture11.png" class="d-block w-100" alt="Slide 1">
            </div>
            <div class="carousel-item">
                <img src="../images/Picture22.png" class="d-block w-100" alt="Slide 2">
            </div>
            <div class="carousel-item">
                <img src="../images/Picture33.png" class="d-block w-100" alt="Slide 3">
            </div>
            <div class="carousel-item">
                <img src="../images/Picture4.png" class="d-block w-100" alt="Slide 2">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Featured Products -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Produk Terbaru</div>
                <div class="card-body">
                    <p class="card-text">Temukan produk terbaru kami yang mungkin Anda suka.</p>
                    <a href="produk.php" class="btn btn-custom">Lihat Produk</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Penawaran Spesial</div>
                <div class="card-body">
                    <p class="card-text">Jangan lewatkan penawaran spesial dan diskon yang sedang berlangsung.</p>
                    <a href="pembayaran.php" class="btn btn-custom">Lihat Penawaran</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Kontak Kami</div>
                <div class="card-body">
                    <p class="card-text">Ada pertanyaan atau butuh bantuan? Hubungi kami melalui halaman kontak.</p>
                    <a href="contact.php" class="btn btn-custom">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

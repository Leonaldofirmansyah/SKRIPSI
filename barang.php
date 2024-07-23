<?php
include "includes/config.php";
session_start();
if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='login.php'</script>";
}

$config = new Config();
$db = $config->getConnection();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != ('Admin')) { ?>
        <div class="container">
            <div class="text-center">Halaman ini hanya untuk hak akses Admin saja!</div>
        </div>
    <?php 
    } else {
        include_once 'includes/barang.inc.php';
        $pro = new Barang($db);
        $stmt = $pro->readAll(); // Ambil data barang
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
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
        .navbar-custom .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            max-height: 50px;
            width: auto;
            margin-right: 10px;
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

    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6 text-left">
                <h3>Data Barang</h3>
            </div>
            <div class="col-md-6 text-right">
                <button onclick="location.href='barang-baru.php'" class="btn btn-primary" id="btn-tambah">Tambah Data</button>
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
                        <td><img src="<?php echo htmlspecialchars($row['gambar']) ?>" alt="Gambar Barang" style="width: 100px; height: 100px;"></td>
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
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
    }
}
?>

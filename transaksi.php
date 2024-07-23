<?php
include_once 'includes/db_connect.php';
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
        include_once 'includes/transaksi.inc.php';
        $pro = new Transaksi($db);

        // Ambil nilai pencarian jika ada
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $stmt = $pro->readAll($search);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            margin-top: 10px; /* Margin untuk membuat ruang antara navbar dan konten */
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
        }
        .btn-primary:hover {
            background-color: #495057;
        }
        /* Custom CSS untuk table dan gambar */
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
    <!-- Tempat untuk Alert -->
    <div class="alert-container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-between mb-3">
            <div class="col-4">
                <h3>Data Transaksi</h3>
            </div>
            <div class="col-4">
                <!-- Form Pencarian -->
                <form method="GET" action="transaksi.php" class="form-inline">
                    <input type="text" name="search" placeholder="Cari Kode Transaksi atau Nama Barang" class="form-control mr-2" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </div>

        <!-- Form Ekspor ke Excel -->
        <form method="POST" action="export_excel.php" class="mb-3">
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" name="export" class="btn btn-success">Ekspor ke Excel</button>
        </form>

        <div class="container-fluid">
            <div class="table-wrapper">
                <table width="150%" class="table table-striped table-bordered text-left" id="tabeldata">
                    <thead>
                        <tr>
                            <th width="30px">No</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th> <!-- Kolom Harga -->
                            <th>Tanggal</th>
                            <th>Pembeli</th>
                            <th>Status Pemesanan</th>
                            <th>Gambar Pesanan</th>
                            <th>Status Pembayaran</th> <!-- Kolom Status Pembayaran -->
                            <th>Bukti Pembayaran</th> <!-- Kolom Unduh Bukti Pembayaran -->
                        </tr>
                    </thead>
                    <tbody>
                <?php
                $no = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo htmlspecialchars($row['id_transaksi']) ?></td>
                            <td><?php echo htmlspecialchars($row['nama_item']) ?></td>
                            <td><?php echo htmlspecialchars($row['jumlah_transaksi']) ?></td>
                            <td>
                                <!-- Form untuk memperbarui harga dengan AJAX -->
                                <form method="POST" class="update-harga-form">
                                    <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($row['id_transaksi']) ?>">
                                    <input type="text" name="harga_item" class="form-control form-control-sm" value="<?php echo htmlspecialchars($row['harga_item']) ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td> <!-- Menampilkan Harga -->
                            <td><?php echo htmlspecialchars($row['tgl_transaksi']) ?></td>
                            <td><?php echo htmlspecialchars($row['pembeli']) ?></td>
                            <td>
                                <form method="POST" action="update_status_pesanan.php">
                                    <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($row['id_transaksi']) ?>">
                                    <select name="status_pesanan" class="form-control form-control-sm" onchange="this.form.submit()">
                                        <option value="menunggu pembayaran" <?php if ($row['status_pesanan'] == 'menunggu pembayaran') echo 'selected'; ?>>Menunggu Pembayaran</option>
                                        <option value="diproses" <?php if ($row['status_pesanan'] == 'diproses') echo 'selected'; ?>>Diproses</option>
                                        <option value="dikirim" <?php if ($row['status_pesanan'] == 'dikirim') echo 'selected'; ?>>Dikirim</option>
                                        <option value="diterima" <?php if ($row['status_pesanan'] == 'diterima') echo 'selected'; ?>>Diterima</option>
                                        <option value="ditolak" <?php if ($row['status_pesanan'] == 'ditolak') echo 'selected'; ?>>Ditolak</option>
                                        <option value="ditinjau" <?php if ($row['status_pesanan'] == 'ditinjau') echo 'selected'; ?>>Ditinjau</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <?php if (!empty($row['gambar'])) { ?>
                                    <a href="<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Pesanan" class="btn btn-info" download>Unduh</a>
                                <?php } ?>
                            </td>
                            <td>
                            <form method="POST" action="update_status_pembayaran.php">
    <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($row['id_transaksi']) ?>">
    <select name="status_pembayaran" class="form-control form-control-sm" onchange="this.form.submit()">
        <option value="Belum Dibayar" <?php echo (isset($row['status_pembayaran']) && $row['status_pembayaran'] == 'Belum Dibayar') ? 'selected' : ''; ?>>Belum Dibayar</option>
        <option value="Diterima" <?php echo (isset($row['status_pembayaran']) && $row['status_pembayaran'] == 'Diterima') ? 'selected' : ''; ?>>Diterima</option>
        <option value="Ditolak" <?php echo (isset($row['status_pembayaran']) && $row['status_pembayaran'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
    </select>
</form>



                            </td>
                            <td>
                                <?php if (!empty($row['bukti_pembayaran'])) { ?>
                                    <a href="<?= htmlspecialchars($row['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" class="btn btn-info" download>Unduh</a>
                                <?php } ?>
                            </td>
                        </tr>
                <?php
                }
                ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- AJAX untuk memperbarui harga -->
    <script>
        $(document).on('submit', '.update-harga-form', function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                type: 'POST',
                url: 'update_harga.php',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    // Menghapus alert sebelumnya
                    $('.alert').remove();
                    if (response.status === 'success') {
                        // Menampilkan alert di bawah navbar
                        $('.alert-container').html('<div class="alert alert-info">' + response.message + '</div>');
                    } else {
                        // Menampilkan alert di bawah navbar
                        $('.alert-container').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan, coba lagi');
                }
            });
        });
    </script>
</body>
</html>
<?php 
    }
} else {
    echo "<div class='container'><div class='text-center'>Anda tidak memiliki akses ke halaman ini.</div></div>";
}
?>

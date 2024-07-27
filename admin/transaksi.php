<?php
include_once '../includes/db_connect.php';
include_once '../includes/config.php';
session_start();

if (!isset($_SESSION['nama_lengkap'])) {
    echo "<script>location.href='../login.php'</script>";
    exit;
}

$config = new Config();
$db = $config->getConnection();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != 'Admin') { ?>
        <div class="container">
            <div class="text-center">Halaman ini hanya untuk hak akses Admin saja!</div>
        </div>
    <?php 
    } else {
        include_once '../includes/transaksi.inc.php';
        $pro = new Transaksi($db);

        // Ambil nilai pencarian jika ada
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $stmt = $pro->readAll($search);
?>
        <!-- Kode HTML untuk menampilkan transaksi -->
        <div class="container mt-4">
            <h1>Data Transaksi</h1>
            <!-- Tambahkan form pencarian dan tabel data transaksi di sini -->
        </div>
<?php
    } // Penutup blok else
} else { ?>
    <div class="container">
        <div class="text-center">Halaman ini hanya untuk hak akses Admin saja!</div>
    </div>
<?php } // Penutup blok if ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan</title>
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
    <div class="content">
        <div class="container">
        <div class="alert-container"></div>
            <h2>Data Transaksi</h2>
            <form method="GET" action="">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Transaksi" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
            <?php if (isset($_SESSION['success_message'])) { ?>
                <div class="alert alert-success alert-container">
                    <?php echo $_SESSION['success_message']; ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php } ?>
            
            <div class="table-wrapper">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th width="30px">No</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th>Pembeli</th>
                            <th>Status Pemesanan</th>
                            <th>Gambar Pesanan</th>
                            <th>Status Pembayaran</th>
                            <th>Bukti Pembayaran</th>
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
                            <form method="POST" class="update-status-pembayaran-form">
    <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($row['id_transaksi']); ?>">
    <select name="status_pembayaran" class="form-control form-control-sm" onchange="this.form.submit()">
        <option value="Pending" <?php echo (isset($row['status_pembayaran']) && $row['status_pembayaran'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
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
                    $('.alert').remove();
                    var alertClass = response.status === 'success' ? 'alert-success' : 'alert-danger';
                    $('.alert-container').html('<div class="alert ' + alertClass + '">' + response.message + '</div>');
                },
                error: function() {
                    $('.alert').remove();
                    $('.alert-container').html('<div class="alert alert-danger">Terjadi kesalahan, coba lagi</div>');
                }
            });
        });
    </script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Modal untuk Alasan Penolakan -->
<div class="modal fade" id="alasanModal" tabindex="-1" role="dialog" aria-labelledby="alasanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alasanModalLabel">Alasan Penolakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="alasanForm" method="POST" action="update_status_pembayaran.php">
                <div class="modal-body">
                    <input type="hidden" name="id_transaksi" id="id_transaksi">
                    <input type="hidden" name="status_pembayaran" value="Ditolak">
                    <div class="form-group">
                        <label for="alasan">Alasan Penolakan:</label>
                        <textarea class="form-control" name="keterangan" id="alasan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Tangani klik pada tombol "Tolak"
        $('.reject-button').click(function() {
            // Dapatkan id_transaksi dari baris terkait
            var id_transaksi = $(this).data('id');
            // Isi input hidden dengan id_transaksi
            $('#alasanModal #id_transaksi').val(id_transaksi);
            // Tampilkan modal
            $('#alasanModal').modal('show');
        });

        // Tangani submit form alasan penolakan
        $('#alasanForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        alert('Status pembayaran berhasil diperbarui.');
                        location.reload();
                    } else {
                        alert('Terjadi kesalahan saat memperbarui status pembayaran.');
                    }
                }
            });
        });
    });
</script>





</body>
</html>
<?php 


?>
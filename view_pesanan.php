<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/transaksi.inc.php';
include_once 'includes/status_pembayaran.inc.php'; // Pastikan jalur ini benar

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pengguna'])) {
    $_SESSION['message'] = 'Anda harus login terlebih dahulu untuk melihat pesanan.';
    $_SESSION['message_type'] = 'danger';
    header("Location: login.php");
    exit;
}

$config = new Config();
$db = $config->getConnection();
$transaksi = new Transaksi($db);
$statusPembayaran = new StatusPembayaran($db); // Instansiasi kelas StatusPembayaran

$id_pengguna = $_SESSION['id_pengguna'];

// Menangani pembatalan pesanan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'cancel') {
    $id_transaksi = $_POST['id_transaksi'];
    
    if ($transaksi->cancelOrder($id_transaksi, $id_pengguna)) {
        $_SESSION['message'] = 'Pesanan berhasil dibatalkan.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Gagal membatalkan pesanan.';
        $_SESSION['message_type'] = 'danger';
    }

    // Redirect untuk menghindari pengiriman data form yang sama pada refresh
    header("Location: view_pesanan.php");
    exit;
}

// Ambil data pesanan
$stmt = $transaksi->readAllByUser($id_pengguna);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .container {
            margin-top: 10px; /* Sesuaikan dengan tinggi navbar */
        }
        .alert {
            margin: 0;
            position: sticky;
            top: 10px; /* Sesuaikan dengan tinggi navbar */
            width: 100%;
            z-index: 1000;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .card-header {
            background-color: #343a40;
            color: #fff;
        }
        .card-header-custom {
            margin-bottom: 20px; /* Mengatur jarak bawah dari card-header */
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">
        <img src="images/logo.png" alt="Company Logo">
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
                <a class="nav-link" href="produk.php">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="keranjang.php">Keranjang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> text-center notification">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php
        // Hapus notifikasi setelah ditampilkan
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>
    
    <div class="card-header card-header-custom">Pesanan Yang Diajukan</div>
    <form id="pesanan-form" method="POST" action="pembayaran.php">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>ID Transaksi</th>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pesan</th>
                    <th>Gambar</th>
                    <th>Harga/pcs</th>
                    <th>Aksi</th> <!-- Kolom untuk aksi -->
                </tr>
            </thead>
            <tbody>
                <?php if ($stmt->rowCount() > 0): ?>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_items[]" value="<?php echo htmlspecialchars($row['id_transaksi']); ?>"
                                <?php if ($row['status_pembayaran'] === 'Diterima'): ?> disabled <?php endif; ?>>
                            </td>
                            <td><?php echo htmlspecialchars($row['id_transaksi']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_item']); ?></td>
                            <td><?php echo htmlspecialchars($row['jumlah_transaksi']); ?></td>
                            <td><?php echo htmlspecialchars($row['tgl_transaksi']); ?></td>
                            <td>
                                <?php if ($row['gambar']): ?>
                                    <img src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar Pesanan" width="100">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['harga_item']); ?></td>
                            <td>
                                <?php if ($row['status_pembayaran'] === 'Diterima'): ?>
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Batalkan</button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-danger btn-sm cancel-btn" data-item="<?php echo htmlspecialchars($row['id_transaksi']); ?>">Batalkan</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada pesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-right">
            <button type="submit" id="submit-btn" class="btn btn-primary">Lanjut ke Pembayaran</button>
        </div>
    </form>
</div>

<form id="cancel-form" method="POST" action="view_pesanan.php" style="display: none;">
    <input type="hidden" name="action" value="cancel">
    <input type="hidden" name="id_transaksi" id="cancel-item">
</form>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.cancel-btn').on('click', function() {
            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                var idTransaksi = $(this).data('item');
                $('#cancel-item').val(idTransaksi);
                $('#cancel-form').submit();
            }
        });

        // Fungsi untuk memeriksa status checkbox
        function checkCheckbox() {
            if ($('input[name="selected_items[]"]:checked').length > 0) {
                $('#submit-btn').prop('disabled', false); // Aktifkan tombol jika ada checkbox yang dipilih
            } else {
                $('#submit-btn').prop('disabled', true);  // Nonaktifkan tombol jika tidak ada checkbox yang dipilih
            }
        }

        // Panggil fungsi saat dokumen siap
        checkCheckbox();

        // Tambahkan event listener pada perubahan status checkbox
        $('input[name="selected_items[]"]').on('change', function() {
            checkCheckbox();
        });
    });
</script>

</body>
</html>

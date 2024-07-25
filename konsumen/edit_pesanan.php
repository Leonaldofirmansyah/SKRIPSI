<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/transaksi.inc.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pengguna'])) {
    $_SESSION['message'] = 'Anda harus login terlebih dahulu untuk mengedit pesanan.';
    $_SESSION['message_type'] = 'danger';
    header("Location: login.php");
    exit;
}

$config = new Config();
$db = $config->getConnection();
$transaksi = new Transaksi($db);

$id_pengguna = $_SESSION['id_pengguna'];

// Mengambil ID transaksi dari parameter URL
if (!isset($_GET['id_transaksi']) || empty($_GET['id_transaksi'])) {
    $_SESSION['message'] = 'ID Transaksi tidak ditemukan.';
    $_SESSION['message_type'] = 'danger';
    header("Location: view_pesanan.php");
    exit;
}

$id_transaksi = $_GET['id_transaksi'];

// Debugging: Cek ID transaksi
error_log("ID Transaksi: " . htmlspecialchars($id_transaksi));

// Ambil data transaksi untuk ditampilkan di formulir
$stmt = $transaksi->getOrderById($id_transaksi);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Debugging: Cek hasil fetch
if (!$order) {
    $_SESSION['message'] = 'Pesanan tidak ditemukan.';
    $_SESSION['message_type'] = 'danger';
    header("Location: view_pesanan.php");
    exit;
}

// Menangani update gambar dan jumlah pesanan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $jumlah_transaksi = $_POST['jumlah_transaksi'];
    $gambar = $_FILES['gambar']['name'];

    // Proses upload gambar
    if ($gambar) {
        $target_dir = "uploads/products/";
        $target_file = $target_dir . basename($gambar);
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Gambar berhasil diupload
        } else {
            // Gagal upload gambar
            $_SESSION['message'] = 'Gagal mengupload gambar.';
            $_SESSION['message_type'] = 'danger';
            header("Location: edit_pesanan.php?id_transaksi=" . urlencode($id_transaksi));
            exit;
        }
    } else {
        // Ambil gambar yang sudah ada
        $target_file = $order['gambar'];
    }

    // Update data pesanan
    $query = "UPDATE transaksi SET jumlah_transaksi = :jumlah_transaksi, gambar = :gambar WHERE id_transaksi = :id_transaksi";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':jumlah_transaksi', $jumlah_transaksi);
    $stmt->bindParam(':gambar', $target_file);
    $stmt->bindParam(':id_transaksi', $id_transaksi);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Pesanan berhasil diperbarui.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Gagal memperbarui pesanan.';
        $_SESSION['message_type'] = 'danger';
        // Debugging: tampilkan error PDO
        $_SESSION['message'] .= '<br>Error: ' . implode(', ', $stmt->errorInfo());
    }

    header("Location: view_pesanan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                <a class="nav-link" href="produk.php">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="keranjang.php">Keranjang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pembayaran.php">Pembayaran</a>
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
    
    <h1 class="text-center">Edit Pesanan</h1>
    <form method="POST" action="edit_pesanan.php?id_transaksi=<?php echo htmlspecialchars($id_transaksi); ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="jumlah_transaksi">Jumlah:</label>
            <input type="number" id="jumlah_transaksi" name="jumlah_transaksi" value="<?php echo htmlspecialchars($order['jumlah_transaksi']); ?>" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="gambar">Gambar:</label>
            <input type="file" id="gambar" name="gambar" class="form-control">
            <?php if ($order['gambar']): ?>
                <img src="<?php echo htmlspecialchars($order['gambar']); ?>" alt="Gambar Pesanan" width="100">
            <?php endif; ?>
        </div>
        <input type="hidden" name="action" value="update">
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

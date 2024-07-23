<?php
include "includes/config.php";
session_start();
if(!isset($_SESSION['nama_lengkap']) || base64_decode($_SESSION['role']) !== 'Admin'){
    echo "<script>location.href='login.php'</script>";
}

$config = new Config();
$db = $config->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status_pesanan = $_POST['status_pesanan'];

    $transaksi = new Transaksi($db);

    if ($transaksi->updateStatus($id, $status_pesanan)) {
        echo "Status pesanan berhasil diubah.";
    } else {
        echo "Gagal mengubah status pesanan.";
    }
}

// Ambil data transaksi untuk ditampilkan
$id = $_GET['id'];
$query = "SELECT * FROM transaksi WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$transaksi = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ubah Status Pesanan</title>
    <!-- Bootstrap -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Ubah Status Pesanan</h2>
        <form method="post" action="ubah_status.php">
            <input type="hidden" name="id" value="<?php echo $transaksi['id']; ?>">
            <div class="form-group">
                <label for="status_pesanan">Status Pesanan</label>
                <select name="status_pesanan" id="status_pesanan" class="form-control">
                    <option value="proses" <?php if($transaksi['status_pesanan'] == 'proses') echo 'selected'; ?>>Proses</option>
                    <option value="diterima" <?php if($transaksi['status_pesanan'] == 'diterima') echo 'selected'; ?>>Diterima</option>
                    <option value="ditinjau" <?php if($transaksi['status_pesanan'] == 'ditinjau') echo 'selected'; ?>>Ditinjau</option>
                    <option value="dikirim" <?php if($transaksi['status_pesanan'] == 'dikirim') echo 'selected'; ?>>Dikirim</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ubah Status</button>
        </form>
    </div>
</body>
</html>

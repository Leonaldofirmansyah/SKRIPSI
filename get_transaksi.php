<?php
include_once 'includes/db_connect.php';
include_once 'includes/config.php';

$config = new Config();
$db = $config->getConnection();

header('Content-Type: application/json');

if (isset($_GET['id_pengguna'])) {
    $id_pengguna = intval($_GET['id_pengguna']);
    
    // Debugging
    file_put_contents('debug.log', 'ID Pengguna: ' . $id_pengguna . PHP_EOL, FILE_APPEND);

    $query = "SELECT id_transaksi, nama_item, jumlah_transaksi, harga_item, (jumlah_transaksi * harga_item) AS total FROM transaksi WHERE id_pengguna = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id_pengguna);

    // Debugging
    if ($stmt->execute()) {
        file_put_contents('debug.log', 'Query Executed Successfully' . PHP_EOL, FILE_APPEND);
        $transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($transaksi);
    } else {
        file_put_contents('debug.log', 'Query Execution Failed' . PHP_EOL, FILE_APPEND);
        echo json_encode(['error' => 'Query Execution Failed']);
    }
} else {
    // Debugging
    file_put_contents('debug.log', 'ID Pengguna Not Set' . PHP_EOL, FILE_APPEND);
    echo json_encode(['error' => 'ID Pengguna Not Set']);
}
?>

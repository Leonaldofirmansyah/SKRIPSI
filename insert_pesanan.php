<?php
class transaksi {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk menambahkan pesanan
    public function create($kode_item, $jumlah_transaksi, $total_transaksi, $pembeli, $status_pesanan, $gambar = null) {
        $query = "INSERT INTO transaksi (kode_item, jumlah_transaksi, total_transaksi, id_pengguna, status_pesanan, gambar) VALUES (:kode_item, :jumlah_transaksi, :total_transaksi, (SELECT id_pengguna FROM pengguna WHERE nama_lengkap = :pembeli), :status_pesanan, :gambar)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_item', $kode_item);
        $stmt->bindParam(':jumlah_transaksi', $jumlah_transaksi);
        $stmt->bindParam(':total_transaksi', $total_transaksi);
        $stmt->bindParam(':pembeli', $pembeli);
        $stmt->bindParam(':status_pesanan', $status_pesanan);
        $stmt->bindParam(':gambar', $gambar);

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            echo "SQL Error: " . $errorInfo[2];
            return false;
        }
        return true;
    }
}
?>

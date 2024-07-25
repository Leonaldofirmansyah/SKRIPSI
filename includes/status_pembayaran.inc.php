<?php
class StatusPembayaran {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Contoh metode untuk mengambil status pembayaran
    public function getStatusByTransactionId($id_transaksi) {
        $query = "SELECT status FROM status_pembayaran WHERE id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_transaksi', $id_transaksi);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambahkan metode lain sesuai kebutuhan
}
?>
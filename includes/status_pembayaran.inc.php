<?php
class StatusPembayaran {
    private $conn;
    private $table_name = "transaksi"; // Ubah ke tabel yang benar jika status ada di tabel lain

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getStatusByTransactionId($id_transaksi) {
        $query = "SELECT status_pembayaran FROM " . $this->table_name . " WHERE id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_transaksi', $id_transaksi);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

public function updateStatus($id_transaksi, $status) {
    $query = "UPDATE transaksi SET status_pembayaran = :status WHERE id_transaksi = :id_transaksi";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id_transaksi', $id_transaksi);
    return $stmt->execute();
}

}
?>

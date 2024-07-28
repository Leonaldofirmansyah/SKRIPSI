<?php
// produksi.inc.php
include_once 'db_connect.php'; // Sesuaikan path dengan lokasi file ini

class Pesanan {
    private $conn;
    private $table_name = "transaksi"; // Nama tabel yang benar

    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function readSiapProduksi() {
        // Gunakan nama kolom yang sesuai dengan tabel
        $query = "SELECT id_transaksi, nama_item, jumlah_transaksi, status_pembayaran FROM " . $this->table_name . " WHERE status_pembayaran = 'Diterima'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>

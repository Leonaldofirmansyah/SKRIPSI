<?php
class Pembayaran {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function simpanPembayaran($id_transaksi, $opsi_pembayaran, $bukti_pembayaran) {
        try {
            $query = "UPDATE transaksi 
                      SET opsi_pembayaran = :opsi_pembayaran, 
                          bukti_pembayaran = :bukti_pembayaran, 
                          status_pembayaran = 'Menunggu Dikonfirmasi' 
                      WHERE id_transaksi = :id_transaksi";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id_transaksi', $id_transaksi);
            $stmt->bindParam(':opsi_pembayaran', $opsi_pembayaran);
            $stmt->bindParam(':bukti_pembayaran', $bukti_pembayaran);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception('Query execution failed.');
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
?>

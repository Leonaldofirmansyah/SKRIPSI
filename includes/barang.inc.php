<?php
class Barang {
    private $conn;
    private $table_satu = "item";

    public $kode_item; // Kode item
    public $nama_item; // Nama item
    public $gambar; // Gambar item

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk menambahkan barang
    public function insert() {
        $query = "INSERT INTO " . $this->table_satu . " (kode_item, nama_item, gambar) VALUES (:kode_item, :nama_item, :gambar)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_item', $this->kode_item);
        $stmt->bindParam(':nama_item', $this->nama_item);
        $stmt->bindParam(':gambar', $this->gambar);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method untuk membaca semua barang
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_satu . " ORDER BY kode_item ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Method untuk menghitung jumlah barang
    public function readCount() {
        $query = "SELECT COUNT(kode_item) AS jumlahdata FROM " . $this->table_satu;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Method untuk membaca data barang berdasarkan kode
    public function readOne() {
        error_log("Executing readOne with kode_item: " . $this->kode_item);
        
        $query = "SELECT * FROM " . $this->table_satu . " WHERE kode_item = :kode_item LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_item', $this->kode_item);
        
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Query executed: " . $query);
            error_log("Query result: " . print_r($row, true));
            
            if ($row) {
                $this->kode_item = $row['kode_item'];
                $this->nama_item = $row['nama_item'];
                $this->gambar = $row['gambar'];
                return true;
            } else {
                return false; // Barang tidak ditemukan
            }
        } catch (Exception $e) {
            error_log("Error executing query: " . $e->getMessage());
            return false; // Kesalahan saat eksekusi query
        }
    }
    
    

    // Method untuk memperbarui data barang
    public function update() {
        $query = "UPDATE " . $this->table_satu . " 
                  SET nama_item = :nama_item,
                      gambar = :gambar
                  WHERE kode_item = :kode_item";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_item', $this->kode_item);
        $stmt->bindParam(':nama_item', $this->nama_item);
        $stmt->bindParam(':gambar', $this->gambar);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method untuk menghapus barang
    public function delete() {
        $this->conn->beginTransaction();
    
        try {
            // Hapus data terkait di tabel transaksi
            $query = "DELETE FROM transaksi WHERE kode_item = :kode_item";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kode_item', $this->kode_item);
            $stmt->execute();
    
            // Hapus barang
            $query = "DELETE FROM " . $this->table_satu . " WHERE kode_item = :kode_item";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kode_item', $this->kode_item);
            $stmt->execute();
    
            // Commit transaksi
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            $this->conn->rollBack();
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }
    
    
}
?>
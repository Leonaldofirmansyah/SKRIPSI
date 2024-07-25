<?php
class Transaksi {
    private $conn;
    private $table = "transaksi";
    private $table_name = "keranjang";
    private $table_name_orders = "pesanan"; // Nama tabel pesanan

    public $id_transaksi;
    public $kode_item;
    public $nama_item;
    public $jumlah_transaksi;
    public $tgl_transaksi;
    public $status_pesanan;
    public $id_pengguna;
    public $gambar;
    public $harga_item;
    public $bukti_pembayaran;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll($search = "") {
        $query = "SELECT t.id_transaksi, i.nama_item, t.jumlah_transaksi, t.harga_item, t.tgl_transaksi, u.nama_lengkap AS pembeli, t.status_pesanan, t.gambar, t.bukti_pembayaran
                  FROM transaksi t
                  JOIN item i ON t.kode_item = i.kode_item
                  JOIN pengguna u ON t.id_pengguna = u.id_pengguna
                  WHERE t.id_transaksi LIKE :search OR i.nama_item LIKE :search
                  ORDER BY t.tgl_transaksi DESC";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%$search%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt;
    }
    public function getAllByUserId() {
        $query = "SELECT * FROM transaksi WHERE id_pengguna = :id_pengguna";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pengguna', $this->id_pengguna);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id_transaksi, $status_pesanan) {
        $query = "UPDATE transaksi SET status_pesanan = :status_pesanan WHERE id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status_pesanan', $status_pesanan);
        $stmt->bindParam(':id_transaksi', $id_transaksi);

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            echo "SQL Error: " . $errorInfo[2];
            return false;
        }
        return true;
    }

    public function updateHarga($id_transaksi, $harga_item) {
        $query = "UPDATE transaksi SET harga_item = :harga_item WHERE id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':harga_item', $harga_item);
        $stmt->bindParam(':id_transaksi', $id_transaksi);

        if ($stmt->execute()) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "SQL Error: " . $errorInfo[2];
            return false;
        }
    }

    public function readById($id_transaksi) {
        $query = "SELECT * FROM transaksi WHERE id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_transaksi', $id_transaksi);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert() {
        $query = "SELECT * FROM transaksi WHERE kode_item = :kode_item AND id_pengguna = :id_pengguna";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_item', $this->kode_item);
        $stmt->bindParam(':id_pengguna', $this->id_pengguna);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Pesanan untuk item ini sudah ada.";
            return false;
        }

        $this->id_transaksi = $this->generateIdTransaksi();

        $query = "INSERT INTO " . $this->table . " (id_transaksi, kode_item, nama_item, jumlah_transaksi, tgl_transaksi, id_pengguna, status_pesanan, harga_item, gambar, bukti_pembayaran) VALUES (:id_transaksi, :kode_item, :nama_item, :jumlah_transaksi, :tgl_transaksi, :id_pengguna, :status_pesanan, :harga_item, :gambar, :bukti_pembayaran)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_transaksi', $this->id_transaksi);
        $stmt->bindParam(':kode_item', $this->kode_item);
        $stmt->bindParam(':nama_item', $this->nama_item);
        $stmt->bindParam(':jumlah_transaksi', $this->jumlah_transaksi);
        $stmt->bindParam(':tgl_transaksi', $this->tgl_transaksi);
        $stmt->bindParam(':id_pengguna', $this->id_pengguna);
        $stmt->bindParam(':status_pesanan', $this->status_pesanan);
        $stmt->bindParam(':harga_item', $this->harga_item);
        $stmt->bindParam(':gambar', $this->gambar);
        $stmt->bindParam(':bukti_pembayaran', $this->bukti_pembayaran);

        if($stmt->execute()) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "SQL Error: " . $errorInfo[2];
            return false;
        }
    }

    public function create($kode_item, $jumlah_transaksi, $total_transaksi, $pembeli, $status_pesanan, $harga_item, $bukti_pembayaran = null, $gambar = null) {
        $query = "INSERT INTO transaksi (kode_item, jumlah_transaksi, total_transaksi, id_pengguna, status_pesanan, harga_item, bukti_pembayaran, gambar) VALUES (:kode_item, :jumlah_transaksi, :total_transaksi, (SELECT id_pengguna FROM pengguna WHERE nama_lengkap = :pembeli), :status_pesanan, :harga_item, :bukti_pembayaran, :gambar)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_item', $kode_item);
        $stmt->bindParam(':jumlah_transaksi', $jumlah_transaksi);
        $stmt->bindParam(':total_transaksi', $total_transaksi);
        $stmt->bindParam(':pembeli', $pembeli);
        $stmt->bindParam(':status_pesanan', $status_pesanan);
        $stmt->bindParam(':harga_item', $harga_item);
        $stmt->bindParam(':bukti_pembayaran', $bukti_pembayaran);
        $stmt->bindParam(':gambar', $gambar);

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            echo "SQL Error: " . $errorInfo[2];
            return false;
        }
        return true;
    }

    

    public function cancelOrder($id_transaksi, $id_pengguna) {
        $query = "DELETE FROM transaksi WHERE id_transaksi = :id_transaksi AND id_pengguna = :id_pengguna";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_transaksi', $id_transaksi);
        $stmt->bindParam(':id_pengguna', $id_pengguna);
        
        return $stmt->execute();
    
    }
    public function updateOrder($id_transaksi, $jumlah_transaksi, $gambar) {
        $query = "UPDATE " . $this->table_name . " 
                  SET jumlah_transaksi = :jumlah_transaksi, gambar = :gambar 
                  WHERE id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_transaksi', $id_transaksi);
        $stmt->bindParam(':jumlah_transaksi', $jumlah_transaksi);
        $stmt->bindParam(':gambar', $gambar);
        return $stmt->execute();
    }
    

    private function generateIdTransaksi() {
        $prefix = date('Ymd');
        $query = "SELECT MAX(id_transaksi) AS max_id FROM transaksi WHERE id_transaksi LIKE :prefix";
        $stmt = $this->conn->prepare($query);
        $prefixLike = $prefix . '%';
        $stmt->bindParam(':prefix', $prefixLike);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $maxId = $result['max_id'];

        $number = 1;
        if ($maxId) {
            $number = intval(substr($maxId, -4)) + 1;
        }

        return $prefix . sprintf('%04d', $number);
    }

    

    public function insertToCart() {
        $query = "INSERT INTO keranjang (kode_item, nama_item, jumlah_transaksi, tgl_transaksi, id_pengguna, gambar) 
                  VALUES (:kode_item, :nama_item, :jumlah_transaksi, :tgl_transaksi, :id_pengguna, :gambar)";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':kode_item', $this->kode_item);
        $stmt->bindParam(':nama_item', $this->nama_item);
        $stmt->bindParam(':jumlah_transaksi', $this->jumlah_transaksi);
        $stmt->bindParam(':tgl_transaksi', $this->tgl_transaksi);
        $stmt->bindParam(':id_pengguna', $this->id_pengguna);
        $stmt->bindParam(':gambar', $this->gambar);
    
        return $stmt->execute();
    }
    // Function to get items from cart
    public function getCartItems() {
        $query = "SELECT * FROM keranjang WHERE id_pengguna = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_pengguna);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to move items from cart to orders
    public function moveToOrders($id_transaksi) {
        $query = "INSERT INTO transaksi (id_transaksi, id_pengguna, kode_item, nama_item, jumlah_transaksi, tgl_transaksi, gambar, status_pesanan, harga_item)
                  SELECT id_transaksi, id_pengguna, kode_item, nama_item, jumlah_transaksi, tgl_transaksi, gambar, 'Pending', NULL
                  FROM keranjang
                  WHERE id_transaksi = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_transaksi);
        $stmt->execute();
    }

    // Function to remove items from cart
    public function removeFromCart($id_transaksi) {
        $query = "DELETE FROM keranjang WHERE id_transaksi = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_transaksi);
        $stmt->execute();
    }

    // Function to read all orders by user
    // Di dalam class Transaksi, method readAllByUser()
public function readAllByUser($id_pengguna) {
    $query = "SELECT 
                t.id_transaksi, 
                t.nama_item, 
                t.jumlah_transaksi, 
                t.tgl_transaksi, 
                t.gambar, 
                t.status_pesanan, 
                t.harga_item, 
                t.status_pembayaran
              FROM 
                transaksi t
              WHERE 
                t.id_pengguna = :id_pengguna";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_pengguna', $id_pengguna);
    $stmt->execute();
    return $stmt;
}

        // Method untuk mengambil satu data pesanan berdasarkan pengguna dan kode item
        public function readOneByUser($id_pengguna, $kode_item) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengguna = ? AND kode_item = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id_pengguna);
            $stmt->bindParam(2, $kode_item);
            $stmt->execute();
            return $stmt;
        }
        // Method in transaksi.inc.php
        public function getOrderById($id_transaksi) {
            $query = "SELECT * FROM transaksi WHERE id_transaksi = :id_transaksi";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_transaksi', $id_transaksi);
            $stmt->execute();
            return $stmt;
        }

        public function getItemByIdTransaksi($id_transaksi, $id_pengguna) {
            $query = "SELECT * FROM transaksi WHERE id_transaksi = ? AND id_pengguna = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id_transaksi);
            $stmt->bindParam(2, $id_pengguna);
            $stmt->execute();
        
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
           // Method untuk memproses pembayaran
    // Method untuk memproses pembayaran
    public function processPayment($id_transaksi, $jumlah_pembayaran, $bukti_pembayaran) {
        try {
            $query = "UPDATE " . $this->table_name . " SET 
                      jumlah_pembayaran = :jumlah_pembayaran, 
                      bukti_pembayaran = :bukti_pembayaran,
                      status_pembayaran = 'Diterima'
                      WHERE id_transaksi = :id_transaksi";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':jumlah_pembayaran', $jumlah_pembayaran);
            $stmt->bindParam(':bukti_pembayaran', $bukti_pembayaran);
            $stmt->bindParam(':id_transaksi', $id_transaksi);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method untuk mengambil pesanan yang belum dibayar
    public function readUnpaidByUser($id_pengguna) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengguna = :id_pengguna AND status = 'Belum Dibayar'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();
        return $stmt;
    }
    public function getPaidOrdersByUserId() {
        $query = "SELECT * FROM transaksi WHERE id_pengguna = :id_pengguna AND status_pembayaran = 'Diterima'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pengguna', $this->id_pengguna);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    


    
}

?>
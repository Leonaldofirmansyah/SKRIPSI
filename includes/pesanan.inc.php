<?php
class barang{
    
    private $conn;
    private $table_satu = "barang";
    private $table_name = "pesanan";

    private $table_dua = "kategori_item";
    
    public $ki; // Kode item
    public $kk; // Kode kategori
    public $ni; // Nama item
    public $hi; // Harga item
    public $si; // Stok item
    public $gambar; // Gambar item

    public function __construct($db){
        $this->conn = $db;
    }
    
    function insert(){
        $query = "INSERT INTO ".$this->table_satu." (kode_item, kode_kategoriitem, nama_item, harga_item, stok_item, gambar) VALUES (:ki, :kk, :ni, :hi, :si, :gambar)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ki', $this->ki);
        $stmt->bindParam(':kk', $this->kk);
        $stmt->bindParam(':ni', $this->ni);
        $stmt->bindParam(':hi', $this->hi);
        $stmt->bindParam(':si', $this->si);
        $stmt->bindParam(':gambar', $this->gambar);
        
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total_pesanan FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_pesanan'];
    }

    // Metode lainnya...
}
?>

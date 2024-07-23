<?php
class Item {
    private $conn;
    private $table = "item";

    public $kode_item;
    public $nama_item;
    public $harga_item;
    public $gambar;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readById($kode_item) {
        $query = "SELECT * FROM " . $this->table . " WHERE kode_item = :kode_item";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode_item', $kode_item);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

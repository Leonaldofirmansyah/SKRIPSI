<?php
class Produk {
    private $conn;
    private $table_name = "item"; // Update dengan nama tabel yang benar

    public function __construct($db) {
        $this->conn = $db;
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>

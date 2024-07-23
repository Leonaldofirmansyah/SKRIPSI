<?php
class Address {
    private $conn;
    private $table_name = "addresses";

    public $id;
    public $user_id;
    public $alamat;
    public $nomor_telepon;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insert() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, alamat, nomor_telepon) VALUES (:user_id, :alamat, :nomor_telepon)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':alamat', $this->alamat);
        $stmt->bindParam(':nomor_telepon', $this->nomor_telepon);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . implode(" - ", $stmt->errorInfo());
            return false;
        }
    }

    public function getAddressByUserId() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = " . $this->user_id . " LIMIT 1";
        $stmt = $this->conn->query($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_DEFAULT);
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET alamat = :alamat, nomor_telepon = :nomor_telepon WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':alamat', $this->alamat);
        $stmt->bindParam(':nomor_telepon', $this->nomor_telepon);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . implode(" - ", $stmt->errorInfo());
            return false;
        }
    }
}
?>

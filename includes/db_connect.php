<?php
class Database {
    private $host = "localhost";
    private $db_name = "skripsi_leo"; // ganti dengan nama database Anda
    private $username = "root"; // ganti dengan username database Anda
    private $password = ""; // ganti dengan password database Anda, jika ada
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Database connection failed: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>

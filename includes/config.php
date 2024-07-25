<?php
class Config {
    private $host = "localhost";
    private $db_name = "skripsi_leo"; // Pastikan nama database sesuai
    private $username = "root";
    private $password = "";

    public function getConnection() {
        $conn = null;
        try {
            $conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $conn;
    }
}
?>

<?php
class Login
{
    private $conn;
    private $table_name = "pengguna";
    
    public $userid;  // Email
    public $passid;  // Password

    public function __construct($db){
        $this->conn = $db;
    }

    public function login()
    {
        $user = $this->checkCredentials();
        if ($user) {
            // Mulai session hanya jika belum dimulai
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Set session variables
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            return $user['nama_lengkap'];
        }
        
        return false;
    }

    protected function checkCredentials()
    {
        // Query untuk mencari user berdasarkan email
        $query = "SELECT id_pengguna, nama_lengkap, username, role, password, is_verified FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->userid);
        $stmt->execute();
        
        // Periksa apakah ada hasil
        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verifikasi password
            if (password_verify($this->passid, $data['password'])) {
                // Periksa status verifikasi email
                if ($data['is_verified'] == 1) {
                    return $data; // Email sudah diverifikasi
                } else {
                    return false; // Email belum diverifikasi
                }
            }
        }
        return false; // Kredensial tidak valid
    }
}
?>

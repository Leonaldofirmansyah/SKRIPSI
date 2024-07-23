<?php
class User {
    private $conn;
    private $table_name = "pengguna";

    public $id;
    public $mail;
    public $nl;
    public $pw;
    public $rl;
    public $token;        // Token untuk verifikasi email
    public $is_verified;  // Status verifikasi email

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insert user data into the database, including token and verification status
    function insert() {
        $query = "INSERT INTO " . $this->table_name . " (nama_lengkap, email, password, role, token, is_verified) 
                  VALUES (:nl, :mail, :pw, :rl, :token, :is_verified)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':nl', $this->nl);
        $stmt->bindParam(':pw', $this->pw);
        $stmt->bindParam(':rl', $this->rl);
        $stmt->bindParam(':token', $this->token);
        $stmt->bindParam(':is_verified', $this->is_verified);

        // Execute query
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    // Verify user email by token
    function verifyEmail($token) {
        $query = "UPDATE " . $this->table_name . " SET is_verified = 1 WHERE token = :token AND is_verified = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Read all users
    function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_pengguna ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read a single user by ID
    function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengguna = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id_pengguna'];
            $this->nl = $row['nama_lengkap'];
            $this->mail = $row['email'];
            $this->pw = $row['password'];
            $this->token = $row['token'];
            $this->is_verified = $row['is_verified'];
        }
    }

    // Read a single user by email
    function readOneByEmail() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :mail LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id_pengguna'];
            $this->nl = $row['nama_lengkap'];
            $this->mail = $row['email'];
            $this->pw = $row['password'];
            $this->token = $row['token'];
            $this->is_verified = $row['is_verified'];
        }
    }

    // Update user data
    function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_lengkap = :nl, email = :mail, password = :pw, role = :rl WHERE id_pengguna = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rl', $this->rl);
        $stmt->bindParam(':nl', $this->nl);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':pw', $this->pw);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete a user
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_pengguna = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>

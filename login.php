<?php
session_start();
include_once 'includes/config.php';

$config = new Config();
$db = $config->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'includes/login.inc.php';
    $login = new Login($db);

    $login->userid = trim($_POST['email']); // Menangani input email
    $login->passid = $_POST['password']; // Menangani input password asli

    if ($login->login()) {
        // Redirect berdasarkan role
        if (isset($_SESSION['role'])) { // Pastikan session role diatur
            if ($_SESSION['role'] === 'Admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: konsumen/dashboard.php");
            }
            exit();
        } else {
            $error_message = "Login gagal! Role tidak diatur dengan benar.";
        }
    } else {
        // Cek apakah email belum diverifikasi
        $query = "SELECT is_verified FROM pengguna WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $email = trim($_POST['email']);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $row['is_verified'] == 0) {
            $error_message = "Email belum diverifikasi! Mohon verifikasi email Anda terlebih dahulu.";
        } else {
            $error_message = "Login gagal! Mohon diperiksa kembali email dan password Anda!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #f4f4f4;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-custom {
            background-color: #343a40;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #fff;
        }
        .navbar-custom .nav-link:hover {
            color: #d1d1d1;
        }
        .login-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            margin: auto;
            max-width: 400px;
            margin-top: 5rem;
        }
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-header h2 {
            color: #343a40;
        }
        .form-label {
            color: #343a40;
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
        }
        .btn-primary:hover {
            background-color: #495057;
        }
        .login-footer a {
            color: #343a40;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .alert {
            margin-top: 1rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="dashboard.php">
        <img src="images/logo.png" alt="Company Logo" style="height: 40px;">
        Surya Teknik Utama
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">HOME</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="login-container">
        <div class="login-header">
            <h2><i class="fa fa-lock"></i> Login</h2>
            <p class="lead">Masukkan email dan password Anda untuk masuk.</p>
        </div>
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me?</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Masuk</button>
            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
        </form>
        <div class="login-footer text-center mt-3">
            <p>Belum mempunyai akun? <a href="register.php">Daftar disini!</a></p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

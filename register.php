<?php
require 'vendor/autoload.php'; // Sertakan Composer autoload untuk PHPMailer

include_once 'includes/config.php';
include_once 'includes/user.inc.php';
include_once 'includes/address.inc.php';

$config = new Config();
$db = $config->getConnection();

if ($_POST && $_POST['mail']) {
    $user = new User($db);
    $user->mail = addslashes($_POST['mail']);
    $user->nl = addslashes($_POST['nl']);
    $user->pw = password_hash(addslashes($_POST['pw']), PASSWORD_BCRYPT); // Gunakan password_hash untuk keamanan
    $user->rl = 'User';
    $user->token = bin2hex(random_bytes(50)); // Token verifikasi unik
    $user->is_verified = 0; // Set status verifikasi ke 0 (belum diverifikasi)

    if ($user->insert()) {
        // Get the last inserted user ID
        $user_id = $user->id;

        // Insert address data
        $address = new Address($db);
        $address->user_id = $user_id;
        $address->alamat = addslashes($_POST['alamat']);
        $address->nomor_telepon = addslashes($_POST['nomor_telepon']);

        if ($address->insert()) {
            // Kirim email verifikasi
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Ganti dengan host SMTP Anda
                $mail->SMTPAuth = true;
                $mail->Username = 'leonaldofirmansyah11@gmail.com'; // Ganti dengan username email Anda
                $mail->Password = 'exhp ieap wrup nnaq'; // Ganti dengan password email Anda
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('your_email@gmail.com', 'anjay');
                $mail->addAddress($user->mail, $user->nl);

                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';
                $mail->Body    = "Please click the link below to verify your email:<br><a href='http://localhost/SKRIPSI/verify.php?token={$user->token}'>Verify Email</a>";

                $mail->send();
                echo "<script>alert('Register berhasil! Silakan cek email Anda untuk verifikasi.')</script>";
                echo "<script>location.href='index.php'</script>";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<script>alert('Register gagal!')</script>";
        }
    } else {
        echo "<script>alert('Register gagal!')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
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
        .register-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            margin: auto;
            max-width: 400px;
            margin-top: 5rem;
        }
        .register-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .register-header h2 {
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
        .register-footer a {
            color: #343a40;
        }
        .register-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">
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
            <li class="nav-item">
                <a class="nav-link" href="contact.php">CONTACT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">ABOUT</a>
            </li>
        </ul>
    </div>
</nav>

<div class="register-container">
    <div class="register-header">
        <h2>Register</h2>
    </div>
    <form method="POST" action="">
        <div class="form-group">
            <label for="mail" class="form-label">Email:</label>
            <input type="email" class="form-control" id="mail" name="mail" required>
        </div>
        <div class="form-group">
            <label for="nl" class="form-label">Nama Lengkap:</label>
            <input type="text" class="form-control" id="nl" name="nl" required>
        </div>
        <div class="form-group">
            <label for="pw" class="form-label">Password:</label>
            <input type="password" class="form-control" id="pw" name="pw" required>
        </div>
        <div class="form-group">
            <label for="alamat" class="form-label">Alamat:</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="nomor_telepon" class="form-label">Nomor Telepon:</label>
            <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <div class="register-footer text-center mt-3">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include_once 'includes/db_connect.php';
include_once 'includes/config.php';
include_once 'includes/user.inc.php';
include_once 'includes/address.inc.php';

session_start();
if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>location.href='login.php'</script>";
    exit();
}

$config = new Config();
$db = $config->getConnection();

$eks = new User($db);
$eks->id = intval($_SESSION['id_pengguna']);
$eks->readOne();

$address = new Address($db);
$address->user_id = intval($_SESSION['id_pengguna']);
$addressData = $address->getAddressByUserId();

if ($_POST) {
    if (isset($_POST['rl'])) {
        $eks->rl = addslashes($_POST['rl']);
    } else {
        $eks->rl = 'User';
    }
    $eks->nl = addslashes($_POST['nl']);
    $eks->mail = addslashes($_POST['mail']);
    $eks->un = addslashes($_POST['un']);
    $eks->pw = md5(addslashes($_POST['pw']));

    if ($eks->update()) {
        $_SESSION['nama_lengkap'] = addslashes($_POST['nl']);
        $message = "Berhasil! Anda telah mengubah profile sendiri.";
        $alert_class = "alert-success";

        // Update alamat dan nomor telepon
        $address->alamat = addslashes($_POST['alamat']);
        $address->nomor_telepon = addslashes($_POST['nomor_telepon']);
        if ($address->update()) {
            $message .= " Alamat dan nomor telepon Anda telah diperbarui.";
        } else {
            $message .= " Namun, gagal memperbarui alamat dan nomor telepon.";
        }
    } else {
        $message = "Gagal Ubah profile! Terjadi kesalahan, coba sekali lagi.";
        $alert_class = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
        .login-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            margin: auto;
            max-width: 600px;
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
        .container {
            margin-top: 50px;
        }
        .card-header {
            background-color: #343a40;
            color: #fff;
        }
        .card-header-custom {
        margin-bottom: 20px; /* Mengatur jarak bawah dari card-header */
    }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
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
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($message)): ?>
            <div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
                <strong><?php echo $message; ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="page-header">
                <div class="card-header card-header-custom">Profil Saya</div>
                </div>

                <form method="post">
                    <div class="form-group">
                        <label for="nl">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nl" name="nl" value="<?php echo htmlspecialchars($eks->nl); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mail">Email</label>
                        <input type="email" class="form-control" id="mail" name="mail" value="<?php echo htmlspecialchars($eks->mail); ?>" required>
                    <div class="form-group">
                        <label for="pw">Password</label>
                        <input type="password" class="form-control" id="pw" name="pw" placeholder="Masukkan password Anda ..." required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $addressData['alamat']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_telepon">Nomor Telepon</label>
                        <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="<?php echo htmlspecialchars($addressData['nomor_telepon']); ?>" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="s qubmit" class="btn btn-primary">Ubah</button>
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Kembali</button>
                    </div>
                </form>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4">
                <!-- Kolom kosong -->
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

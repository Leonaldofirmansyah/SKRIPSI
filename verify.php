<?php
include_once 'includes/config.php';
include_once 'includes/user.inc.php';

$config = new Config();
$db = $config->getConnection();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $user = new User($db);

    // Verifikasi token
    if ($user->verifyEmail($token)) {
        echo "Email berhasil diverifikasi. Anda dapat login sekarang.";
    } else {
        echo "Verifikasi gagal. Token tidak valid atau sudah kadaluarsa.";
    }
} else {
    echo "Permintaan tidak valid.";
}
?>

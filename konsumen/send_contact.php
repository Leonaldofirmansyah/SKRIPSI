<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Sertakan file autoload Composer
require '../vendor/autoload.php'; // Jika menggunakan Composer

// Ambil data dari formulir
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Buat instance PHPMailer
$mail = new PHPMailer(true);

try {
    // Pengaturan server
    $mail->isSMTP(); 
    $mail->Host       = 'smtp.gmail.com'; // Ganti dengan SMTP server Anda
    $mail->SMTPAuth   = true; 
    $mail->Username   = 'leonaldofirmansyah11@gmail.com'; // Ganti dengan email Anda
    $mail->Password   = 'exhp ieap wrup nnaq'; // Ganti dengan password email Anda
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = 587; 

    // Email ke Admin
    $mail->setFrom($email, $name); // Set email pengirim sebagai pengirim
    $mail->addAddress('leonaldofirmansyah11@gmail.com', 'Admin'); // Ganti dengan email admin
    $mail->Subject = $subject;
    $mail->Body    = "Anda telah menerima pesan baru dari formulir kontak di situs web Anda.\n\n".
                      "Nama: $name\n".
                      "Email: $email\n".
                      "Subjek: $subject\n".
                      "Pesan:\n$message";

    $mail->send();

    // Email balasan ke pengirim
    $mail->clearAddresses(); // Clear all previous addresses
    $mail->addAddress($email, $name); // Tambahkan email pengirim
    $mail->Subject = 'Terima Kasih Telah Menghubungi Kami';
    $mail->Body    = "Dear $name,\n\nTerima kasih telah menghubungi kami. Kami telah menerima pesan Anda dan akan segera menghubungi Anda.\n\nPesan Anda:\n$message\n\nHormat kami,\nSurya Teknik Utama";

    $mail->send();

    echo 'Pesan telah dikirim ke admin dan Anda';
} catch (Exception $e) {
    echo "Pesan tidak dapat dikirim. Kesalahan Mailer: {$mail->ErrorInfo}";
}
?>

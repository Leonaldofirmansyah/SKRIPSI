<?php
$id_pengguna = isset($_POST['id_pengguna']) ? $_POST['id_pengguna'] : '';
$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
$kontak = isset($_POST['kontak']) ? $_POST['kontak'] : '';

echo 'ID Pengguna: ' . htmlspecialchars($id_pengguna) . '<br>';
echo 'Nama: ' . htmlspecialchars($nama) . '<br>';
echo 'Alamat: ' . htmlspecialchars($alamat) . '<br>';
echo 'Kontak: ' . htmlspecialchars($kontak) . '<br>';
?>

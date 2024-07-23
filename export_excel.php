<?php
require 'vendor/autoload.php';  // Pastikan path ini benar

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include_once 'includes/db_connect.php';  // Memuat $db dari db_connect.php
include_once 'includes/transaksi.inc.php';

// Mendapatkan koneksi database
$database = new Database();
$db = $database->getConnection();

// Cek apakah $db didefinisikan
if (!$db) {
    die("Database connection failed.");
}

// Buat instance dari kelas Transaksi dengan parameter $db
$transaksi = new Transaksi($db);

// Ambil nilai pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stmt = $transaksi->readAll($search);

// Buat objek Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Transaksi');

// Set header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Kode Transaksi');
$sheet->setCellValue('C1', 'Nama Barang');
$sheet->setCellValue('D1', 'Jumlah');
$sheet->setCellValue('E1', 'Harga');
$sheet->setCellValue('F1', 'Tanggal');
$sheet->setCellValue('G1', 'Pembeli');
$sheet->setCellValue('H1', 'Status Pemesanan');

// Isi data transaksi ke spreadsheet
$rowNum = 2;  // Mulai dari baris ke-2
$no = 1;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sheet->setCellValue('A' . $rowNum, $no++);
    $sheet->setCellValue('B' . $rowNum, $row['id_transaksi']);
    $sheet->setCellValue('C' . $rowNum, $row['nama_item']);
    $sheet->setCellValue('D' . $rowNum, $row['jumlah_transaksi']);
    $sheet->setCellValue('E' . $rowNum, $row['harga_item']);
    $sheet->setCellValue('F' . $rowNum, $row['tgl_transaksi']);
    $sheet->setCellValue('G' . $rowNum, $row['pembeli']);
    $sheet->setCellValue('H' . $rowNum, $row['status_pesanan']);
    $rowNum++;
}

// Simpan file Excel ke output
$writer = new Xlsx($spreadsheet);
$filename = 'Data_Transaksi_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>

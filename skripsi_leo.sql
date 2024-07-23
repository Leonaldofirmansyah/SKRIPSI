-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Jul 2024 pada 10.23
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi_leo`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE `item` (
  `kode_item` varchar(6) NOT NULL,
  `nama_item` varchar(120) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`kode_item`, `nama_item`, `gambar`) VALUES
('001', 'Rubber Seal', 'uploads/Picture1.jpg'),
('002', 'Besh Barrel Cot', 'uploads/Picture2.jpg'),
('003', 'Transporting Roller PU', 'uploads/Picture3.jpg'),
('004', 'Rubber Strip', 'uploads/Picture4.jpg'),
('005', 'Rubber Roll Belah', 'uploads/Picture5.jpg'),
('006', 'Bout Spiral Silicone', 'uploads/Picture6.jpg'),
('007', 'Rubber Padder', 'uploads/Picture7.jpg'),
('008', 'Rubber Strip Kulit Jeruk', 'uploads/Picture8.jpg'),
('009', 'Kopling PU', 'uploads/Picture9.jpg'),
('010', 'Rubber Kopling Bintang', 'uploads/Picture10.jpg'),
('011', 'Karbon Seal, O-ring Viton', 'uploads/Picture11.jpg'),
('012', 'Rubber Seal Oil', 'uploads/Picture12.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_lengkap`, `email`, `username`, `password`, `role`) VALUES
(1, 'Leonaldo FIrmansyahh', 'leonaldofirmansyah@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin'),
(5, 'konsumen', 'leonaldofirmansyah123@gmail.com', 'konsumen', '94727b16c2221c188d39993e39f39ac3', 'User');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` varchar(12) NOT NULL,
  `alamat` text NOT NULL,
  `no_rekening` varchar(20) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `kode_item` varchar(6) NOT NULL,
  `nama_item` varchar(255) DEFAULT NULL,
  `jumlah_transaksi` bigint(20) NOT NULL,
  `total_transaksi` bigint(20) NOT NULL,
  `tgl_transaksi` datetime NOT NULL,
  `status_pesanan` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `harga_item` decimal(10,2) DEFAULT NULL,
  `status_pembayaran` enum('Diterima','Ditolak') DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `alamat`, `no_rekening`, `id_pengguna`, `kode_item`, `nama_item`, `jumlah_transaksi`, `total_transaksi`, `tgl_transaksi`, `status_pesanan`, `gambar`, `harga_item`, `status_pembayaran`, `bukti_pembayaran`) VALUES
('202407130002', '', '', 1, '003', 'Transporting Roller PU', 1000, 0, '2024-07-13 10:05:54', 'diterima', 'uploads/logo_unikom_kuning.png', 10000000.00, NULL, NULL),
('202407130003', '', '', 5, '005', 'Rubber Roll Belah', 20, 0, '2024-07-13 10:08:39', 'diterima', 'uploads/leo.jpg', 200000.00, NULL, 'uploads/IMG_20240517_194044.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`kode_item`) USING BTREE;

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`,`id_pengguna`,`kode_item`),
  ADD KEY `id_pengguna` (`id_pengguna`,`kode_item`),
  ADD KEY `transkodeitem_FK` (`kode_item`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transIDPeng_FK` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`),
  ADD CONSTRAINT `transkodeitem_FK` FOREIGN KEY (`kode_item`) REFERENCES `item` (`kode_item`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

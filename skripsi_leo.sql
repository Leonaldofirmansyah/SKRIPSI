-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Jul 2024 pada 21.01
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
-- Struktur dari tabel `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `nomor_telepon` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `alamat`, `nomor_telepon`) VALUES
(1, 1, 'cicaheum jalur langit9', '0812746728'),
(24, 41, 'dsbnadjdsjadvfhdsa', '654354576'),
(30, 47, 'SDCBLDSHAJCDSJC', '397213627036'),
(32, 49, 'kjsdfbdksf', '76567');

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE `item` (
  `kode_item` varchar(6) NOT NULL,
  `nama_item` varchar(120) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`kode_item`, `nama_item`, `gambar`) VALUES
('001', 'Rubber Seal', 'Picture1.jpg'),
('002', 'Besh Barrel Cot', 'Picture2.jpg'),
('003', 'Transporting Roller PU', 'Picture3.jpg'),
('004', 'Rubber Strip', 'Picture4.jpg'),
('005', 'Rubber Roll Belah', 'Picture5.jpg'),
('006', 'Bout Spiral Silicone', 'Picture6.jpg'),
('007', 'Rubber Padder', 'Picture7.jpg'),
('008', 'Rubber Strip Kulit Jeruk', 'Picture8.jpg'),
('009', 'Kopling PU', 'Picture9.jpg'),
('010', 'Rubber Kopling Bintang', 'Picture10.jpg'),
('011', 'Karbon Seal, O-ring Viton', 'Picture11.jpg'),
('012', 'Rubber Seal Oil', 'Picture12.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id_transaksi` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `kode_item` varchar(50) NOT NULL,
  `nama_item` varchar(100) NOT NULL,
  `jumlah_transaksi` int(11) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `role` enum('Admin','User','Produksi','Pengiriman','Pemilik') NOT NULL DEFAULT 'User',
  `token` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_lengkap`, `email`, `username`, `password`, `role`, `token`, `is_verified`) VALUES
(1, 'Leonaldo FIrmansyahh', 'leonaldofirmansyahh@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'User', NULL, 1),
(5, 'konsumen', 'leonaldofirmansyah123@gmail.com', 'konsumen', '94727b16c2221c188d39993e39f39ac3', 'User', NULL, 0),
(6, 'anjay', 'anjay@gmail.com', 'anjayy', 'ce9b3fce235a262d940d40c8d892dbc6', 'User', NULL, 0),
(41, 'akuadalahburung', 'leonaldo.10520076@mahasiswa.unikom.ac.id', '', '$2y$10$W1XUrXKJ4KywgycBapcRjeczzF1KeJcPctS3g2Z4BSSYPWZGNuSCq', 'User', 'b375e4c00ab63afd696296979177f9f6c3e10f62006fdab3be5554950afd6ad0fbfb6b1b6bd0a047d310b7be2e8b892be13d', 1),
(47, 'SJKDBCHDSACBLSHAJCD', 'leonaldofirmansyah@gmail.com', '', '$2y$10$5DedIhggm7WTMyUxhajML.PWYM1rlTTnP6uqtqq6M2dICP7EzcmGW', 'Admin', 'c7b3e56b4aa105587156f6d84b99e325fc95ce641971984b44f0ee7e87a9d68da45790bcfdf14d98770535633f337dc744f6', 1),
(49, 'totong', 'totongrohanda@gmail.com', '', '$2y$10$Rc3OZAoD.7hiHF7dnd3Fv.JXtMlB.wlVpup/o8fv1I9mwF5rbZnJa', 'User', 'ad2df7c3f2c54c6efa6c1a9feebd67b9d3f58a8ab1593d351ae13572154424b5f1b007948ef88fac6171862f0a41c83eb28d', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` varchar(12) NOT NULL,
  `alamat` mediumtext NOT NULL,
  `no_rekening` varchar(20) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `kode_item` varchar(6) NOT NULL,
  `nama_item` varchar(255) DEFAULT NULL,
  `jumlah_transaksi` bigint(20) NOT NULL,
  `total_transaksi` bigint(20) NOT NULL,
  `tgl_transaksi` datetime NOT NULL,
  `status_pesanan` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `harga_item` decimal(10,0) DEFAULT NULL,
  `status_pembayaran` enum('Diterima','Ditolak','Pending') DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `catatan` mediumtext DEFAULT NULL,
  `opsi_pembayaran` enum('DP','Lunas') DEFAULT 'DP',
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`kode_item`) USING BTREE;

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_transaksi`);

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
-- AUTO_INCREMENT untuk tabel `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_kode_item` FOREIGN KEY (`kode_item`) REFERENCES `item` (`kode_item`),
  ADD CONSTRAINT `transIDPeng_FK` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

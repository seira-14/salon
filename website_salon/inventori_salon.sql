-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Nov 2025 pada 07.34
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
-- Database: `inventori_salon`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `stok` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_supplier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `gambar`, `stok`, `harga`, `id_kategori`, `id_supplier`) VALUES
(1, 'Makarizo Shampoo Melon', NULL, 10, 65000.00, 1, 1),
(2, 'Makarizo Shampoo Honey', NULL, 12, 67000.00, 1, 1),
(3, 'Makarizo Shampoo Avocado', NULL, 11, 69000.00, 1, 1),
(4, 'Makarizo Shampoo Kiwi', NULL, 9, 71000.00, 1, 1),
(5, 'L’Oréal Repair Shampoo', NULL, 8, 95000.00, 1, 2),
(6, 'Makarizo Conditioner Melon', NULL, 14, 70000.00, 2, 1),
(7, 'Makarizo Conditioner Honey', NULL, 13, 72000.00, 2, 1),
(8, 'Makarizo Conditioner Avocado', NULL, 12, 74000.00, 2, 1),
(9, 'Makarizo Conditioner Kiwi', NULL, 10, 76000.00, 2, 1),
(10, 'L’Oréal Total Repair Conditioner', NULL, 9, 98000.00, 2, 2),
(11, 'Makarizo Hair Mask Melon', NULL, 8, 90000.00, 3, 1),
(12, 'Makarizo Hair Mask Honey', NULL, 9, 92000.00, 3, 1),
(13, 'Makarizo Hair Mask Avocado', NULL, 10, 94000.00, 3, 1),
(14, 'Makarizo Hair Mask Kiwi', NULL, 7, 96000.00, 3, 1),
(15, 'L’Oréal Deep Repair Mask', NULL, 6, 120000.00, 3, 2),
(16, 'Makarizo Cat Rambut Melon', NULL, 6, 85000.00, 4, 1),
(17, 'Makarizo Cat Rambut Honey', NULL, 7, 87000.00, 4, 1),
(18, 'Makarizo Cat Rambut Avocado', NULL, 5, 89000.00, 4, 1),
(19, 'Makarizo Cat Rambut Kiwi', NULL, 8, 91000.00, 4, 1),
(20, 'L’Oréal Premium Color', NULL, 7, 130000.00, 4, 2),
(21, 'Matrix Smoothing Cream', NULL, 6, 85000.00, 5, 2),
(22, 'Garnier Easy Smooth Cream', NULL, 8, 80000.00, 5, 3),
(23, 'Makarizo Smooth & Shine', NULL, 10, 78000.00, 5, 1),
(24, 'L’Oréal Expert Smoothing Cream', NULL, 5, 95000.00, 5, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Shampoo'),
(2, 'Conditioner'),
(3, 'Masker Rambut'),
(4, 'Cat Rambut'),
(5, 'Smoothing');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `no_telp`, `alamat`, `telepon`) VALUES
(1, 'PT Makarizo Sejahtera', '081234567890', 'Jl. Melati No.10, Bintaro', NULL),
(2, 'PT L’Oréal Indonesia', '081298765432', 'Jl. Mawar No.5, Tangerang', NULL),
(3, 'PT Garnier Beauty Care', '081212341234', 'Jl. Anggrek No.7, Jakarta Selatan', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `level` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama_lengkap`, `level`) VALUES
(1, 'admin', 'admin456', 'Administrator', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

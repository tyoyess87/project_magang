-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 26, 2025 at 07:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `olshop_skincare`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis_produk`
--

CREATE TABLE `jenis_produk` (
  `id_jenis_produk` int NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `alamat_tujuan` varchar(255) NOT NULL,
  `harga_barang` int NOT NULL,
  `data_dibuat` timestamp NOT NULL,
  `data_diedit` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int NOT NULL,
  `id_user` int NOT NULL,
  `id_produk` int NOT NULL,
  `kuantitas_barang` int NOT NULL,
  `total_barang` int NOT NULL,
  `data_dibuat` timestamp NOT NULL,
  `data_diedit` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `id_produk`, `kuantitas_barang`, `total_barang`, `data_dibuat`, `data_diedit`) VALUES
(96, 18, 4, 3, 5, '2025-01-21 03:43:27', '2025-02-11 10:51:08'),
(97, 18, 3, 2, 1, '2025-01-21 03:56:07', '2025-02-10 08:01:33'),
(98, 18, 2, 4, 1, '2025-01-21 07:02:59', '2025-02-10 09:09:32'),
(101, 17, 3, 2, 1, '2025-01-29 15:07:52', '2025-01-29 22:07:52'),
(102, 17, 4, 3, 1, '2025-01-29 15:07:59', '2025-01-29 22:07:59');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_user` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `biaya_ongkir` int NOT NULL,
  `tanggal_pembayaran` datetime NOT NULL,
  `total_harga` int NOT NULL,
  `data_dibuat` timestamp NOT NULL,
  `data_diedit` datetime NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `id_user` int NOT NULL,
  `id_produk` int NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kuantitas_barang` int NOT NULL,
  `total_harga` int NOT NULL,
  `harga_barang` int NOT NULL,
  `data_dibuat` timestamp NOT NULL,
  `data_diedit` datetime NOT NULL,
  `id_keranjang` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `harga_barang` int NOT NULL,
  `stok_barang` int NOT NULL,
  `data_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_diedit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_user` int DEFAULT NULL,
  `foto_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `kategori`, `harga_barang`, `stok_barang`, `data_dibuat`, `data_diedit`, `id_user`, `foto_produk`) VALUES
(2, 'Ceramik Skin Saviour Mointuzer Gel', 'SKINCARE', 52000, 100, '2024-12-19 04:02:27', '2024-12-18 20:54:42', NULL, 'Moisturizer_Gel_1.png'),
(3, 'Airy Poreless Powder Foundation', 'MAKEUP', 65000, 200, '2024-12-19 04:02:27', '2024-12-18 20:54:42', NULL, 'Airy_Poreless_Powder_Foundation.png'),
(4, 'Heartleaf Silky Moisture Suncream', 'SUNSCREEM', 72000, 300, '2024-12-19 04:02:27', '2024-12-18 20:54:42', NULL, 'suncream.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gmail` varchar(125) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `data_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_diedit` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `username`, `password`, `gmail`, `no_hp`, `alamat`, `data_dibuat`, `data_diedit`, `role`) VALUES
(6, 'bbb', 'tyokoplaxx21', '$2y$10$7kJLwi8oy0Ry78yiiEvmfetWqohXlUTNi0hbk54pAFJnSm/2.ROyS', 'yogg@gmail.com', '097399632986376', 'desa sambonggede rt 003 rw 005 kecamatan merakurak tuban', '2024-12-18 03:26:59', '2024-12-18 10:26:59', 'user'),
(7, 'yogaa', 'tyokoplaxx22', '$2y$10$VSSKQFBL9VYeG.0q6VvGBOWhXqH3aCtJg7FRYkBUfghufbime.rsq', 'yogg@gmail.com', '08633764687524', 'Kel. Doromukti Tuban', '2024-12-18 03:39:34', '2024-12-18 10:39:34', 'user'),
(8, 'hujhhjdf', 'po00', '$2y$10$ueDocVlnfKphruvymHSWR.sGgnnFz4eBzdDrBNjvHpnus2JKGCLQu', 'tyo@gmauil.com', '085732226739', 'Jl. Pramuka 12', '2024-12-18 03:41:23', '2024-12-18 10:41:23', 'user'),
(9, 'pp11', 'pp11', '$2y$10$YcYatSUJaCYFZX4.58FIxOGQivBegivYI534FvOJNxvFkw38l6UGa', 'yogg@gmail.com', '08733364276745', 'Kel. Doromukti Tuban', '2024-12-18 07:46:54', '2024-12-18 14:46:54', 'user'),
(10, 'pp', 'pp21', '$2y$10$Jmxwu0B6.f1VJVGlsOwwJODcvL8Pd9S5jYDvCwNmPswExC8SJIkE.', 'gjbsdj2', '221', 'jl.pramuka', '2024-12-19 03:01:16', '2024-12-19 10:01:16', 'user'),
(11, 'yonaaa', 'admin21', '21', 'gggg@gmail.com', '22224854868848', 'jl.jjjjjs', '2024-12-23 08:40:58', '2024-12-23 15:40:58', 'user'),
(12, 'ppqw11', 'pp12', '$2y$10$VvwVVp/F9XI0rvY.X.eJ0uC/zY9aE5.1JusMW02dTJ2b3c5hsYfl6', 'ghvdabkd@gnai.vo', '7621631469186412', 'msshvaajhxvhq', '2024-12-24 06:29:12', '2024-12-24 13:29:12', 'user'),
(13, 'jefkbkfB', 'F11', '$2y$10$Ue73TyqDMh60hZOYZNHF..sBAP.wnq0iWbJ8lEzWZY71o1nPla7zC', 'bfkab@gmail.com', 'u328423438', 'fmnxmk', '2024-12-24 06:31:53', '2024-12-24 13:31:53', 'user'),
(14, 'ppoi00', 'popo00', '$2y$10$EuCkkzXYLYIiPmAmuGQPZ.5wYfpeoqjDg.qffONEHIUN.5Ul82fEC', 'bvbc@gmail.com', '72139698639', 'mdke', '2024-12-24 06:42:10', '2024-12-24 13:42:10', 'user'),
(15, 'vgc,vHVX', 'poo99', '$2y$10$UbfcCQv8eSBIHBolJgKZCuqPIz1Br347ZT.pAABjbPYxgg2c7jzwC', 'hvjq@gmail.com', '8137126938', 'vh,jv', '2024-12-24 06:43:45', '2024-12-24 13:43:45', 'user'),
(16, 'pp', 'op11', '$2y$10$bv3/kwTi.Lcroj0qvsEekeTShqDob5RWEK/gwECruWdub9qTTQ5SC', 'hxh@gmail.com', '128-0381-491', 'chbK;BW', '2024-12-30 03:13:15', '2024-12-30 10:13:15', 'user'),
(17, 'yona11', 'yona11', '$2y$10$XzXaIdbu7Lgp8WFByAoN3.4ryYSQJuRQNPDCNkQy6/RNGBFNGxgCy', 'yona11@gmail.com', '0928934981273817', 'jl.pramuka', '2025-01-03 02:01:32', '2025-01-03 09:01:32', 'admin'),
(18, 'm.yona', 'yona', '$2y$10$J.4v0WvZzibRkMQMfOJaHe9Y/OExxCSdvNrl8Im855AGRkO86YFi.', 'kambang@gmail.com', '98725356', 'jl.pramuka', '2025-01-04 08:20:54', '2025-01-04 15:20:54', 'user'),
(19, 'kaka', 'kaka11', '$2y$10$TWnVL0WeWPqo/p/Ftf/zjeCspEJTo3gIsKrb/ygwFdyvLxktUo7.C', 'kaka12@gmail.com', '09847238748774', 'jl.pramuka', '2025-01-08 02:50:29', '2025-01-08 09:50:29', 'user'),
(20, 'kaka12', 'kaka12', '$2y$10$eiEAqlwNybK5FOxh8hCQMe3/GHgr.oFvbmlA8YcpKgC2jEn5.LRP6', 'akakbd@gmail.com', '830420374', ' ajdjahvuvwd', '2025-01-08 02:53:48', '2025-01-08 09:53:48', 'user'),
(21, 'kakak12', 'kakak12', '$2y$10$DhUHLySWeA8n97MBJaAhnuY.EQq60bH2hhHxOuTKwLVW02U9CfEhu', 'kakak12@gmail.com', '78473847', 'sn fj s', '2025-01-08 03:08:03', '2025-01-08 10:08:03', 'user'),
(22, 'jagung', 'jagung11', '$2y$10$kFd4CGpwpwp9JCpUEyX8O.nQSZHtUMj9zwFjBYmYPc9iBNHYVeYIu', 'akujagung@gmail.com', '0768442768376764', 'jl.pramuka', '2025-01-08 03:10:35', '2025-01-08 10:10:35', 'user'),
(23, 'Yoga Kurniawan', 'yga krniawan123', '$2y$10$i3uIYyP3JksLj9ptepFwc.vrNlpM.o02ZZyWl1eE49k/GxjgasOku', 'yogakurniawan@gmail.com', '083847080510', 'tuwiri wetn', '2025-01-17 06:52:02', '2025-01-17 13:52:02', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_produk`
--
ALTER TABLE `jenis_produk`
  ADD PRIMARY KEY (`id_jenis_produk`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `fk_id_pesanan` (`id_pesanan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `fk_userid_pesanan` (`id_user`),
  ADD KEY `fk_idproduk` (`id_produk`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `fk_userid_produk` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_produk`
--
ALTER TABLE `jenis_produk`
  MODIFY `id_jenis_produk` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_id_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_idproduk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_userid_pesanan` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_userid_produk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

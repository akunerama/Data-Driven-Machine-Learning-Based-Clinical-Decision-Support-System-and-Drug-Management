-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 06:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `obat_bpk`
--

-- --------------------------------------------------------

--
-- Table structure for table `is_obat`
--

CREATE TABLE `is_obat` (
  `kode_obat` varchar(7) NOT NULL,
  `nama_obat` varchar(50) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `stok` int(11) NOT NULL,
  `created_user` int(3) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_user` int(3) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tanggal_masuk` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `is_obat`
--

INSERT INTO `is_obat` (`kode_obat`, `nama_obat`, `satuan`, `stok`, `created_user`, `created_date`, `updated_user`, `updated_date`, `tanggal_masuk`) VALUES
('B000001', 'Siladex', 'S000004', 0, 1, '2025-05-23 15:13:54', 1, '2025-05-23 15:13:54', NULL),
('B000002', 'Paracetamol', 'S000001', 30, 1, '2025-05-23 15:14:06', 1, '2025-06-05 06:17:48', NULL),
('B000003', 'Dumin', 'S000001', 28, 1, '2025-05-23 15:14:15', 1, '2025-06-05 06:12:42', NULL),
('B000004', 'Actived', 'S000004', 13, 1, '2025-05-23 15:14:45', 1, '2025-05-24 12:48:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `is_obat_expired`
--

CREATE TABLE `is_obat_expired` (
  `expired_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `is_obat_keluar`
--

CREATE TABLE `is_obat_keluar` (
  `id_keluar` varchar(10) NOT NULL,
  `kode_transaksi` varchar(15) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `nip` varchar(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `diagnosa` text DEFAULT NULL,
  `kode_obat` varchar(7) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `created_user` int(3) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expired_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `is_obat_keluar`
--

INSERT INTO `is_obat_keluar` (`id_keluar`, `kode_transaksi`, `tanggal_keluar`, `nip`, `nama`, `diagnosa`, `kode_obat`, `jumlah_keluar`, `created_user`, `created_date`, `expired_date`) VALUES
('ik_00001', 'TK-2025-0000001', '2025-05-30', '09040557755', '', 'P', 'B000004', 2, 1, '2025-05-24 12:44:00', '2027-12-24'),
('ik_00002', 'TK-2025-0000002', '2025-05-31', '0804566945', '', 'Pus', 'B000003', 2, 1, '2025-05-24 12:44:21', '2027-11-30');

-- --------------------------------------------------------

--
-- Table structure for table `is_obat_masuk`
--

CREATE TABLE `is_obat_masuk` (
  `kode_transaksi` varchar(15) NOT NULL,
  `kode_obat` varchar(7) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `created_user` int(3) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expired_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `is_obat_masuk`
--

INSERT INTO `is_obat_masuk` (`kode_transaksi`, `kode_obat`, `tanggal_masuk`, `jumlah_masuk`, `created_user`, `created_date`, `expired_date`) VALUES
('TM-2025-0000001', 'B000004', '2025-05-24', 8, 1, '2025-05-24 12:44:00', '2027-12-24'),
('TM-2025-0000002', 'B000003', '2025-05-25', 8, 1, '2025-05-24 12:44:21', '2027-11-30'),
('TM-2025-0000003', 'B000002', '2025-05-28', 10, 1, '2025-05-24 12:41:33', '2027-11-24'),
('TM-2025-0000004', 'B000004', '2025-06-18', 5, 1, '2025-05-24 12:48:59', '2028-06-13'),
('TM-2025-0000005', 'B000003', '2025-06-12', 10, 1, '2025-05-24 12:49:53', '2027-11-25'),
('TM-2025-0000006', 'B000003', '2025-06-30', 5, 10, '2025-05-27 03:16:22', '2027-09-20'),
('TM-2025-0000007', 'B000002', '2025-07-08', 5, 10, '2025-05-27 03:21:11', '2027-12-22'),
('TM-2025-0000008', 'B000003', '2025-05-21', 5, 10, '2025-06-05 06:12:42', '2026-10-05'),
('TM-2025-0000009', 'B000002', '2025-06-05', 5, 10, '2025-06-05 06:16:49', '2028-11-15'),
('TM-2025-0000010', 'B000002', '2025-05-21', 10, 10, '2025-06-05 06:17:48', '2028-11-15');

-- --------------------------------------------------------

--
-- Table structure for table `is_pasien`
--

CREATE TABLE `is_pasien` (
  `nip` varchar(11) NOT NULL,
  `nama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `is_pasien`
--

INSERT INTO `is_pasien` (`nip`, `nama`) VALUES
('0804566945', 'Azmi'),
('09040557755', 'Akbar');

-- --------------------------------------------------------

--
-- Table structure for table `is_satuan`
--

CREATE TABLE `is_satuan` (
  `id_satuan` varchar(11) NOT NULL,
  `nama_satuan` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `is_satuan`
--

INSERT INTO `is_satuan` (`id_satuan`, `nama_satuan`, `created_at`) VALUES
('S000001', 'Tablet', '2025-05-23 03:56:00'),
('S000002', 'Tube', '2025-05-23 14:53:02'),
('S000003', 'Strip', '2025-05-23 14:53:08'),
('S000004', 'Sirup', '2025-05-23 14:53:29');

-- --------------------------------------------------------

--
-- Table structure for table `is_stok`
--

CREATE TABLE `is_stok` (
  `kode_obat` varchar(50) DEFAULT NULL,
  `transaksi_masuk` varchar(50) DEFAULT NULL,
  `jml_masuk_per_exp` varchar(50) DEFAULT NULL,
  `jml_keluar_per_exp` varchar(50) DEFAULT NULL,
  `stok_per_exp` varchar(50) DEFAULT NULL,
  `exp_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `is_stok`
--

INSERT INTO `is_stok` (`kode_obat`, `transaksi_masuk`, `jml_masuk_per_exp`, `jml_keluar_per_exp`, `stok_per_exp`, `exp_date`) VALUES
('B000004', 'TM-2025-0000001', '10', NULL, '8', '2027-12-24'),
('B000003', 'TM-2025-0000002', '10', NULL, '8', '2027-11-30'),
('B000002', 'TM-2025-0000003', '10', NULL, '10', '2027-11-24'),
('B000004', 'TM-2025-0000004', '5', NULL, '5', '2028-06-13'),
('B000003', 'TM-2025-0000005', '10', NULL, '10', '2027-11-25'),
('B000003', 'TM-2025-0000006', '5', NULL, '5', '2027-09-20'),
('B000002', 'TM-2025-0000007', '5', NULL, '5', '2027-12-22'),
('B000003', 'TM-2025-0000008', '5', NULL, '5', '2026-10-05'),
('B000002', 'TM-2025-0000009', '5', NULL, '5', '2028-11-15'),
('B000002', 'TM-2025-0000010', '10', NULL, '10', '2028-11-15');

-- --------------------------------------------------------

--
-- Table structure for table `is_users`
--

CREATE TABLE `is_users` (
  `id_user` int(3) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_user` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telepon` varchar(13) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `hak_akses` enum('Super Admin','Manajer','Gudang') NOT NULL,
  `status` enum('aktif','blokir') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `is_users`
--

INSERT INTO `is_users` (`id_user`, `username`, `nama_user`, `password`, `email`, `telepon`, `foto`, `hak_akses`, `status`, `created_at`, `updated_at`) VALUES
(1, 'liok', 'lio kusnata', '5705d54eaba0eae11e9818ac16be924b', 'liokusnata321@gmail.com', '083875222635', 'Remini20211202144350586.jpg', 'Super Admin', 'aktif', '2017-04-01 15:15:15', '2024-11-11 14:05:30'),
(9, 'mankus', 'Kusman', 'bea69e37831f7772550d600defe0498d', '', '', NULL, 'Super Admin', 'blokir', '2025-05-21 04:43:13', '2025-05-26 02:22:24'),
(10, 'rama', 'Admin Rama', 'e00cf25ad42683b3df678c61f42c6bda', '', '', 'WhatsApp Image 2024-06-05 at 21.25.54.jpeg', 'Super Admin', 'aktif', '2025-05-26 02:20:11', '2025-05-27 03:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `is_view_masuk`
--

CREATE TABLE `is_view_masuk` (
  `kode_transaksi` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kode_obat` varchar(7) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `created_user` int(3) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expired_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `is_view_masuk`
--

INSERT INTO `is_view_masuk` (`kode_transaksi`, `kode_obat`, `tanggal_masuk`, `jumlah_masuk`, `created_user`, `created_date`, `expired_date`) VALUES
('TM-2025-0000001', 'B000004', '2025-05-24', 10, 1, '2025-05-24 12:40:38', '2027-12-24'),
('TM-2025-0000002', 'B000003', '2025-05-25', 10, 1, '2025-05-24 12:41:02', '2027-11-30'),
('TM-2025-0000003', 'B000002', '2025-05-28', 10, 1, '2025-05-24 12:41:33', '2027-11-24'),
('TM-2025-0000004', 'B000004', '2025-06-18', 5, 1, '2025-05-24 12:48:59', '2028-06-13'),
('TM-2025-0000005', 'B000003', '2025-06-12', 10, 1, '2025-05-24 12:49:53', '2027-11-25'),
('TM-2025-0000006', 'B000003', '2025-06-30', 5, 10, '2025-05-27 03:16:22', '2027-09-20'),
('TM-2025-0000007', 'B000002', '2025-07-08', 5, 10, '2025-05-27 03:21:11', '2027-12-22'),
('TM-2025-0000008', 'B000003', '2025-05-21', 5, 10, '2025-06-05 06:12:42', '2026-10-05'),
('TM-2025-0000009', 'B000002', '2025-06-05', 5, 10, '2025-06-05 06:16:49', '2028-11-15'),
('TM-2025-0000010', 'B000002', '2025-05-21', 10, 10, '2025-06-05 06:17:48', '2028-11-15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `is_obat`
--
ALTER TABLE `is_obat`
  ADD PRIMARY KEY (`kode_obat`),
  ADD KEY `created_user` (`created_user`),
  ADD KEY `updated_user` (`updated_user`),
  ADD KEY `is_obat_masuk` (`tanggal_masuk`);

--
-- Indexes for table `is_obat_keluar`
--
ALTER TABLE `is_obat_keluar`
  ADD PRIMARY KEY (`id_keluar`);

--
-- Indexes for table `is_obat_masuk`
--
ALTER TABLE `is_obat_masuk`
  ADD PRIMARY KEY (`kode_transaksi`),
  ADD KEY `id_barang` (`kode_obat`),
  ADD KEY `created_user` (`created_user`),
  ADD KEY `expired_date` (`expired_date`);

--
-- Indexes for table `is_pasien`
--
ALTER TABLE `is_pasien`
  ADD PRIMARY KEY (`nip`);

--
-- Indexes for table `is_satuan`
--
ALTER TABLE `is_satuan`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `is_users`
--
ALTER TABLE `is_users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `level` (`hak_akses`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `is_users`
--
ALTER TABLE `is_users`
  MODIFY `id_user` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `is_obat`
--
ALTER TABLE `is_obat`
  ADD CONSTRAINT `is_obat_ibfk_1` FOREIGN KEY (`created_user`) REFERENCES `is_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `is_obat_ibfk_2` FOREIGN KEY (`updated_user`) REFERENCES `is_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `is_obat_masuk`
--
ALTER TABLE `is_obat_masuk`
  ADD CONSTRAINT `fk_obat_masuk` FOREIGN KEY (`kode_obat`) REFERENCES `is_obat` (`kode_obat`) ON UPDATE CASCADE,
  ADD CONSTRAINT `is_obat_masuk_ibfk_1` FOREIGN KEY (`kode_obat`) REFERENCES `is_obat` (`kode_obat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `is_obat_masuk_ibfk_2` FOREIGN KEY (`created_user`) REFERENCES `is_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

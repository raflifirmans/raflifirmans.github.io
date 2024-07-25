-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 22, 2021 at 05:37 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meeting_panrb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_book`
--

CREATE TABLE `tb_book` (
  `id_book` varchar(50) NOT NULL,
  `judul_meeting` varchar(250) DEFAULT NULL,
  `pemohon` varchar(50) DEFAULT NULL,
  `snack` varchar(50) DEFAULT NULL,
  `makan_siang` varchar(50) DEFAULT NULL,
  `jumlah_peserta` varchar(50) DEFAULT NULL,
  `pic` varchar(50) DEFAULT NULL,
  `notelp_pic` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `catatan` varchar(500) DEFAULT NULL,
  `id_ruang` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_book`
--

INSERT INTO `tb_book` (`id_book`, `judul_meeting`, `pemohon`, `snack`, `makan_siang`, `jumlah_peserta`, `pic`, `notelp_pic`, `tanggal`, `jam_mulai`, `jam_selesai`, `catatan`, `id_ruang`, `id_user`) VALUES
('DC/18-28/001', 'Rapat Dinas', 'Diskominfo Lebak', 'ya', 'ya', '5', 'M Chandra Nugraha', '083878635951', '2021-05-29', '08:00:00', '09:00:00', 'Harap membawa laptop', 1, 1),
('DC/18-28/002', 'Rapat Pengembangan Infrastuktur TI', 'Biro HUKIP', 'ya', 'tidak', '20', 'Tito', '081287287103', '2021-11-10', '10:00:00', '12:00:00', '', 1, 1),
('DC/18-28/003', 'Rapat Pengembangan Infrastuktur TI', 'Biro HUKIP', 'ya', 'ya', '20', 'Tito', '081287287103', '2021-11-21', '08:00:00', '09:00:00', '', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_ruang`
--

CREATE TABLE `tb_ruang` (
  `id_ruang` int(11) NOT NULL,
  `lantai_ruang` varchar(50) DEFAULT NULL,
  `gedung` varchar(50) DEFAULT NULL,
  `nama_ruang` varchar(50) DEFAULT NULL,
  `kapasitas` varchar(50) DEFAULT NULL,
  `ketersediaan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_ruang`
--

INSERT INTO `tb_ruang` (`id_ruang`, `lantai_ruang`, `gedung`, `nama_ruang`, `kapasitas`, `ketersediaan`) VALUES
(1, '2', 'Kementerian PANRB', 'Ruang Rapat MajaPahit', '50', 'tersedia'),
(11, '2', 'Kementerian PANRB', 'Ruang Rapat Sriwijaya', '100', 'tersedia'),
(12, '1', 'Kementerian PANRB', 'Ruang Media Center', '10', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `no_telp` varchar(50) NOT NULL DEFAULT '0',
  `nama_user` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `no_telp`, `nama_user`, `password`, `role`) VALUES
(1, '083878635951', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(9, 'demoadmin', 'demoadmin', '61152c80d1514e22fba66002597d0104', 'user'),
(10, 'demouser', 'demouser', '91017d590a69dc49807671a51f10ab7f', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tb_waktu`
--

CREATE TABLE `tb_waktu` (
  `id_book` varchar(50) DEFAULT NULL,
  `waktu` datetime DEFAULT NULL,
  `id_ruang` int(11) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_waktu`
--

INSERT INTO `tb_waktu` (`id_book`, `waktu`, `id_ruang`, `tipe`) VALUES
('DC/18-28/001', '2021-05-29 08:30:00', 1, NULL),
('DC/18-28/002', '2021-11-10 11:00:00', 1, NULL),
('DC/18-28/003', '2021-11-21 08:30:00', 11, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_book`
--
ALTER TABLE `tb_book`
  ADD PRIMARY KEY (`id_book`),
  ADD KEY `fkbookruang` (`id_ruang`),
  ADD KEY `fkbookuser` (`id_user`);

--
-- Indexes for table `tb_ruang`
--
ALTER TABLE `tb_ruang`
  ADD PRIMARY KEY (`id_ruang`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tb_waktu`
--
ALTER TABLE `tb_waktu`
  ADD KEY `fkwaktubook` (`id_book`),
  ADD KEY `fkwakturuang` (`id_ruang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_ruang`
--
ALTER TABLE `tb_ruang`
  MODIFY `id_ruang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_book`
--
ALTER TABLE `tb_book`
  ADD CONSTRAINT `fkbookruang` FOREIGN KEY (`id_ruang`) REFERENCES `tb_ruang` (`id_ruang`),
  ADD CONSTRAINT `fkbookuser` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_waktu`
--
ALTER TABLE `tb_waktu`
  ADD CONSTRAINT `fkwaktubook` FOREIGN KEY (`id_book`) REFERENCES `tb_book` (`id_book`),
  ADD CONSTRAINT `fkwakturuang` FOREIGN KEY (`id_ruang`) REFERENCES `tb_ruang` (`id_ruang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

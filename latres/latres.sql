-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 03:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `latres`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `IDJadwal` varchar(5) NOT NULL,
  `NamaPesawat` varchar(20) NOT NULL,
  `KotaAwal` varchar(256) NOT NULL,
  `KotaAkhir` varchar(256) NOT NULL,
  `WaktuBerangkat` datetime NOT NULL,
  `WaktuSampai` datetime NOT NULL,
  `JumlahKursi` int(3) NOT NULL,
  `KursiTerisi` int(3) NOT NULL,
  `Harga` int(20) NOT NULL,
  `Status` enum('available','cancelled','delayed') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`IDJadwal`, `NamaPesawat`, `KotaAwal`, `KotaAkhir`, `WaktuBerangkat`, `WaktuSampai`, `JumlahKursi`, `KursiTerisi`, `Harga`, `Status`) VALUES
('SA123', 'SurabayaAir', 'Yogyakarta', 'Surabaya', '2025-11-18 11:20:00', '2025-11-18 14:20:00', 1, 1, 100, ''),
('SJ303', 'Sriwijaya Air', 'Yogyakarta', 'Jakarta', '2024-11-22 14:15:00', '2024-11-22 16:15:00', 160, 147, 400000, 'delayed');

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `IDTiket` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `IDJadwal` varchar(50) NOT NULL,
  `NamaPenumpang` varchar(100) NOT NULL,
  `NomorIdentitas` varchar(50) NOT NULL,
  `TanggalPesan` datetime DEFAULT current_timestamp(),
  `Status` varchar(20) DEFAULT 'Confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket`
--

INSERT INTO `tiket` (`IDTiket`, `UserID`, `IDJadwal`, `NamaPenumpang`, `NomorIdentitas`, `TanggalPesan`, `Status`) VALUES
(3, 1, 'SJ303', 'halah', '432', '2025-11-18 10:48:47', 'Confirmed'),
(4, 4, 'SJ303', 'qqqq', 'qwerty', '2025-11-18 11:14:27', 'Confirmed'),
(5, 1, 'SA123', 'a', 'a', '2025-11-18 12:28:02', 'Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FullName` varchar(50) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Role` enum('admin','user') NOT NULL DEFAULT 'user',
  `Email` varchar(30) NOT NULL,
  `Password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `Username`, `Role`, `Email`, `Password`) VALUES
(1, 'ass', 'a', 'user', 'a@gmail.com', '$2y$10$d8SO6GMB9.ueERrmB.0AmOM1QatMENX5ZNJwCu5ZjvOGTKvus5RPq'),
(2, 'admin1', 'admin', 'admin', 'admin@gmail.com', '$2y$10$0Lpv0HrjN9fJcuRFHnG/MurhSstd/WdyjAJhKGelFhcAm/EGMkv0W'),
(3, 'cihuy', 'amang', 'user', 'cihuy@gmail.com', '$2y$10$0GOEzB8GbvFzxBKo7K6HMO58Fd3mDbLCkkgby6rQBth9ucckJsOJi'),
(4, 'qqq', 'q', 'user', 'q@gmail', '$2y$10$Xb3ysx1RoCRcgNZh7/Xae.W9cSSW.XHbiWRi91E5kH90N0O6oN3U.'),
(5, 'c', 'c', 'user', 'c@m', '$2y$10$.Y81E1bKrao17P24S5a50u6R0gUQPqjDQmFLApogOErnHKri3hOca'),
(6, 'i', 'i', 'user', 'i@i', '$2y$10$HIpvLje2VdiMTlpMP9Kz2u0BFWgekmD74kg6f2ft5B341JgdlWRxa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`IDJadwal`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`IDTiket`),
  ADD KEY `fk_user` (`UserID`),
  ADD KEY `fk_jadwal` (`IDJadwal`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `IDTiket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `fk_jadwal` FOREIGN KEY (`IDJadwal`) REFERENCES `jadwal` (`IDJadwal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

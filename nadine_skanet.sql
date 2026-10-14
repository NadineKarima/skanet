-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2026 at 05:47 PM
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
-- Database: `nadine_skanet`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_admin`, `created_at`) VALUES
(1, 'admin', '$2y$10$gzbBtesm/Q8sP.0PKP3zBuNpqMJQyiM21K7KquObqhoccidkgJRZG', 'Administrator', '2026-08-01 15:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `status` enum('Aktif','Nonaktif') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `nip`, `username`, `password`, `nama_guru`, `jenis_kelamin`, `alamat`, `no_hp`, `status`, `created_at`) VALUES
(5, '08976598', 'agung', '$2y$10$Am97ygH7d8FfzIfm0CnLiObGa3DDMNLLrNskGcEAzpgYojqs982cO', 'Agung Cahyono', 'L', 'Magetan', '0897965876', 'Aktif', '2026-08-06 07:19:38'),
(2, '8638141', 'hermin', '$2y$10$ECNVPQhAR5cWFoHcfJxXOeXE5Z5zkzU1QWkb4apQd9gewCZHOLa.W', 'Hermin', 'P', 'Kuwonharjo', '08769564321', 'Aktif', '2026-08-06 02:06:17'),
(3, '8974913864', 'yusuf', '$2y$10$0qaK9atFJ6ykuTSIKOzIBeKlGLlKokuOV38ii1B7fwxuVPhsHBoiW', 'Muhammad Jusuf', 'L', 'Sukomoro', '0897659843', 'Aktif', '2026-08-06 06:53:27'),
(1, '9028340927', 'heru', '$2y$10$P2gHPbAhqrVBkdG7Ltg3M.P9RQLkC0mlOa5hoOWZ65dFL/D022zEa', 'Heru Prabawa', 'L', 'Magetan', '087654321912', 'Aktif', '2026-08-05 13:50:43'),
(4, '90890731', 'sulis', '$2y$10$YL6um.KUSvOBX9yO2r40Gu7/bK5MhyedA6By9/uvBdaJul.MVGQGO', 'Sulistiawati', 'P', 'Belotan', '087356135162', 'Aktif', '2026-08-06 06:56:54'),
(7, '9274983284', 'imron', '$2y$10$G1UIgtHSNtz1wJGN.AONIOO2V1zt3M9v94Fr3h5hLrf.VL3sXtgS6', 'Imron Budi', 'L', 'Magetan', '08764732742131', 'Aktif', '2026-08-12 02:20:32'),
(6, '9724224255', 'yopi', '$2y$10$ab70Va3DS7S.37PHxSW/YOEMu1ZpZpM61lvJcp9Y1KuR8hcbJZyS2', 'Yopi', 'L', 'Kawedanan', '089734567231', 'Aktif', '2026-08-06 13:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `guru_mapel`
--

CREATE TABLE `guru_mapel` (
  `id` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru_mapel`
--

INSERT INTO `guru_mapel` (`id`, `id_guru`, `id_mapel`) VALUES
(1, 1, 1),
(9, 2, 15),
(3, 3, 3),
(4, 4, 5),
(8, 5, 4),
(10, 6, 16),
(7, 7, 10);

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `nama_jurusan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`) VALUES
(3, 'DKV'),
(4, 'Kuliner'),
(1, 'RPL'),
(2, 'TKJ');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `tingkat` enum('X','XI','XII') NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `rombel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `tingkat`, `id_jurusan`, `rombel`) VALUES
(28, 'X', 1, '1'),
(29, 'X', 1, '2'),
(34, 'X', 2, '1'),
(35, 'X', 2, '2'),
(37, 'X', 2, '3'),
(13, 'X', 3, '1'),
(14, 'X', 3, '2'),
(15, 'X', 3, '3'),
(22, 'X', 4, '1'),
(23, 'X', 4, '2'),
(30, 'XI', 1, '1'),
(31, 'XI', 1, '2'),
(38, 'XI', 2, '1'),
(43, 'XI', 2, '2'),
(39, 'XI', 2, '3'),
(16, 'XI', 3, '1'),
(17, 'XI', 3, '2'),
(18, 'XI', 3, '3'),
(24, 'XI', 4, '1'),
(25, 'XI', 4, '2'),
(32, 'XII', 1, '1'),
(33, 'XII', 1, '2'),
(40, 'XII', 2, '1'),
(41, 'XII', 2, '2'),
(42, 'XII', 2, '3'),
(21, 'XII', 3, '1'),
(20, 'XII', 3, '2'),
(19, 'XII', 3, '3'),
(26, 'XII', 4, '1'),
(27, 'XII', 4, '2');

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int(11) NOT NULL,
  `kode_mapel` varchar(20) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kkm` int(11) DEFAULT 75,
  `status` enum('Aktif','Nonaktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `kode_mapel`, `nama_mapel`, `kkm`, `status`) VALUES
(1, 'MTK', 'MTK', 75, 'Aktif'),
(3, 'PAI', 'PAI', 75, 'Aktif'),
(4, 'BIN', 'Bahasa Indonesia', 75, 'Aktif'),
(5, 'BIG', 'Bahasa Inggris', 75, 'Aktif'),
(6, 'IPAS', 'IPAS', 75, 'Aktif'),
(7, 'BJW', 'Bahasa Jawa', 75, 'Aktif'),
(9, 'KKA', 'KKA', 75, 'Aktif'),
(10, 'INF', 'Informatika', 75, 'Aktif'),
(11, 'DPK', 'DPK', 75, 'Aktif'),
(12, 'MPL', 'MaPil', 75, 'Aktif'),
(15, 'PP', 'PPKn', 75, 'Aktif'),
(16, 'PJOK', 'PJOK', 75, 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_tahun` int(11) NOT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT 0.00,
  `predikat` varchar(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_siswa`, `id_mapel`, `id_guru`, `id_tahun`, `nilai_akhir`, `predikat`, `created_at`) VALUES
(1, 1, 5, 4, 1, 85.50, 'B', '2026-08-13 07:02:42'),
(3, 14, 5, 4, 1, 75.80, 'C', '2026-08-13 07:03:18'),
(6, 15, 5, 4, 1, 77.50, 'C', '2026-08-13 07:03:26'),
(13, 16, 5, 4, 1, 84.70, 'B', '2026-08-13 07:03:57'),
(18, 4, 5, 4, 1, 86.90, 'B', '2026-08-13 07:04:18'),
(23, 17, 5, 4, 1, 87.70, 'B', '2026-08-13 07:05:40'),
(24, 2, 5, 4, 1, 82.50, 'B', '2026-08-13 07:05:40'),
(25, 9, 5, 4, 1, 79.20, 'C', '2026-08-13 07:05:40'),
(26, 3, 5, 4, 1, 88.30, 'B', '2026-08-13 07:05:40'),
(28, 5, 5, 4, 1, 83.50, 'B', '2026-08-13 07:05:40'),
(30, 1, 1, 1, 1, 90.00, 'A', '2026-08-16 09:50:10'),
(33, 14, 1, 1, 1, 56.00, 'D', '2026-08-16 10:02:19'),
(34, 15, 1, 1, 1, 23.00, 'D', '2026-08-16 10:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `preferensi_user`
--

CREATE TABLE `preferensi_user` (
  `id_preferensi` int(11) NOT NULL,
  `user_type` enum('admin','guru') NOT NULL,
  `user_id` int(11) NOT NULL,
  `dark_mode` tinyint(1) DEFAULT 0,
  `sidebar_collapsed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `preferensi_user`
--

INSERT INTO `preferensi_user` (`id_preferensi`, `user_type`, `user_id`, `dark_mode`, `sidebar_collapsed`) VALUES
(1, 'guru', 1, 0, 0),
(10, 'admin', 1, 0, 0),
(37, 'guru', 6, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sekolah`
--

CREATE TABLE `sekolah` (
  `id_sekolah` int(11) NOT NULL,
  `nama_sekolah` varchar(150) NOT NULL,
  `npsn` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `kepala_sekolah` varchar(100) DEFAULT NULL,
  `nip_kepala` varchar(30) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sekolah`
--

INSERT INTO `sekolah` (`id_sekolah`, `nama_sekolah`, `npsn`, `alamat`, `telepon`, `email`, `kepala_sekolah`, `nip_kepala`, `logo`) VALUES
(1, 'SMK Negeri Takeran', '20103040', 'Desa Kuwonharjo, Kecamatan Takeran, Kabupaten Magetan, Jawa Timur, kode pos 63383', '(0351) 439094', 'smkn1takeran@gmail.com', 'Rukayah, M.Pd.I', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id_setting` int(11) NOT NULL,
  `user_type` enum('admin','guru') NOT NULL,
  `user_id` int(11) NOT NULL,
  `dark_mode` tinyint(1) DEFAULT 0,
  `sidebar_collapsed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text DEFAULT NULL,
  `status` enum('Aktif','Lulus','Pindah') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nama_lengkap`, `jenis_kelamin`, `alamat`, `status`, `created_at`) VALUES
(1, '0930980723', 'Malik Abdul', 'L', 'Jomblang', 'Aktif', '2026-08-05 13:52:37'),
(3, '27389749835', 'Muji Burohkhim', 'L', 'Magetan', 'Aktif', '2026-08-05 13:54:01'),
(22, '342567127', 'Jessica Chandra', 'P', 'Madiun', 'Aktif', '2026-08-06 07:15:51'),
(5, '467657269', 'Noviana Nur', 'P', 'Madiun', 'Aktif', '2026-08-05 13:55:12'),
(24, '5462564', 'Angelina Christy', 'P', 'Setren', 'Aktif', '2026-08-06 07:17:30'),
(17, '64732673258', 'Muhammad Hidayat', 'L', 'Lembeyan', 'Aktif', '2026-08-06 07:09:25'),
(6, '647692982', 'Rennatha Sulistyana', 'P', 'Kepuhrejo', 'Aktif', '2026-08-05 13:56:07'),
(18, '6586542', 'Raisha', 'P', 'Ngawi', 'Aktif', '2026-08-06 07:10:23'),
(26, '6743647364', 'Indah Cahya', 'P', 'Ngawi', 'Aktif', '2026-08-12 06:15:15'),
(19, '687324587', 'Aulia Rahmawati', 'P', 'Madiun', 'Aktif', '2026-08-06 07:13:23'),
(20, '76239721', 'Agam Chandra', 'L', 'Magetan', 'Aktif', '2026-08-06 07:13:53'),
(16, '763213984', 'Muhammad Hanif', 'L', 'Barat', 'Aktif', '2026-08-06 07:08:37'),
(23, '76371341', 'Nathanael Anggara', 'L', 'Ponorogo', 'Aktif', '2026-08-06 07:16:41'),
(8, '76487224324', 'Shilvanes Nahara', 'P', 'Magetan', 'Aktif', '2026-08-05 13:57:38'),
(12, '7649724', 'Erika', 'P', 'Garon', 'Aktif', '2026-08-05 14:01:40'),
(15, '76762413', 'Muhammad Alfin', 'L', 'Magetan', 'Aktif', '2026-08-06 07:08:10'),
(9, '7692798274', 'Muhammad Rifky', 'L', 'Belotan', 'Aktif', '2026-08-05 13:58:17'),
(21, '78437029374', 'Freyana Shifa', 'P', 'Madiun', 'Aktif', '2026-08-06 07:14:55'),
(7, '787236429', 'Shafira Kurniawati', 'P', 'Garon', 'Aktif', '2026-08-05 13:56:43'),
(4, '8734653', 'Nadine Karima', 'P', 'Setren', 'Aktif', '2026-08-05 13:54:43'),
(10, '87398834', 'Usup Mega', 'L', 'Pojok', 'Aktif', '2026-08-05 13:59:03'),
(14, '8873874', 'Maulana Ardan', 'L', 'Magetan', 'Aktif', '2026-08-06 07:06:53'),
(2, '972937492', 'Muhammad Raffi', 'L', 'Madigondo', 'Aktif', '2026-08-05 13:53:14'),
(13, '98742941', 'Khanza', 'P', 'Kawedanan', 'Aktif', '2026-08-06 01:58:05'),
(25, '989374273', 'Christian Manuel', 'L', 'Maospati', 'Aktif', '2026-08-06 07:35:14'),
(11, '992649263', 'Raden Panji', 'L', 'Magetan', 'Aktif', '2026-08-05 13:59:48');

-- --------------------------------------------------------

--
-- Table structure for table `siswa_kelas`
--

CREATE TABLE `siswa_kelas` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_tahun` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa_kelas`
--

INSERT INTO `siswa_kelas` (`id`, `id_siswa`, `id_kelas`, `id_tahun`) VALUES
(1, 1, 31, 1),
(2, 2, 31, 1),
(3, 3, 31, 1),
(4, 4, 31, 1),
(5, 5, 31, 1),
(6, 6, 31, 1),
(7, 7, 31, 1),
(8, 8, 31, 1),
(9, 9, 31, 1),
(10, 10, 31, 1),
(11, 11, 31, 1),
(12, 12, 30, 1),
(14, 13, 29, 1),
(15, 14, 31, 1),
(16, 15, 31, 1),
(17, 16, 31, 1),
(18, 17, 31, 1),
(19, 18, 31, 1),
(20, 19, 30, 1),
(21, 20, 30, 1),
(22, 21, 16, 1),
(23, 22, 17, 1),
(24, 23, 18, 1),
(25, 24, 33, 1),
(26, 25, 32, 1),
(27, 26, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id_tahun` int(11) NOT NULL,
  `tahun` varchar(20) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `status` enum('Aktif','Nonaktif') DEFAULT 'Nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id_tahun`, `tahun`, `semester`, `status`) VALUES
(1, '2026/2027', 'Ganjil', 'Aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`nip`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `id_guru_unik` (`id_guru`);

--
-- Indexes for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_guru` (`id_guru`,`id_mapel`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`),
  ADD UNIQUE KEY `nama_jurusan` (`nama_jurusan`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD UNIQUE KEY `kelas_unik` (`tingkat`,`id_jurusan`,`rombel`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`),
  ADD UNIQUE KEY `kode_mapel` (`kode_mapel`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD UNIQUE KEY `nilai_unik` (`id_siswa`,`id_mapel`,`id_tahun`),
  ADD KEY `id_mapel` (`id_mapel`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_tahun` (`id_tahun`);

--
-- Indexes for table `preferensi_user`
--
ALTER TABLE `preferensi_user`
  ADD PRIMARY KEY (`id_preferensi`),
  ADD UNIQUE KEY `user_unik` (`user_type`,`user_id`);

--
-- Indexes for table `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id_sekolah`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`),
  ADD UNIQUE KEY `id_siswa_unik` (`id_siswa`);

--
-- Indexes for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_siswa` (`id_siswa`,`id_tahun`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_tahun` (`id_tahun`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id_tahun`),
  ADD UNIQUE KEY `tahun_semester` (`tahun`,`semester`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `preferensi_user`
--
ALTER TABLE `preferensi_user`
  MODIFY `id_preferensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `sekolah`
--
ALTER TABLE `sekolah`
  MODIFY `id_sekolah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id_tahun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  ADD CONSTRAINT `guru_mapel_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE,
  ADD CONSTRAINT `guru_mapel_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id_mapel`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`);

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id_mapel`),
  ADD CONSTRAINT `nilai_ibfk_3` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `nilai_ibfk_4` FOREIGN KEY (`id_tahun`) REFERENCES `tahun_ajaran` (`id_tahun`);

--
-- Constraints for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  ADD CONSTRAINT `siswa_kelas_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_kelas_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`),
  ADD CONSTRAINT `siswa_kelas_ibfk_3` FOREIGN KEY (`id_tahun`) REFERENCES `tahun_ajaran` (`id_tahun`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

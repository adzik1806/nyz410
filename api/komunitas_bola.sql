-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Bulan Mei 2026 pada 09.24
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
-- Database: `komunitas_bola`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(100) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_admin`, `last_login`) VALUES
(1, 'admin', 'fe3bd8b97834117ec68892c245bd1b08', 'Super Admin Nyenkzer', '2026-05-11 15:26:26'),
(2, 'nyenkzer', 'aec07f935649a5b028c070251f617de7', 'Staff Admin', '2026-05-11 15:26:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id_event` int(11) NOT NULL,
  `judul_event` varchar(255) NOT NULL,
  `kategori` enum('Sepakbola','Futsal','Minisoccer') NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `maps_link` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `harga` int(11) NOT NULL,
  `kuota_maksimal` int(11) DEFAULT 20,
  `deskripsi` text DEFAULT NULL,
  `gambar_event` varchar(255) DEFAULT 'event_default.jpg',
  `status_event` enum('tersedia','penuh','selesai') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id_event`, `judul_event`, `kategori`, `lokasi`, `maps_link`, `tanggal`, `jam`, `harga`, `kuota_maksimal`, `deskripsi`, `gambar_event`, `status_event`) VALUES
(8, 'Mabol Rutin', 'Sepakbola', 'BIG HAM', NULL, '2026-05-30', '19:00:00', 100000, 20, NULL, 'event_default.jpg', 'tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gallery`
--

CREATE TABLE `gallery` (
  `id_gallery` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `caption` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gallery`
--

INSERT INTO `gallery` (`id_gallery`, `foto`, `caption`) VALUES
(1, '6a021d24dc0f8_foto kegiatan1.jpg', 'Mabol'),
(2, '6a021d306c7cd_foto kegiatan4.jpg', 'Futsal');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas`
--

CREATE TABLE `kas` (
  `id_kas` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tipe` enum('masuk','keluar') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kas`
--

INSERT INTO `kas` (`id_kas`, `keterangan`, `tanggal`, `jumlah`, `tipe`) VALUES
(2, 'Bayar lapangan', '2026-05-23', 1200000, 'keluar'),
(3, 'Kas', '2026-05-08', 2000000, 'masuk'),
(4, 'Bayar sampah', '2026-05-15', 100000, 'keluar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id_daftar` int(11) NOT NULL,
  `id_event` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_bayar` enum('pending','lunas') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id_setting` int(11) NOT NULL,
  `nama_website` varchar(100) DEFAULT NULL,
  `deskripsi_website` text DEFAULT NULL,
  `logo_website` varchar(255) DEFAULT NULL,
  `wa_admin` varchar(20) DEFAULT NULL,
  `stats_member` int(11) DEFAULT 0,
  `stats_venue` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id_setting`, `nama_website`, `deskripsi_website`, `logo_website`, `wa_admin`, `stats_member`, `stats_venue`) VALUES
(1, 'NYENKZER 410', 'Menjali silaturahmi melalui olahraga', '6a021cab23106_logo nyenkzer.png', '6282111812964', 50, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sponsors`
--

CREATE TABLE `sponsors` (
  `id_sponsor` int(11) NOT NULL,
  `nama_sponsor` varchar(100) NOT NULL,
  `link_website` varchar(255) DEFAULT '#',
  `logo_icon` varchar(50) DEFAULT 'fa-bolt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sponsors`
--

INSERT INTO `sponsors` (`id_sponsor`, `nama_sponsor`, `link_website`, `logo_icon`) VALUES
(9, 'walikota', '#', '6a020f8f26445_depok.png'),
(13, 'NIKE', '#', '6a0211b649e31_logonu.png'),
(15, 'NU', '#', '6a07623018ae1_IMG_4931.JPG');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `posisi_bermain` varchar(20) DEFAULT NULL,
  `level` enum('admin','member') DEFAULT 'member',
  `foto_profil` varchar(255) DEFAULT 'default_user.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `venues`
--

CREATE TABLE `venues` (
  `id_venue` int(11) NOT NULL,
  `nama_venue` varchar(100) NOT NULL,
  `kategori_sport` enum('Sepakbola','Futsal','Minisoccer') NOT NULL,
  `alamat_venue` text DEFAULT NULL,
  `maps_link` text DEFAULT NULL,
  `foto_venue` varchar(255) DEFAULT 'default_venue.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `venues`
--

INSERT INTO `venues` (`id_venue`, `nama_venue`, `kategori_sport`, `alamat_venue`, `maps_link`, `foto_venue`) VALUES
(8, 'Detos ARENA', 'Minisoccer', 'Mall Depok Town square', 'https://maps.app.goo.gl/ypZ6HNeiXSGecU5v6', '6a074d7788d1f_foto kegiatan1.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id_event`);

--
-- Indeks untuk tabel `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id_gallery`);

--
-- Indeks untuk tabel `kas`
--
ALTER TABLE `kas`
  ADD PRIMARY KEY (`id_kas`);

--
-- Indeks untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id_daftar`),
  ADD KEY `id_event` (`id_event`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indeks untuk tabel `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`id_sponsor`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id_venue`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id_gallery` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kas`
--
ALTER TABLE `kas`
  MODIFY `id_kas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_daftar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `id_sponsor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `venues`
--
ALTER TABLE `venues`
  MODIFY `id_venue` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`id_event`) REFERENCES `events` (`id_event`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

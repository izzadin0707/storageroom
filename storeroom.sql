-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Apr 2025 pada 16.38
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `storeroom`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `id_type` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `category`
--

INSERT INTO `category` (`id`, `id_type`, `nama`, `created_at`) VALUES
(2, 5, 'PCS', '2025-02-11 15:48:29'),
(5, 6, 'Makanan', '2025-02-11 17:49:10'),
(7, 6, 'Minuman', '2025-02-11 17:49:46'),
(8, 8, 'IN', '2025-04-11 17:17:44'),
(9, 8, 'OUT', '2025-04-11 17:17:49'),
(10, 7, 'INITIAL', '2025-04-11 16:49:54'),
(11, 7, 'UPDATE', '2025-04-15 13:54:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `id_location` int(11) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL,
  `id_category` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `history`
--

INSERT INTO `history` (`id`, `id_location`, `id_product`, `id_users`, `id_category`, `qty`, `created_at`) VALUES
(10, 1, 2, 1, 10, 25, '2025-04-13 09:22:55'),
(11, 2, 2, 1, 10, 25, '2025-04-13 09:23:20'),
(12, 1, 2, 1, 9, 50, '2025-04-15 06:18:29'),
(13, 1, 2, 1, 10, 500, '2025-04-15 06:53:36'),
(14, 1, 3, 1, 11, 250, '2025-04-15 07:01:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `location`
--

INSERT INTO `location` (`id`, `nama`, `created_at`) VALUES
(1, 'L1-R1-B1', '2025-02-10 17:29:39'),
(2, 'L1-R1-B2', '2025-02-12 08:21:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `id_category` int(11) DEFAULT NULL,
  `id_uom` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `expired` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`id`, `id_category`, `id_uom`, `code`, `nama`, `description`, `expired`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 'PRD0001', 'Onigiri Alfamart', 'Rasa salmon', '2025-02-19', '2025-02-11 17:56:07', '2025-02-11 10:59:41'),
(2, 7, 2, 'PRD0002', 'Ultramilk Rasa Cokelat', '100 Liter', '2025-02-27', '2025-02-11 18:00:21', '2025-02-11 11:00:21'),
(3, 5, 2, 'PRD0003', 'Odol Sikat Gigi', 'Rasa Mint', '2025-04-30', '2025-02-12 01:22:33', '2025-02-11 18:22:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `rolename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id`, `rolename`) VALUES
(1, 'admin'),
(6, 'employee');

-- --------------------------------------------------------

--
-- Struktur dari tabel `storage`
--

CREATE TABLE `storage` (
  `id` int(11) NOT NULL,
  `id_location` int(11) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `storage`
--

INSERT INTO `storage` (`id`, `id_location`, `id_product`, `qty`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(10, 1, 2, 100, 1, '2025-02-12 09:48:37', 1, '2025-04-15 06:53:28'),
(13, 1, 1, 75, 1, '2025-04-11 10:41:58', 1, '2025-04-11 10:53:34'),
(15, 2, 2, 25, 1, '2025-04-13 09:23:20', NULL, NULL),
(16, 1, 3, 250, 1, '2025-04-15 06:53:35', 1, '2025-04-15 07:01:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `type`
--

INSERT INTO `type` (`id`, `nama`, `created_at`) VALUES
(5, 'UOM', '2025-02-11 15:43:49'),
(6, 'Product', '2025-02-11 17:48:07'),
(7, 'Storage', '2025-02-12 15:38:44'),
(8, 'Transaction', '2025-04-11 17:11:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `roleid` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `roleid`) VALUES
(1, 'superuser', '+4ZjXqyj/q9mN77eAa00jYEK5HHl/PSu+d1qGn1/3kcVr1kIRRF+ivzPKlSa0yYMGJvVwzxp9iqDc8lOPdPyr22QyUoKx6Af/47LXGPCDfbeeXKiCVvkAHJDiB4=', 1),
(5, 'arsyi', 'OCn7mej+3v4wEOoV4E6NI0bLZ0P7GJ9jsXvPpfQbVN57XD0caIISmu49ekxSHXLuq/K8s8UaPUD0bf2mqJ0zdl6FvMb8QMh3qWoGirtYmgZzkcBJiXzqwA==', 6);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`rolename`);

--
-- Indeks untuk tabel `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `storage`
--
ALTER TABLE `storage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_category_type` FOREIGN KEY (`id_type`) REFERENCES `type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

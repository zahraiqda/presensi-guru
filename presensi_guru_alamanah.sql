-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 08 Nov 2026 pada 12.49
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `presensi_guru_alamanah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `arsip_presensi`
--

CREATE TABLE `arsip_presensi` (
  `id` bigint UNSIGNED NOT NULL,
  `arsip_tahun_ajaran_id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED DEFAULT NULL,
  `nama_guru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_guru` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mata_pelajaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `status` enum('masuk','pulang','sakit','izin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bukti_sakit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_izin` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_status` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `bukti_izin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_kepsek` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `arsip_presensi`
--

INSERT INTO `arsip_presensi` (`id`, `arsip_tahun_ajaran_id`, `guru_id`, `nama_guru`, `nip_guru`, `mata_pelajaran`, `tanggal`, `waktu`, `status`, `latitude`, `longitude`, `keterangan`, `bukti_sakit`, `token_izin`, `approval_status`, `approved_at`, `bukti_izin`, `catatan_kepsek`, `created_at`, `updated_at`) VALUES
(21, 2, 11, 'Dina Maharanie', NULL, 'Ilmu Pengetahuan Alam (IPA)', '2026-10-17', '08:05:32', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(22, 2, 10, 'Zahra Iqda Rahmadan', NULL, 'Informatika', '2026-10-17', '08:06:14', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(23, 2, 21, 'Aan Nur Hasanah, S.Pd.', NULL, 'PPKn', '2026-10-17', '09:02:25', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(24, 2, 11, 'Dina Maharanie', NULL, 'Ilmu Pengetahuan Alam (IPA)', '2026-10-17', '15:46:22', 'pulang', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(25, 2, 10, 'Zahra Iqda Rahmadan', NULL, 'Informatika', '2026-10-17', '15:46:46', 'pulang', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(26, 2, 10, 'Zahra Iqda Rahmadan', NULL, 'Informatika', '2026-10-19', '06:59:34', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(27, 2, 21, 'Aan Nur Hasanah, S.Pd.', NULL, 'PPKn', '2026-10-19', '07:01:03', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(28, 2, 11, 'Dina Maharanie', NULL, 'Ilmu Pengetahuan Alam (IPA)', '2026-10-19', '07:01:54', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(29, 2, 12, 'Ahmad Fauzi', NULL, 'PAI', '2026-10-19', '07:04:27', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(30, 2, 13, 'Nike Aditya Putri, M.Pd.', NULL, 'B. Indonesia', '2026-10-19', '07:05:42', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(31, 2, 16, 'Nafisatun Umami, S.Pd., M.Pd.', NULL, 'Matematika', '2026-10-19', '11:24:46', 'masuk', -7.1515972, 108.8113189, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(32, 2, 10, 'Zahra Iqda Rahmadan', NULL, 'Informatika', '2026-10-19', '16:53:41', 'pulang', -7.4124172, 109.2734817, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(33, 2, 12, 'Ahmad Fauzi', NULL, 'PAI', '2026-10-19', '16:54:19', 'pulang', -7.4123172, 109.2738008, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(34, 2, 11, 'Dina Maharanie', NULL, 'Ilmu Pengetahuan Alam (IPA)', '2026-10-19', '16:54:52', 'pulang', -7.4123172, 109.2738008, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(35, 2, 21, 'Aan Nur Hasanah, S.Pd.', NULL, 'PPKn', '2026-10-19', '16:55:19', 'pulang', -7.4123172, 109.2738008, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(36, 2, 16, 'Nafisatun Umami, S.Pd., M.Pd.', NULL, 'Matematika', '2026-10-19', '16:56:01', 'pulang', -7.4123172, 109.2738008, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(37, 2, 13, 'Nike Aditya Putri, M.Pd.', NULL, 'B. Indonesia', '2026-10-19', '16:56:22', 'pulang', -7.4123734, 109.2737222, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18'),
(38, 2, 27, 'Wulansari', NULL, 'B. Inggris', '2026-10-21', '21:16:58', 'izin', NULL, NULL, 'ada kepentingan ke luar kota', NULL, 'CrzMrX2TTOcxZDKiQwrHatDUTHDfoIQFIQmMcwof', 'disetujui', '2026-10-21 14:28:17', 'bukti_izin/NfUBumZNizJ7VN9zK2utwdXCu8VT1gF4Yz4hzwhh.jpg', NULL, '2026-10-24 17:27:18', '2026-10-24 17:27:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `arsip_tahun_ajaran`
--

CREATE TABLE `arsip_tahun_ajaran` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'semua',
  `nama_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `total_presensi` int UNSIGNED NOT NULL DEFAULT '0',
  `total_hadir` int UNSIGNED NOT NULL DEFAULT '0',
  `total_terlambat` int UNSIGNED NOT NULL DEFAULT '0',
  `total_sakit` int UNSIGNED NOT NULL DEFAULT '0',
  `total_izin` int UNSIGNED NOT NULL DEFAULT '0',
  `diarsipkan_oleh` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `arsip_tahun_ajaran`
--

INSERT INTO `arsip_tahun_ajaran` (`id`, `tahun_ajaran`, `semester`, `nama_label`, `keterangan`, `tanggal_mulai`, `tanggal_selesai`, `total_presensi`, `total_hadir`, `total_terlambat`, `total_sakit`, `total_izin`, `diarsipkan_oleh`, `created_at`, `updated_at`) VALUES
(2, '2026/2027', 'ganjil', '2026/2027 - Semester Ganjil', NULL, '2026-10-17', '2026-10-21', 18, 17, 0, 0, 1, 1, '2026-10-30 01:09:41', '2026-10-30 01:09:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nik` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mata_pelajaran` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id`, `user_id`, `nik`, `nama`, `mata_pelajaran`, `created_at`, `updated_at`) VALUES
(10, 12, '2303040076342564', 'Zahra Iqda Rahmadan', 'Informatika', '2026-10-13 07:45:29', '2026-10-20 14:04:46'),
(11, 13, '2301100044698753', 'Dina Maharanie', 'Ilmu Pengetahuan Alam (IPA)', '2026-10-13 07:46:55', '2026-10-20 14:04:12'),
(12, 14, '3201234567890001', 'Ahmad Fauzi', 'PAI', '2026-10-13 07:49:19', '2026-10-20 13:48:34'),
(13, 15, '3201234567890010', 'Nike Aditya Putri, M.Pd.', 'B. Indonesia', '2026-10-13 08:58:25', '2026-10-20 13:47:27'),
(16, 18, '3201234567890013', 'Nafisatun Umami, S.Pd., M.Pd.', 'Matematika', '2026-10-13 08:58:29', '2026-10-20 13:47:38'),
(21, 23, '3201234567890018', 'Aan Nur Hasanah, S.Pd.', 'PPKn', '2026-10-13 08:58:36', '2026-10-20 13:39:13'),
(25, 27, '3329010808920001', 'Ali', 'Informatika', '2026-10-19 02:59:29', '2026-10-20 13:48:20'),
(27, 29, '3329876543210983', 'Wulansari', 'B. Inggris', '2026-10-21 14:05:38', '2026-10-21 14:05:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_08_19_000000_create_failed_jobs_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2024_01_01_000001_create_users_table', 1),
(4, '2024_01_01_000002_create_guru_table', 1),
(5, '2024_01_01_000003_create_pengaturan_table', 1),
(6, '2024_01_01_000004_create_presensi_table', 1),
(7, '2024_01_01_000005_create_password_reset_tokens_table', 1),
(8, '2024_01_01_000006_create_sessions_table', 1),
(9, '2024_01_01_000007_update_radius_pengaturan_to_60_meter', 2),
(10, '2024_01_01_000008_add_jam_operasional_to_pengaturan_table', 3),
(11, '2026_10_20_202739_add_mata_pelajaran_to_guru_table', 4),
(12, '2026_10_20_213957_add_bukti_sakit_to_presensi_table', 5),
(15, '2026_10_20_222310_add_no_wa_kepsek_to_pengaturan_table', 6),
(16, '2026_10_20_222323_add_approval_izin_to_presensi_table', 6),
(18, '2026_10_25_000001_create_arsip_presensi_tables', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('admin@alamanah.sch.id', '$2y$12$VZtu8gMPl1YDYZFSrmt/COsuHCTzzI5njHrpPWec2/.4FXKeUSo5K', '2026-10-05 15:02:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` bigint UNSIGNED NOT NULL,
  `latitude_sekolah` decimal(10,7) NOT NULL,
  `longitude_sekolah` decimal(10,7) NOT NULL,
  `radius` int UNSIGNED NOT NULL COMMENT 'radius dalam meter',
  `no_wa_kepsek` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_masuk_mulai` time NOT NULL DEFAULT '06:00:00',
  `jam_masuk_toleransi` time NOT NULL,
  `jam_masuk_selesai` time NOT NULL DEFAULT '12:00:00',
  `jam_pulang_minimal` time NOT NULL,
  `jam_pulang_selesai` time NOT NULL DEFAULT '19:00:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `latitude_sekolah`, `longitude_sekolah`, `radius`, `no_wa_kepsek`, `jam_masuk_mulai`, `jam_masuk_toleransi`, `jam_masuk_selesai`, `jam_pulang_minimal`, `jam_pulang_selesai`, `created_at`, `updated_at`) VALUES
(1, -7.4148330, 109.2757770, 60, '081953010121', '06:00:00', '07:15:00', '12:00:00', '15:00:00', '17:30:00', '2026-09-27 09:54:44', '2026-10-21 14:10:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `status` enum('masuk','pulang','sakit','izin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_izin` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_status` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `bukti_izin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_kepsek` text COLLATE utf8mb4_unicode_ci,
  `bukti_sakit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `presensi`
--

INSERT INTO `presensi` (`id`, `guru_id`, `tanggal`, `waktu`, `status`, `latitude`, `longitude`, `keterangan`, `token_izin`, `approval_status`, `approved_at`, `bukti_izin`, `catatan_kepsek`, `bukti_sakit`, `created_at`, `updated_at`) VALUES
(164, 11, '2026-10-17', '08:05:32', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(165, 10, '2026-10-17', '08:06:14', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(166, 21, '2026-10-17', '09:02:25', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(167, 11, '2026-10-17', '15:46:22', 'pulang', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(168, 10, '2026-10-17', '15:46:46', 'pulang', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(169, 10, '2026-10-19', '06:59:34', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(170, 21, '2026-10-19', '07:01:03', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(171, 11, '2026-10-19', '07:01:54', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(172, 12, '2026-10-19', '07:04:27', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(173, 13, '2026-10-19', '07:05:42', 'masuk', -7.1526540, 108.8092905, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(174, 16, '2026-10-19', '11:24:46', 'masuk', -7.1515972, 108.8113189, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(175, 10, '2026-10-19', '16:53:41', 'pulang', -7.4124172, 109.2734817, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(176, 12, '2026-10-19', '16:54:19', 'pulang', -7.4123172, 109.2738008, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(177, 11, '2026-10-19', '16:54:52', 'pulang', -7.4123172, 109.2738008, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(178, 21, '2026-10-19', '16:55:19', 'pulang', -7.4123172, 109.2738008, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(179, 16, '2026-10-19', '16:56:01', 'pulang', -7.4123172, 109.2738008, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(180, 13, '2026-10-19', '16:56:22', 'pulang', -7.4123734, 109.2737222, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(181, 27, '2026-10-21', '21:16:58', 'izin', NULL, NULL, 'ada kepentingan ke luar kota', 'CrzMrX2TTOcxZDKiQwrHatDUTHDfoIQFIQmMcwof', 'disetujui', '2026-10-21 14:28:17', 'bukti_izin/NfUBumZNizJ7VN9zK2utwdXCu8VT1gF4Yz4hzwhh.jpg', NULL, NULL, '2026-10-30 01:10:16', '2026-10-30 01:10:16'),
(182, 13, '2026-10-30', '08:12:54', 'sakit', NULL, NULL, 'demam', NULL, NULL, NULL, NULL, NULL, 'bukti_sakit/RZ0sdd17DfRgJPkVTlzY9GsP5mflSRoYsV7NAmvj.png', '2026-10-30 01:12:54', '2026-10-30 01:12:54'),
(183, 25, '2026-10-30', '08:13:54', 'izin', NULL, NULL, 'keluar kota', '67mppHImVLva9PSrpyRnXEQbe3CehUV1tgQyRqpq', 'disetujui', '2026-10-30 01:15:29', 'bukti_izin/cLyQtvbESmwTdd9fkKguDcnP5HMBPh7HSazjuia9.png', NULL, NULL, '2026-10-30 01:13:54', '2026-10-30 01:15:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru','kepala_sekolah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 'zahra.iqda30@gmail.com', '$2y$12$BAhkWQCXgPQjVWzmlmY9Z.2Dk55jgKFp3O06vdhLxbsVw9LbCUK3.', 'admin', 'BVN6t6wvN8oevXOnWKayABS7Gt6hjx9AHAPx6KvJrKeGmIQgMwKDLcaosQMN', '2026-09-27 09:54:44', '2026-10-05 15:21:33'),
(2, 'Faatih Ghozwatul Fikri, S.T., M.Pd.', 'kepsek', 'kepsek@alamanah.sch.id', '$2y$12$U67nB8jPC.v71UbViWaCdue9NUh4nHxE7TmkGRXMTFpboPiTD8g7C', 'kepala_sekolah', 'w4da6tjr9CWzS04FqKVQwJ3atssnBZBIwKJ15VPXVwG010N9c4kL9KmkaV2R', '2026-09-27 09:54:44', '2026-10-21 15:47:17'),
(12, 'Zahra Iqda Rahmadan', 'Zahra.iqda30', NULL, '$2y$12$iF8f65lN/LqXEI19z2xSoORzm99AJ47vj4rcRDGANiJzUis92A7s6', 'guru', 'ScjJoD9NANEEmoVPj5oF4c5lo98wmSfUjKFGufudUWNduzCuwk1fuiZWoH5X', '2026-10-13 07:45:29', '2026-10-19 02:52:13'),
(13, 'Dina Maharanie', 'Dina_Maharanie', NULL, '$2y$12$Vf53h2FYvyfN1C04RjS/3.hgX.Q8bELrb7Fub0MDnhljxNHXBLtGu', 'guru', NULL, '2026-10-13 07:46:55', '2026-10-13 07:46:55'),
(14, 'Ahmad Fauzi', 'guru1', NULL, '$2y$12$TIEnPYxQvNfpJqe64CHav.WnvBL5Go93D//qqieW/9Av17N5S8rQC', 'guru', 'bJHd7cw4lMwizdrZekNzeFI3yUsxRzpg99XbE2NnMwhFP0zAPnTKAc4e0YmH', '2026-10-13 07:49:19', '2026-10-13 07:49:19'),
(15, 'Nike Aditya Putri, M.Pd.', '3201234567890010', NULL, '$2y$12$vVgivA/n5.TGYL1J22t8LeUy2qR4q.MvK2OfT.xhQ2FFi097Ztc9C', 'guru', NULL, '2026-10-13 08:58:25', '2026-10-13 08:58:25'),
(18, 'Nafisatun Umami, S.Pd., M.Pd.', '3201234567890013', NULL, '$2y$12$nECLpm99dnXIfwlwxK5zVuOL4sYQUx0jVt1LiqZ3BmhvVe02xvf7G', 'guru', NULL, '2026-10-13 08:58:29', '2026-10-13 08:58:29'),
(23, 'Aan Nur Hasanah, S.Pd.', '3201234567890018', NULL, '$2y$12$79VU/IYfkySILpKGgIRjeuDCuC9BpnM8CZQb0qG.LTjKsKQYT6UnK', 'guru', NULL, '2026-10-13 08:58:36', '2026-10-13 08:58:36'),
(27, 'Ali', 'Ali', NULL, '$2y$12$OFzZXl16SH5C9oMcJ.ypNeiK7ctvp96mM9Mv1dA1gupxJXO2DRAgS', 'guru', NULL, '2026-10-19 02:59:29', '2026-10-19 02:59:29'),
(29, 'Wulansari', 'Wulan_sari', NULL, '$2y$12$pQ8jNU5cSIHIwg35QDjhA.euXRc3ptfwzi5nHA29AVZ2mV0X61qZi', 'guru', 'QBJlaKUNj2gIYGgB6t48OfxwQoN4WkDfYpEA6A12fvbpWVgpRm1I6IzockBy', '2026-10-21 14:05:38', '2026-10-21 14:05:38');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `arsip_presensi`
--
ALTER TABLE `arsip_presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arsip_presensi_guru_id_foreign` (`guru_id`),
  ADD KEY `arsip_presensi_arsip_tahun_ajaran_id_tanggal_index` (`arsip_tahun_ajaran_id`,`tanggal`),
  ADD KEY `arsip_presensi_arsip_tahun_ajaran_id_status_index` (`arsip_tahun_ajaran_id`,`status`),
  ADD KEY `arsip_presensi_arsip_tahun_ajaran_id_guru_id_index` (`arsip_tahun_ajaran_id`,`guru_id`);

--
-- Indeks untuk tabel `arsip_tahun_ajaran`
--
ALTER TABLE `arsip_tahun_ajaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arsip_tahun_ajaran_diarsipkan_oleh_foreign` (`diarsipkan_oleh`),
  ADD KEY `arsip_tahun_ajaran_tahun_ajaran_index` (`tahun_ajaran`),
  ADD KEY `arsip_tahun_ajaran_nama_label_index` (`nama_label`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guru_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `guru_nik_unique` (`nik`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `presensi_token_izin_unique` (`token_izin`),
  ADD KEY `presensi_guru_id_tanggal_index` (`guru_id`,`tanggal`),
  ADD KEY `presensi_tanggal_status_index` (`tanggal`,`status`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `arsip_presensi`
--
ALTER TABLE `arsip_presensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `arsip_tahun_ajaran`
--
ALTER TABLE `arsip_tahun_ajaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `arsip_presensi`
--
ALTER TABLE `arsip_presensi`
  ADD CONSTRAINT `arsip_presensi_arsip_tahun_ajaran_id_foreign` FOREIGN KEY (`arsip_tahun_ajaran_id`) REFERENCES `arsip_tahun_ajaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `arsip_presensi_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `arsip_tahun_ajaran`
--
ALTER TABLE `arsip_tahun_ajaran`
  ADD CONSTRAINT `arsip_tahun_ajaran_diarsipkan_oleh_foreign` FOREIGN KEY (`diarsipkan_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

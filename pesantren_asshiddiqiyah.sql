-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Jan 2026 pada 14.05
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
-- Database: `pesantren_asshiddiqiyah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan_harian`
--

CREATE TABLE `catatan_harian` (
  `id` int(11) NOT NULL,
  `santri_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `judul` varchar(200) NOT NULL,
  `isi_catatan` text NOT NULL,
  `kategori` enum('Ibadah','Belajar','Kegiatan','Refleksi','Lainnya') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `catatan_harian`
--

INSERT INTO `catatan_harian` (`id`, `santri_id`, `tanggal`, `judul`, `isi_catatan`, `kategori`, `created_at`, `updated_at`) VALUES
(1, 3, '2024-12-15', 'Sholat Tahajud Pertama Kali', 'Alhamdulillah hari ini saya berhasil bangun untuk sholat tahajud. Rasanya sangat tenang dan damai. Semoga bisa istiqomah setiap hari.', 'Ibadah', '2026-01-01 11:29:27', '2026-01-01 11:29:27'),
(2, 3, '2024-12-14', 'Belajar Nahwu dengan Ustadz', 'Hari ini belajar tentang fiil madhi dan mudhari. Mulai paham sedikit demi sedikit. Harus banyak latihan menghafalkan tashrif.', 'Belajar', '2026-01-01 11:29:27', '2026-01-01 11:29:27'),
(3, 4, '2024-12-15', 'Kegiatan Membersihkan Masjid', 'Pagi ini jadwal piket membersihkan masjid. Menyapu, mengepel, dan merapikan mushaf. Senang bisa merawat rumah Allah.', 'Kegiatan', '2026-01-01 11:29:27', '2026-01-01 11:29:27'),
(4, 4, '2024-12-13', 'Refleksi Minggu Ini', 'Minggu ini banyak pelajaran yang saya dapat. Terutama tentang sabar dan ikhlas. Masih banyak yang harus diperbaiki, tapi Alhamdulillah ada progress.', 'Refleksi', '2026-01-01 11:29:27', '2026-01-01 11:29:27'),
(5, 5, '2024-12-15', 'Hafalan Juz 30 Halaman 5', 'Hari ini berhasil menambah hafalan surat An-Naba ayat 21-30. Target minggu ini selesai surat An-Naba. Semangat!', 'Belajar', '2026-01-01 11:29:27', '2026-01-01 11:29:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_pelanggaran`
--

CREATE TABLE `kategori_pelanggaran` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `poin_pelanggaran` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `kategori_pelanggaran`
--

INSERT INTO `kategori_pelanggaran` (`id`, `nama_kategori`, `deskripsi`, `poin_pelanggaran`, `created_at`) VALUES
(1, 'Keterlambatan', 'Terlambat mengikuti kegiatan pesantren', 5, '2025-12-31 08:06:24'),
(2, 'Tidak Mengikuti Kegiatan', 'Tidak hadir tanpa keterangan', 10, '2025-12-31 08:06:24'),
(3, 'Pelanggaran Tata Tertib Ringan', 'Pelanggaran tata tertib tingkat ringan adalah pelanggaran yang bersifat ringan dan tidak mengganggu keamanan pesantren, seperti keterlambatan mengikuti kegiatan, tidak mematuhi aturan berpakaian, atau kurang menjaga kebersihan. seperti berisik, pakaian tidak syar\'i, melanggar kebersihan.', 15, '2025-12-31 08:06:24'),
(4, 'Pelanggaran Tata Tertib Sedang', 'Pelanggaran tata tertib tingkat sedang merupakan pelanggaran yang berdampak pada ketertiban dan kenyamanan lingkungan pesantren, seperti tidak mengikuti kegiatan wajib, melanggar aturan penggunaan fasilitas, atau mengulangi pelanggaran ringan.', 25, '2025-12-31 08:06:24'),
(5, 'Pelanggaran Tata Tertib Berat', 'Pelanggaran tata tertib tingkat berat pelanggaran serius yang mengganggu keamanan, ketertiban, dan nilai-nilai pesantren, seperti tindakan kekerasan, perundungan, pelanggaran moral, atau pelanggaran berat yang dilakukan berulang.', 50, '2025-12-31 08:06:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi_edukasi`
--

CREATE TABLE `materi_edukasi` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `kategori` enum('Tata Tertib','Adab Santri','Pencegahan Kekerasan','Motivasi') NOT NULL,
  `isi_materi` text NOT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `dibuat_oleh` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `materi_edukasi`
--

INSERT INTO `materi_edukasi` (`id`, `judul`, `kategori`, `isi_materi`, `file_pdf`, `gambar`, `dibuat_oleh`, `created_at`, `updated_at`) VALUES
(1, 'Pentingnya Disiplin di Pesantren', 'Tata Tertib', 'Disiplin adalah kunci kesuksesan seorang santri. Dengan disiplin, santri dapat mengatur waktu dengan baik antara ibadah, belajar, dan istirahat. Ketepatan waktu dalam mengikuti kegiatan pesantren menunjukkan kesungguhan dalam menuntut ilmu.', NULL, NULL, 1, '2025-12-31 08:06:24', '2025-12-31 08:06:24'),
(2, 'Adab Terhadap Guru dan Sesama', 'Adab Santri', 'Menghormati guru adalah wajib bagi setiap santri. Adab yang baik meliputi berbicara dengan sopan, mendengarkan dengan seksama, tidak membantah dengan kasar, dan selalu mendoakan guru. Kepada sesama santri, kita harus saling menghormati dan membantu.', NULL, NULL, 1, '2025-12-31 08:06:24', '2026-01-03 07:26:24'),
(3, 'Mencegah Bullying di Lingkungan Pesantren', 'Pencegahan Kekerasan', 'Bullying atau perundungan adalah tindakan yang tidak dibenarkan dalam Islam. Setiap santri harus saling menjaga dan melindungi. Jika melihat teman yang di-bully, segera laporkan kepada pengasuh. Mari ciptakan lingkungan pesantren yang aman dan nyaman.', NULL, NULL, 1, '2025-12-31 08:06:24', '2025-12-31 08:06:24'),
(6, 'Pentingnya Menjaga Kebersihan', 'Tata Tertib', 'Kebersihan adalah sebagian dari iman. Sebagai santri, kita wajib menjaga kebersihan diri dan lingkungan.\r\nBeberapa hal yang harus diperhatikan:\r\n 1). Mandi minimal 2 kali sehari\r\n2). Mencuci pakaian secara teratur\r\n3).Menjaga kebersihan kamar dan tempat tidur\r\n4).Membuang sampah pada tempatnya\r\n5). Membersihkan area belajar\r\n6). Menjaga wudhu\r\n Dengan menjaga kebersihan, kita akan merasa nyaman dalam beribadah dan belajar.', NULL, NULL, 2, '2025-12-31 08:08:49', '2026-01-03 07:16:43'),
(7, 'Bahaya Pergaulan Bebas', 'Pencegahan Kekerasan', 'Pergaulan bebas adalah salah satu bahaya besar yang mengancam generasi muda saat ini. Islam mengajarkan kita untuk menjaga pergaulan dengan baik. \r\nBatasan-batasan yang harus dijaga:\r\n 1). Jaga pandangan dari yang haram\r\n2). Hindari khalwat (berduaan) dengan lawan jenis yang bukan mahram\r\n3). Jauhi tempat-tempat maksiat\r\n4). Bergaul dengan teman yang baik\r\n5). Ikuti kegiatan positif di pesantren\r\nMari kita jaga diri kita dari pergaulan yang tidak Islami!!!', NULL, NULL, 1, '2025-12-31 08:08:49', '2026-01-03 07:18:33'),
(8, 'Keutamaan Menghafal Al-Quran', 'Motivasi', 'Menghafal Al-Quran adalah ibadah yang sangat mulia. Rasulullah SAW bersabda: \"Orang yang paling utama di antara kalian adalah yang belajar Al-Quran dan mengajarkannya\" (HR. Bukhari). Keutamaan penghafal Al-Quran: \r\n1). Mendapat syafaat di hari kiamat\r\n2). Dimuliakan oleh Allah\r\n3). Orang tuanya mendapat mahkota cahaya\r\n4). Ditempatkan di derajat tertinggi surga\r\nMari kita semangat menghafal Al-Quran dengan baik dan benar.', NULL, NULL, 2, '2025-12-31 08:08:49', '2026-01-03 07:19:59'),
(9, 'Cara Mengelola Emosi', 'Pencegahan Kekerasan', 'Mengelola emosi adalah keterampilan penting yang harus dimiliki setiap santri. Ketika marah, lakukan hal berikut: \r\n1).Istighfar dan membaca taawudz\r\n2).Ubah posisi (jika berdiri maka duduk, jika duduk maka berbaring)\r\n3) Wudhu untuk meredakan amarah\r\n4).Diamkan diri sejenak sebelum berbicara\r\n5).Ingat akibat buruk dari marah yang tidak terkontrol \r\nRasulullah SAW bersabda: \"Bukan orang yang kuat adalah yang menang dalam bergulat, tetapi orang yang kuat adalah yang mampu mengendalikan dirinya ketika marah\" (HR. Bukhari Muslim).', NULL, NULL, 1, '2025-12-31 08:08:49', '2026-01-03 07:23:55'),
(11, 'Adab  Santri Pesantren', 'Adab Santri', 'Adab santri adalah sikap dan perilaku mulia yang harus dijaga dalam menuntut ilmu sebagai wujud ketaatan kepada Allah SWT, penghormatan kepada guru, dan pembentukan akhlakul karimah.\r\n\r\nDalil Al-Qur’an\r\n“Wahai orang-orang yang beriman! Taatilah Allah dan taatilah Rasul serta ulil amri di antara kamu.”\r\n(QS. An-Nisa: 59)\r\n\r\n“Allah akan meninggikan derajat orang-orang yang beriman di antaramu dan orang-orang yang diberi ilmu beberapa derajat.”\r\n(QS. Al-Mujadilah: 11)\r\n\r\nDalil Hadis\r\nRasulullah ? bersabda:\r\n\r\n“Bukan termasuk golongan kami orang yang tidak menghormati yang lebih tua dan tidak menyayangi yang lebih muda.”\r\n(HR. Ahmad)\r\n\r\n“Sesungguhnya aku diutus untuk menyempurnakan akhlak yang mulia.”\r\n(HR. Malik)\r\n\r\nMenjaga adab kepada Allah, guru, sesama santri, serta lingkungan pesantren merupakan kunci keberkahan ilmu dan keberhasilan pendidikan santri.', NULL, NULL, 16, '2026-01-03 06:53:48', '2026-01-03 07:20:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggaran`
--

CREATE TABLE `pelanggaran` (
  `id` int(11) NOT NULL,
  `santri_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `tanggal_pelanggaran` date NOT NULL,
  `waktu_pelanggaran` time NOT NULL,
  `deskripsi_pelanggaran` text DEFAULT NULL,
  `tindakan_pembinaan` text DEFAULT NULL,
  `status` enum('Belum Ditindak','Sedang Pembinaan','Selesai') DEFAULT 'Belum Ditindak',
  `dicatat_oleh` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `pelanggaran`
--

INSERT INTO `pelanggaran` (`id`, `santri_id`, `kategori_id`, `tanggal_pelanggaran`, `waktu_pelanggaran`, `deskripsi_pelanggaran`, `tindakan_pembinaan`, `status`, `dicatat_oleh`, `created_at`) VALUES
(1, 3, 1, '2025-08-07', '06:00:00', 'Terlambat mengikuti shalat ashar berjamaah', 'Diberi teguran  dan diminta untuk lebih disiplin waktu', 'Sedang Pembinaan', 1, '2025-12-31 08:08:49'),
(2, 4, 2, '2024-12-02', '14:00:00', 'Tidak mengikuti kajian siang tanpa izin', 'Diberi nasihat tentang pentingnya menuntut ilmu', 'Sedang Pembinaan', 1, '2025-12-31 08:08:49'),
(3, 5, 3, '2025-06-05', '00:00:00', 'Keluar kamar setelah jam malam', 'Diberi peringatan dan dijelaskan tata tertib pesantren', 'Selesai', 2, '2025-12-31 08:08:49'),
(4, 6, 5, '2024-12-04', '11:00:00', 'Merusak fasilitas pondok', 'Diberi sanksi tegas dan pembinaan intensif', 'Sedang Pembinaan', 1, '2025-12-31 08:08:49'),
(6, 8, 4, '2025-05-04', '13:30:00', 'Tidak mengikuti kegiatan olahraga', 'Diberi teguran dan pemahaman tentang pentingnya menjaga kesehatan', 'Selesai', 1, '2025-12-31 08:08:49'),
(7, 9, 5, '2025-02-01', '07:00:00', 'Membully santri', 'Diberi sanksi tegas dan pembinaan intensif', 'Belum Ditindak', 2, '2025-12-31 08:08:49'),
(9, 3, 2, '2025-03-02', '10:00:00', 'Tidur saat kajian kitab', 'Diberi teguran dan diminta untuk istirahat cukup di malam hari', 'Selesai', 2, '2025-12-31 08:08:49'),
(11, 5, 1, '2025-11-10', '07:00:00', 'Terlambat mengikuti shalat subuh', 'Diberi teguran dan nasihat', 'Selesai', 1, '2025-12-31 08:08:49'),
(15, 9, 2, '2025-09-08', '13:00:00', 'Tidak mengikuti kegiatan ekstrakurikuler', 'Diberi  teguran dan pemahaman tentang pentingnya kegiatan', 'Sedang Pembinaan', 1, '2025-12-31 08:08:49'),
(27, 33, 3, '2026-01-04', '11:17:00', 'Memakai makeup berlebihan', 'Diberi teguran dan nasihat', 'Belum Ditindak', 16, '2026-01-04 10:18:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembinaan`
--

CREATE TABLE `pembinaan` (
  `id` int(11) NOT NULL,
  `santri_id` int(11) NOT NULL,
  `pelanggaran_id` int(11) DEFAULT NULL,
  `jenis_pembinaan` varchar(100) NOT NULL,
  `tanggal_pembinaan` date NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `hasil_pembinaan` text DEFAULT NULL,
  `pembina_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `pembinaan`
--

INSERT INTO `pembinaan` (`id`, `santri_id`, `pelanggaran_id`, `jenis_pembinaan`, `tanggal_pembinaan`, `deskripsi`, `hasil_pembinaan`, `pembina_id`, `created_at`) VALUES
(2, 6, NULL, 'Konseling', '2025-01-03', 'Konseling tentang pentingnya menuntut ilmu dan tidak meninggalkan kegiatan tanpa izin', 'Santri memahami dan berjanji tidak mengulangi', 2, '2025-12-31 08:08:49'),
(3, 5, NULL, 'Bimbingan Kelompok', '2026-01-02', 'Pembinaan kelompok tentang tata tertib dan pentingnya mematuhi peraturan pesantren', 'Seluruh peserta memahami dan berkomitmen mematuhi tata tertib', 16, '2025-12-31 08:08:49'),
(5, 3, NULL, 'Konseling', '2026-01-01', 'Konseling tentang pentingnya istirahat cukup dan manajemen waktu yang baik', 'Santri berkomitmen untuk mengatur waktu istirahat dengan lebih baik', 14, '2025-12-31 08:08:49'),
(9, 9, NULL, 'Bimbingan Personal', '2026-01-06', 'pembinaan intensif melalui pemanggilan orang tua/wali, pendampingan khusus, sanksi tegas yang bersifat mendidik, serta evaluasi berkelanjutan. Pembinaan diarahkan pada perubahan perilaku, penanaman tanggung jawab, dan pemulihan nilai-nilai akhlak sesuai ketentuan pesantren.', 'santri berjanji tidak akan mengulanginya lagi', 2, '2026-01-03 07:32:48'),
(10, 4, NULL, 'Bimbingan Kelompok', '2026-01-05', 'Pembinaan kelompok tentang tata tertib dan pentingnya mematuhi peraturan pesantren', 'Seluruh peserta memahami dan berkomitmen mematuhi tata tertib', 16, '2026-01-03 07:33:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prestasi`
--

CREATE TABLE `prestasi` (
  `id` int(11) NOT NULL,
  `santri_id` int(11) NOT NULL,
  `jenis_prestasi` varchar(100) NOT NULL,
  `tanggal_prestasi` date NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `poin_prestasi` int(11) DEFAULT 0,
  `dicatat_oleh` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `prestasi`
--

INSERT INTO `prestasi` (`id`, `santri_id`, `jenis_prestasi`, `tanggal_prestasi`, `deskripsi`, `poin_prestasi`, `dicatat_oleh`, `created_at`) VALUES
(2, 4, 'Juara 2 Pidato Bahasa Arab', '2025-02-01', 'Juara 2 lomba pidato bahasa arab tingkat kabupaten', 40, 1, '2025-12-31 08:08:49'),
(4, 6, 'Juara 1 MTQ', '2025-06-04', 'Juara 1 musabaqah tilawatil qur\'an tingkat provinsi', 80, 1, '2025-12-31 08:08:49'),
(5, 7, 'Hafal Juz 30', '2025-12-10', 'Berhasil menyelesaikan hafalan juz 30 dengan baik dan lancar', 30, 2, '2025-12-31 08:08:49'),
(6, 8, 'Juara 3 Cerdas Cermat', '2025-10-07', 'Juara 3 lomba cerdas cermat tentang fiqih tingkat pesantren', 50, 1, '2025-12-31 08:08:49'),
(9, 17, 'Juara 1 kaligrafi ', '2025-02-02', 'Juara 1 lomba kaligrafi tingkat nasional', 90, 16, '2026-01-02 10:07:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_arab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','santri') NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nomor_telepon` varchar(15) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `nama_arab`, `username`, `password`, `role`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `nomor_telepon`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Pesantren', NULL, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Laki-laki', '1980-01-01', 'Pondok Pesantren Asshiddiqiyah Karawang', '081234567890', 'default.png', '2025-12-31 08:06:23', '2025-12-31 08:06:23'),
(2, 'Pengasuh Utama', NULL, 'pengasuh', '177893bd111df76f44827bcc56a12443', 'admin', 'Laki-laki', '1975-05-15', 'Pondok Pesantren Asshiddiqiyah Karawang', '081234567891', 'default.png', '2025-12-31 08:06:23', '2025-12-31 08:06:23'),
(3, 'Dadang Bahtiar', NULL, 'dadang.bahtiar', 'a30d0068e64ed4ef45f0d1652def4ddf', 'santri', 'Laki-laki', '2008-11-10', 'Manggung Jaya', '085819930875', 'default.png', '2025-12-31 08:06:24', '2026-01-02 12:55:08'),
(4, 'Fatimah Zahra', NULL, 'fatimah.zahra', 'a30d0068e64ed4ef45f0d1652def4ddf', 'santri', 'Perempuan', '2005-07-10', 'Bekasi', '081234567893', 'default.png', '2025-12-31 08:06:24', '2026-01-02 09:54:01'),
(5, 'Nurul Fadliyah', NULL, 'nurul.fadliyah', 'f04e85fc790e30dc95b6a45e13b7eb7f', 'santri', 'Perempuan', '2008-02-05', 'Jakarta', '081314024203', 'default.png', '2025-12-31 08:06:24', '2026-01-02 13:01:19'),
(6, 'Ahmad Bagus', NULL, 'ahmad.bagus', 'a30d0068e64ed4ef45f0d1652def4ddf', 'santri', 'Laki-laki', '2006-03-02', 'Sukahaji', '085819956863', 'default.png', '2025-12-31 08:08:49', '2026-01-02 09:51:49'),
(7, 'Amelia Syahidah', NULL, 'amelia.syahidah', 'a30d0068e64ed4ef45f0d1652def4ddf', 'santri', 'Perempuan', '2007-04-04', 'Cilamaya Wetan', '085693427433', 'default.png', '2025-12-31 08:08:49', '2026-01-02 09:52:37'),
(8, 'Muhammad Iqbal', NULL, 'muhammad.iqbal', 'a30d0068e64ed4ef45f0d1652def4ddf', 'santri', 'Laki-laki', '2008-09-12', 'Purwakarta', '081234567897', 'default.png', '2025-12-31 08:08:49', '2026-01-02 12:57:02'),
(9, 'Elisya Ayu Cristin', NULL, 'elisya.a.c', 'a30d0068e64ed4ef45f0d1652def4ddf', 'santri', 'Perempuan', '2006-12-05', 'Tanggerang', '0883120298949', 'default.png', '2025-12-31 08:08:49', '2026-01-02 09:53:37'),
(11, 'Hafizah Rahmawati', NULL, 'hafizah.rahma', 'a30d0068e64ed4ef45f0d1652def4ddf', 'santri', 'Perempuan', '2006-11-18', 'Cirebon', '081234567800', 'default.png', '2025-12-31 08:08:49', '2026-01-02 12:56:15'),
(14, 'Ustadz Ahmad', NULL, 'ustadz.ahmad', '0192023a7bbd73250516f069df18b500', 'admin', 'Laki-laki', '1985-08-10', 'Pondok Pesantren Asshiddiqiyah', '081234567810', 'default.png', '2025-12-31 08:08:49', '2025-12-31 08:08:49'),
(15, 'Ustadzah Fatimah', NULL, 'ustadzah.fatimah', '0192023a7bbd73250516f069df18b500', 'admin', 'Perempuan', '1988-03-15', 'Pondok Pesantren Asshiddiqiyah', '081234567811', 'default.png', '2025-12-31 08:08:49', '2025-12-31 08:08:49'),
(16, 'Azzahra Salsabila', NULL, 'azzahrasalsabilz', '12c156d1ff3a7ef8bbb5807ed28c752b', 'admin', 'Perempuan', '2005-11-19', 'Desa Cilamaya Girang, Dusun Mekar Jaya RT 01 RW 02', '083138962889', '6957ff35b59bc.jpg', '2025-12-31 14:40:34', '2026-01-04 12:36:07'),
(17, 'Nadia Miftahul Jannah', NULL, 'nadia.miftahul', '0bede5de6ee7c4eaf62c77cd7b62c108', 'santri', 'Perempuan', '2006-05-01', 'Cilamaya girang', '083175795125', '69574e674b810_1767329383.jpeg', '2025-12-31 15:50:35', '2026-01-04 12:39:56'),
(18, 'Nadila', NULL, 'na.dila', 'ce3478df962fd53d63976da71e06dc05', 'santri', 'Perempuan', '2005-12-15', 'Cikampek', '0881025284185', 'default.png', '2025-12-31 16:13:56', '2026-01-02 12:58:29'),
(19, 'Ahmad Sihabudin', NULL, 'ahmad.sihabudin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'Laki-laki', '2026-01-01', 'Rawameneng', '08515482449', 'default.png', '2026-01-01 11:32:36', '2026-01-01 11:32:36'),
(20, 'Naurah Cinta Anindra', NULL, 'naurah.cinta', '4c7d3759cfae5dae23c8643b442143f7', 'santri', 'Perempuan', '2007-09-02', 'Indramayu', '083815194865', 'default.png', '2026-01-02 09:27:38', '2026-01-02 13:01:04'),
(21, 'Adi Sulaeman', NULL, 'adi.sulaeman', 'b9334bbe3ea1c8852a22af599f6e6df7', 'santri', 'Laki-laki', '2005-02-01', 'Karawang Barat', '085150824483', 'default.png', '2026-01-02 09:33:21', '2026-01-04 09:56:04'),
(22, 'Nang Hadi', NULL, 'nang.hadi', '1fb34a620125021901ccd19c6ba2b509', 'santri', 'Laki-laki', '2005-11-10', 'Sukamandi', '082125278912', 'default.png', '2026-01-02 09:40:48', '2026-01-02 09:55:51'),
(23, 'Saripudin', NULL, 'sarip.udin', 'c0597cd65cd16d92c1704e696e33cc4e', 'santri', 'Laki-laki', '2006-12-20', 'Subang', '085692456008', 'default.png', '2026-01-02 09:42:47', '2026-01-02 09:57:04'),
(24, 'Syntia Ainur Dinata', NULL, 'aelia', 'bf7922f2c6502d735306685bcbf7141d', 'santri', 'Perempuan', '2005-11-29', 'Karawang Timur', '083815194826', 'default.png', '2026-01-02 13:04:12', '2026-01-02 13:04:12'),
(25, 'Lidiya Safitri', NULL, 'lidiya.safitri', 'e76579c64678a6cb0e1be19675734e9d', 'santri', 'Perempuan', '2005-10-10', 'Cikarang', '082321266619', 'default.png', '2026-01-02 13:06:11', '2026-01-02 13:06:11'),
(30, 'Dina Angletia', NULL, 'angletia', '4d50cc86174ab655d294776ec10db965', 'santri', 'Perempuan', '2005-07-16', 'Padang', '082578987612', 'default.png', '2026-01-04 09:54:58', '2026-01-04 09:54:58'),
(32, 'Dedi Wijaya', NULL, 'dedi.wijaya', '8952bbed6503eb7621bb2a417a487303', 'santri', 'Laki-laki', '2006-10-07', 'Bandung', '084912345247', 'default.png', '2026-01-04 10:03:44', '2026-01-04 10:03:44'),
(33, 'Alfina Damayanti', NULL, 'alfina.damayanti', '3db3e4dc19bb27e51493bf96d0aeadb4', 'santri', 'Perempuan', '2005-11-05', 'Banjarmasin', '087234781231', 'default.png', '2026-01-04 10:14:23', '2026-01-04 10:14:23'),
(34, 'Bayu Saputra', NULL, 'bayu.saputra', '000956b948de7d2184664890b7f97e0a', 'santri', 'Laki-laki', '2007-06-15', 'Karawang Barat', '081567823451', 'default.png', '2026-01-04 12:25:31', '2026-01-04 12:25:31');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `catatan_harian`
--
ALTER TABLE `catatan_harian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`);

--
-- Indeks untuk tabel `kategori_pelanggaran`
--
ALTER TABLE `kategori_pelanggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dibuat_oleh` (`dibuat_oleh`);

--
-- Indeks untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `dicatat_oleh` (`dicatat_oleh`);

--
-- Indeks untuk tabel `pembinaan`
--
ALTER TABLE `pembinaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `pelanggaran_id` (`pelanggaran_id`),
  ADD KEY `pembina_id` (`pembina_id`);

--
-- Indeks untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `dicatat_oleh` (`dicatat_oleh`);

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
-- AUTO_INCREMENT untuk tabel `catatan_harian`
--
ALTER TABLE `catatan_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `kategori_pelanggaran`
--
ALTER TABLE `kategori_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `pembinaan`
--
ALTER TABLE `pembinaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `catatan_harian`
--
ALTER TABLE `catatan_harian`
  ADD CONSTRAINT `catatan_harian_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  ADD CONSTRAINT `materi_edukasi_ibfk_1` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD CONSTRAINT `pelanggaran_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pelanggaran_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pelanggaran` (`id`),
  ADD CONSTRAINT `pelanggaran_ibfk_3` FOREIGN KEY (`dicatat_oleh`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `pembinaan`
--
ALTER TABLE `pembinaan`
  ADD CONSTRAINT `pembinaan_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembinaan_ibfk_2` FOREIGN KEY (`pelanggaran_id`) REFERENCES `pelanggaran` (`id`),
  ADD CONSTRAINT `pembinaan_ibfk_3` FOREIGN KEY (`pembina_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  ADD CONSTRAINT `prestasi_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestasi_ibfk_2` FOREIGN KEY (`dicatat_oleh`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

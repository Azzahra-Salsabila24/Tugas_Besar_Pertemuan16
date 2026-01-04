<?php
// config.php - Konfigurasi Database
session_start();

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pesantren_asshiddiqiyah');

// Koneksi ke Database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8");

// Fungsi untuk mencegah SQL Injection
function escape($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

// Fungsi untuk cek login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk cek role admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fungsi untuk cek role santri
function isSantri() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'santri';
}

// Fungsi untuk redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../index.php");
        exit();
    }
}

// Fungsi untuk redirect jika bukan admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        // Jika bukan admin, arahkan ke halaman login utama, bukan ke dashboard lain
        header("Location: " . BASE_URL . "index.php"); 
        exit();
    }
}

// Fungsi untuk redirect jika bukan santri
function requireSantri() {
    requireLogin();
    if (!isSantri()) {
        // Jika bukan santri, arahkan ke halaman login utama
        header("Location: " . BASE_URL . "index.php");
        exit();
    }
}

// Fungsi untuk format tanggal indonesia
function formatTanggalIndo($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Fungsi untuk upload file
function uploadFile($file, $target_dir, $allowed_types = ['jpg', 'jpeg', 'png', 'pdf']) {
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    $file_error = $file['error'];
    
    if ($file_error !== 0) {
        return ['success' => false, 'message' => 'Terjadi kesalahan saat upload file'];
    }
    
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_types)) {
        return ['success' => false, 'message' => 'Format file tidak diizinkan'];
    }
    
    if ($file_size > 5000000) { // 5MB
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }
    
    $new_file_name = uniqid() . '_' . time() . '.' . $file_ext;
    $target_file = $target_dir . $new_file_name;
    
    if (move_uploaded_file($file_tmp, $target_file)) {
        return ['success' => true, 'filename' => $new_file_name];
    } else {
        return ['success' => false, 'message' => 'Gagal mengupload file'];
    }
}

// Base URL
define('BASE_URL', 'http://localhost/pesantren_asshiddiqiyah/');
?>
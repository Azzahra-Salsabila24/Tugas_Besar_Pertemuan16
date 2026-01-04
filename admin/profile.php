<?php
require_once '../config.php';
requireAdmin();

// Set Charset Untuk Aksen Arab
mysqli_set_charset($conn, "utf8mb4");

$page_title = 'Profile Akun';
$success = '';
$error = '';

$user_id = $_SESSION['user_id'];

// 1. Ambil Data User Terbaru Dari Database
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);

// 2. Proses Update Profile
if (isset($_POST['update_profile'])) {
    $nama_lengkap = escape($_POST['nama_lengkap']);
    $username = escape($_POST['username']);
    $jenis_kelamin = escape($_POST['jenis_kelamin']);
    $tanggal_lahir = escape($_POST['tanggal_lahir']);
    $alamat = escape($_POST['alamat']);
    $nomor_telepon = escape($_POST['nomor_telepon']);
    
    // Cek Apakah Username Sudah Dipakai Orang Lain
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND id != '$user_id'");
    
    if (mysqli_num_rows($cek) > 0) {
        $error = 'Username sudah digunakan!';
    } else {
        $foto_baru = $user['foto_profil'];

        // LOGIKA UPLOAD FOTO
        if (!empty($_FILES['foto_profil']['name'])) {
            $nama_file = $_FILES['foto_profil']['name'];
            $ukuran_file = $_FILES['foto_profil']['size'];
            $tmp_file = $_FILES['foto_profil']['tmp_name'];
            $error_file = $_FILES['foto_profil']['error'];
            
            $ekstensi_valid = ['jpg', 'jpeg', 'png'];
            $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

            if (!in_array($ekstensi, $ekstensi_valid)) {
                $error = "Ekstensi file tidak valid (Gunakan JPG/PNG)";
            } elseif ($ukuran_file > 5000000) {
                $error = "Ukuran file terlalu besar (Max 5MB)";
            } elseif ($error_file === 0) {
                $nama_file_baru = uniqid() . '.' . $ekstensi;
                $target_dir = '../assets/images/';
                
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                if (move_uploaded_file($tmp_file, $target_dir . $nama_file_baru)) {
                    if ($user['foto_profil'] != 'default.png' && file_exists($target_dir . $user['foto_profil'])) {
                        @unlink($target_dir . $user['foto_profil']);
                    }
                    $foto_baru = $nama_file_baru;
                } else {
                    $error = "Gagal memindahkan file ke server.";
                }
            }
        }

        if (empty($error)) {
            $query_update = "UPDATE users SET 
                            nama_lengkap = '$nama_lengkap',
                            username = '$username',
                            jenis_kelamin = '$jenis_kelamin',
                            tanggal_lahir = '$tanggal_lahir',
                            alamat = '$alamat',
                            nomor_telepon = '$nomor_telepon',
                            foto_profil = '$foto_baru'
                            WHERE id = '$user_id'";
            
            if (mysqli_query($conn, $query_update)) {
                $_SESSION['nama_lengkap'] = $nama_lengkap;
                $_SESSION['username'] = $username;
                $_SESSION['foto_profil'] = $foto_baru;
                
                $success = 'Profile berhasil diupdate!';
                $user['foto_profil'] = $foto_baru; 
            } else {
                $error = 'Gagal menyimpan ke database!';
            }
        }
    }
}

// 3. Proses Update Password
if (isset($_POST['update_password'])) {
    $password_lama = md5($_POST['password_lama']);
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password'];
    
    if ($password_lama != $user['password']) {
        $error = 'Password lama tidak sesuai!';
    } elseif ($password_baru != $konfirmasi) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $pass_final = md5($password_baru);
        if (mysqli_query($conn, "UPDATE users SET password = '$pass_final' WHERE id = '$user_id'")) {
            $success = 'Password berhasil diubah!';
        }
    }
}

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .font-arab { font-family: 'Amiri', serif; }
    .bg-emerald { background-color: #059669 !important; }
    .text-emerald { color: #059669 !important; }
    .card-modern { border: none; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); }
    .input-modern { border-radius: 12px; padding: 0.75rem; border: 1px solid #e2e8f0; background-color: #f8fafc; transition: all 0.3s; }
    .input-modern:focus { box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); border-color: #059669; outline: none; background-color: #fff; }
    .profile-img { width: 130px; height: 130px; object-fit: cover; border: 5px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_admin.php'; ?>

    <div class="flex-grow-1" id="content-wrapper">
        <div class="container-fluid px-4 py-4">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-12">
                <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Pengaturan Profile</h3>
                    <p class="text-emerald fw-bold font-arab mb-0" style="font-size: 1.2rem;" dir="rtl">تعديل الملف الشخصي والإعدادات</p>
                </div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-check-circle me-2"></i><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-exclamation-triangle me-2"></i><?= $error ?></div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card card-modern">
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="text-center mb-5">
                                    <div class="position-relative d-inline-block">
                                        <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $user['foto_profil']; ?>" 
                                             class="rounded-circle profile-img mb-3"
                                             onerror="this.src='<?php echo BASE_URL; ?>assets/images/default.png'">
                                        <label class="btn btn-sm btn-dark position-absolute bottom-0 end-0 rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                            <i class="fas fa-camera"></i>
                                            <input type="file" name="foto_profil" hidden>
                                        </label>
                                    </div>
                                    <h5 class="fw-bold mb-0 mt-2"><?php echo $user['nama_lengkap']; ?></h5>
                                    <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill small">Administrator Account</span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="form-control input-modern" value="<?php echo $user['nama_lengkap']; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Username</label>
                                        <input type="text" name="username" class="form-control input-modern" value="<?php echo $user['username']; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-select input-modern">
                                            <option value="Laki-laki" <?php echo $user['jenis_kelamin']=='Laki-laki'?'selected':''; ?>>Laki-laki</option>
                                            <option value="Perempuan" <?php echo $user['jenis_kelamin']=='Perempuan'?'selected':''; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">No. Telepon</label>
                                        <input type="text" name="nomor_telepon" class="form-control input-modern" value="<?php echo $user['nomor_telepon']; ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold small">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control input-modern" value="<?php echo $user['tanggal_lahir']; ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold small">Alamat</label>
                                        <textarea name="alamat" class="form-control input-modern" rows="3"><?php echo $user['alamat']; ?></textarea>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top">
                                    <button type="submit" name="update_profile" class="btn bg-emerald text-white px-5 py-3 rounded-3 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i>SIMPAN PERUBAHAN
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-modern h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 d-flex align-items-center">
                                <i class="fas fa-lock text-warning me-2"></i> Keamanan Akun
                            </h5>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Password Lama</label>
                                    <input type="password" name="password_lama" class="form-control input-modern" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Password Baru</label>
                                    <input type="password" name="password_baru" class="form-control input-modern" required minlength="6">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Konfirmasi Password Baru</label>
                                    <input type="password" name="konfirmasi_password" class="form-control input-modern" required>
                                </div>
                                <div class="pt-2">
                                    <button type="submit" name="update_password" class="btn btn-warning text-dark w-100 py-3 rounded-3 fw-bold">
                                        <i class="fas fa-key me-2"></i>UBAH PASSWORD
                                    </button>
                                </div>
                            </form>
                            
                            <div class="mt-4 p-3 bg-light rounded-4 border-start border-4 border-warning">
                                <small class="text-muted">Gunakan minimal 6 karakter dengan kombinasi angka untuk keamanan lebih baik.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
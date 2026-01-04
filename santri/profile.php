<?php
require_once '../config.php';
requireSantri();

$page_title = 'Profil Saya';
$success = '';
$error = '';

$user_id = $_SESSION['user_id'];

// Ambil Data User
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);

// Proses Update Profile
if (isset($_POST['update_profile'])) {
    $nomor_telepon = escape($_POST['nomor_telepon']);
    
    if (!empty($_FILES['foto_profil']['name'])) {
        $upload = uploadFile($_FILES['foto_profil'], '../assets/images/', ['jpg', 'jpeg', 'png']);
        if ($upload['success']) {
            if ($user['foto_profil'] != 'default.png') {
                @unlink('../assets/images/' . $user['foto_profil']);
            }
            $foto_profil = $upload['filename'];
            $query_update = "UPDATE users SET nomor_telepon = '$nomor_telepon', foto_profil = '$foto_profil' WHERE id = '$user_id'";
        } else {
            $error = $upload['message'];
        }
    } else {
        $query_update = "UPDATE users SET nomor_telepon = '$nomor_telepon' WHERE id = '$user_id'";
    }
    
    if (isset($query_update) && mysqli_query($conn, $query_update)) {
        if (isset($foto_profil)) { $_SESSION['foto_profil'] = $foto_profil; }
        $success = 'Profil berhasil diperbarui!';
        $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
        $user = mysqli_fetch_assoc($query);
    } else if (!isset($error)) {
        $error = 'Gagal memperbarui profil!';
    }
}

// Proses Update Password
if (isset($_POST['update_password'])) {
    $password_lama = md5($_POST['password_lama']);
    $password_baru = md5($_POST['password_baru']);
    $konfirmasi_password = md5($_POST['konfirmasi_password']);
    
    if ($password_lama != $user['password']) {
        $error = 'Password lama tidak sesuai!';
    } elseif ($password_baru != $konfirmasi_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $query_update_pass = "UPDATE users SET password = '$password_baru' WHERE id = '$user_id'";
        if (mysqli_query($conn, $query_update_pass)) {
            $success = 'Password berhasil diubah!';
        } else {
            $error = 'Gagal mengubah password!';
        }
    }
}

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<style>
    .font-arab { font-family: 'Amiri', serif; }
    .profile-header {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .profile-header::after {
        content: 'الشَّخْصِيَّة';
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-family: 'Amiri', serif;
        font-size: 8rem;
        opacity: 0.1;
    }
    .profile-img-container {
        position: relative;
        display: inline-block;
    }
    .profile-img-container img {
        border: 5px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    .info-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px;
        border-left: 4px solid #10b981;
    }
    .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
</style>

<div class="d-flex">
    <?php include '../includes/sidebar_santri.php'; ?>
    
    <div class="flex-grow-1 bg-light min-vh-100">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm mb-4 px-4 py-3">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 fw-bold me-3 text-dark"><i class="fas fa-user-circle me-2 text-success"></i> Profil Saya</h4>
                <h4 dir="rtl" lang="ar" class="mb-0 font-arab text-muted d-none d-md-block">مَلَفِّي الشَّخْصِي</h4>
            </div>
        </nav>

        <div class="container-fluid px-4">
            <?php if ($success): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-3">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-3">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="profile-header shadow-sm text-center text-md-start">
                <div class="row align-items-center">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <div class="profile-img-container">
                            <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $user['foto_profil']; ?>" 
                                 class="rounded-circle shadow" width="130" height="130" style="object-fit: cover;"
                                 onerror="this.src='<?php echo BASE_URL; ?>assets/images/default.png'">
                        </div>
                    </div>
                    <div class="col-md">
                        <h2 class="fw-bold mb-1"><?php echo $user['nama_lengkap']; ?></h2>
                        <p class="mb-2 opacity-75"><i class="fas fa-id-badge me-2"></i>Santri Pesantren • ID #<?php echo $user_id; ?></p>
                        <span class="badge bg-white text-success px-3 py-2 rounded-pill fw-bold">AKTIF</span>
                    </div>
                </div>
            </div>

            <div class="card card-custom mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">
                        <i class="fas fa-address-card me-2 text-success"></i>Informasi Data Diri
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="info-box">
                                <small class="text-muted d-block">Username</small>
                                <span class="fw-bold"><?php echo $user['username']; ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <small class="text-muted d-block">Jenis Kelamin</small>
                                <span class="fw-bold"><?php echo $user['jenis_kelamin']; ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <small class="text-muted d-block">Telepon</small>
                                <span class="fw-bold text-success"><?php echo $user['nomor_telepon'] ?: '-'; ?></span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="info-box border-success">
                                <small class="text-muted d-block">Alamat Lengkap</small>
                                <span class="fw-bold"><?php echo $user['alamat']; ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box border-warning">
                                <small class="text-muted d-block">Tanggal Lahir</small>
                                <span class="fw-bold"><?php echo formatTanggalIndo($user['tanggal_lahir']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card card-custom h-100">
                        <div class="card-header bg-white pt-4 px-4 border-0">
                            <h5 class="fw-bold mb-0">Perbarui Kontak & Foto</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Ubah Foto Profil</label>
                                    <input type="file" name="foto_profil" class="form-control form-control-sm border-0 bg-light" accept="image/*">
                                    <div class="form-text x-small text-muted">Gunakan foto rapi berseragam (JPG/PNG).</div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted">Nomor WhatsApp Aktif</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fab fa-whatsapp text-success"></i></span>
                                        <input type="text" name="nomor_telepon" class="form-control border-0 bg-light px-3" value="<?php echo $user['nomor_telepon']; ?>" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-success w-100 rounded-pill py-2 shadow-sm">
                                    <i class="fas fa-sync-alt me-2"></i>Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card card-custom h-100 border-start border-warning border-4">
                        <div class="card-header bg-white pt-4 px-4 border-0 d-flex justify-content-between">
                            <h5 class="fw-bold mb-0">Keamanan Akun</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST">
                                <div class="mb-3">
                                    <input type="password" name="password_lama" class="form-control border-0 bg-light" placeholder="Password Lama" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="password_baru" class="form-control border-0 bg-light" placeholder="Password Baru (Min. 6 Karakter)" required minlength="6">
                                </div>
                                <div class="mb-4">
                                    <input type="password" name="konfirmasi_password" class="form-control border-0 bg-light" placeholder="Konfirmasi Password Baru" required minlength="6">
                                </div>
                                <button type="submit" name="update_password" class="btn btn-warning w-100 rounded-pill py-2 text-white fw-bold shadow-sm">
                                    <i class="fas fa-key me-2"></i>Ganti Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert bg-white shadow-sm border-0 rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-soft-success p-3 rounded-circle me-4">
                        <i class="fas fa-headset fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Ingin mengubah Nama atau Alamat?</h6>
                        <p class="small text-muted mb-0">Demi keamanan data, perubahan identitas inti harus dilakukan melalui <strong>Bagian Administrasi Pesantren</strong> dengan membawa identitas diri yang sah.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
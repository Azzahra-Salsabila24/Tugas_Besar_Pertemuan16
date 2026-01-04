<?php
require_once 'config.php';

// Jika sudah login,redirect ke dashboard
if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: santri/dashboard.php");
    }
    exit();
}

$error = '';
$success = '';

// Proses register
if (isset($_POST['register'])) {
    $nama_lengkap = escape($_POST['nama_lengkap']);
    $username = escape($_POST['username']);
    $password = md5($_POST['password']);
    $konfirmasi_password = md5($_POST['konfirmasi_password']);
    $role = escape($_POST['role']);
    $jenis_kelamin = escape($_POST['jenis_kelamin']);
    $tanggal_lahir = escape($_POST['tanggal_lahir']);
    $alamat = escape($_POST['alamat']);
    $nomor_telepon = escape($_POST['nomor_telepon']);
    
    if ($password != $konfirmasi_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Username sudah digunakan!';
        } else {
            $query = "INSERT INTO users (nama_lengkap, username, password, role, jenis_kelamin, tanggal_lahir, alamat, nomor_telepon) 
                      VALUES ('$nama_lengkap', '$username', '$password', '$role', '$jenis_kelamin', '$tanggal_lahir', '$alamat', '$nomor_telepon')";
            
            if (mysqli_query($conn, $query)) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Registrasi gagal! Silakan coba lagi.';
            }
        }
    }
}

// Proses login
if (isset($_POST['login'])) {
    $username = escape($_POST['username']);
    $password = md5($_POST['password']);
    
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['foto_profil'] = $user['foto_profil'];
        
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: santri/dashboard.php");
        }
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk & Daftar - Asshiddiqiyah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0fdf4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .font-arab { font-family: 'Amiri', serif; }
        
        .main-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
            border: none;
        }

        .side-info {
            background: linear-gradient(135deg, #059669 0%, #064e3b 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .side-info::before {
            content: 'مُذَكِّرَات';
            position: absolute;
            top: 20px;
            right: 20px;
            font-family: 'Amiri', serif;
            font-size: 5rem;
            opacity: 0.1;
        }

        .nav-pills .nav-link {
            color: #64748b;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 24px;
        }

        .nav-pills .nav-link.active {
            background-color: #059669;
            color: white;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
            border-color: #059669;
        }

        .btn-auth {
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-primary-custom {
            background-color: #059669;
            border: none;
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: #047857;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="main-card card">
    <div class="row g-0">
        <div class="col-lg-5 side-info d-none d-lg-flex">
            <div class="text-center mb-4">
                <i class="fas fa-mosque fa-4x mb-3"></i>
                <h2 dir="rtl" class="font-arab mb-1" style="font-size: 3rem;">مَعْهَدُ الصِّدِّيقِيَّةِ</h2>
                <p class="small opacity-75">Asshiddiqiyah Islamic Boarding School</p>
            </div>
            
            <p class="text-center px-4">Sistem Informasi Pemantauan & Edukasi Santri.</p>
            
            <div class="mt-4 p-3 bg-white bg-opacity-10 rounded-4">
                <small class="d-block mb-2 text-warning fw-bold"><i class="fas fa-key me-2"></i>Akses Demo:</small>
                <div class="small">
                    Admin: <code>admin / admin123</code><br>
                    Santri: <code>santri / santri123</code>
                </div>
            </div>
        </div>

        <div class="col-lg-7 p-4 p-md-5">
            <div class="text-center d-lg-none mb-4">
                <h3 dir="rtl" class="font-arab text-success fs-1">مَعْهَدُ الصِّدِّيقِيَّةِ</h3>
            </div>
             <div class="text-center">
    <h3 class="fw-bold mb-2">
        <span class="font-arab fs-2 text-success me-2" dir="rtl">أَهْلًا وَسَهْلًا</span> 
    </h3>
    <p class="text-muted mb-4">Silakan masuk untuk mengakses sistem informasi pemantauan & edukasi santri.</p>
</div>   
            <?php if ($error): ?>
                <div class="alert alert-danger border-0 rounded-4 small shadow-sm"><i class="fas fa-circle-exclamation me-2"></i><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success border-0 rounded-4 small shadow-sm"><i class="fas fa-check-circle me-2"></i><?php echo $success; ?></div>
            <?php endif; ?>

            <ul class="nav nav-pills mb-4 bg-light p-1 rounded-3" id="authTab" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login-pane" type="button">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100" id="register-tab" data-bs-toggle="pill" data-bs-target="#register-pane" type="button">
                        <i class="fas fa-user-plus me-2"></i>Daftar
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="login-pane">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary-custom btn-auth w-100 shadow">
                            Masuk Ke Sistem
                        </button>
                    </form>
                </div>

                <div class="tab-pane fade" id="register-pane" style="max-height: 450px; overflow-y: auto; padding-right: 10px;">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama sesuai ijazah" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Untuk login" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Daftar Sebagai</label>
                                <select name="role" class="form-select" style="border-radius: 12px; padding: 12px;" required>
                                    <option value="santri">Santri</option>
                                    <option value="admin">Admin / Pengasuh</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Password</label>
                                <input type="password" name="password" class="form-control" required minlength="6">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Konfirmasi</label>
                                <input type="password" name="konfirmasi_password" class="form-control" required minlength="6">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" style="border-radius: 12px; padding: 12px;" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary-custom btn-auth w-100 mt-4 shadow-sm">
                            Daftar Sekarang
                        </button>
                    </form>
                </div>
            </div>
            
            <p class="text-center text-muted small mt-4">
                &copy; 2026 PP Asshiddiqiyah Karawang
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
require_once '../config.php';
requireSantri();

$page_title = 'Dashboard Santri';
$santri_id = $_SESSION['user_id'];

// Ambil Data Santri
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$santri_id'");
$user = mysqli_fetch_assoc($query_user);

// Statistik (Data Pribadi Santri)
$total_pelanggaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggaran WHERE santri_id = '$santri_id'"))['total'];
$total_prestasi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM prestasi WHERE santri_id = '$santri_id'"))['total'];
$total_pembinaan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembinaan WHERE santri_id = '$santri_id'"))['total'];

// Data Terbaru
$query_pelanggaran_terbaru = mysqli_query($conn, "
    SELECT p.*, k.nama_kategori 
    FROM pelanggaran p 
    JOIN kategori_pelanggaran k ON p.kategori_id = k.id 
    WHERE p.santri_id = '$santri_id' 
    ORDER BY p.tanggal_pelanggaran DESC LIMIT 5
");

$query_materi = mysqli_query($conn, "SELECT * FROM materi_edukasi ORDER BY created_at DESC LIMIT 5");

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0fdf4; }
    .font-arab { font-family: 'Amiri', serif; }

    /* Custom Navbar */
    .topbar { background: white; border-bottom: 1px solid #e2e8f0; padding: 0.75rem 1.5rem; }
    .user-avatar { width: 40px; height: 40px; border-radius: 12px; object-fit: cover; border: 2px solid #059669; }

    /* Stat Card */
    .stat-card-custom {
        background: white; border: none; border-radius: 24px; padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        position: relative; overflow: hidden; border: 1px solid rgba(5, 150, 105, 0.05);
    }
    .stat-card-custom:hover { transform: translateY(-10px); box-shadow: 0 20px 25px -5px rgba(5, 150, 105, 0.1); }
    
    .stat-card-custom i.bg-icon {
        position: absolute; right: -15px; bottom: -15px; font-size: 5rem; opacity: 0.05; color: #064e3b;
    }

    .icon-box {
        width: 54px; height: 54px; border-radius: 16px; display: flex; align-items: center;
        justify-content: center; margin-bottom: 20px; font-size: 1.4rem;
    }

    /* Color Variations */
    .bg-emerald-light { background-color: #ecfdf5; color: #059669; }
    .bg-amber-light { background-color: #fffbeb; color: #d97706; }
    .bg-blue-light { background-color: #eff6ff; color: #2563eb; }
    .bg-purple-light { background-color: #faf5ff; color: #7c3aed; }

    /* Table & Card */
    .card-modern { border: none; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); background: white; }
    .card-modern .card-header { 
        background: white; border-bottom: 1px solid #f1f5f9; padding: 1.5rem; border-radius: 24px 24px 0 0;
    }
    
    .btn-emerald { background: #059669; color: white; border: none; transition: all 0.3s; }
    .btn-emerald:hover { background: #047857; color: white; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3); }

    .table thead th { 
        background: #f8fafc; color: #64748b; font-size: 0.7rem; 
        text-transform: uppercase; letter-spacing: 1px; padding: 15px;
    }

    /* Card Hadits */
    .card-hadits {
        border: none; border-radius: 24px; background: white;
        border-left: 6px solid #059669; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_santri.php'; ?>

    <div class="flex-grow-1" id="content-wrapper">
        <nav class="navbar navbar-expand navbar-light topbar mb-4 shadow-sm sticky-top">
            <button class="btn btn-link d-md-none text-success" id="sidebarToggleTop">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-none d-md-block ms-2">
                <span class="text-muted small">Panel Kendali Santri</span>
                <h6 class="fw-bold mb-0 text-dark font-arab" dir="rtl">نظام إدارة الطلاب</h6>
            </div>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown">
                        <div class="text-end me-3 d-none d-lg-block">
                            <span class="fw-bold text-dark d-block" style="line-height: 1;"><?php echo $user['nama_lengkap']; ?></span>
                            <small class="text-success fw-semibold text-uppercase" style="font-size: 10px;">Santri Aktif</small>
                        </div>
                        <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $_SESSION['foto_profil']; ?>" 
                         class="rounded-circle border" width="40" height="40" style="object-fit: cover;"
                         onerror="this.src='<?php echo BASE_URL; ?>assets/images/default.png'">                    </a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid px-4">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h3 class="fw-bold text-dark mb-0">Dashboard Santri</h3>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card-custom">
                        <div class="icon-box bg-emerald-light">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Prestasi</h6>
                        <h2 class="fw-bold mb-0"><?php echo $total_prestasi; ?></h2>
                        <small class="text-success">Pencapaian kamu</small>
                        <i class="fas fa-award bg-icon"></i>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card-custom">
                        <div class="icon-box bg-amber-light">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Pelanggaran</h6>
                        <h2 class="fw-bold mb-0"><?php echo $total_pelanggaran; ?></h2>
                        <small class="text-warning">Poin kedisiplinan</small>
                        <i class="fas fa-gavel bg-icon"></i>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card-custom">
                        <div class="icon-box bg-blue-light">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Pembinaan</h6>
                        <h2 class="fw-bold mb-0"><?php echo $total_pembinaan; ?></h2>
                        <small class="text-primary">Sesi bimbingan</small>
                        <i class="fas fa-heart bg-icon"></i>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card-custom">
                        <div class="icon-box bg-purple-light">
                            <i class="fas fa-book"></i>
                        </div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Materi Edukasi</h6>
                        <h2 class="fw-bold mb-0"><?php echo mysqli_num_rows($query_materi); ?></h2>
                        <small class="text-purple">Bahan belajar baru</small>
                        <i class="fas fa-book-open bg-icon"></i>
                    </div>
                </div>
            </div>

            <div class="card card-hadits mb-4">
                <div class="card-body p-4 text-center">
                    <h4 dir="rtl" class="font-arab text-success mb-3" style="line-height: 1.8; font-size: 1.8rem;">
                        مَنْ سَلَكَ طَرِيقًا يَلْتَمِسُ فِيهِ عِلْمًا، سَهَّلَ اللَّهُ لَهُ بِهِ طَرِيقًا إِلَى الْجَنَّةِ
                    </h4>
                    <p class="text-muted fst-italic mb-1">"Barangsiapa menempuh suatu jalan untuk menuntut ilmu, maka Allah akan memudahkan baginya jalan menuju surga."</p>
                    <small class="fw-bold text-success text-uppercase">— HR. Muslim</small>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card card-modern h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Riwayat Catatan Terbaru</h6>
                                <small class="text-muted">Pantau kedisiplinanmu di sini</small>
                            </div>
                            <a href="my_pelanggaran.php" class="btn btn-sm btn-outline-success border-2 rounded-pill px-3 fw-bold" style="font-size: 11px;">LIHAT SEMUA</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Detail Pelanggaran</th>
                                            <th>Tanggal</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($query_pelanggaran_terbaru) > 0): ?>
                                            <?php while ($row = mysqli_fetch_assoc($query_pelanggaran_terbaru)): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-soft bg-emerald-light rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                            <i class="fas fa-info-circle" style="font-size: 12px;"></i>
                                                        </div>
                                                        <span class="fw-semibold text-dark"><?php echo $row['nama_kategori']; ?></span>
                                                    </div>
                                                </td>
                                                <td class="small text-muted"><?php echo date('d M Y', strtotime($row['tanggal_pelanggaran'])); ?></td>
                                                <td class="text-center">
                                                    <?php 
                                                    $bg = 'bg-warning text-dark';
                                                    if ($row['status'] == 'Selesai') $bg = 'bg-success text-white';
                                                    elseif ($row['status'] == 'Sedang Pembinaan') $bg = 'bg-info text-white';
                                                    ?>
                                                    <span class="badge rounded-pill <?php echo $bg; ?> px-3 py-2" style="font-size: 10px;"><?php echo $row['status']; ?></span>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-center py-5 text-muted small">Alhamdulillah, tidak ada catatan pelanggaran.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-modern h-100">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0 text-dark">Materi Terbaru</h6>
                            <small class="text-muted">Klik untuk membaca materi</small>
                        </div>
                        <div class="card-body">
                            <?php while($m = mysqli_fetch_assoc($query_materi)): ?>
                            <div class="d-flex align-items-center mb-3 p-2 border-bottom border-light">
                                <div class="bg-emerald-light rounded p-2 me-3" style="width: 40px; height: 40px; text-align: center;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="mb-0 small fw-bold text-truncate" style="max-width: 180px;"><?php echo $m['judul']; ?></h6>
                                    <small class="text-muted" style="font-size: 10px;"><?php echo $m['kategori']; ?></small>
                                </div>
                                <a href="materi_edukasi.php" class="ms-auto btn btn-sm text-emerald"><i class="fas fa-chevron-right"></i></a>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggleTop').addEventListener('click', function() {
        document.getElementById('wrapper').classList.toggle('toggled');
    });
</script>
<?php include '../includes/footer.php'; ?>
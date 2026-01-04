<?php
require_once '../config.php';
requireAdmin();

$page_title = 'Dashboard Admin';

// Statistik
$query_santri = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'santri'");
$total_santri = mysqli_fetch_assoc($query_santri)['total'];

$query_pelanggaran = mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggaran WHERE MONTH(tanggal_pelanggaran) = MONTH(CURRENT_DATE())");
$total_pelanggaran_bulan_ini = mysqli_fetch_assoc($query_pelanggaran)['total'];

$query_pembinaan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pembinaan WHERE MONTH(tanggal_pembinaan) = MONTH(CURRENT_DATE())");
$total_pembinaan_bulan_ini = mysqli_fetch_assoc($query_pembinaan)['total'];

$query_prestasi = mysqli_query($conn, "SELECT COUNT(*) as total FROM prestasi WHERE MONTH(tanggal_prestasi) = MONTH(CURRENT_DATE())");
$total_prestasi_bulan_ini = mysqli_fetch_assoc($query_prestasi)['total'];

// Pelanggaran Terbaru
$query_pelanggaran_terbaru = mysqli_query($conn, "
    SELECT p.*, u.nama_lengkap, k.nama_kategori 
    FROM pelanggaran p
    JOIN users u ON p.santri_id = u.id
    JOIN kategori_pelanggaran k ON p.kategori_id = k.id
    ORDER BY p.created_at DESC
    LIMIT 5
");

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Global Styles */
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background-color: #f8fafc; color: #1e293b; }
    .font-arab { font-family: 'Amiri', serif !important; }

    /* Navbar & Topbar */
    .topbar { background: white; border-bottom: 1px solid #e2e8f0; padding: 0.75rem 1.5rem; }
    
    /* Foto Profil */
    .user-avatar { 
        width: 42px; 
        height: 42px; 
        border-radius: 50% !important; 
        object-fit: cover; 
        border: 2px solid #10b981; 
    }
    
    .text-emerald { color: #059669 !important; }

    /* Stat Card  */
    .stat-card-custom {
        background: white; border: none; border-radius: 24px; padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        position: relative; overflow: hidden;
    }
    .stat-card-custom:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
    .stat-card-custom h2 { font-weight: 800; letter-spacing: -1px; }
    .stat-card-custom i.bg-icon {
        position: absolute; right: -10px; bottom: -10px; font-size: 4.5rem; opacity: 0.06; color: #064e3b;
    }

    /* Icon Box Palette */
    .icon-box { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; font-size: 1.2rem; }
    .bg-emerald-light { background-color: #ecfdf5; color: #059669; }
    .bg-amber-light { background-color: #fffbeb; color: #d97706; }
    .bg-blue-light { background-color: #eff6ff; color: #2563eb; }
    .bg-purple-light { background-color: #faf5ff; color: #7c3aed; }

    /* Card & Table */
    .card-modern { border: none; border-radius: 24px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); background: white; }
    .card-modern .card-header { background: white; border-bottom: 1px solid #f1f5f9; padding: 1.5rem; border-radius: 24px 24px 0 0; }
    .btn-emerald { background: #059669; color: white; border: none; font-weight: 700; font-size: 0.75rem; letter-spacing: 0.5px; transition: all 0.3s; }
    .btn-emerald:hover { background: #047857; color: white; transform: scale(1.05); }

    .table thead th { 
        background: #f8fafc; color: #64748b; font-size: 0.65rem; 
        text-transform: uppercase; letter-spacing: 1.2px; padding: 18px; border: none;
    }
    .table tbody td { padding: 15px; border-color: #f1f5f9; }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_admin.php'; ?>

    <div class="flex-grow-1" id="content-wrapper">
        <nav class="navbar navbar-expand navbar-light topbar mb-4 sticky-top">
            <button class="btn btn-link d-md-none text-success" id="sidebarToggleTop">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-none d-md-block ms-2">
                <span class="text-muted fw-medium" style="font-size: 0.75rem;">Panel Kendali Utama</span>
                <h6 class="fw-bold mb-0 text-dark font-arab" dir="rtl" style="font-size: 1.1rem;">نظام إدارة المعهد</h6>
            </div>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown">
                        <div class="text-end me-3 d-none d-lg-block">
                            <span class="fw-bold text-dark d-block" style="font-size: 0.85rem; line-height: 1.2;">
                                <?php echo $_SESSION['nama_lengkap']; ?>
                            </span>
                            <small class="text-emerald fw-bold text-uppercase" style="font-size: 9px; letter-spacing: 0.5px;">Administrator</small>
                        </div>
                        <img class="user-avatar shadow-sm" src="<?php echo BASE_URL; ?>assets/images/<?php echo $_SESSION['foto_profil']; ?>" onerror="this.src='<?php echo BASE_URL; ?>assets/images/default.png'">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3">
                        <li><a class="dropdown-item py-2 small fw-semibold" href="profile.php"><i class="fas fa-user-cog me-2 text-muted"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 small fw-semibold text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="container-fluid px-4">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Dashboard</h3>
                </div>
                <div class="col-auto">
                    <a href="laporan.php" class="btn btn-emerald rounded-pill px-4 py-2 shadow-sm">
                        <i class="fas fa-file-download me-2"></i> LAPORAN
                    </a>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card-custom">
                        <div class="icon-box bg-emerald-light"><i class="fas fa-users"></i></div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Santri</h6>
                        <h2 class="mb-0"><?php echo $total_santri; ?></h2>
                        <i class="fas fa-user-graduate bg-icon"></i>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card-custom">
                        <div class="icon-box bg-amber-light"><i class="fas fa-exclamation-triangle"></i></div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Pelanggaran</h6>
                        <h2 class="mb-0"><?php echo $total_pelanggaran_bulan_ini; ?></h2>
                        <i class="fas fa-gavel bg-icon"></i>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card-custom">
                        <div class="icon-box bg-blue-light"><i class="fas fa-hand-holding-heart"></i></div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Pembinaan</h6>
                        <h2 class="mb-0"><?php echo $total_pembinaan_bulan_ini; ?></h2>
                        <i class="fas fa-heart bg-icon"></i>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card-custom">
                        <div class="icon-box bg-purple-light"><i class="fas fa-trophy"></i></div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Prestasi</h6>
                        <h2 class="mb-0"><?php echo $total_prestasi_bulan_ini; ?></h2>
                        <i class="fas fa-award bg-icon"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card card-modern h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Aktivitas Terbaru</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">Riwayat pelanggaran terakhir</small>
                            </div>
                            <a href="pelanggaran.php" class="btn btn-sm btn-outline-success border-2 rounded-pill px-3 fw-bold" style="font-size: 10px;">SEMUA</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Santri</th>
                                            <th>Kategori</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($query_pelanggaran_terbaru)): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-emerald-light rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold text-emerald" style="width: 34px; height: 34px; font-size: 11px;">
                                                        <?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                                    </div>
                                                    <span class="fw-bold text-dark" style="font-size: 0.85rem;"><?php echo $row['nama_lengkap']; ?></span>
                                                </div>
                                            </td>
                                            <td><span class="text-muted fw-semibold" style="font-size: 0.8rem;"><?php echo $row['nama_kategori']; ?></span></td>
                                            <td class="text-center">
                                                <?php 
                                                $cls = ($row['status'] == 'Selesai') ? 'bg-success' : (($row['status'] == 'Sedang Pembinaan') ? 'bg-info' : 'bg-warning');
                                                ?>
                                                <span class="badge rounded-pill <?php echo $cls; ?> px-3 py-2" style="font-size: 9px; font-weight: 700;"><?php echo strtoupper($row['status']); ?></span>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-modern h-100">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0 text-dark">Distribusi Kasus</h6>
                            <small class="text-muted" style="font-size: 0.7rem;">Persentase penanganan</small>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center px-4">
                            <div style="height: 200px;">
                                <canvas id="chartPelanggaran"></canvas>
                            </div>
                            <div class="mt-4 text-center">
                                <span class="small fw-bold text-muted">Update: <?php echo date('F Y'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
const ctxPelanggaran = document.getElementById('chartPelanggaran').getContext('2d');
new Chart(ctxPelanggaran, {
    type: 'doughnut',
    data: {
        labels: ['Belum', 'Proses', 'Selesai'],
        datasets: [{
            data: [
                <?php 
                echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggaran WHERE status = 'Belum Ditindak'"))['total']; ?>,
                <?php echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggaran WHERE status = 'Sedang Pembinaan'"))['total']; ?>,
                <?php echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggaran WHERE status = 'Selesai'"))['total']; ?>
            ],
            backgroundColor: ['#f59e0b', '#3b82f6', '#10b981'],
            borderWidth: 0
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 10, weight: '600' } } } },
        cutout: '80%', responsive: true, maintainAspectRatio: false
    }
});
</script>
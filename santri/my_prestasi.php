<?php
require_once '../config.php';
requireSantri();

$page_title = 'Prestasi Saya';
$santri_id = $_SESSION['user_id'];

// Ambil Data Prestasi Santri Yang Login
$query_prestasi = mysqli_query($conn, "
    SELECT * FROM prestasi
    WHERE santri_id = '$santri_id'
    ORDER BY tanggal_prestasi DESC
");

// Statistik
$total_prestasi = mysqli_num_rows($query_prestasi);

$total_poin = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(poin_prestasi) as total 
    FROM prestasi
    WHERE santri_id = '$santri_id'
"))['total'] ?? 0;

$prestasi_bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM prestasi 
    WHERE santri_id = '$santri_id' 
    AND MONTH(tanggal_prestasi) = MONTH(CURRENT_DATE())
    AND YEAR(tanggal_prestasi) = YEAR(CURRENT_DATE())
"))['total'];

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<style>
    .font-arab { font-family: 'Amiri', serif; }
    .stat-card { transition: all 0.3s ease; border-radius: 15px; border: none; overflow: hidden; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-card i { position: absolute; right: 20px; bottom: 20px; font-size: 3.5rem; opacity: 0.2; }
    .achievement-item { transition: all 0.3s ease; border-radius: 15px; }
    .achievement-item:hover { border-left-width: 8px !important; background-color: #f8fafc !important; }
    .bg-soft-purple { background-color: #f5f3ff; color: #7c3aed; }
</style>

<div class="d-flex">
    <?php include '../includes/sidebar_santri.php'; ?>
    
    <div class="flex-grow-1 bg-light min-vh-100">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm mb-4 px-4 py-3">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 fw-bold me-3 text-dark"><i class="fas fa-trophy me-2 text-warning"></i> Prestasi Saya</h4>
                <h4 dir="rtl" lang="ar" class="mb-0 font-arab text-success d-none d-md-block">إِنْجَازَاتِي</h4>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <div class="text-end me-3 d-none d-sm-block">
                    <h6 class="mb-0 fw-bold small"><?php echo $_SESSION['nama_lengkap']; ?></h6>
                    <span class="badge bg-soft-purple" style="font-size: 0.7rem;">SANTRI BERPRESTASI</span>
                </div>
                <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $_SESSION['foto_profil']; ?>" 
                     class="rounded-circle shadow-sm" width="40" height="40" style="object-fit: cover;"
                     onerror="this.src='<?php echo BASE_URL; ?>assets/images/default.png'">
            </div>
        </nav>

        <div class="container-fluid px-4">
            
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm text-white h-100" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase opacity-75 small fw-bold">Total Prestasi</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo $total_prestasi; ?></h2>
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm text-white h-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase opacity-75 small fw-bold">Total Poin</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo $total_poin; ?></h2>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm text-white h-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase opacity-75 small fw-bold">Bulan Ini</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo $prestasi_bulan_ini; ?></h2>
                            <i class="fas fa-medal"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark">Riwayat Pencapaian</h5>
                            <span dir="rtl" class="font-arab text-muted">سِجِلِّ الإِنْجَازَاتِ</span>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($total_prestasi > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($query_prestasi)): 
                                    $color = ($row['poin_prestasi'] >= 40) ? '#7c3aed' : (($row['poin_prestasi'] >= 25) ? '#3b82f6' : '#10b981');
                                ?>
                                <div class="achievement-item border-start border-4 p-3 mb-3 bg-white shadow-sm" style="border-color: <?php echo $color; ?> !important;">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark"><?php echo $row['jenis_prestasi']; ?></h6>
                                            <p class="small text-muted mb-2"><i class="fas fa-calendar-alt me-1"></i> <?php echo formatTanggalIndo($row['tanggal_prestasi']); ?></p>
                                            <p class="mb-0 text-secondary"><?php echo $row['deskripsi']; ?></p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge rounded-pill px-3 py-2" style="background-color: <?php echo $color; ?>;">
                                                +<?php echo $row['poin_prestasi']; ?> Poin
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <img src="https://illustrations.popsy.co/flat/award.svg" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                    <h5 class="text-muted">Belum ada catatan prestasi</h5>
                                    <p class="small text-muted">Ayo semangat belajar dan raih mimpimu!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm text-center mb-4" style="background-color: #fffbeb; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-quote-right text-warning opacity-50 fa-2x"></i>
                            </div>
                            <h4 dir="rtl" lang="ar" class="font-arab text-warning mb-3" style="line-height: 1.6; font-size: 1.8rem;">
                                مَنْ جَدَّ وَجَدَ
                            </h4>
                            <p class="fw-bold mb-1" style="color: #92400e;">"Man Jadda Wajada"</p>
                            <p class="small text-muted mb-0">Barangsiapa yang bersungguh-sungguh, maka dia akan berhasil.</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h6 class="fw-bold"><i class="fas fa-lightbulb text-warning me-2"></i>Tips Berprestasi</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-2">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light p-2 rounded-circle me-3 text-success"><i class="fas fa-check-circle"></i></div>
                                <div class="small fw-bold text-secondary">Jaga Ibadah & Doa Orang Tua</div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light p-2 rounded-circle me-3 text-primary"><i class="fas fa-check-circle"></i></div>
                                <div class="small fw-bold text-secondary">Manajemen Waktu Yang Disiplin</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded-circle me-3 text-warning"><i class="fas fa-check-circle"></i></div>
                                <div class="small fw-bold text-secondary">Fokus Pada Proses, Bukan Hasil</div>
                            </div>
                        </div>
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
<?php
require_once '../config.php';
requireSantri();

$page_title = 'Catatan Saya';
$santri_id = $_SESSION['user_id'];

// Ambil Data Pelanggaran Santri Yang Login
$query_pelanggaran = mysqli_query($conn, "
    SELECT p.*, k.nama_kategori, k.poin_pelanggaran
    FROM pelanggaran p
    JOIN kategori_pelanggaran k ON p.kategori_id = k.id
    WHERE p.santri_id = '$santri_id'
    ORDER BY p.tanggal_pelanggaran DESC, p.waktu_pelanggaran DESC
");

// Statistik
$total_pelanggaran = mysqli_num_rows($query_pelanggaran);

$total_poin = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(k.poin_pelanggaran) as total 
    FROM pelanggaran p
    JOIN kategori_pelanggaran k ON p.kategori_id = k.id
    WHERE p.santri_id = '$santri_id'
"))['total'] ?? 0;

$belum_ditindak = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM pelanggaran 
    WHERE santri_id = '$santri_id' AND status = 'Belum Ditindak'
"))['total'];

$selesai = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM pelanggaran 
    WHERE santri_id = '$santri_id' AND status = 'Selesai'
"))['total'];

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<style>
    .font-arab { font-family: 'Amiri', serif; }
    .stat-card { transition: all 0.3s ease; border: none; overflow: hidden; border-radius: 15px; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-card i { position: absolute; right: 20px; bottom: 20px; font-size: 3rem; opacity: 0.2; }
    .violation-card { transition: all 0.3s ease; border-radius: 15px; border: 1px solid #e2e8f0; }
    .violation-card:hover { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: #cbd5e1; }
    .bg-soft-danger { background-color: #fef2f2; color: #dc2626; }
    .bg-soft-warning { background-color: #fffbeb; color: #d97706; }
    .bg-soft-success { background-color: #f0fdf4; color: #16a34a; }
    .bg-soft-secondary { background-color: #f8fafc; color: #475569; }
</style>

<div class="d-flex">
    <?php include '../includes/sidebar_santri.php'; ?>
    
    <div class="flex-grow-1 bg-light min-vh-100">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm mb-4 px-4 py-3">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 fw-bold me-3 text-dark"><i class="fas fa-clipboard-list me-2 text-danger"></i> Catatan Pelanggaran</h4>
                <h4 dir="rtl" lang="ar" class="mb-0 font-arab text-muted d-none d-md-block">سِجِلُّ الْمُخَالَفَاتِ</h4>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <div class="text-end me-3 d-none d-sm-block">
                    <h6 class="mb-0 fw-bold small"><?php echo $_SESSION['nama_lengkap']; ?></h6>
                    <span class="text-muted small">ID Santri: #<?php echo $santri_id; ?></span>
                </div>
                <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $_SESSION['foto_profil']; ?>" 
                     class="rounded-circle shadow-sm" width="40" height="40" style="object-fit: cover;"
                     onerror="this.src='<?php echo BASE_URL; ?>assets/images/default.png'">
            </div>
        </nav>

        <div class="container-fluid px-4">
            
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm text-white h-100" style="background: linear-gradient(135deg, #475569 0%, #1e293b 100%);">
                        <div class="card-body p-3 p-md-4">
                            <h6 class="small fw-bold opacity-75">TOTAL CATATAN</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_pelanggaran; ?></h2>
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm text-white h-100" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                        <div class="card-body p-3 p-md-4">
                            <h6 class="small fw-bold opacity-75">TOTAL POIN</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_poin; ?></h2>
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm text-white h-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <div class="card-body p-3 p-md-4">
                            <h6 class="small fw-bold opacity-75">PENDING</h6>
                            <h2 class="fw-bold mb-0"><?php echo $belum_ditindak; ?></h2>
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm text-white h-100" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                        <div class="card-body p-3 p-md-4">
                            <h6 class="small fw-bold opacity-75">SELESAI</h6>
                            <h2 class="fw-bold mb-0"><?php echo $selesai; ?></h2>
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="mb-0 fw-bold text-dark">Detail Riwayat</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <?php if ($total_pelanggaran > 0): ?>
                                <?php mysqli_data_seek($query_pelanggaran, 0);
                                while ($row = mysqli_fetch_assoc($query_pelanggaran)): 
                                    $status_bg = ($row['status'] == 'Selesai') ? 'bg-soft-success' : (($row['status'] == 'Belum Ditindak') ? 'bg-soft-danger' : 'bg-soft-warning');
                                ?>
                                <div class="violation-card p-4 mb-3 bg-white">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge mb-2 <?php echo $status_bg; ?> px-3">
                                                <i class="fas fa-tag me-1"></i> <?php echo $row['nama_kategori']; ?>
                                            </span>
                                            <h5 class="fw-bold text-dark mb-1"><?php echo $row['status']; ?></h5>
                                            <div class="text-muted small">
                                                <i class="fas fa-calendar-day me-1"></i> <?php echo formatTanggalIndo($row['tanggal_pelanggaran']); ?> 
                                                <span class="mx-2">|</span>
                                                <i class="fas fa-clock me-1"></i> <?php echo $row['waktu_pelanggaran']; ?>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h4 class="text-danger fw-bold mb-0">+<?php echo $row['poin_pelanggaran']; ?></h4>
                                            <small class="text-muted fw-bold">POIN</small>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-light p-3 rounded-3 mb-3">
                                        <small class="fw-bold text-secondary d-block mb-1 text-uppercase" style="font-size: 0.7rem;">Keterangan Kejadian:</small>
                                        <p class="mb-0 text-dark small"><?php echo $row['deskripsi_pelanggaran']; ?></p>
                                    </div>

                                    <?php if (!empty($row['tindakan_pembinaan'])): ?>
                                    <div class="border-top pt-3">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-soft-success p-2 rounded-circle me-3">
                                                <i class="fas fa-hand-holding-heart fa-sm"></i>
                                            </div>
                                            <div>
                                                <small class="fw-bold text-success d-block mb-1" style="font-size: 0.7rem;">TINDAKAN PEMBINAAN:</small>
                                                <p class="mb-0 text-muted small italic">"<?php echo $row['tindakan_pembinaan']; ?>"</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-check-circle fa-5x text-success opacity-25"></i>
                                    </div>
                                    <h4 dir="rtl" class="font-arab text-success mb-3" style="font-size: 2rem;">مَا شَاءَ اللهُ! لَا يُوجَدُ سِجِلُّ مُخَالَفَاتٍ</h4>
                                    <h5 class="fw-bold text-dark">Luar Biasa, Bersih dari Pelanggaran!</h5>
                                    <p class="text-muted">Alhamdulillah, pertahankan kedisiplinan dan adabmu di pondok.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <?php if ($total_pelanggaran > 0): ?>
                    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #fff1f2 0%, #fff5f5 100%); border-radius: 15px;">
                        <div class="card-body p-4 text-center">
                            <h2 dir="rtl" class="font-arab text-danger mb-3" style="font-size: 1.8rem;">خَيْرُ الْخَطَّائِينَ التَّوَّابُونَ</h2>
                            <p class="fw-bold text-danger mb-1 small">"Sebaik-baik orang yang bersalah adalah yang bertaubat."</p>
                            <p class="text-muted x-small mb-0" style="font-size: 0.8rem;">(HR. Tirmidzi & Ibnu Majah)</p>
                            <hr class="my-3 opacity-10">
                            <p class="text-secondary small mb-0">Jangan berkecil hati. Jadikan catatan ini sebagai anak tangga untuk menjadi pribadi yang lebih mulia.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="card border-0 shadow-sm rounded-4 bg-dark text-white mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-question-circle me-2 text-warning"></i>Butuh Klarifikasi?</h6>
                            <p class="small opacity-75 mb-3">Jika terdapat kesalahan pencatatan atau ingin mendiskusikan proses pembinaan, silakan hubungi:</p>
                            <div class="d-flex align-items-center bg-white bg-opacity-10 p-2 rounded-3">
                                <i class="fas fa-user-tie me-3 fs-4"></i>
                                <div>
                                    <div class="fw-bold small">Bagian Kesantrian</div>
                                    <div class="x-small opacity-50" style="font-size: 0.7rem;">Kantor Pusat Lt. 1</div>
                                </div>
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
<?php
require_once '../config.php';
requireAdmin();

// Set Charset Untuk Aksen Arab
mysqli_set_charset($conn, "utf8mb4");

$page_title = 'Laporan Statistik';

// Filter
$bulan = isset($_GET['bulan']) ? escape($_GET['bulan']) : date('m');
$tahun = isset($_GET['tahun']) ? escape($_GET['tahun']) : date('Y');

// Statistik Pelanggaran Per Kategori
$query_kategori = mysqli_query($conn, "
    SELECT k.nama_kategori, COUNT(p.id) as total
    FROM kategori_pelanggaran k
    LEFT JOIN pelanggaran p ON k.id = p.kategori_id 
        AND MONTH(p.tanggal_pelanggaran) = '$bulan' 
        AND YEAR(p.tanggal_pelanggaran) = '$tahun'
    GROUP BY k.id
    ORDER BY total DESC
");

// Santri dengan pelanggaran terbanyak
$query_top_pelanggaran = mysqli_query($conn, "
    SELECT u.nama_lengkap, COUNT(p.id) as total_pelanggaran
    FROM users u
    JOIN pelanggaran p ON u.id = p.santri_id
    WHERE MONTH(p.tanggal_pelanggaran) = '$bulan' 
        AND YEAR(p.tanggal_pelanggaran) = '$tahun'
    GROUP BY u.id
    ORDER BY total_pelanggaran DESC
    LIMIT 5
");

// Santri dengan prestasi terbanyak
$query_top_prestasi = mysqli_query($conn, "
    SELECT u.nama_lengkap, COUNT(pr.id) as total_prestasi
    FROM users u
    JOIN prestasi pr ON u.id = pr.santri_id
    WHERE MONTH(pr.tanggal_prestasi) = '$bulan' 
        AND YEAR(pr.tanggal_prestasi) = '$tahun'
    GROUP BY u.id
    ORDER BY total_prestasi DESC
    LIMIT 5
");

// Total statistik
$total_pelanggaran = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM pelanggaran 
     WHERE MONTH(tanggal_pelanggaran) = '$bulan' 
     AND YEAR(tanggal_pelanggaran) = '$tahun'"))['total'];

$total_pembinaan = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM pembinaan 
     WHERE MONTH(tanggal_pembinaan) = '$bulan' 
     AND YEAR(tanggal_pembinaan) = '$tahun'"))['total'];

$total_prestasi = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM prestasi 
     WHERE MONTH(tanggal_prestasi) = '$bulan' 
     AND YEAR(tanggal_prestasi) = '$tahun'"))['total'];

// Nama bulan
$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background-color: #f8fafc; }
    .font-arab { font-family: 'Amiri', serif !important; }
    .bg-emerald { background-color: #059669 !important; }
    .text-emerald { color: #059669 !important; }
    .card-modern { border: none; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); transition: transform 0.2s; }
    .input-modern { border-radius: 12px; padding: 0.75rem; border: 1px solid #e2e8f0; background-color: #f8fafc; }
    
    .stat-box {
        padding: 25px;
        border-radius: 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .stat-box i {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.2;
    }

    @media print {
        #wrapper .sidebar-wrapper, .btn, .filter-section, .top-bar-info { display: none !important; }
        .main-content { margin-left: 0 !important; width: 100% !important; }
        .card-modern { box-shadow: none !important; border: 1px solid #eee !important; }
    }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_admin.php'; ?>

    <div class="flex-grow-1" id="content-wrapper">
        <div class="container-fluid px-4 py-4">
            
            <div class="row align-items-center mb-4 top-bar-info">
                <div class="col-md-7">
                   <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Laporan Statistik Pesantren</h3>
                                       <p class="text-emerald fw-bold font-arab mb-0" style="font-size: 1.2rem;" dir="rtl">التقارير والإحصائيات الشهرية</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <button onclick="window.print()" class="btn bg-dark text-white shadow-sm px-4 py-2 fw-bold rounded-3">
                        <i class="fas fa-print me-2"></i>CETAK LAPORAN
                    </button>
                </div>
            </div>

            <div class="card card-modern mb-4 filter-section">
                <div class="card-body p-4">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Pilih Bulan</label>
                            <select name="bulan" class="form-select input-modern">
                                <?php foreach ($nama_bulan as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $bulan == $key ? 'selected' : '' ?>><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Pilih Tahun</label>
                            <select name="tahun" class="form-select input-modern">
                                <?php for ($i = date('Y'); $i >= 2020; $i--): ?>
                                <option value="<?= $i ?>" <?= $tahun == $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn bg-emerald text-white w-100 py-2 rounded-3 fw-bold shadow-sm">
                                <i class="fas fa-filter me-1"></i> FILTER
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-emerald p-4 rounded-4 text-white mb-4 shadow-sm d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">Periode: <?= $nama_bulan[$bulan] ?> <?= $tahun ?></h3>
                    <p class="mb-0 opacity-75">Ringkasan data kedisiplinan dan prestasi santri</p>
                </div>
                <i class="fas fa-calendar-check fa-3x opacity-25"></i>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-box shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <p class="small fw-bold mb-1">TOTAL PELANGGARAN</p>
                        <h2 class="fw-bold mb-0"><?= $total_pelanggaran ?></h2>
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box shadow-sm" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                        <p class="small fw-bold mb-1">TOTAL PEMBINAAN</p>
                        <h2 class="fw-bold mb-0"><?= $total_pembinaan ?></h2>
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <p class="small fw-bold mb-1">TOTAL PRESTASI</p>
                        <h2 class="fw-bold mb-0"><?= $total_prestasi ?></h2>
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card card-modern h-100">
                        <div class="card-header bg-transparent border-0 p-4 pb-0">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-chart-pie me-2 text-emerald"></i>Pelanggaran per Kategori</h5>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 300px;">
                                <canvas id="chartKategori"></canvas>
                            </div>
                            <div class="mt-4">
                                <table class="table table-sm small">
                                    <tbody class="border-top-0">
                                        <?php 
                                        $labels = []; $data = [];
                                        while ($row = mysqli_fetch_assoc($query_kategori)): 
                                            $labels[] = $row['nama_kategori'];
                                            $data[] = $row['total'];
                                        ?>
                                        <tr>
                                            <td class="text-muted"><?= $row['nama_kategori'] ?></td>
                                            <td class="text-end fw-bold"><?= $row['total'] ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-modern mb-4">
                        <div class="card-header bg-transparent border-0 p-4 pb-0">
                            <h5 class="fw-bold text-danger mb-0"><i class="fas fa-user-slash me-2"></i>Perlu Perhatian (Pelanggaran)</h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if(mysqli_num_rows($query_top_pelanggaran) > 0): ?>
                                <?php while($tp = mysqli_fetch_assoc($query_top_pelanggaran)): ?>
                                <div class="d-flex align-items-center mb-3 p-2 bg-light rounded-3">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small"><?= $tp['nama_lengkap'] ?></div>
                                    </div>
                                    <span class="badge bg-danger rounded-pill"><?= $tp['total_pelanggaran'] ?> Kasus</span>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-center text-muted py-4">Tidak ada data.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card card-modern">
                        <div class="card-header bg-transparent border-0 p-4 pb-0">
                            <h5 class="fw-bold text-success mb-0"><i class="fas fa-star me-2"></i>Bintang Prestasi Bulan Ini</h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if(mysqli_num_rows($query_top_prestasi) > 0): ?>
                                <?php while($tr = mysqli_fetch_assoc($query_top_prestasi)): ?>
                                <div class="d-flex align-items-center mb-3 p-2 bg-light rounded-3">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small"><?= $tr['nama_lengkap'] ?></div>
                                    </div>
                                    <span class="badge bg-success rounded-pill"><?= $tr['total_prestasi'] ?> Prestasi</span>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-center text-muted py-4">Tidak ada data.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-modern mt-4 bg-info-subtle border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-info"><i class="fas fa-lightbulb me-2"></i>Kesimpulan & Rekomendasi</h5>
                    <p class="mb-0 small text-dark opacity-75">
                        Berdasarkan data bulan <?= $nama_bulan[$bulan] ?>, terdapat <strong><?= $total_pelanggaran ?></strong> pelanggaran dan <strong><?= $total_prestasi ?></strong> prestasi. 
                        Rekomendasi sistem: Fokuskan pembinaan pada kategori <strong><?= !empty($labels) ? $labels[0] : '-' ?></strong> untuk menekan angka pelanggaran di bulan berikutnya.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Chart kategori
const ctxKategori = document.getElementById('chartKategori').getContext('2d');
new Chart(ctxKategori, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            data: <?= json_encode($data) ?>,
            backgroundColor: [
                '#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', 
                '#ec4899', '#6366f1', '#14b8a6', '#f97316', '#06b6d4'
            ],
            borderWidth: 0,
            hoverOffset: 20
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        cutout: '70%'
    }
});
</script>
<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
require_once '../config.php';
requireSantri();

$page_title = 'Materi Edukasi';

// Filter Kategori
$filter_kategori = isset($_GET['kategori']) ? escape($_GET['kategori']) : '';

// Ambil Data Materi
$query_materi = "SELECT * FROM materi_edukasi WHERE 1=1";
if (!empty($filter_kategori)) {
    $query_materi .= " AND kategori = '$filter_kategori'";
}
$query_materi .= " ORDER BY created_at DESC";
$result_materi = mysqli_query($conn, $query_materi);

// Hitung Jumlah Per Kategori
$count_all = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi_edukasi"))['total'];
$count_tata_tertib = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi_edukasi WHERE kategori = 'Tata Tertib'"))['total'];
$count_adab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi_edukasi WHERE kategori = 'Adab Santri'"))['total'];
$count_kekerasan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi_edukasi WHERE kategori = 'Pencegahan Kekerasan'"))['total'];
$count_motivasi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi_edukasi WHERE kategori = 'Motivasi'"))['total'];

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<style>
    .font-arab { font-family: 'Amiri', serif; }
    .category-card { transition: all 0.3s ease; border: none; cursor: pointer; }
    .category-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .category-card.active { border: 2px solid #198754 !important; background-color: #f0fdf4; }
    .materi-card { transition: all 0.3s ease; border-radius: 15px; }
    .materi-card:hover { border-color: #198754 !important; }
    .bg-soft-orange { background-color: #fff7ed; color: #c2410c; }
    .bg-soft-blue { background-color: #eff6ff; color: #1d4ed8; }
    .bg-soft-red { background-color: #fef2f2; color: #dc2626; }
    .bg-soft-purple { background-color: #faf5ff; color: #7e22ce; }
</style>

<div class="d-flex">
    <?php include '../includes/sidebar_santri.php'; ?>
    
    <div class="flex-grow-1 bg-light min-vh-100">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm mb-4 px-4 py-3">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 fw-bold me-3 text-dark"><i class="fas fa-book-reader me-2"></i> Materi Edukasi</h4>
                <h4 dir="rtl" lang="ar" class="mb-0 font-arab text-success d-none d-md-block">اَلْمَوَادُّ التَّعْلِيمِيَّةُ</h4>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $_SESSION['foto_profil']; ?>" 
                     class="rounded-circle shadow-sm me-2" width="40" height="40" style="object-fit: cover;"
                     onerror="this.src='<?php echo BASE_URL; ?>assets/images/default.png'">
            </div>
        </nav>

        <div class="container-fluid px-4">
            
            <div class="card border-0 shadow-sm text-white mb-4" style="background: linear-gradient(135deg, #064e3b 0%, #198754 100%); border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="fw-bold mb-2">Pusat Edukasi Santri</h3>
                            <p class="mb-0 opacity-75">Tingkatkan wawasan dan perbaiki adab demi menjadi santri yang bertaqwa dan berakhlakul karimah.</p>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                            <h1 dir="rtl" class="font-arab opacity-50 mb-0" style="font-size: 3.5rem;">طَلَبُ الْعِلْمِ</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-2">
                    <a href="materi_edukasi.php" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card text-center p-3 <?php echo empty($filter_kategori) ? 'active shadow' : ''; ?>">
                            <i class="fas fa-th-large mb-2 text-success fs-4"></i>
                            <div class="fw-bold small text-dark">Semua</div>
                            <span class="badge bg-light text-dark rounded-pill"><?php echo $count_all; ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-2">
                    <a href="?kategori=Tata Tertib" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card text-center p-3 <?php echo $filter_kategori == 'Tata Tertib' ? 'active shadow' : ''; ?>">
                            <i class="fas fa-clipboard-list mb-2 text-warning fs-4"></i>
                            <div class="fw-bold small text-dark text-truncate">Tata Tertib</div>
                            <span class="badge bg-warning text-white rounded-pill"><?php echo $count_tata_tertib; ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-2">
                    <a href="?kategori=Adab Santri" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card text-center p-3 <?php echo $filter_kategori == 'Adab Santri' ? 'active shadow' : ''; ?>">
                            <i class="fas fa-hands-praying mb-2 text-primary fs-4"></i>
                            <div class="fw-bold small text-dark text-truncate">Adab</div>
                            <span class="badge bg-primary text-white rounded-pill"><?php echo $count_adab; ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3">
                    <a href="?kategori=Pencegahan Kekerasan" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card text-center p-3 <?php echo $filter_kategori == 'Pencegahan Kekerasan' ? 'active shadow' : ''; ?>">
                            <i class="fas fa-shield-alt mb-2 text-danger fs-4"></i>
                            <div class="fw-bold small text-dark text-truncate">Anti Kekerasan</div>
                            <span class="badge bg-danger text-white rounded-pill"><?php echo $count_kekerasan; ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-2">
                    <a href="?kategori=Motivasi" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card text-center p-3 <?php echo $filter_kategori == 'Motivasi' ? 'active shadow' : ''; ?>">
                            <i class="fas fa-rocket mb-2 text-purple fs-4" style="color: #8b5cf6;"></i>
                            <div class="fw-bold small text-dark text-truncate">Motivasi</div>
                            <span class="badge text-white rounded-pill" style="background-color: #8b5cf6;"><?php echo $count_motivasi; ?></span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <?php if (mysqli_num_rows($result_materi) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_materi)): 
                        $cat_class = ''; $icon = '';
                        switch($row['kategori']) {
                            case 'Tata Tertib': $cat_class = 'bg-soft-orange'; $icon = 'clipboard-list'; break;
                            case 'Adab Santri': $cat_class = 'bg-soft-blue'; $icon = 'hands-praying'; break;
                            case 'Pencegahan Kekerasan': $cat_class = 'bg-soft-red'; $icon = 'shield-alt'; break;
                            case 'Motivasi': $cat_class = 'bg-soft-purple'; $icon = 'rocket'; break;
                        }
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 materi-card border-0 shadow-sm" onclick='bacaMateri(<?php echo json_encode($row); ?>)' style="cursor: pointer;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle p-2 <?php echo $cat_class; ?> me-3">
                                        <i class="fas fa-<?php echo $icon; ?> fa-fw"></i>
                                    </div>
                                    <span class="badge rounded-pill <?php echo $cat_class; ?>"><?php echo $row['kategori']; ?></span>
                                </div>
                                <h5 class="fw-bold text-dark mb-3 line-clamp-2"><?php echo $row['judul']; ?></h5>
                                <p class="text-muted small mb-4">
                                    <?php echo substr(strip_tags($row['isi_materi']), 0, 100); ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                    <small class="text-muted"><i class="fas fa-calendar-day me-1"></i> <?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                                    <span class="text-success fw-bold small">Baca <i class="fas fa-chevron-right ms-1"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm p-5 text-center">
                            <i class="fas fa-book-open fa-4x text-muted opacity-25 mb-3"></i>
                            <h4 class="text-muted">Materi belum tersedia</h4>
                            <p class="mb-0 text-muted">Silakan pilih kategori lain atau kembali lagi nanti.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBaca" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-0 pt-4 px-4">
                <div class="d-flex align-items-center">
                    <div id="modal_icon_box" class="p-2 rounded me-3 shadow-sm text-white">
                        <i id="modal_icon" class="fas fa-book-open"></i>
                    </div>
                    <h5 class="modal-title fw-bold" id="baca_judul"></h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="baca_badge" class="mb-3"></div>
                <div id="baca_isi" class="text-dark fs-6" style="line-height: 1.8; text-align: justify;"></div>
                
                <div class="mt-4 p-3 bg-light rounded-3 border-start border-success border-4">
                    <p dir="rtl" class="font-arab text-success mb-1" style="font-size: 1.4rem;">اَلْعِلْمُ بِلَا عَمَلٍ كَالشَّجَرِ بِلَا ثَمَرٍ</p>
                    <small class="text-muted">"Ilmu tanpa amal bagaikan pohon tanpa buah."</small>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function bacaMateri(data) {
    let icon = ''; let color = '';
    switch(data.kategori) {
        case 'Tata Tertib': icon = 'clipboard-list'; color = '#f59e0b'; break;
        case 'Adab Santri': icon = 'hands-praying'; color = '#3b82f6'; break;
        case 'Pencegahan Kekerasan': icon = 'shield-alt'; color = '#ef4444'; break;
        case 'Motivasi': icon = 'rocket'; color = '#8b5cf6'; break;
        default: icon = 'book'; color = '#198754';
    }
    
    document.getElementById('modal_icon').className = 'fas fa-' + icon;
    document.getElementById('modal_icon_box').style.backgroundColor = color;
    document.getElementById('baca_judul').textContent = data.judul;
    document.getElementById('baca_badge').innerHTML = `<span class="badge" style="background-color: ${color}">${data.kategori}</span>`;
    document.getElementById('baca_isi').innerHTML = data.isi_materi.replace(/\n/g, '<br>');
    
    var myModal = new bootstrap.Modal(document.getElementById('modalBaca'));
    myModal.show();
}
</script>
<?php include '../includes/footer.php'; ?>

</body>
</html>
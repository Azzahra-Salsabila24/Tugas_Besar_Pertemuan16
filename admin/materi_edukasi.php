<?php
require_once '../config.php';
requireAdmin();

// Set Charset Untuk Aksen Arab
mysqli_set_charset($conn, "utf8mb4");

$page_title = 'Materi Edukasi';
$success = '';
$error = '';

// Proses Tambah
if (isset($_POST['tambah'])) {
    $judul = escape($_POST['judul']);
    $kategori = escape($_POST['kategori']);
    $isi_materi = escape($_POST['isi_materi']);
    
    $query = "INSERT INTO materi_edukasi (judul, kategori, isi_materi, dibuat_oleh) 
              VALUES ('$judul', '$kategori', '$isi_materi', '".$_SESSION['user_id']."')";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Materi edukasi berhasil ditambahkan!';
    } else {
        $error = 'Gagal menambahkan materi edukasi!';
    }
}

// Proses Ubah
if (isset($_POST['ubah'])) {
    $id = escape($_POST['id']);
    $judul = escape($_POST['judul']);
    $kategori = escape($_POST['kategori']);
    $isi_materi = escape($_POST['isi_materi']);
    
    $query = "UPDATE materi_edukasi SET judul = '$judul', kategori = '$kategori', isi_materi = '$isi_materi' WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Materi edukasi berhasil diubah!';
    } else {
        $error = 'Gagal mengubah materi edukasi!';
    }
}

// Proses Hapus 
if (isset($_GET['hapus']) && empty($success) && empty($error)) {
    $id = escape($_GET['hapus']);
    if (mysqli_query($conn, "DELETE FROM materi_edukasi WHERE id = '$id'")) {
        $success = 'Materi edukasi berhasil dihapus!';
    } else {
        $error = 'Gagal menghapus materi edukasi!';
    }
}

// Filter & Search
$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$filter_kategori = isset($_GET['kategori']) ? escape($_GET['kategori']) : '';

$query_materi = "SELECT m.*, u.nama_lengkap as pembuat FROM materi_edukasi m
                 JOIN users u ON m.dibuat_oleh = u.id WHERE 1=1";
if (!empty($search)) $query_materi .= " AND (m.judul LIKE '%$search%' OR m.isi_materi LIKE '%$search%')";
if (!empty($filter_kategori)) $query_materi .= " AND m.kategori = '$filter_kategori'";
$query_materi .= " ORDER BY m.created_at DESC";
$result_materi = mysqli_query($conn, $query_materi);

// Hitung Statistik
$count_all = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi_edukasi"))['total'];
$count_tata_tertib = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi_edukasi WHERE kategori = 'Tata Tertib'"))['total'];
$count_adab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi_edukasi WHERE kategori = 'Adab Santri'"))['total'];

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Font Jakarta Sans tanpa merusak Ikon */
    html, body, div, span, h1, h2, h3, h4, h5, h6, p, a, b, table, thead, tbody, tr, th, td, input, select, textarea, button { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
    }
    
    .fa, .fas, .far, .fab {
        font-family: "Font Awesome 5 Free" !important;
    }

    body { background-color: #f8fafc; }
    .font-arab { font-family: 'Amiri', serif !important; }
    .fw-800 { font-weight: 800; }
    
    .bg-emerald { background-color: #059669 !important; }
    .text-emerald { color: #059669 !important; }

    .arab-text { 
        font-family: 'Amiri', serif !important; 
        font-size: 1.8rem; 
        line-height: 2.2; 
        direction: rtl; 
        text-align: right; 
        display: block;
        margin: 15px 0;
        color: #064e3b;
        padding: 20px;
        background: #f0fdf4;
        border-right: 5px solid #059669;
        border-radius: 8px;
    }

    .card-modern {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
    }
    .card-materi:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
    }

    .category-badge {
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
    }

    .stat-mini-card {
        border-radius: 20px;
        border: none;
        transition: all 0.3s;
    }

    .input-modern { 
        border-radius: 12px; 
        padding: 0.75rem; 
        border: 1px solid #e2e8f0; 
        background-color: #f8fafc; 
    }
    .input-modern:focus { 
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); 
        border-color: #059669; 
        outline: none; 
    }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_admin.php'; ?>

    <div class="flex-grow-1" id="content-wrapper">
        <div class="container-fluid px-4 py-4">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-7">
                    <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Edukasi & Adab Santri</h3>
                    <p class="text-emerald fw-bold font-arab mb-0" style="font-size: 1.2rem;" dir="rtl">بوابة المواد التعليمية والأخلاق</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <button class="btn bg-emerald text-white shadow-sm px-4 py-2 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus-circle me-2"></i> TAMBAH MATERI
                    </button>
                </div>
            </div>

            <?php if($success): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <a href="materi_edukasi.php" class="text-decoration-none">
                        <div class="card stat-mini-card shadow-sm p-4 <?= empty($filter_kategori) ? 'bg-emerald text-white' : 'bg-white' ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small fw-bold opacity-75">SEMUA MATERI</div>
                                    <div class="h3 fw-bold mb-0"><?= $count_all ?></div>
                                </div>
                                <i class="fas fa-book-open fa-2x opacity-25"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="?kategori=Tata Tertib" class="text-decoration-none">
                        <div class="card stat-mini-card shadow-sm p-4 <?= $filter_kategori == 'Tata Tertib' ? 'bg-warning text-dark' : 'bg-white' ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small fw-bold opacity-75 text-uppercase">Tata Tertib</div>
                                    <div class="h3 fw-bold mb-0"><?= $count_tata_tertib ?></div>
                                </div>
                                <i class="fas fa-gavel fa-2x opacity-25"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="?kategori=Adab Santri" class="text-decoration-none">
                        <div class="card stat-mini-card shadow-sm p-4 <?= $filter_kategori == 'Adab Santri' ? 'bg-primary text-white' : 'bg-white' ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small fw-bold opacity-75 text-uppercase">Adab & Akhlak</div>
                                    <div class="h3 fw-bold mb-0"><?= $count_adab ?></div>
                                </div>
                                <i class="fas fa-heart fa-2x opacity-25"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="card card-modern mb-4">
                <div class="card-body p-3">
                    <form method="GET" class="row g-2">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control input-modern border-start-0 ps-0" placeholder="Cari konten edukasi..." value="<?= $search ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100 py-2 rounded-3 fw-bold">CARI DATA</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <?php if(mysqli_num_rows($result_materi) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_materi)): 
                        $color = '#059669'; 
                        if($row['kategori'] == 'Tata Tertib') $color = '#f59e0b';
                        if($row['kategori'] == 'Pencegahan Kekerasan') $color = '#ef4444';
                        if($row['kategori'] == 'Motivasi') $color = '#8b5cf6';
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card card-modern card-materi h-100 overflow-hidden">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="category-badge" style="background: <?= $color ?>15; color: <?= $color ?>;">
                                        <?= $row['kategori'] ?>
                                    </span>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0 text-muted" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
                                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick='editMateri(<?= json_encode($row) ?>)'><i class="fas fa-edit me-2 text-warning"></i>Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item py-2 text-danger" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus materi ini?')"><i class="fas fa-trash me-2"></i>Hapus</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h5 class="fw-bold text-dark mb-3"><?= $row['judul'] ?></h5>
                                <p class="text-muted small mb-4 flex-grow-1">
                                    <?= substr(strip_tags($row['isi_materi']), 0, 120) ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                    <button class="btn btn-sm btn-link text-emerald fw-bold p-0 text-decoration-none" onclick='detailMateri(<?= json_encode($row) ?>)'>PRATINJAU <i class="fas fa-arrow-right ms-1"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-emerald text-white rounded-top-4 p-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0">Publikasi Materi Baru</h5>
                    <small class="opacity-75 font-arab">إضافة مادة تعليمية جديدة</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold small text-muted">JUDUL MATERI</label>
                            <input type="text" name="judul" class="form-control input-modern" placeholder="Judul materi..." required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">KATEGORI</label>
                            <select name="kategori" class="form-select input-modern" required>
                                <option value="Adab Santri">Adab Santri</option>
                                <option value="Tata Tertib">Tata Tertib</option>
                                <option value="Motivasi">Motivasi</option>
                                <option value="Pencegahan Kekerasan">Pencegahan Kekerasan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">ISI KONTEN</label>
                        <textarea name="isi_materi" class="form-control input-modern" rows="10" placeholder="Tuliskan materi di sini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" name="tambah" class="btn bg-emerald text-white px-5 rounded-3 fw-bold shadow-sm">PUBLIKASIKAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-warning text-dark rounded-top-4 p-4">
                <div>
                     <h5 class="modal-title fw-bold">Update Data Santri</h5>
                    <small class="font-arab opacity-75" dir="rtl">تحديث بيانات الطالب</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">JUDUL MATERI</label>
                            <input type="text" name="judul" id="edit_judul" class="form-control input-modern" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">KATEGORI</label>
                            <select name="kategori" id="edit_kategori" class="form-select input-modern" required>
                                <option value="Adab Santri">Adab Santri</option>
                                <option value="Tata Tertib">Tata Tertib</option>
                                <option value="Motivasi">Motivasi</option>
                                <option value="Pencegahan Kekerasan">Pencegahan Kekerasan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">ISI KONTEN</label>
                        <textarea name="isi_materi" id="edit_isi" class="form-control input-modern" rows="10" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" name="ubah" class="btn btn-warning px-5 rounded-3 fw-bold shadow-sm">SIMPAN PERUBAHAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-5 pt-0">
                <div id="detail_badge" class="mb-3"></div>
                <h2 id="detail_judul" class="fw-800 text-dark mb-4"></h2>
                <div id="detail_isi" class="text-secondary" style="line-height: 1.8; font-size: 1.05rem;"></div>
            </div>
            <div class="modal-footer bg-light border-0 py-3 justify-content-center">
                <small class="text-muted font-arab">معهد الصديقية الإسلامي - Karawang</small>
            </div>
        </div>
    </div>
</div>

<script>
function editMateri(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_judul').value = data.judul;
    document.getElementById('edit_kategori').value = data.kategori;
    document.getElementById('edit_isi').value = data.isi_materi;
    
    var modalUbah = new bootstrap.Modal(document.getElementById('modalUbah'));
    modalUbah.show();
}

function detailMateri(data) {
    let content = data.isi_materi
        .replace(/\[arab\]/g, '<span class="arab-text">')
        .replace(/\[\/arab\]/g, '</span>')
        .replace(/\n/g, '<br>');

    document.getElementById('detail_judul').innerText = data.judul;
    document.getElementById('detail_isi').innerHTML = content;
    
    let color = '#059669';
    if(data.kategori == 'Tata Tertib') color = '#f59e0b';
    if(data.kategori == 'Pencegahan Kekerasan') color = '#ef4444';
    if(data.kategori == 'Motivasi') color = '#8b5cf6';
    
    document.getElementById('detail_badge').innerHTML = `<span class="category-badge" style="background:${color}15; color:${color}">${data.kategori}</span>`;
    
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}
</script>

<?php include '../includes/footer.php'; ?>
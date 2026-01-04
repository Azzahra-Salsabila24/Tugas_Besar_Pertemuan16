<?php
require_once '../config.php';
requireSantri();

$page_title = 'Catatan Harian Saya';
$success = '';
$error = '';
$santri_id = $_SESSION['user_id'];

// CREATE (Tambah Data)
if (isset($_POST['tambah'])) {
    $tanggal = escape($_POST['tanggal']);
    $judul = escape($_POST['judul']);
    $isi_catatan = escape($_POST['isi_catatan']);
    $kategori = escape($_POST['kategori']);
    
    $query = "INSERT INTO catatan_harian (santri_id, tanggal, judul, isi_catatan, kategori, created_at) 
              VALUES ('$santri_id', '$tanggal', '$judul', '$isi_catatan', '$kategori', NOW())";
    
    if (mysqli_query($conn, $query)) {
        header("Location: my_catatan.php?msg=tambah_success"); // Redirect agar URL bersih
        exit();
    } else {
        $error = 'Gagal menyimpan catatan!';
    }
}

// UPDATE (Ubah Data)
else if (isset($_POST['ubah'])) {
    $id = escape($_POST['id']);
    $tanggal = escape($_POST['tanggal']);
    $judul = escape($_POST['judul']);
    $isi_catatan = escape($_POST['isi_catatan']);
    $kategori = escape($_POST['kategori']);
    
    $query = "UPDATE catatan_harian SET tanggal = '$tanggal', judul = '$judul', 
              isi_catatan = '$isi_catatan', kategori = '$kategori' 
              WHERE id = '$id' AND santri_id = '$santri_id'";
    
    if (mysqli_query($conn, $query)) {
        header("Location: my_catatan.php?msg=ubah_success");
        exit();
    } else {
        $error = 'Gagal memperbarui catatan!';
    }
}

// DELETE (Hapus Data)
else if (isset($_GET['hapus'])) {
    $id = escape($_GET['hapus']);
    $query = "DELETE FROM catatan_harian WHERE id = '$id' AND santri_id = '$santri_id'";
    
    if (mysqli_query($conn, $query)) {
        header("Location: my_catatan.php?msg=hapus_success");
        exit();
    } else {
        $error = 'Gagal menghapus catatan!';
    }
}

// TANGKAP PESAN DARI REDIRECT
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'tambah_success') $success = 'Catatan berhasil disimpan!';
    if ($_GET['msg'] == 'ubah_success') $success = 'Catatan berhasil diperbarui!';
    if ($_GET['msg'] == 'hapus_success') $success = 'Catatan telah dihapus!';
}

// READ (Baca Data)
$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$query_catatan = "SELECT * FROM catatan_harian WHERE santri_id = '$santri_id'";
if (!empty($search)) {
    $query_catatan .= " AND (judul LIKE '%$search%' OR isi_catatan LIKE '%$search%')";
}
$query_catatan .= " ORDER BY tanggal DESC";
$result_catatan = mysqli_query($conn, $query_catatan);

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<style>
    .font-arab { font-family: 'Amiri', serif; }
    .materi-card { transition: all 0.3s ease; border-radius: 15px; border: 1px solid #eee; background: #fff; }
    .materi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
    .bg-soft-green { background-color: #f0fdf4; color: #166534; }
    .bg-soft-blue { background-color: #eff6ff; color: #1d4ed8; }
    .bg-soft-purple { background-color: #faf5ff; color: #7e22ce; }
    .bg-soft-orange { background-color: #fff7ed; color: #c2410c; }
</style>

<div class="d-flex">
    <?php include '../includes/sidebar_santri.php'; ?>
    
    <div class="flex-grow-1 bg-light min-vh-100">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm mb-4 px-4 py-3">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 fw-bold me-3"><i class="fas fa-book-open me-2 text-success"></i> Buku Harian</h4>
                <h4 dir="rtl" class="mb-0 font-arab text-success d-none d-md-block">مُذَكِّرَاتِي اليَوْمِيَّة</h4>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <div class="text-end me-3 d-none d-sm-block">
                    <h6 class="mb-0 fw-bold small"><?php echo $_SESSION['nama_lengkap']; ?></h6>
                    <span class="badge bg-soft-purple" style="font-size: 0.7rem;">SANTRI AKTIF</span>
                </div>
                <img src="<?php echo BASE_URL; ?>assets/images/<?php echo $_SESSION['foto_profil']; ?>" 
                     class="rounded-circle shadow-sm" width="40" height="40" style="object-fit: cover;"
                     onerror="this.src='<?php echo BASE_URL; ?>assets/images/default.png'">
            </div>
        </nav>

        <div class="container-fluid px-4">
            
            <?php if ($success): ?>
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm text-white mb-4" style="background: linear-gradient(135deg, #064e3b 0%, #198754 100%); border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h3 class="fw-bold mb-2">Jurnal Kegiatan Santri</h3>
                            <p class="mb-3 opacity-75">Tuliskan pengalaman, hafalan, dan refleksi harianmu di sini.</p>
                            <button class="btn btn-light text-success fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Catatan Baru
                            </button>
                        </div>
                        <div class="col-md-5 text-end d-none d-md-block">
                            <h1 dir="rtl" class="font-arab opacity-50 mb-0" style="font-size: 3rem;">اَلْعِلْمُ صَيْدٌ وَالْكِتَابَةُ قَيْدُهُ</h1>
                            <small class="opacity-75">"Ilmu adalah buruan dan tulisan adalah pengikatnya"</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-5">
                    <form method="GET" class="input-group shadow-sm border-0 rounded-3 overflow-hidden">
                        <input type="text" name="search" class="form-control border-0" placeholder="Cari catatan..." value="<?php echo $search; ?>">
                        <button class="btn btn-white text-success border-0" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <div class="row">
                <?php if (mysqli_num_rows($result_catatan) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_catatan)): 
                        $color_class = 'bg-soft-green'; 
                        if($row['kategori'] == 'Belajar') $color_class = 'bg-soft-blue';
                        if($row['kategori'] == 'Refleksi') $color_class = 'bg-soft-purple';
                        if($row['kategori'] == 'Kegiatan') $color_class = 'bg-soft-orange';
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 materi-card shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="badge rounded-pill <?php echo $color_class; ?>"><?php echo $row['kategori']; ?></span>
                                    <small class="text-muted"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/y', strtotime($row['tanggal'])); ?></small>
                                </div>
                                <h5 class="fw-bold text-dark mb-2"><?php echo $row['judul']; ?></h5>
                                <p class="text-muted small mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo strip_tags($row['isi_catatan']); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-warning border-0" onclick='editCatatan(<?php echo json_encode($row); ?>)'>
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                    <button class="btn btn-sm text-success fw-bold p-0" onclick='bacaDetail(<?php echo json_encode($row); ?>)'>
                                        Detail <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                        <i class="fas fa-feather fa-3x text-muted opacity-25 mb-3"></i>
                        <h5 class="text-muted">Belum ada catatan. Silakan tambah catatan baru!</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Tambah Catatan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="Ibadah">📿 Ibadah</option>
                            <option value="Belajar">📚 Belajar</option>
                            <option value="Kegiatan">🎯 Kegiatan</option>
                            <option value="Refleksi">💭 Refleksi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul</label>
                        <input type="text" name="judul" class="form-control" placeholder="Tulis judul..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Isi Catatan</label>
                        <textarea name="isi_catatan" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-success px-4">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning py-3">
                <h5 class="modal-title fw-bold text-dark">Edit Catatan Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kategori</label>
                        <select name="kategori" id="edit_kategori" class="form-select" required>
                            <option value="Ibadah">📿 Ibadah</option>
                            <option value="Belajar">📚 Belajar</option>
                            <option value="Kegiatan">🎯 Kegiatan</option>
                            <option value="Refleksi">💭 Refleksi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul</label>
                        <input type="text" name="judul" id="edit_judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Isi Catatan</label>
                        <textarea name="isi_catatan" id="edit_isi" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="ubah" class="btn btn-warning px-4 text-dark">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                <div id="detail_icon" class="mb-3 fs-1 text-success"></div>
                <h4 class="fw-bold mb-1" id="detail_judul"></h4>
                <div class="mb-3"><span id="detail_kategori" class="badge bg-light text-success"></span></div>
                <hr>
                <p id="detail_isi" class="text-dark py-3" style="text-align: justify; white-space: pre-line;"></p>
                <button type="button" class="btn btn-secondary w-100 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editCatatan(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_tanggal').value = data.tanggal;
    document.getElementById('edit_kategori').value = data.kategori;
    document.getElementById('edit_judul').value = data.judul;
    document.getElementById('edit_isi').value = data.isi_catatan;
    new bootstrap.Modal(document.getElementById('modalUbah')).show();
}

function bacaDetail(data) {
    document.getElementById('detail_judul').textContent = data.judul;
    document.getElementById('detail_kategori').textContent = data.kategori;
    document.getElementById('detail_isi').textContent = data.isi_catatan;
    document.getElementById('detail_icon').innerHTML = '<i class="fas fa-scroll"></i>';
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}
</script>

<?php include '../includes/footer.php'; ?>
<?php
require_once '../config.php';
requireAdmin();

// Set Charset Untuk Aksen Arab
mysqli_set_charset($conn, "utf8mb4");

$page_title = 'Data Pembinaan';
$success = '';
$error = '';

// --- PROSES TAMBAH ---
if (isset($_POST['tambah'])) {
    $santri_id = escape($_POST['santri_id']);
    $pembina_id = escape($_POST['pembina_id']); 
    $pelanggaran_id = !empty($_POST['pelanggaran_id']) ? escape($_POST['pelanggaran_id']) : NULL;
    $jenis_pembinaan = escape($_POST['jenis_pembinaan']);
    $tanggal = escape($_POST['tanggal_pembinaan']);
    $deskripsi = escape($_POST['deskripsi']);
    $hasil = escape($_POST['hasil_pembinaan']);
    
    $pel_val = $pelanggaran_id ? "'$pelanggaran_id'" : "NULL";
    $query = "INSERT INTO pembinaan (santri_id, pelanggaran_id, jenis_pembinaan, tanggal_pembinaan, deskripsi, hasil_pembinaan, pembina_id) 
              VALUES ('$santri_id', $pel_val, '$jenis_pembinaan', '$tanggal', '$deskripsi', '$hasil', '$pembina_id')";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Data pembinaan berhasil ditambahkan!';
    } else {
        $error = 'Gagal menambahkan data!';
    }
}

// --- PROSES UBAH ---
if (isset($_POST['ubah'])) {
    $id = escape($_POST['id']);
    $santri_id = escape($_POST['santri_id']);
    $pembina_id = escape($_POST['pembina_id']);
    $pelanggaran_id = !empty($_POST['pelanggaran_id']) ? escape($_POST['pelanggaran_id']) : NULL;
    $jenis_pembinaan = escape($_POST['jenis_pembinaan']);
    $tanggal = escape($_POST['tanggal_pembinaan']);
    $deskripsi = escape($_POST['deskripsi']);
    $hasil = escape($_POST['hasil_pembinaan']);
    
    $pel_val = $pelanggaran_id ? "'$pelanggaran_id'" : "NULL";
    $query = "UPDATE pembinaan SET 
              santri_id = '$santri_id',
              pembina_id = '$pembina_id',
              pelanggaran_id = $pel_val,
              jenis_pembinaan = '$jenis_pembinaan',
              tanggal_pembinaan = '$tanggal',
              deskripsi = '$deskripsi',
              hasil_pembinaan = '$hasil'
              WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Data pembinaan berhasil diperbarui!';
    }
}

// --- PROSES HAPUS ---
if (isset($_GET['hapus'])) {
    $id = escape($_GET['hapus']);
    if (mysqli_query($conn, "DELETE FROM pembinaan WHERE id = '$id'")) {
        $success = 'Data pembinaan berhasil dihapus!';
    }
}

// Query Data
$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$query_pembinaan = "SELECT p.*, u.nama_lengkap, pb.nama_lengkap as nama_pembina
                    FROM pembinaan p
                    JOIN users u ON p.santri_id = u.id
                    JOIN users pb ON p.pembina_id = pb.id";
if (!empty($search)) {
    $query_pembinaan .= " WHERE u.nama_lengkap LIKE '%$search%' OR p.jenis_pembinaan LIKE '%$search%'";
}
$query_pembinaan .= " ORDER BY p.tanggal_pembinaan DESC";
$result_pembinaan = mysqli_query($conn, $query_pembinaan);

$query_santri = mysqli_query($conn, "SELECT id, nama_lengkap FROM users WHERE role = 'santri' ORDER BY nama_lengkap ASC");
$query_pembina_list = mysqli_query($conn, "SELECT id, nama_lengkap FROM users WHERE role IN ('admin', 'pembina', 'ustadz') ORDER BY nama_lengkap ASC");

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    /* Global Font */
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background-color: #f8fafc; }
    .font-arab { font-family: 'Amiri', serif !important; }

    .bg-emerald { background-color: #059669 !important; }
    .text-emerald { color: #059669 !important; }
    .card-modern { border: none; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); }
    
    /* Header Tabel: Rata Tengah */
    .table thead th { 
        background-color: #f8fafc; 
        text-transform: uppercase; 
        font-size: 0.75rem; 
        letter-spacing: 0.05em; 
        color: #64748b; 
        padding: 1.25rem; 
        border: none;
        text-align: center;
    }

    
    .table tbody td {
        padding: 1rem 0.75rem;
        text-align: left; 
    }

    .text-center-important { text-align: center !important; }

    .btn-action { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s; border: none; }
    .input-modern { border-radius: 12px; padding: 0.75rem; border: 1px solid #e2e8f0; background-color: #f8fafc; }
    .input-modern:focus { box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); border-color: #059669; outline: none; }
    .badge-pembinaan { padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.75rem; background: #ecfdf5; color: #065f46; }
    
    .avatar-circle {
        width: 38px; height: 38px; background-color: #059669; color: white;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.9rem;
    }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_admin.php'; ?>

    <div class="flex-grow-1" id="content-wrapper">
        <div class="container-fluid px-4 py-4">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-7">
                <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Manajemen Pembinaan Santri</h3>
                <p class="text-emerald fw-bold font-arab mb-0" style="font-size: 1.2rem;" dir="rtl">إِدَارَةُ تَرْبِيَةِ وَتَوْجِيْهِ الطُّلَّابِ</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <button type="button" class="btn bg-emerald text-white shadow-sm px-4 py-2 fw-bold rounded-3" onclick="openTambahModal()">
                        <i class="fas fa-plus-circle me-2"></i>TAMBAH PEMBINAAN
                    </button>
                </div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-check-circle me-2"></i><?= $success ?></div>
            <?php endif; ?>

            <div class="card card-modern mb-4">
                <div class="card-body p-4">
                    <form method="GET" class="row g-3">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control input-modern border-start-0 ps-0" placeholder="Cari nama santri..." value="<?= $search ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100 py-2 rounded-3 fw-bold">FILTER</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-modern overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">SANTRI</th>
                                <th>JENIS PEMBINAAN</th>
                                <th>TANGGAL</th>
                                <th>PEMBINA</th>
                                <th class="pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result_pembinaan)): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            <?= strtoupper(substr($row['nama_lengkap'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $row['nama_lengkap'] ?></div>
                                            <small class="text-muted">ID: #P-<?= $row['id'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-pembinaan"><?= strtoupper($row['jenis_pembinaan']) ?></span></td>
                                <td><div class="small fw-bold text-dark"><i class="far fa-calendar-alt me-1 text-emerald"></i> <?= date('d/m/Y', strtotime($row['tanggal_pembinaan'])) ?></div></td>
                                <td><div class="small text-muted fw-bold"><?= $row['nama_pembina'] ?></div></td>
                                <td class="text-center-important pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn-action bg-info-subtle text-info" onclick='openDetailModal(<?= json_encode($row) ?>)'><i class="fas fa-eye fa-sm"></i></button>
                                        <button type="button" class="btn-action bg-warning-subtle text-warning" onclick='openEditModal(<?= json_encode($row) ?>)'><i class="fas fa-pencil-alt fa-sm"></i></button>
                                        <a href="?hapus=<?= $row['id'] ?>" class="btn-action bg-danger-subtle text-danger" onclick="return confirm('Hapus data?')"><i class="fas fa-trash fa-sm"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-emerald text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold">Tambah Pembinaan</h5>
                    <small class="font-arab opacity-75" dir="rtl">إضافة سجل تربوي</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Santri</label>
                            <select name="santri_id" class="form-select input-modern" required>
                                <option value="">-- Pilih Santri --</option>
                                <?php mysqli_data_seek($query_santri, 0); while($s = mysqli_fetch_assoc($query_santri)): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['nama_lengkap'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Pembina</label>
                            <select name="pembina_id" class="form-select input-modern" required>
                                <option value="">-- Pilih Pembina --</option>
                                <?php mysqli_data_seek($query_pembina_list, 0); while($p = mysqli_fetch_assoc($query_pembina_list)): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($_SESSION['user_id'] == $p['id'] ? 'selected' : '') ?>><?= $p['nama_lengkap'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jenis Pembinaan</label>
                            <select name="jenis_pembinaan" class="form-select input-modern" required>
                                <option value="Bimbingan Personal">Bimbingan Personal</option>
                                <option value="Konseling">Konseling</option>
                                <option value="Bimbingan Kelompok">Bimbingan Kelompok</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tanggal</label>
                            <input type="date" name="tanggal_pembinaan" class="form-control input-modern" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control input-modern" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Hasil Pembinaan</label>
                            <textarea name="hasil_pembinaan" class="form-control input-modern" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="tambah" class="btn bg-emerald text-white w-100 py-3 rounded-3 fw-bold">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-warning p-4">
                <div>
                    <h5 class="modal-title fw-bold">Ubah Pembinaan</h5>
                    <small class="font-arab opacity-75" dir="rtl">تعديل بيانات التوجيه</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Santri</label>
                            <select name="santri_id" id="edit_santri_id" class="form-select input-modern">
                                <?php mysqli_data_seek($query_santri, 0); while($s = mysqli_fetch_assoc($query_santri)): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['nama_lengkap'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Pembina</label>
                            <select name="pembina_id" id="edit_pembina_id" class="form-select input-modern">
                                <?php mysqli_data_seek($query_pembina_list, 0); while($p = mysqli_fetch_assoc($query_pembina_list)): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['nama_lengkap'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jenis</label>
                            <select name="jenis_pembinaan" id="edit_jenis" class="form-select input-modern">
                                <option value="Bimbingan Personal">Bimbingan Personal</option>
                                <option value="Konseling">Konseling</option>
                                <option value="Bimbingan Kelompok">Bimbingan Kelompok</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tanggal</label>
                            <input type="date" name="tanggal_pembinaan" id="edit_tanggal" class="form-control input-modern">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi" class="form-control input-modern" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Hasil Pembinaan</label>
                            <textarea name="hasil_pembinaan" id="edit_hasil" class="form-control input-modern" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="ubah" class="btn btn-warning w-100 py-3 rounded-3 fw-bold text-dark">UPDATE DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-body p-5 text-center">
                <div class="bg-emerald-subtle text-emerald rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: #ecfdf5;">
                    <i class="fas fa-user-graduate fa-2x"></i>
                </div>
                <h4 id="det_nama" class="fw-bold mb-1"></h4>
                <p id="det_jenis" class="text-muted mb-4"></p>
                
                <div class="text-start bg-light p-4 rounded-4 small">
                    <div class="mb-2"><strong>Tanggal:</strong> <span id="det_tgl"></span></div>
                    <div class="mb-2"><strong>Pembina:</strong> <span id="det_pembina"></span></div>
                    <hr>
                    <div class="mb-2"><strong>Deskripsi:</strong></div>
                    <div id="det_desk" class="text-secondary mb-3"></div>
                    <div><strong>Hasil:</strong></div>
                    <div id="det_hasil" class="text-success fw-bold"></div>
                </div>
                <button type="button" class="btn btn-dark w-100 mt-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script>
function openTambahModal() {
    new bootstrap.Modal(document.getElementById('modalTambah')).show();
}

function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_santri_id').value = data.santri_id;
    document.getElementById('edit_pembina_id').value = data.pembina_id;
    document.getElementById('edit_jenis').value = data.jenis_pembinaan;
    document.getElementById('edit_tanggal').value = data.tanggal_pembinaan;
    document.getElementById('edit_deskripsi').value = data.deskripsi;
    document.getElementById('edit_hasil').value = data.hasil_pembinaan;
    new bootstrap.Modal(document.getElementById('modalUbah')).show();
}

function openDetailModal(data) {
    document.getElementById('det_nama').innerText = data.nama_lengkap;
    document.getElementById('det_jenis').innerText = data.jenis_pembinaan.toUpperCase();
    document.getElementById('det_tgl').innerText = data.tanggal_pembinaan;
    document.getElementById('det_pembina').innerText = data.nama_pembina;
    document.getElementById('det_desk').innerText = data.deskripsi;
    document.getElementById('det_hasil').innerText = data.hasil_pembinaan || 'Belum ada hasil';
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}
</script>
<?php include '../includes/footer.php'; ?>

</body>
</html>
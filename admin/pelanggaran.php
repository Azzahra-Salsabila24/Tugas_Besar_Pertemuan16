<?php
require_once '../config.php';
requireAdmin();

mysqli_set_charset($conn, "utf8mb4");

$page_title = 'Data Pelanggaran';
$success = '';
$error = '';

// --- PROSES HAPUS (Gaya Transaksi yang Aman) ---
if (isset($_GET['hapus'])) {
    $id = escape($_GET['hapus']);
    mysqli_begin_transaction($conn);
    try {
        // Hapus relasi di pembinaan dulu agar tidak error
        mysqli_query($conn, "DELETE FROM pembinaan WHERE pelanggaran_id = '$id'");
        $query_hapus = "DELETE FROM pelanggaran WHERE id = '$id'";
        
        if (mysqli_query($conn, $query_hapus)) {
            mysqli_commit($conn);
            $success = 'Data pelanggaran berhasil dihapus!';
        } else {
            throw new Exception("Gagal");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = 'Gagal menghapus data! Data sedang digunakan.';
    }
}

// --- PROSES TAMBAH ---
if (isset($_POST['tambah'])) {
    $santri_id = escape($_POST['santri_id']);
    $kategori_id = escape($_POST['kategori_id']);
    $tanggal = escape($_POST['tanggal_pelanggaran']);
    $waktu = escape($_POST['waktu_pelanggaran']);
    $deskripsi = escape($_POST['deskripsi_pelanggaran']);
    $tindakan = escape($_POST['tindakan_pembinaan']);
    $status = escape($_POST['status']);
    
    $query = "INSERT INTO pelanggaran (santri_id, kategori_id, tanggal_pelanggaran, waktu_pelanggaran, deskripsi_pelanggaran, tindakan_pembinaan, status, dicatat_oleh) 
              VALUES ('$santri_id', '$kategori_id', '$tanggal', '$waktu', '$deskripsi', '$tindakan', '$status', '".$_SESSION['user_id']."')";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Data pelanggaran baru berhasil ditambahkan!';
    } else {
        $error = 'Gagal menambahkan data!';
    }
}

// --- PROSES UBAH ---
if (isset($_POST['ubah'])) {
    $id = escape($_POST['id']);
    $santri_id = escape($_POST['santri_id']);
    $kategori_id = escape($_POST['kategori_id']);
    $tanggal = escape($_POST['tanggal_pelanggaran']);
    $waktu = escape($_POST['waktu_pelanggaran']);
    $deskripsi = escape($_POST['deskripsi_pelanggaran']);
    $tindakan = escape($_POST['tindakan_pembinaan']);
    $status = escape($_POST['status']);
    
    $query = "UPDATE pelanggaran SET santri_id='$santri_id', kategori_id='$kategori_id', tanggal_pelanggaran='$tanggal', waktu_pelanggaran='$waktu', deskripsi_pelanggaran='$deskripsi', tindakan_pembinaan='$tindakan', status='$status' WHERE id='$id'";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Data pelanggaran berhasil diperbarui!';
    } else {
        $error = 'Gagal memperbarui data!';
    }
}

// --- LOAD DATA ---
$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$query_pelanggaran = "SELECT p.*, u.nama_lengkap, k.nama_kategori, k.poin_pelanggaran 
                      FROM pelanggaran p
                      JOIN users u ON p.santri_id = u.id
                      JOIN kategori_pelanggaran k ON p.kategori_id = k.id";
if (!empty($search)) {
    $query_pelanggaran .= " WHERE u.nama_lengkap LIKE '%$search%' OR k.nama_kategori LIKE '%$search%'";
}
$query_pelanggaran .= " ORDER BY p.tanggal_pelanggaran DESC, p.waktu_pelanggaran DESC";
$result_pelanggaran = mysqli_query($conn, $query_pelanggaran);

$query_santri = mysqli_query($conn, "SELECT id, nama_lengkap FROM users WHERE role = 'santri' ORDER BY nama_lengkap ASC");
$query_kategori = mysqli_query($conn, "SELECT * FROM kategori_pelanggaran ORDER BY nama_kategori ASC");

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background-color: #f8fafc; color: #1e293b; }
    .font-arab { font-family: 'Amiri', serif !important; }
    .bg-emerald { background-color: #059669 !important; }
    .text-emerald { color: #059669 !important; }
    .card-modern { border: none; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); background: white; }
    .table thead th { background-color: #f8fafc; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b; padding: 1.25rem; border: none; font-weight: 800; text-align: center; }
    .badge-status { padding: 6px 12px; border-radius: 10px; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.3px; }
    .status-selesai { background: #dcfce7; color: #15803d; }
    .status-proses { background: #dbeafe; color: #1d4ed8; }
    .status-belum { background: #fee2e2; color: #b91c1c; }
    .btn-action { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s; border: none; text-decoration: none; cursor: pointer; }
    .btn-action:hover { transform: translateY(-2px); }
    .input-modern { border-radius: 12px; padding: 0.75rem; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 0.9rem; }
    .input-modern:focus { box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); border-color: #059669; outline: none; }
    .alert-modern { border: none; border-radius: 15px; padding: 1rem 1.5rem; }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_admin.php'; ?>
    <div class="flex-grow-1" id="content-wrapper">
        <div class="container-fluid px-4 py-4">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-7">
                    <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Log Pelanggaran Santri</h3>
                    <p class="text-emerald fw-bold font-arab mb-0" style="font-size: 1.2rem;" dir="rtl">سِجِلُّ الْمُخَالَفَاتِ وَالتَّأْدِيبِ لِلطُّلَّابِ</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <button class="btn bg-emerald text-white shadow-sm px-4 py-2 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus-circle me-2"></i> CATAT PELANGGARAN
                    </button>
                </div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success alert-modern shadow-sm mb-4 d-flex align-items-center fade show">
                    <i class="fas fa-check-circle me-3 fa-lg"></i>
                    <div class="fw-600"><?= $success ?></div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-modern shadow-sm mb-4 d-flex align-items-center fade show">
                    <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                    <div class="fw-600"><?= $error ?></div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card card-modern mb-4">
                <div class="card-body p-3">
                    <form method="GET" class="row g-2">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control input-modern" placeholder="Cari nama santri atau kategori..." value="<?= $search ?>">
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
                                <th width="250">SANTRI</th>
                                <th>KATEGORI</th>
                                <th>WAKTU</th>
                                <th>STATUS</th>
                                <th width="150">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result_pelanggaran) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result_pelanggaran)): 
                                    $status_class = ($row['status'] == 'Selesai') ? 'status-selesai' : (($row['status'] == 'Sedang Pembinaan') ? 'status-proses' : 'status-belum');
                                    $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-emerald text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                <?= strtoupper(substr($row['nama_lengkap'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= $row['nama_lengkap'] ?></div>
                                                <small class="text-muted">ID: #S-<?= $row['santri_id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-1"><?= $row['nama_kategori'] ?></div>
                                        <span class="badge bg-danger-subtle text-danger border-0" style="font-size: 0.65rem; font-weight: 800;">
                                            <i class="fas fa-bolt me-1"></i>+<?= $row['poin_pelanggaran'] ?> POIN
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="small fw-bold text-dark"><?= date('d/m/Y', strtotime($row['tanggal_pelanggaran'])) ?></div>
                                        <div class="small text-muted"><?= $row['waktu_pelanggaran'] ?></div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-status <?= $status_class ?>"><?= strtoupper($row['status']) ?></span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn-action bg-info-subtle text-info" onclick='detailPelanggaran(<?= $jsonData ?>)' title="Detail"><i class="fas fa-eye fa-sm"></i></button>
                                            <button class="btn-action bg-warning-subtle text-warning" onclick='openEditModal(<?= $jsonData ?>)' title="Ubah"><i class="fas fa-pencil-alt fa-sm"></i></button>
                                            <a href="?hapus=<?= $row['id'] ?>" class="btn-action bg-danger-subtle text-danger" onclick="return confirm('Hapus data pelanggaran ini?')" title="Hapus"><i class="fas fa-trash fa-sm"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted">Data tidak ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-emerald text-white p-4 border-0">
                <div>
                    <h5 class="modal-title fw-bold">Tambah Pelanggaran</h5>
                    <small class="font-arab opacity-75" dir="rtl">إضافة سجل مخالفة جديد</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">SANTRI</label>
                            <select name="santri_id" class="form-select input-modern" required>
                                <option value="">-- Pilih Santri --</option>
                                <?php mysqli_data_seek($query_santri, 0); while($s = mysqli_fetch_assoc($query_santri)): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['nama_lengkap'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">KATEGORI</label>
                            <select name="kategori_id" class="form-select input-modern" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php mysqli_data_seek($query_kategori, 0); while($k = mysqli_fetch_assoc($query_kategori)): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="small fw-bold">TANGGAL</label><input type="date" name="tanggal_pelanggaran" class="form-control input-modern" value="<?= date('Y-m-d') ?>" required></div>
                        <div class="col-md-6"><label class="small fw-bold">WAKTU</label><input type="time" name="waktu_pelanggaran" class="form-control input-modern" value="<?= date('H:i') ?>" required></div>
                        <div class="col-12"><label class="small fw-bold">KRONOLOGI / DESKRIPSI</label><textarea name="deskripsi_pelanggaran" class="form-control input-modern" rows="3" required></textarea></div>
                        <div class="col-12"><label class="small fw-bold">TINDAKAN AWAL</label><input type="text" name="tindakan_pembinaan" class="form-control input-modern"></div>
                        <div class="col-md-6">
                            <label class="small fw-bold">STATUS</label>
                            <select name="status" class="form-select input-modern">
                                <option value="Belum Ditindak">Belum Ditindak</option>
                                <option value="Sedang Pembinaan">Sedang Pembinaan</option>
                                <option value="Selesai">Selesai</option>
                            </select>
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

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-warning p-4 border-0">
                <div>
                    <h5 class="modal-title fw-bold">Update Data Pelanggaran</h5>
                    <small class="font-arab opacity-75" dir="rtl">تعديل بيانات المخالفة</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">SANTRI</label>
                            <select name="santri_id" id="edit_santri" class="form-select input-modern">
                                <?php mysqli_data_seek($query_santri, 0); while($s = mysqli_fetch_assoc($query_santri)): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['nama_lengkap'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">KATEGORI</label>
                            <select name="kategori_id" id="edit_kategori" class="form-select input-modern">
                                <?php mysqli_data_seek($query_kategori, 0); while($k = mysqli_fetch_assoc($query_kategori)): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="small fw-bold">TANGGAL</label><input type="date" name="tanggal_pelanggaran" id="edit_tanggal" class="form-control input-modern"></div>
                        <div class="col-md-6"><label class="small fw-bold">WAKTU</label><input type="time" name="waktu_pelanggaran" id="edit_waktu" class="form-control input-modern"></div>
                        <div class="col-12"><label class="small fw-bold">KRONOLOGI</label><textarea name="deskripsi_pelanggaran" id="edit_deskripsi" class="form-control input-modern" rows="3"></textarea></div>
                        <div class="col-12"><label class="small fw-bold">TINDAKAN</label><input type="text" name="tindakan_pembinaan" id="edit_tindakan" class="form-control input-modern"></div>
                        <div class="col-md-6">
                            <label class="small fw-bold">STATUS</label>
                            <select name="status" id="edit_status" class="form-select input-modern">
                                <option value="Belum Ditindak">Belum Ditindak</option>
                                <option value="Sedang Pembinaan">Sedang Pembinaan</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="ubah" class="btn btn-warning text-dark w-100 py-3 rounded-3 fw-bold">PERBARUI DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body p-4 text-center bg-white rounded-4">
                <i class="fas fa-exclamation-triangle text-emerald mb-3" style="font-size: 3rem;"></i>
                <h4 id="detail_nama" class="fw-bold mb-1 text-dark"></h4>
                <div id="detail_status_container" class="mb-4 mt-2"></div>
                <div class="text-start bg-light p-3 rounded-4">
                    <p class="small mb-1 text-muted fw-bold">KRONOLOGI:</p>
                    <p id="detail_deskripsi" class="small text-dark mb-3"></p>
                    <hr class="opacity-50">
                    <p class="small mb-1 text-emerald fw-bold">TINDAKAN PEMBINAAN:</p>
                    <p id="detail_tindakan" class="small fw-bold text-dark mb-0"></p>
                </div>
                <button type="button" class="btn btn-dark w-100 mt-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_santri').value = data.santri_id;
    document.getElementById('edit_kategori').value = data.kategori_id;
    document.getElementById('edit_tanggal').value = data.tanggal_pelanggaran;
    document.getElementById('edit_waktu').value = data.waktu_pelanggaran;
    document.getElementById('edit_deskripsi').value = data.deskripsi_pelanggaran;
    document.getElementById('edit_tindakan').value = data.tindakan_pembinaan;
    document.getElementById('edit_status').value = data.status;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

function detailPelanggaran(data) {
    document.getElementById('detail_nama').innerText = data.nama_lengkap;
    document.getElementById('detail_deskripsi').innerText = data.deskripsi_pelanggaran;
    document.getElementById('detail_tindakan').innerText = data.tindakan_pembinaan || 'Belum ada tindakan yang dicatat';
    let cls = (data.status === 'Selesai') ? 'status-selesai' : (data.status === 'Sedang Pembinaan' ? 'status-proses' : 'status-belum');
    document.getElementById('detail_status_container').innerHTML = `<span class="badge-status ${cls}">${data.status.toUpperCase()}</span>`;
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}
</script>

<?php include '../includes/footer.php'; ?>
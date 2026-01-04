<?php
require_once '../config.php';
requireAdmin();

$page_title = 'Kategori Pelanggaran';
$success = '';
$error = '';

// --- PROSES TAMBAH ---
if (isset($_POST['tambah'])) {
    $nama_kategori = escape($_POST['nama_kategori']);
    $deskripsi = escape($_POST['deskripsi']);
    $poin = escape($_POST['poin_pelanggaran']);
    
    $query = "INSERT INTO kategori_pelanggaran (nama_kategori, deskripsi, poin_pelanggaran) 
              VALUES ('$nama_kategori', '$deskripsi', '$poin')";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Kategori baru berhasil ditambahkan ke sistem!';
    } else {
        $error = 'Gagal menambahkan kategori! Silakan coba lagi.';
    }
}

// --- PROSES UBAH ---
if (isset($_POST['ubah'])) {
    $id = escape($_POST['id']);
    $nama_kategori = escape($_POST['nama_kategori']);
    $deskripsi = escape($_POST['deskripsi']);
    $poin = escape($_POST['poin_pelanggaran']);
    
    $query = "UPDATE kategori_pelanggaran SET 
              nama_kategori = '$nama_kategori',
              deskripsi = '$deskripsi',
              poin_pelanggaran = '$poin'
              WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Data kategori berhasil diperbarui!';
    } else {
        $error = 'Gagal memperbarui data kategori!';
    }
}

// --- PROSES HAPUS ---
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_begin_transaction($conn);
    try {
        mysqli_query($conn, "UPDATE pelanggaran SET kategori_id = NULL WHERE kategori_id = '$id'");
        $delete = mysqli_query($conn, "DELETE FROM kategori_pelanggaran WHERE id = '$id'");

        if ($delete) {
            mysqli_commit($conn);
            $success = 'Kategori telah berhasil dihapus dari sistem.';
        } else { 
            throw new Exception("Gagal"); 
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = 'Gagal menghapus kategori! Data mungkin sedang digunakan.';
    }
}

$query_kategori = mysqli_query($conn, "SELECT * FROM kategori_pelanggaran ORDER BY poin_pelanggaran ASC");
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
    
    .btn-action { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s; border: none; text-decoration: none; cursor: pointer; }
    .btn-action:hover { transform: translateY(-2px); }

    .table thead th { 
        background-color: #f8fafc; 
        text-transform: uppercase; 
        font-size: 0.75rem; 
        letter-spacing: 0.05em; 
        color: #64748b; 
        padding: 1.25rem; 
        border: none; 
        font-weight: 800;
        text-align: center; 
    }
    
    .badge-poin {
        background-color: #fef2f2;
        color: #dc2626;
        font-weight: 800;
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(220, 38, 38, 0.1);
    }

    .input-modern { border-radius: 12px; padding: 0.75rem; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 0.9rem; transition: all 0.2s; }
    .input-modern:focus { box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); border-color: #059669; outline: none; background-color: #fff; }

    /* Custom Alert Styling */
    .alert-modern { border: none; border-radius: 15px; padding: 1rem 1.5rem; }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_admin.php'; ?>
    
    <div class="flex-grow-1" id="content-wrapper">
        <div class="container-fluid px-4 py-4">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-7">
                    <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Kategori Pelanggaran</h3>
                    <p class="text-emerald fw-bold font-arab mb-0" style="font-size: 1.2rem;" dir="rtl">إدارة فئات المخالفات</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <button class="btn bg-emerald text-white shadow-sm px-4 py-2 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus-circle me-2"></i> TAMBAH KATEGORI
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

            <div class="card card-modern overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="70">NO</th>
                                <th>NAMA KATEGORI</th>
                                <th>DESKRIPSI PELANGGARAN</th>
                                <th width="150">BOBOT POIN</th>
                                <th width="150">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($query_kategori) > 0):
                                while ($row = mysqli_fetch_assoc($query_kategori)): 
                                    $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?= $no++; ?></td>
                                <td><div class="fw-bold text-dark ps-2"><?= $row['nama_kategori']; ?></div></td>
                                <td><div class="text-muted small ps-2" style="max-width: 400px;"><?= $row['deskripsi']; ?></div></td>
                                <td class="text-center">
                                    <div class="badge-poin">
                                        <i class="fas fa-exclamation-circle"></i> 
                                        <span><?= $row['poin_pelanggaran']; ?> Poin</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn-action bg-warning-subtle text-warning" title="Edit" onclick='editKategori(<?= $jsonData; ?>)'>
                                            <i class="fas fa-pencil-alt fa-sm"></i>
                                        </button>
                                        <a href="?hapus=<?= $row['id']; ?>" class="btn-action bg-danger-subtle text-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            <i class="fas fa-trash fa-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data kategori.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-emerald text-white p-4 border-0">
                <div>
                    <h5 class="modal-title fw-bold">Tambah Kategori Pelanggaran</h5>
                    <small class="font-arab opacity-75" dir="rtl">إضافة فئة جديدة</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">NAMA KATEGORI</label>
                        <input type="text" name="nama_kategori" class="form-control input-modern" placeholder="Masukkan nama kategori" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">DESKRIPSI</label>
                        <textarea name="deskripsi" class="form-control input-modern" rows="3" placeholder="Penjelasan singkat mengenai kategori"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small">BOBOT POIN</label>
                        <input type="number" name="poin_pelanggaran" class="form-control input-modern" min="0" placeholder="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="tambah" class="btn bg-emerald text-white w-100 py-3 rounded-3 fw-bold shadow-sm">SIMPAN KATEGORI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-warning p-4 border-0">
                <div>
                     <h5 class="modal-title fw-bold">Update Kategori Pelanggaran</h5>
                    <small class="font-arab opacity-75" dir="rtl">تحديث فئة المخالفة</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                        <label class="form-label fw-bold small">NAMA KATEGORI</label>
                        <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control input-modern" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">DESKRIPSI</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control input-modern" rows="3"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small">BOBOT POIN</label>
                        <input type="number" name="poin_pelanggaran" id="edit_poin" class="form-control input-modern" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="ubah" class="btn btn-warning text-dark w-100 py-3 rounded-3 fw-bold shadow-sm">UPDATE DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
function editKategori(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama_kategori').value = data.nama_kategori;
    document.getElementById('edit_deskripsi').value = data.deskripsi;
    document.getElementById('edit_poin').value = data.poin_pelanggaran;
    
    var myModal = new bootstrap.Modal(document.getElementById('modalUbah'));
    myModal.show();
}

// Menghilangkan parameter 'hapus' dari URL setelah notifikasi muncul agar tidak terhapus lagi saat refresh
if (window.history.replaceState) {
    const url = new URL(window.location.href);
    url.searchParams.delete('hapus');
    window.history.replaceState({path: url.href}, '', url.href);
}
</script>
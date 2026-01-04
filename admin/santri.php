<?php
require_once '../config.php';
requireAdmin();

// Set Charset Untuk Aksen Arab
mysqli_set_charset($conn, "utf8mb4");

$page_title = 'Manajemen Data Santri';
$success = '';
$error = '';

// Proses Tambah Santri
if (isset($_POST['tambah'])) {
    $nama_lengkap = escape($_POST['nama_lengkap']);
    $username = escape($_POST['username']);
    $password = md5($_POST['password']);
    $jenis_kelamin = escape($_POST['jenis_kelamin']);
    $tanggal_lahir = escape($_POST['tanggal_lahir']);
    $alamat = escape($_POST['alamat']);
    $nomor_telepon = escape($_POST['nomor_telepon']);
    
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = 'Username sudah digunakan oleh santri lain!';
    } else {
        $query = "INSERT INTO users (nama_lengkap, username, password, role, jenis_kelamin, tanggal_lahir, alamat, nomor_telepon) 
                  VALUES ('$nama_lengkap', '$username', '$password', 'santri', '$jenis_kelamin', '$tanggal_lahir', '$alamat', '$nomor_telepon')";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data santri berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan data santri!';
        }
    }
}

// Proses Ubah Santri
if (isset($_POST['ubah'])) {
    $id = escape($_POST['id']);
    $nama_lengkap = escape($_POST['nama_lengkap']);
    $username = escape($_POST['username']);
    $jenis_kelamin = escape($_POST['jenis_kelamin']);
    $tanggal_lahir = escape($_POST['tanggal_lahir']);
    $alamat = escape($_POST['alamat']);
    $nomor_telepon = escape($_POST['nomor_telepon']);
    
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND id != '$id'");
    if (mysqli_num_rows($cek) > 0) {
        $error = 'Username sudah digunakan oleh santri lain!';
    } else {
        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']);
            $query = "UPDATE users SET 
                      nama_lengkap = '$nama_lengkap', username = '$username', password = '$password',
                      jenis_kelamin = '$jenis_kelamin', tanggal_lahir = '$tanggal_lahir',
                      alamat = '$alamat', nomor_telepon = '$nomor_telepon'
                      WHERE id = '$id'";
        } else {
            $query = "UPDATE users SET 
                      nama_lengkap = '$nama_lengkap', username = '$username',
                      jenis_kelamin = '$jenis_kelamin', tanggal_lahir = '$tanggal_lahir',
                      alamat = '$alamat', nomor_telepon = '$nomor_telepon'
                      WHERE id = '$id'";
        }
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data santri berhasil diperbarui!';
        } else {
            $error = 'Gagal mengubah data santri!';
        }
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = escape($_GET['hapus']);
    $query = "DELETE FROM users WHERE id = '$id' AND role = 'santri'";
    if (mysqli_query($conn, $query)) {
        $success = 'Data santri berhasil dihapus!';
    } else {
        $error = 'Gagal menghapus data santri!';
    }
}

// Ambil Data Santri
$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$query_santri = "SELECT * FROM users WHERE role = 'santri'";
if (!empty($search)) {
    $query_santri .= " AND (nama_lengkap LIKE '%$search%' OR username LIKE '%$search%' OR alamat LIKE '%$search%')";
}
$query_santri .= " ORDER BY nama_lengkap ASC";
$result_santri = mysqli_query($conn, $query_santri);

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background-color: #f8fafc; color: #1e293b; }
    .font-arab { font-family: 'Amiri', serif !important; }
    .bg-emerald { background-color: #059669 !important; }
    .text-emerald { color: #059669 !important; }
    .card-modern { border: none; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); }
    
    .table thead th { 
        background-color: #f8fafc; 
        text-transform: uppercase; 
        font-size: 0.75rem; 
        letter-spacing: 0.05em; 
        color: #64748b; 
        padding: 1.25rem; 
        border: none; 
        font-weight: 700; 
        text-align: center; 
    }
    
    .btn-action { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s; border: none; }
    .input-modern { border-radius: 12px; padding: 0.75rem; border: 1px solid #e2e8f0; background-color: #f8fafc; }
    .input-modern:focus { box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); border-color: #059669; outline: none; }
    .badge-gender { padding: 5px 12px; border-radius: 8px; font-weight: 700; font-size: 0.7rem; }
</style>

<div class="d-flex" id="wrapper">
    <?php include '../includes/sidebar_admin.php'; ?>

    <div class="flex-grow-1" id="content-wrapper">
        <div class="container-fluid px-4 py-4">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-7">
                    <h3 class="fw-800 text-dark mb-0" style="letter-spacing: -0.5px;">Manajemen Data Santri</h3>
                    <p class="text-emerald fw-bold font-arab mb-0" style="font-size: 1.2rem;" dir="rtl">إدارة بيانات الطلاب والطلاب</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <button type="button" class="btn bg-emerald text-white shadow-sm px-4 py-2 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-user-plus me-2"></i>TAMBAH SANTRI
                    </button>
                </div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-check-circle me-2"></i><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-exclamation-triangle me-2"></i><?= $error ?></div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <div class="card card-modern">
                        <div class="card-body p-4">
                            <form method="GET" class="row g-3">
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" name="search" class="form-control input-modern border-start-0 ps-0" placeholder="Cari nama, username, atau alamat santri..." value="<?= $search ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-dark w-100 py-2 rounded-3 fw-bold">FILTER</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-modern overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="60">NO</th>
                                <th>IDENTITAS SANTRI</th>
                                <th>USERNAME</th>
                                <th>GENDER</th>
                                <th>KONTAK & ALAMAT</th>
                                <th width="120">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($result_santri) > 0):
                                while ($row = mysqli_fetch_assoc($result_santri)): 
                            ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $row['nama_lengkap'] ?></div>
                                    <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?= date('d/m/Y', strtotime($row['tanggal_lahir'])) ?></small>
                                </td>
                                <td><code class="text-emerald fw-bold bg-light px-2 py-1 rounded"><?= $row['username'] ?></code></td>
                                <td class="text-center">
                                    <?php if($row['jenis_kelamin'] == 'Laki-laki'): ?>
                                        <span class="badge-gender bg-info-subtle text-info text-uppercase">Laki-laki</span>
                                    <?php else: ?>
                                        <span class="badge-gender bg-danger-subtle text-danger text-uppercase">Perempuan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="small fw-bold"><i class="fas fa-phone-alt me-1 text-muted"></i> <?= $row['nomor_telepon'] ?: '-' ?></div>
                                    <div class="small text-muted text-truncate" style="max-width: 180px;"><i class="fas fa-map-marker-alt me-1 text-muted"></i> <?= $row['alamat'] ?></div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn-action bg-warning-subtle text-warning" onclick='editSantri(<?= json_encode($row) ?>)'><i class="fas fa-pencil-alt fa-sm"></i></button>
                                        <a href="?hapus=<?= $row['id'] ?>" class="btn-action bg-danger-subtle text-danger" onclick="return confirm('Hapus data santri ini?')"><i class="fas fa-trash fa-sm"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Data santri tidak ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-emerald text-white p-4 border-0">
                <div>
                    <h5 class="modal-title fw-bold">Tambah Santri Baru</h5>
                    <small class="font-arab opacity-75" dir="rtl">إضافة طالب جديد</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control input-modern" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Username</label>
                            <input type="text" name="username" class="form-control input-modern" placeholder="Username untuk login" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Password</label>
                            <input type="password" name="password" class="form-control input-modern" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select input-modern" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control input-modern" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">No. Telepon</label>
                            <input type="text" name="nomor_telepon" class="form-control input-modern" placeholder="08xx">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control input-modern" rows="3" placeholder="Alamat asal santri..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="tambah" class="btn bg-emerald text-white w-100 py-3 rounded-3 fw-bold">SIMPAN DATA SANTRI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-warning p-4 border-0">
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
                            <label class="form-label fw-bold small">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="edit_nama_lengkap" class="form-control input-modern" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Username</label>
                            <input type="text" name="username" id="edit_username" class="form-control input-modern" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Ganti Password <small class="text-danger">(Kosongkan jika tetap)</small></label>
                            <input type="password" name="password" class="form-control input-modern">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="edit_jenis_kelamin" class="form-select input-modern" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" class="form-control input-modern" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">No. Telepon</label>
                            <input type="text" name="nomor_telepon" id="edit_nomor_telepon" class="form-control input-modern">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Alamat Lengkap</label>
                            <textarea name="alamat" id="edit_alamat" class="form-control input-modern" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="ubah" class="btn btn-warning text-dark w-100 py-3 rounded-3 fw-bold">UPDATE DATA SANTRI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
function editSantri(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
    document.getElementById('edit_username').value = data.username;
    document.getElementById('edit_jenis_kelamin').value = data.jenis_kelamin;
    document.getElementById('edit_tanggal_lahir').value = data.tanggal_lahir;
    document.getElementById('edit_alamat').value = data.alamat;
    document.getElementById('edit_nomor_telepon').value = data.nomor_telepon;
    
    var myModal = new bootstrap.Modal(document.getElementById('modalUbah'));
    myModal.show();
}
</script>
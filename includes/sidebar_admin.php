<style>
    .sidebar {
        background: linear-gradient(180deg, #064e3b 0%, #059669 100%);
        min-height: 100vh;
        transition: all 0.3s;
        box-shadow: 4px 0 15px rgba(0,0,0,0.15);
        z-index: 1000;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .sidebar-brand {
        padding: 2rem 1.5rem;
        text-align: center;
        color: white;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .brand-icon {
        background: rgba(255, 255, 255, 0.15);
        width: 60px;
        height: 60px;
        line-height: 60px;
        border-radius: 15px;
        margin: 0 auto 15px;
        font-size: 28px;
        display: block;
    }

    .sidebar-brand h5 {
        font-family: 'Amiri', serif;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0;
        letter-spacing: 0.5px;
    }

    .sidebar-heading {
        padding: 1.5rem 1.5rem 0.5rem;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: rgba(255,255,255,0.4) !important;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
        padding: 12px 20px !important;
        margin: 2px 15px;
        border-radius: 10px;
        transition: all 0.2s;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff !important;
        transform: translateX(4px);
    }

    .nav-link.active {
        background: #ffffff !important;
        color: #064e3b !important;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .nav-link i {
        width: 28px;
        font-size: 1.1rem;
    }

    .nav-link.active i {
        color: #059669;
    }

    .logout-btn {
        background: rgba(220, 53, 69, 0.2);
        margin-top: 20px;
    }

    .logout-btn:hover {
        background: #dc3545 !important;
    }

    hr.sidebar-divider {
        border-top: 1px solid rgba(255,255,255,0.1);
        margin: 10px 20px;
    }
</style>

<div class="sidebar d-flex flex-column p-0" id="accordionSidebar" style="width: 250px;">
    
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-mosque"></i>
        </div>
        <h5 dir="rtl">الصِّدِّيقِيَّةِ</h5>
        <small class="text-uppercase opacity-75" style="font-size: 10px; font-weight: 700;">Admin Panel</small>
    </div>
    
    <ul class="nav flex-column py-3">
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
               href="dashboard.php">
                <i class="fas fa-fw fa-th-large me-2"></i>
                <span>لوحة القيادة <small class="d-block text-xs opacity-50">Dashboard</small></span>
            </a>
        </li>
        
        <div class="sidebar-heading">Data Master</div>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'santri.php' ? 'active' : ''; ?>" 
               href="santri.php">
                <i class="fas fa-fw fa-user-graduate me-2"></i>
                <span>بيانات الطلاب <small class="d-block text-xs opacity-50">Data Santri</small></span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kategori_pelanggaran.php' ? 'active' : ''; ?>" 
               href="kategori_pelanggaran.php">
                <i class="fas fa-fw fa-tags me-2"></i>
                <span>فئات المخالفات <small class="d-block text-xs opacity-50">Kategori Poin</small></span>
            </a>
        </li>
        
        <div class="sidebar-heading">Monitoring</div>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pelanggaran.php' ? 'active' : ''; ?>" 
               href="pelanggaran.php">
                <i class="fas fa-fw fa-exclamation-circle me-2"></i>
                <span>المخالفات <small class="d-block text-xs opacity-50">Pelanggaran</small></span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pembinaan.php' ? 'active' : ''; ?>" 
               href="pembinaan.php">
                <i class="fas fa-fw fa-hand-holding-heart me-2"></i>
                <span>التوجيه <small class="d-block text-xs opacity-50">Pembinaan</small></span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'prestasi.php' ? 'active' : ''; ?>" 
               href="prestasi.php">
                <i class="fas fa-fw fa-award me-2"></i>
                <span>الإنجازات <small class="d-block text-xs opacity-50">Data Prestasi</small></span>
            </a>
        </li>
        
        <div class="sidebar-heading">Edukasi & Media</div>

        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'materi_edukasi.php' ? 'active' : ''; ?>" 
               href="materi_edukasi.php">
                <i class="fas fa-fw fa-book-reader me-2"></i>
                <span>مواد تعليمية <small class="d-block text-xs opacity-50">Materi Edukasi</small></span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : ''; ?>" 
               href="laporan.php">
                <i class="fas fa-fw fa-file-invoice me-2"></i>
                <span>التقارير <small class="d-block text-xs opacity-50">Laporan Statistik</small></span>
            </a>
        </li>

        <hr class="sidebar-divider">
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" 
               href="profile.php">
                <i class="fas fa-fw fa-id-card me-2"></i>
                <span>الملف الشخصي <small class="d-block text-xs opacity-50">Profile</small></span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link logout-btn" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-fw fa-sign-out-alt me-2"></i>
                <span>تسجيل الخروج <small class="d-block text-xs opacity-50">Keluar Sistem</small></span>
            </a>
        </li>
        
    </ul>
    
    <div class="text-center mt-auto p-4 d-none d-md-block">
        <button class="btn btn-link text-white-50 p-0" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    
</div>
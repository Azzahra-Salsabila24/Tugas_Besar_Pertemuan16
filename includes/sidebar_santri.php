<?php
?>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
        color: white;
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
        text-decoration: none;
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

    .text-xs {
        font-size: 10px;
    }
    
    .opacity-50 {
        opacity: 0.5;
    }

    .logout-btn {
        background: rgba(220, 53, 69, 0.2) !important;
        margin-top: 20px;
    }

    .logout-btn:hover {
        background: #dc3545 !important;
        color: white !important;
    }

    hr.sidebar-divider {
        border-top: 1px solid rgba(255,255,255,0.1);
        margin: 10px 20px;
    }
</style>

<div class="sidebar d-flex flex-column p-0" id="accordionSidebar" style="width: 250px;">
    
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <h5 dir="rtl">الصِّدِّيقِيَّةِ</h5>
        <small class="text-uppercase opacity-75" style="font-size: 10px; font-weight: 700;">Panel Santri</small>
    </div>
    
    <ul class="nav flex-column py-3">
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
               href="dashboard.php">
                <i class="fas fa-fw fa-th-large me-2"></i>
                <span>لوحة القيادة <small class="d-block text-xs opacity-50">Dashboard</small></span>
            </a>
        </li>
        
        <div class="sidebar-heading">Aktivitas & Prestasi</div>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'my_catatan.php' ? 'active' : ''; ?>" 
               href="my_catatan.php">
                <i class="fas fa-fw fa-book me-2"></i>
                <span>مذكراتي <small class="d-block text-xs opacity-50">Catatan Harian</small></span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'my_prestasi.php' ? 'active' : ''; ?>" 
               href="my_prestasi.php">
                <i class="fas fa-fw fa-trophy me-2"></i>
                <span>إنجازاتي <small class="d-block text-xs opacity-50">Prestasi Saya</small></span>
            </a>
        </li>
        
        <div class="sidebar-heading">Edukasi & Pembinaan</div>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'materi_edukasi.php' ? 'active' : ''; ?>" 
               href="materi_edukasi.php">
                <i class="fas fa-fw fa-book-reader me-2"></i>
                <span>مواد تعليمية <small class="d-block text-xs opacity-50">Materi Edukasi</small></span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'my_pelanggaran.php' ? 'active' : ''; ?>" 
               href="my_pelanggaran.php">
                <i class="fas fa-fw fa-exclamation-circle me-2"></i>
                <span>سجل التوجيه <small class="d-block text-xs opacity-50">Catatan Pembinaan</small></span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Pengaturan</div>
        
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" 
               href="profile.php">
                <i class="fas fa-fw fa-id-card me-2"></i>
                <span>الملف الشخصي <small class="d-block text-xs opacity-50">Profile Saya</small></span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link logout-btn" href="../logout.php" onclick="return confirm('Yakin ingin logout?')">
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
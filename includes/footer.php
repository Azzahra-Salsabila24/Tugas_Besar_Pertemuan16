<footer class="bg-white py-4 mt-auto border-top" style="border-top: 3px solid #f0fdf4 !important;">
                <div class="container-fluid px-4">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between small">
                        <div class="text-muted">
                            &copy; <?php echo date('Y'); ?> 
                            <strong style="color: #059669;">Pondok Pesantren Asshiddiqiyah Karawang</strong>.
                        </div>
                        <div class="text-center text-md-end mt-2 mt-md-0">
                            <span class="text-muted">Sistem Pemantauan & Edukasi Santri</span>
                            <span class="mx-2 text-silver">|</span>
                            <span class="badge bg-emerald-light text-emerald px-2 py-1 rounded-pill" style="background: #f0fdf4; color: #059669;">v1.0.0</span>
                        </div>
                    </div>
                </div>
            </footer>
            </div>
        </div>
    <a class="scroll-to-top shadow-lg" href="#page-top" id="scrollToTopBtn">
        <i class="fas fa-chevron-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fas fa-sign-out-alt text-danger me-2"></i>Konfirmasi Keluar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted mb-0">Apakah Anda yakin ingin mengakhiri sesi ini? Perubahan yang belum disimpan mungkin akan hilang.</p>
                </div>
                <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-light fw-bold px-4 rounded-3" data-bs-dismiss="modal">BATAL</button>
                    <a class="btn btn-danger fw-bold px-4 rounded-3 shadow-sm" href="<?php echo BASE_URL; ?>logout.php">YA, LOGOUT</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .scroll-to-top {
            position: fixed;
            right: 1.5rem;
            bottom: 1.5rem;
            width: 45px;
            height: 45px;
            text-align: center;
            color: white;
            background: #059669;
            line-height: 45px;
            border-radius: 12px;
            z-index: 1030;
            display: none;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .scroll-to-top:hover {
            background: #047857;
            color: white;
            transform: translateY(-5px);
        }
        .text-silver { color: #e2e8f0; }
        .rounded-4 { border-radius: 1rem !important; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Scroll To Top Logic
        const scrollBtn = document.getElementById('scrollToTopBtn');
        window.onscroll = function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollBtn.style.display = "block";
            } else {
                scrollBtn.style.display = "none";
            }
        };

        scrollBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Sidebar Toggle Logic
        document.getElementById('sidebarToggleTop')?.addEventListener('click', function() {
            document.getElementById('wrapper').classList.toggle('toggled');
            document.querySelector('.sidebar')?.classList.toggle('show');
        });
    </script>

</body>
</html>
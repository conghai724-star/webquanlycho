 <style>
        .sidebar-collapse-btn {
            margin-left: auto;
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            width: 28px; height: 28px;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s, color 0.15s, transform 0.22s;
            flex-shrink: 0;
        }
        .sidebar-collapse-btn:hover { background: rgba(255,255,255,0.1); color: var(--text-color); }
        body.sidebar-rail .sidebar-collapse-btn svg { transform: rotate(180deg); }
        @media (max-width: 768px) { .sidebar-collapse-btn { display: none; } }
        .sidebar-backdrop {
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 95;
            opacity: 1;
            transition: opacity 0.18s;
        }
        .sidebar-backdrop[hidden] { display: none; }
        @media (min-width: 769px) { .sidebar-backdrop { display: none !important; } }
        body.sidebar-rail .sidebar-user-info { display: none; }
    </style>
<aside class="sidebar" aria-label="Primary navigation">
    <div class="sidebar-brand">
        <div class="brand-icon" style="background-color: var(--primary); color: white; border-radius: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: bold;">W</div>
        <div class="brand-name">ADMIN WEB <small style="font-size: 10px; color: var(--text-muted)">v1.0</small></div>
        <button type="button" class="sidebar-collapse-btn" aria-label="Thu gọn sidebar" title="Thu gọn/Mở rộng sidebar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <!-- Nhóm Tổng quan -->
        <?php if (session::hasWebModule('dashboard')): ?>
            <div class="nav-group">
                <div class="nav-label">Tổng quan</div>
                <a class="nav-link <?php echo (isset($title) && strpos($title, 'Tổng quan') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/dashboard" data-rail-label="Trang chủ">
                    <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <span class="nav-text">Trang chủ</span>
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Nhóm Bản đồ số -->
        <?php if (session::hasWebModule('map_editor') || session::hasWebModule('map_tree')): ?>
            <div class="nav-group">
                <div class="nav-label">Bản đồ số</div>
                
                <?php if (session::hasWebModule('map_editor')): ?>
                    <a class="nav-link <?php echo (isset($title) && strpos($title, 'Biên Tập') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/map_editor" data-rail-label="Biên tập bản đồ">
                        <i class="fa-solid fa-map-location-dot icon" style="font-size: 16px; margin-right: 8px;"></i>
                        <span class="nav-text">Biên tập Bản đồ số</span>
                    </a>
                <?php endif; ?>

                <?php if (session::hasWebModule('map_tree')): ?>
                    <a class="nav-link <?php echo (isset($title) && strpos($title, 'Cây Sơ Đồ') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/map_tree" data-rail-label="Cây sơ đồ">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2v8M6 10h12M6 10v6M18 10v6M2 16h8M14 16h8"/></svg>
                        <span class="nav-text">Sơ đồ Cây bản đồ</span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Nhóm Quản lý Nội dung Web -->
        <?php if (session::hasWebModule('banners') || session::hasWebModule('registrations') || session::hasWebModule('feedbacks')): ?>
            <div class="nav-group">
                <div class="nav-label">Nội dung & Yêu cầu</div>
                
                <?php if (session::hasWebModule('banners')): ?>
                    <a class="nav-link <?php echo (isset($title) && strpos($title, 'Banner') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/banners" data-rail-label="Banner">
                        <i class="fa-solid fa-images icon" style="font-size: 16px; margin-right: 8px;"></i>
                        <span class="nav-text">Quản lý Banner</span>
                    </a>
                    <a class="nav-link <?php echo (isset($title) && strpos($title, 'Tin Tức') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/posts" data-rail-label="Tin tức">
                        <i class="fa-solid fa-newspaper icon" style="font-size: 16px; margin-right: 8px;"></i>
                        <span class="nav-text">Quản lý Tin Tức</span>
                    </a>
                    <a class="nav-link <?php echo (isset($title) && strpos($title, 'Thông Tin Liên Hệ') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/contact_settings" data-rail-label="Liên hệ">
                        <i class="fa-solid fa-address-book icon" style="font-size: 16px; margin-right: 8px;"></i>
                        <span class="nav-text">Thông tin Liên hệ</span>
                    </a>
                <?php endif; ?>


                <?php if (session::hasWebModule('registrations')): ?>
                    <a class="nav-link <?php echo (isset($title) && strpos($title, 'Thuê Sạp') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/registrations" data-rail-label="Đăng ký sạp">
                        <i class="fa-solid fa-file-signature icon" style="font-size: 16px; margin-right: 8px;"></i>
                        <span class="nav-text">Đăng ký Thuê Sạp</span>
                    </a>
                <?php endif; ?>

                <?php if (session::hasWebModule('feedbacks')): ?>
                    <a class="nav-link <?php echo (isset($title) && strpos($title, 'Góp ý') !== false || strpos($title, 'Khiếu nại') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/feedbacks" data-rail-label="Góp ý/Khiếu nại">
                        <i class="fa-solid fa-comments icon" style="font-size: 16px; margin-right: 8px;"></i>
                        <span class="nav-text">Khiếu nại & Góp ý</span>
                    </a>
                <?php endif; ?>
            </div>

        <?php endif; ?>

        <!-- Nhóm Tài khoản & Phân quyền -->
        <?php if (session::hasWebModule('users') || session::hasWebModule('roles')): ?>
            <div class="nav-group">
                <div class="nav-label">Hệ thống</div>
                
                <a class="nav-link <?php echo (isset($title) && (strpos($title, 'Tài Khoản') !== false || strpos($title, 'Phân Quyền') !== false || strpos($title, 'Vai Trò') !== false)) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/users" data-rail-label="Tài khoản">
                    <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="nav-text">Tài khoản & Phân quyền</span>
                </a>
            </div>
        <?php endif; ?>


    </nav>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sidebar = document.querySelector('.sidebar');
        var body = document.body;

        var mobileToggle = document.querySelector('.sidebar-toggle');
        var backdrop = document.createElement('div');
        backdrop.className = 'sidebar-backdrop';
        backdrop.hidden = true;
        document.body.appendChild(backdrop);

        function openMobileSidebar() {
            sidebar.classList.add('open');
            backdrop.hidden = false;
            if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'true');
        }
        function closeMobileSidebar() {
            sidebar.classList.remove('open');
            backdrop.hidden = true;
            if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'false');
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function () {
                var isOpen = sidebar.classList.contains('open');
                isOpen ? closeMobileSidebar() : openMobileSidebar();
            });
        }
        backdrop.addEventListener('click', closeMobileSidebar);

        var collapseBtn = document.querySelector('.sidebar-collapse-btn');
        if (document.documentElement.classList.contains('sidebar-rail-pending') || 
            (localStorage.getItem('sidebar-collapsed') === '1' && window.innerWidth > 768)) {
            body.classList.add('sidebar-rail');
            document.documentElement.classList.remove('sidebar-rail-pending');
        }

        if (collapseBtn) {
            collapseBtn.addEventListener('click', function () {
                var isRail = body.classList.toggle('sidebar-rail');
                localStorage.setItem('sidebar-collapsed', isRail ? '1' : '0');
            });
        }
    });
</script>

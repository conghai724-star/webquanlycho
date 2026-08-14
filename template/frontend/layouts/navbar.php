    <!-- ================= HEADER NAVBAR ================= -->
    <header id="siteHeader">
        <div class="header-inner">
            <button class="menu-toggle" id="menuToggle" aria-label="Mở menu"
                aria-expanded="false"><span></span><span></span><span></span></button>
            <div class="brand">
                <div class="brand-mark">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M3 9L12 3L21 9V20C21 20.55 20.55 21 20 21H4C3.45 21 3 20.55 3 20V9Z" stroke="white"
                            stroke-width="1.8" stroke-linejoin="round" />
                        <path d="M9 21V13H15V21" stroke="white" stroke-width="1.8" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="brand-name">
                    <strong>CHỢ TRUNG TÂM THÀNH PHỐ</strong>
                    <span>Ban Quản lý Chợ</span>
                </div>
            </div>
            <nav class="main-nav">
                <a href="<?php echo BASE_URL; ?>" class="<?php echo ($activePage ?? 'home') === 'home' ? 'active' : ''; ?>">Trang chủ</a>
                <a href="<?php echo BASE_URL; ?>home/about" class="<?php echo ($activePage ?? 'home') === 'about' ? 'active' : ''; ?>">Giới thiệu</a>
                <a href="<?php echo BASE_URL; ?>home/map" class="<?php echo ($activePage ?? 'home') === 'map' ? 'active' : ''; ?>">Sơ đồ chợ (SVG)</a>

                <a href="<?php echo BASE_URL; ?>home/traders" class="<?php echo ($activePage ?? 'home') === 'traders' ? 'active' : ''; ?>">Tiểu thương</a>
                <a href="<?php echo BASE_URL; ?>home/register" class="<?php echo ($activePage ?? 'home') === 'register' ? 'active' : ''; ?>">Đăng ký thuê sạp</a>
                <a href="<?php echo BASE_URL; ?>home/posts" class="<?php echo ($activePage ?? 'home') === 'posts' ? 'active' : ''; ?>">Tin tức</a>
                <a href="<?php echo BASE_URL; ?>#thuvien">Thư viện</a>
                <a href="<?php echo BASE_URL; ?>home/contact" class="<?php echo ($activePage ?? 'home') === 'contact' ? 'active' : ''; ?>">Liên hệ</a>
            </nav>
            <div class="header-right">
                <!-- Đã bỏ nút đăng nhập theo yêu cầu -->
            </div>
        </div>
        <div class="mobile-nav" id="mobileNav">
            <nav class="mobile-nav-panel">
                <a href="<?php echo BASE_URL; ?>" class="<?php echo ($activePage ?? 'home') === 'home' ? 'active' : ''; ?>">Trang chủ</a>
                <a href="<?php echo BASE_URL; ?>home/about" class="<?php echo ($activePage ?? 'home') === 'about' ? 'active' : ''; ?>">Giới thiệu</a>
                <a href="<?php echo BASE_URL; ?>home/map" class="<?php echo ($activePage ?? 'home') === 'map' ? 'active' : ''; ?>">Sơ đồ chợ (SVG)</a>

                <a href="<?php echo BASE_URL; ?>home/traders" class="<?php echo ($activePage ?? 'home') === 'traders' ? 'active' : ''; ?>">Tiểu thương</a>
                <a href="<?php echo BASE_URL; ?>home/register" class="<?php echo ($activePage ?? 'home') === 'register' ? 'active' : ''; ?>">Đăng ký thuê sạp</a>
                <a href="<?php echo BASE_URL; ?>home/posts" class="<?php echo ($activePage ?? 'home') === 'posts' ? 'active' : ''; ?>">Tin tức</a>
                <a href="<?php echo BASE_URL; ?>#thuvien">Thư viện</a>
                <a href="<?php echo BASE_URL; ?>home/contact" class="<?php echo ($activePage ?? 'home') === 'contact' ? 'active' : ''; ?>">Liên hệ</a>
            </nav>
        </div>
    </header>


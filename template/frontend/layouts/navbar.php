    <?php
    $fCfg = $settings ?? $this->view->data['settings'] ?? $this->data['settings'] ?? [];
    $siteName = !empty($fCfg['website_name']) ? $fCfg['website_name'] : 'CHỢ TRUNG TÂM THÀNH PHỐ';
    $siteSubtitle = !empty($fCfg['website_subtitle']) ? $fCfg['website_subtitle'] : 'Ban Quản lý Chợ';
    $siteLogo = !empty($fCfg['website_logo']) ? $fCfg['website_logo'] : '';
    ?>
    <!-- ================= HEADER NAVBAR ================= -->
    <header id="siteHeader">
        <div class="header-inner">
            <button class="menu-toggle" id="menuToggle" aria-label="Mở menu"
                aria-expanded="false"><span></span><span></span><span></span></button>
            <a href="<?php echo BASE_URL; ?>" class="brand" style="text-decoration:none; color:inherit;">
                <div class="brand-mark" style="overflow: hidden;">
                    <?php if (!empty($siteLogo)): ?>
                        <img src="<?php echo htmlspecialchars($siteLogo); ?>" alt="<?php echo htmlspecialchars($siteName); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php else: ?>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M3 9L12 3L21 9V20C21 20.55 20.55 21 20 21H4C3.45 21 3 20.55 3 20V9Z" stroke="white"
                                stroke-width="1.8" stroke-linejoin="round" />
                            <path d="M9 21V13H15V21" stroke="white" stroke-width="1.8" stroke-linejoin="round" />
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="brand-name">
                    <strong><?php echo htmlspecialchars($siteName); ?></strong>
                    <span><?php echo htmlspecialchars($siteSubtitle); ?></span>
                </div>
            </a>
            <nav class="main-nav">
                <a href="<?php echo BASE_URL; ?>" class="<?php echo ($activePage ?? 'home') === 'home' ? 'active' : ''; ?>">Trang chủ</a>
                <a href="<?php echo BASE_URL; ?>home/about" class="<?php echo ($activePage ?? 'home') === 'about' ? 'active' : ''; ?>">Giới thiệu</a>
                <a href="<?php echo BASE_URL; ?>home/map" class="<?php echo ($activePage ?? 'home') === 'map' ? 'active' : ''; ?>">Sơ đồ chợ (SVG)</a>

                <a href="<?php echo BASE_URL; ?>home/traders" class="<?php echo ($activePage ?? 'home') === 'traders' ? 'active' : ''; ?>">Tiểu thương</a>
                <a href="<?php echo BASE_URL; ?>home/register" class="<?php echo ($activePage ?? 'home') === 'register' ? 'active' : ''; ?>">Đăng ký thuê sạp</a>
                <a href="<?php echo BASE_URL; ?>home/posts" class="<?php echo ($activePage ?? 'home') === 'posts' ? 'active' : ''; ?>">Tin tức</a>
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
                <a href="<?php echo BASE_URL; ?>home/contact" class="<?php echo ($activePage ?? 'home') === 'contact' ? 'active' : ''; ?>">Liên hệ</a>
            </nav>
        </div>
    </header>


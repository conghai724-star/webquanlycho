<header class="topbar">
    <div class="topbar-left">
        <!-- Nút toggle sidebar trên mobile -->
        <button class="sidebar-toggle" type="button" aria-label="Mở menu" aria-controls="sidebar" aria-expanded="false">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        
        <!-- Breadcrumb dẫn đường -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo BASE_URL; ?>admin/dashboard" class="bc-link">Home</a>
            <span class="separator">/</span>
            <span class="current" aria-current="page"><?php echo $title ?? 'Tổng quan'; ?></span>
        </nav>
    </div>

    <!-- Ô tìm kiếm trang hoặc chạy lệnh -->
    <div class="search-box">
        <svg class="s-icon" width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="7" cy="7" r="5"/><path d="M11 11l3.5 3.5"/></svg>
        <input type="text" placeholder="Tìm nhanh sạp hoặc tiểu thương..." aria-label="Tìm kiếm">
        <kbd>⌘K</kbd>
    </div>

    <!-- Actions bên phải -->
    <div class="topbar-right">
        <!-- Bộ chọn chợ nhanh cho BQL -->
        <?php if (session::get('user_logged_in')): ?>
            <?php
            $db = database::getInstance();
            $accMarkets = marketService::getAccessibleMarketIds();
            $activeMarketId = session::get('active_market_id') !== null ? (int)session::get('active_market_id') : 0;
            ?>
            <div class="market-selector-wrapper" style="margin-right: 12px; display: inline-flex; align-items: center; gap: 6px;">
                <label for="topbar-market-selector" style="font-size: 12px; font-weight: 500; color: var(--text-muted); display: none; @media(min-width:768px){display:inline;}">Chợ đang chọn:</label>
                <select id="topbar-market-selector" style="padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-surface); color: var(--text-color); font-size: 13px; font-weight: 500; cursor: pointer; outline: none; transition: border-color 0.2s;">
                    <?php if (marketService::isSuperAdmin() || marketService::isAdminMarket()): ?>
                        <option value="0" <?php echo ($activeMarketId === 0) ? 'selected' : ''; ?>>📊 Toàn Bộ Hệ Thống (Trang Tổng)</option>
                    <?php endif; ?>
                    <?php if (!empty($accMarkets)): ?>
                        <?php
                        $marketListObj = $db->select("SELECT market_id, market_name FROM markets WHERE market_id IN (" . implode(',', $accMarkets) . ") AND market_status_code = 'active' ORDER BY market_name ASC");
                        foreach ($marketListObj as $mObj):
                        ?>
                            <option value="<?php echo $mObj['market_id']; ?>" <?php echo ($activeMarketId === (int)$mObj['market_id']) ? 'selected' : ''; ?>>
                                🏪 <?php echo htmlspecialchars($mObj['market_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <script>
                    document.getElementById('topbar-market-selector').addEventListener('change', function() {
                        var marketId = this.value;
                        fetch('<?php echo BASE_URL; ?>api/changeMarketScope?id=' + marketId)
                            .then(response => response.json())
                            .then(res => {
                                if (res.status === 200) {
                                    if (marketId == 0) {
                                        window.location.href = '<?php echo BASE_URL; ?>system/dashboard';
                                    } else {
                                        window.location.href = '<?php echo BASE_URL; ?>admin/dashboard';
                                    }
                                } else {
                                    alert(res.message);
                                }
                            })
                            .catch(() => alert('Không thể kết nối với máy chủ.'));
                    });
                </script>
            </div>
        <?php endif; ?>

        <!-- Nút bật/tắt Dark Mode -->
        <button class="tb-btn theme-toggle" type="button" title="Chuyển chế độ tối/sáng" aria-label="Toggle theme" aria-pressed="false">
            <svg class="theme-icon-light" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
            <svg class="theme-icon-dark" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>



        <!-- Avatar người dùng kèm Dropdown -->
        <div class="avatar-dropdown-wrapper" style="position: relative; display: inline-block;">
            <button class="tb-avatar" id="avatar-btn" type="button" aria-label="Menu tài khoản" aria-haspopup="menu" aria-expanded="false" style="background-color: var(--primary); color: white; font-weight: bold; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; cursor: pointer;">
                <?php echo strtoupper(substr(session::get('username', 'Q'), 0, 1)); ?>
            </button>
            <div class="avatar-dropdown-menu" id="avatar-menu" style="display: none; position: absolute; right: 0; top: calc(100% + 8px); background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 180px; z-index: 1000; padding: 6px 0;">
                <a href="<?php echo BASE_URL; ?>admin/profile" class="avatar-dropdown-item" style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; color: var(--text-color); text-decoration: none; font-size: 13px; font-weight: 500; transition: background-color 0.2s;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Thông tin cá nhân
                </a>
                <div style="border-top: 1px solid var(--border-color); margin: 6px 0;"></div>
                <a href="<?php echo BASE_URL; ?>home/logout" class="avatar-dropdown-item logout-item" style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; color: var(--red, #e74c3c); text-decoration: none; font-size: 13px; font-weight: 500; transition: background-color 0.2s;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Đăng xuất
                </a>
            </div>
        </div>
        <style>
            .avatar-dropdown-item:hover {
                background-color: var(--bg-surface-secondary) !important;
            }
            .avatar-dropdown-item.logout-item:hover {
                background-color: rgba(231, 76, 60, 0.08) !important;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Theme toggle click handler
                var themeBtn = document.querySelector('.theme-toggle');
                if (themeBtn) {
                    themeBtn.addEventListener('click', function() {
                        var html = document.documentElement;
                        var currentTheme = html.getAttribute('data-theme') || 'light';
                        var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                        
                        html.setAttribute('data-theme', newTheme);
                        html.style.background = newTheme === 'dark' ? '#0f1623' : '#f5f7fb';
                    });
                }

                // Avatar dropdown click handler
                var avatarBtn = document.getElementById('avatar-btn');
                var avatarMenu = document.getElementById('avatar-menu');
                if (avatarBtn && avatarMenu) {
                    avatarBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        var isExpanded = avatarBtn.getAttribute('aria-expanded') === 'true';
                        avatarBtn.setAttribute('aria-expanded', !isExpanded);
                        avatarMenu.style.display = isExpanded ? 'none' : 'block';
                    });
                    document.addEventListener('click', function() {
                        avatarBtn.setAttribute('aria-expanded', 'false');
                        avatarMenu.style.display = 'none';
                    });
                }
            });
        </script>
    </div>
</header>

<!-- Main area of dashboard -->
<main id="main-content" tabindex="-1" class="main">
<div class="page-wrapper">

    <!-- Tiêu đề trang -->
    <div class="page-header" style="margin-bottom: 24px;">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Hệ thống Quản lý Chợ Smart</div>
                <h1 class="page-title"><?php echo $title ?? 'Dashboard'; ?></h1>
            </div>
            <!-- Nút lối tắt -->
            <?php 
            $activeMId = session::get('active_market_id') !== null ? (int)session::get('active_market_id') : 0;
            if ($activeMId > 0): 
            ?>
                <div class="page-actions">
                    <a href="<?php echo BASE_URL; ?>admin/traders" class="btn btn-outline" style="text-decoration: none;">
                        <i class="fa-solid fa-users me-1" style="font-size: 11px;"></i> Tiểu thương
                    </a>
                    <a href="<?php echo BASE_URL; ?>admin/stalls" class="btn btn-primary" style="text-decoration: none; color: white;">
                        <i class="fa-solid fa-store me-1" style="font-size: 11px;"></i> Quản lý Sạp
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

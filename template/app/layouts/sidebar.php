 <style>
        /* ponytail: Desktop collapse button */
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
        /* ponytail: Hide collapse btn on mobile, show hamburger instead */
        @media (max-width: 768px) { .sidebar-collapse-btn { display: none; } }
        /* ponytail: Sidebar backdrop for mobile overlay */
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
        /* ponytail: hide user info text in rail mode */
        body.sidebar-rail .sidebar-user-info { display: none; }

        .income-menu-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            border: 0;
            background: transparent;
            padding: 10px 12px;
            border-radius: 8px;
            font: inherit;
            text-align: left;
            cursor: pointer;
        }

        .income-menu-toggle:hover {
            background: rgba(37, 99, 235, 0.08);
        }

        .income-menu-toggle.active {
            color: var(--primary);
            background: rgba(37, 99, 235, 0.1);
        }

        .income-menu-chevron {
            margin-left: auto;
            transition: transform 0.2s ease;
        }

        .income-menu-toggle[aria-expanded="true"] .income-menu-chevron {
            transform: rotate(180deg);
        }

        .income-submenu {
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin: 0 0 0 28px;
            padding-left: 9px;
            border-left: 1px solid var(--border-color);
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.25s ease, opacity 0.2s ease, margin 0.25s ease;
        }

        .income-submenu.is-open {
            max-height: 220px;
            opacity: 1;
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .income-submenu .nav-link {
            min-height: 32px;
            padding: 7px 10px;
            font-size: 12px;
        }
    </style>
<aside class="sidebar" aria-label="Primary navigation">
    <div class="sidebar-brand">
        <div class="brand-icon" style="background-color: var(--primary); color: white; border-radius: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: bold;">C</div>
        <div class="brand-name">CHỢ SMART <small style="font-size: 10px; color: var(--text-muted)">BQL</small></div>
        <!-- Nút thu gọn sidebar trên desktop -->
        <button type="button" class="sidebar-collapse-btn" aria-label="Thu gọn sidebar" title="Thu gọn/Mở rộng sidebar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
    </div>
    
    <?php
    $activeMarketId = session::get('active_market_id') !== null ? (int)session::get('active_market_id') : 0;
    $showMarketMenus = ($activeMarketId > 0);
    ?>
    <nav class="sidebar-nav">
        <!-- Nhóm chung -->
        <div class="nav-group">
            <div class="nav-label">Chung</div>
            
            <!-- Dashboard trang tổng hợp (nếu là super_market hoặc admin_market) -->
            <?php if ($activeMarketId === 0 && (marketService::isSuperAdmin() || marketService::isAdminMarket())): ?>
                <a class="nav-link <?php echo (isset($title) && $title === 'Trang Tổng Quan Hợp Nhất') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>system/dashboard" data-rail-label="Trang tổng quan">
                    <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="4" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="10" width="7" height="11" rx="1.5"/></svg>
                    <span class="nav-text">Trang tổng quan</span>
                </a>
            <?php endif; ?>

            <!-- Quản lý Chợ (chỉ dành cho super_market) -->
            <?php if (marketService::isSuperAdmin()): ?>
                <a class="nav-link <?php echo (isset($title) && $title === 'Quản Lý Danh Sách Chợ') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>system/markets" data-rail-label="Quản lý Chợ">
                    <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18M3 10h18M3 6h18M3 14h18M9 21v-7h6v7"/></svg>
                    <span class="nav-text">Quản lý Chợ</span>
                </a>
            <?php endif; ?>

            <!-- Dashboard chợ hiện tại (nếu có chợ đang hoạt động) -->
            <?php if ($activeMarketId > 0 && marketService::checkModuleAccess('dashboard')): ?>
                <a class="nav-link <?php echo (isset($title) && $title === 'Bảng Điều Khiển') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/dashboard" data-rail-label="Trang chủ">
                    <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <span class="nav-text">Trang chủ</span>
                </a>
            <?php endif; ?>

            <!-- Thông tin cá nhân -->
            <?php if (marketService::checkModuleAccess('profile')): ?>
                <a class="nav-link <?php echo (isset($title) && $title === 'Thông tin cá nhân') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/profile" data-rail-label="Thông tin cá nhân">
                    <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="nav-text">Thông tin cá nhân</span>
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Nhóm quản lý sạp & tiểu thương -->
        <?php if ($showMarketMenus): ?>
            <div class="nav-group">
                <?php 
                $showStalls = marketService::checkModuleAccess('stall');
                $showMapTree = marketService::checkModuleAccess('map_tree');
                $showTraders = marketService::checkModuleAccess('trader');
                $showContracts = marketService::checkModuleAccess('contract');
                
                if ($showStalls || $showMapTree || $showTraders || $showContracts):
                ?>
                    <div class="nav-label">Mặt bằng & Con người</div>
                    
                    <!-- Sạp chợ -->
                    <?php if ($showStalls): ?>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/stalls" data-rail-label="Sạp chợ">
                            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M9 10v9M15 10v9"/></svg>
                            <span class="nav-text">Quản lý Sạp chợ</span>
                        </a>
                    <?php endif; ?>

                    <!-- Sơ đồ Cây sạp chợ -->
                    <?php if ($showMapTree): ?>
                        <a class="nav-link <?php echo (isset($title) && $title === 'Sơ đồ Cây sạp chợ tương tác') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/map_tree" data-rail-label="Sơ đồ Cây">
                            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2v8M6 10h12M6 10v6M18 10v6M2 16h8M14 16h8"/></svg>
                            <span class="nav-text">Sơ đồ Cây sạp chợ</span>
                        </a>
                    <?php endif; ?>

                    <!-- Tiểu thương -->
                    <?php if ($showTraders): ?>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/traders" data-rail-label="Tiểu thương">
                            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                            <span class="nav-text">Quản lý Tiểu thương</span>
                        </a>
                    <?php endif; ?>

                    <!-- Hợp đồng -->
                    <?php if ($showContracts): ?>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/contracts" data-rail-label="Hợp đồng">
                            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="M2 7l10 6 10-6"/></svg>
                            <span class="nav-text">Hợp đồng Thuê sạp</span>
                        </a>
                    <?php endif; ?>

                    <!-- Cấu hình mẫu in (Chỉ dành cho Super Admin hoặc Admin Chợ khi đang chọn Chợ làm việc) -->
                    <?php if ($showContracts && $activeMarketId > 0 && (marketService::isSuperAdmin() || marketService::isAdminMarket())): ?>
                        <a class="nav-link <?php echo (isset($title) && strpos($title, 'Mẫu In Hợp Đồng') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>system/market_contract_configs/<?php echo $activeMarketId; ?>" data-rail-label="Cấu hình mẫu in">
                            <i class="fa-solid fa-print icon" style="font-size: 16px; margin-right: 8px;"></i>
                            <span class="nav-text">Cấu hình mẫu in</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

              <!-- Nhóm dịch vụ & tài chính -->
            <?php 
            $showUtilities = marketService::checkModuleAccess('utilities');
            $showFinance = marketService::checkModuleAccess('finance');
            
            if ($showUtilities || $showFinance):
            ?>
                <div class="nav-group">
                    <div class="nav-label">Vận hành & Tài chính</div>
                    
                    <!-- Chỉ số dịch vụ -->
                    <?php if ($showUtilities): ?>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/utilities" data-rail-label="Điện & Nước">
                            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span class="nav-text">Chỉ số Điện & Nước</span>
                        </a>
                    <?php endif; ?>

                    <!-- Thu Chi -->
                    <?php if ($showFinance): ?>
                        <?php $isIncomeMenu = isset($title) && in_array($title, ['Thu - Chi tài chính', 'Quản Lý Thu', 'Quản Lý Chi', 'Danh Mục Thu Chi', 'Báo Cáo Thu Chi'], true); ?>
                        <button type="button" class="nav-link income-menu-toggle <?php echo $isIncomeMenu ? 'active' : ''; ?>" aria-expanded="<?php echo $isIncomeMenu ? 'true' : 'false'; ?>" aria-controls="income-submenu">
                            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="2" x2="12" y2="22"/><path d="M16 6H9.5a3.5 3.5 0 100 7h5a3.5 3.5 0 010 7H7"/></svg>
                            <span class="nav-text">Thu - Chi tài chính</span>
                            <span class="income-menu-chevron" aria-hidden="true">⌄</span>
                        </button>
                        <div id="income-submenu" class="income-submenu <?php echo $isIncomeMenu ? 'is-open' : ''; ?>">
                            <a class="nav-link <?php echo (isset($title) && $title === 'Quản Lý Thu') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/income">
                                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 20V4M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
                                <span class="nav-text">Quản lý Thu</span>
                            </a>
                            <a class="nav-link <?php echo (isset($title) && $title === 'Quản Lý Chi') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/expense">
                                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 4v16M7 15l5 5 5-5"/><path d="M5 4h14"/></svg>
                                <span class="nav-text">Quản lý Chi</span>
                            </a>
                            <a class="nav-link <?php echo (isset($title) && $title === 'Danh Mục Thu Chi') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/income_categories">
                                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                                <span class="nav-text">Danh mục Thu Chi</span>
                            </a>
                            <a class="nav-link <?php echo (isset($title) && $title === 'Báo Cáo Thu Chi') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/income_report">
                                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 3h11l3 3v15H5z"/><path d="M8 11h8M8 15h8M8 7h3"/></svg>
                                <span class="nav-text">Báo cáo Thu Chi</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Nhóm kiểm tra & hệ thống -->
        <div class="nav-group">
            <?php 
            $showFoodSafety = $showMarketMenus && marketService::checkModuleAccess('foodsafety');
            $showUsers = marketService::isSuperAdmin() || marketService::isAdminMarket();
            $showLogs = marketService::checkModuleAccess('logs');
            $showCategories = $showMarketMenus && (marketService::isSuperAdmin() || marketService::isAdminMarket() || (session::get('actor_code') === 'admin' && marketService::checkModuleAccess('category')));
            $showTheme = marketService::isSuperAdmin();
            
            if ($showFoodSafety || $showUsers || $showLogs || $showCategories || $showTheme):
            ?>
                <div class="nav-label">Hệ thống</div>
                
                <!-- An toàn thực phẩm -->
                <?php if ($showFoodSafety): ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>admin/foodsafety" data-rail-label="ATTP">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="12" cy="12" r="3"/></svg>
                        <span class="nav-text">An toàn thực phẩm</span>
                    </a>
                <?php endif; ?>

                <!-- Tài khoản -->
                <?php if ($showUsers): ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>system/users" data-rail-label="Tài khoản">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span class="nav-text">Tài khoản & Quyền</span>
                    </a>
                    
                    <!-- Phân quyền nhanh dành cho admin_market và super_market -->
                    <?php if (marketService::isSuperAdmin() || marketService::isAdminMarket()): ?>
                        <a class="nav-link <?php echo (isset($title) && $title === 'Phân Quyền Nhân Viên') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>system/permissions" data-rail-label="Phân quyền">
                            <i class="fa-solid fa-user-shield icon" style="font-size: 16px;"></i>
                            <span class="nav-text">Phân quyền Nhân viên</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Cấu hình mẫu in hợp đồng (Hiển thị ở trang tổng cho Super Admin và Admin Chợ) -->
                <?php if ($activeMarketId === 0 && (marketService::isSuperAdmin() || marketService::isAdminMarket())): ?>
                    <a class="nav-link <?php echo (isset($title) && strpos($title, 'Mẫu In Hợp Đồng') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>system/all_contract_configs" data-rail-label="Mẫu in">
                        <i class="fa-solid fa-print icon" style="font-size: 16px; margin-right: 8px;"></i>
                        <span class="nav-text">Mẫu in hợp đồng</span>
                    </a>
                <?php endif; ?>

                <!-- Nhật ký hoạt động -->
                <?php if ($showLogs): ?>
                    <a class="nav-link <?php echo (isset($title) && $title === 'Nhật ký hoạt động') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>system/logs" data-rail-label="Nhật ký">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="nav-text">Nhật ký hoạt động</span>
                    </a>
                <?php endif; ?>

                <!-- Danh mục -->
                <?php if ($showCategories): ?>
                    <a class="nav-link <?php echo (isset($title) && $title === 'Quản Lý Danh Mục') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/categories" data-rail-label="Danh mục">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19h16M4 15h16M4 11h16M4 7h16"/></svg>
                        <span class="nav-text">Quản lý Danh mục</span>
                    </a>
                <?php endif; ?>

                <!-- Tùy biến chủ đề -->
                <?php if ($showTheme): ?>
                    <a class="nav-link <?php echo (isset($title) && $title === 'Tùy Biến Giao Diện') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/theme" data-rail-label="Chủ đề">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 11H5a2 2 0 00-2 2v2a2 2 0 002 2h2v3a1 1 0 001 1h3a1 1 0 001-1v-3h7a2 2 0 002-2v-2a2 2 0 00-2-2z"/><path d="M19 11V5a2 2 0 00-2-2h-2a2 2 0 00-2-2v6"/></svg>
                        <span class="nav-text">Trình tạo chủ đề</span>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Thùng chứa user sidebar footer của Gentelella -->
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar" style="background-color: var(--primary); color: white; font-weight: bold; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center;">
                <?php echo strtoupper(substr(session::get('username', 'Q'), 0, 1)); ?>
                <span class="online"></span>
            </div>
            <div class="sidebar-user-info">
                <div class="name" style="font-weight: 600; font-size: 13.5px;"><?php echo session::get('user_fullname', 'BQL Chợ'); ?></div>
                <div class="role" style="font-size: 11px;">
                    <?php 
                    $roleName = 'Nhân viên';
                    $actorCode = session::get('actor_code');
                    if ($actorCode === 'super_market') {
                        $roleName = 'Quản trị tối cao';
                    } elseif ($actorCode === 'admin_market') {
                        $roleName = 'Quản lý chợ';
                    } else {
                        // Admin cấp 3: Lấy vai trò cụ thể tại chợ đang hoạt động
                        $db = database::getInstance();
                        $activeMarketId = session::get('active_market_id');
                        $userId = session::get('user_id');
                        
                        if ($activeMarketId && $userId) {
                            $umRole = $db->selectOne("
                                SELECT user_market_role_id 
                                FROM user_markets 
                                WHERE user_market_user_id = :user_id AND user_market_market_id = :market_id
                            ", ['user_id' => $userId, 'market_id' => $activeMarketId]);
                            
                            if ($umRole) {
                                $roleId = (int)$umRole['user_market_role_id'];
                                $mRoleInfo = $db->selectOne("SELECT role_name FROM market_roles WHERE role_id = :rid", ['rid' => $roleId]);
                                $roleName = $mRoleInfo ? $mRoleInfo['role_name'] : 'Nhân viên vận hành';
                            } else {
                                $roleName = 'Nhân viên vận hành';
                            }
                        } else {
                            $roleName = 'Nhân viên vận hành';
                        }
                    }
                    echo htmlspecialchars($roleName);
                    ?>
                </div>
            </div>
            <!-- Bấm để đổi mật khẩu -->
            <a href="<?php echo BASE_URL; ?>admin/change_password" class="more-btn" aria-label="Đổi mật khẩu" title="Đổi mật khẩu" style="display: flex; align-items: center; justify-content: center; color: var(--text-muted); margin-right: 8px;">
                <i class="fa-solid fa-key" style="font-size: 14px;"></i>
            </a>
            <!-- Bấm để đăng xuất -->
            <a href="<?php echo BASE_URL; ?>home/logout" class="more-btn" aria-label="Đăng xuất" title="Đăng xuất" style="display: flex; align-items: center; justify-content: center; color: var(--text-muted); hover: {color: var(--red)}">
                <i class="fa-solid fa-right-from-bracket" style="font-size: 14px;"></i>
            </a>
        </div>
    </div>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // === Income submenu toggle ===
        document.querySelectorAll('.income-menu-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                var submenu = document.getElementById(this.getAttribute('aria-controls'));
                if (!submenu) return;
                var isOpen = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', String(!isOpen));
                submenu.classList.toggle('is-open', !isOpen);
                submenu.style.maxHeight = !isOpen ? (submenu.scrollHeight + 'px') : '0px';
            });
        });

        var sidebar = document.querySelector('.sidebar');
        var body = document.body;

        // === Mobile: hamburger toggle ===
        var mobileToggle = document.querySelector('.sidebar-toggle');
        // Create backdrop element for mobile overlay
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

        // === Desktop: sidebar collapse (rail mode) ===
        var collapseBtn = document.querySelector('.sidebar-collapse-btn');
        // Restore saved state (sidebar-rail-pending set in header.php before body paint)
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

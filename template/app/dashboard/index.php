<!-- ── HÀNG CARD THỐNG KÊ WEB ADMIN (3 CỘT) ── -->
<div class="row col-3" style="margin-bottom: 24px;">
    <!-- Card 1: Bản đồ số -->
    <div class="card">
        <div class="stat">
            <div class="stat-icon blue" style="background-color: rgba(66, 133, 244, 0.1); color: #4285F4;">
                <i class="fa-solid fa-map-location-dot" style="font-size: 18px;"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Bản đồ số & Đối tượng</div>
                <div class="stat-value-row">
                    <span class="stat-value"><?php echo $stats['total_map_elements'] ?? 0; ?></span>
                </div>
                <div class="stat-subtext">Đối tượng sơ đồ đã vẽ</div>
            </div>
        </div>
        <div style="padding: 0 16px 12px; margin-top: 8px;">
            <a href="<?php echo BASE_URL; ?>admin/map_editor" class="btn btn-outline btn-sm" style="width: 100%; text-decoration: none; justify-content: center; display: inline-flex;">
                <i class="fa-solid fa-pen-to-square me-1"></i> Vào biên tập bản đồ
            </a>
        </div>
    </div>

    <!-- Card 2: Tài khoản Web -->
    <div class="card">
        <div class="stat">
            <div class="stat-icon green" style="background-color: rgba(52, 168, 83, 0.1); color: #34A853;">
                <i class="fa-solid fa-users" style="font-size: 18px;"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tài khoản Web</div>
                <div class="stat-value-row">
                    <span class="stat-value"><?php echo $stats['total_web_users'] ?? 0; ?></span>
                </div>
                <div class="stat-subtext">Quản trị & Biên tập viên</div>
            </div>
        </div>
        <div style="padding: 0 16px 12px; margin-top: 8px;">
            <?php if (session::isWebAdmin()): ?>
                <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline btn-sm" style="width: 100%; text-decoration: none; justify-content: center; display: inline-flex;">
                    <i class="fa-solid fa-user-gear me-1"></i> Quản lý tài khoản
                </a>
            <?php else: ?>
                <span class="chip" style="display: block; text-align: center; font-size: 12px;">Role: Biên tập viên</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Card 3: Biên tập viên hoạt động -->
    <div class="card">
        <div class="stat">
            <div class="stat-icon yellow" style="background-color: rgba(251, 188, 4, 0.1); color: #FBBC04;">
                <i class="fa-solid fa-user-pen" style="font-size: 18px;"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Biên tập viên hoạt động</div>
                <div class="stat-value-row">
                    <span class="stat-value"><?php echo $stats['active_editors'] ?? 0; ?></span>
                </div>
                <div class="stat-subtext">Trạng thái sẵn sàng</div>
            </div>
        </div>
    </div>
</div>

<!-- LỐI TẮC TRUY CẬP NHANH -->
<div class="card" style="padding: 20px;">
    <h5 style="margin-top: 0; font-weight: 600; color: var(--text-heading);">Truy Cập Nhanh Chức Năng</h5>
    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 12px;">
        <a href="<?php echo BASE_URL; ?>admin/map_editor" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-map-location-dot"></i> Biên tập Bản đồ số
        </a>
        <a href="<?php echo BASE_URL; ?>admin/map_tree" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-sitemap"></i> Xem Cây Sơ Đồ
        </a>
        <?php if (session::isWebAdmin()): ?>
            <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-users-gear"></i> Tài khoản & Quyền Web
            </a>
        <?php endif; ?>
    </div>
</div>

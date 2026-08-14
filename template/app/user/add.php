<div style="margin-bottom: 20px;">
    <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách tài khoản
    </a>
</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600;">Thêm tài khoản Web & Phân quyền module mới</div>
    </div>
    <div class="card-body" style="padding: 24px;">
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px; background-color: rgba(234, 67, 53, 0.1); color: #EA4335; border: 1px solid rgba(234, 67, 53, 0.2); border-radius: 4px;">
                <?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>admin/user_add" method="POST">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="username" style="font-weight: 500;">Tên đăng nhập <span style="color: var(--red)">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Ví dụ: editor01" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="fullname" style="font-weight: 500;">Họ tên người dùng <span style="color: var(--red)">*</span></label>
                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Nhập họ và tên đầy đủ" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="email" style="font-weight: 500;">Địa chỉ Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="editor@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone" style="font-weight: 500;">Số điện thoại</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="0901234567">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
                <div class="form-group">
                    <label class="form-label" for="password" style="font-weight: 500;">Mật khẩu khởi tạo <span style="color: var(--red)">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="role" style="font-weight: 500;">Mẫu vai trò mặc định <span style="color: var(--red)">*</span></label>
                    <select id="role" name="role" class="form-control" required onchange="applyRolePerms(this.value)">
                        <option value="editor">Biên tập viên Web (Editor)</option>
                        <option value="admin">Quản trị viên Web (Admin - Full Access)</option>
                        <?php if (!empty($webRoles)): foreach ($webRoles as $r): ?>
                            <?php if (!in_array($r['role_code'], ['admin', 'editor'])): ?>
                                <option value="<?php echo htmlspecialchars($r['role_code']); ?>" data-perms="<?php echo htmlspecialchars($r['permissions']); ?>">
                                    <?php echo htmlspecialchars($r['role_name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status" style="font-weight: 500;">Trạng thái tài khoản</label>
                    <select id="status" name="status" class="form-control">
                        <option value="1" selected>Hoạt động bình thường</option>
                        <option value="0">Tạm khóa tài khoản</option>
                    </select>
                </div>
            </div>

            <!-- PHÂN QUYỀN MODULE CHI TIẾT (MENU ADMIN WEB MỚI) -->
            <div id="permissionsArea" style="margin-bottom: 24px; padding: 20px; background: var(--bg-surface-secondary, #f8f9fa); border: 1px solid var(--border-color); border-radius: 8px;">
                <label class="form-label" style="font-weight: 700; margin-bottom: 12px; display: block; color: var(--text-heading);">
                    <i class="fa-solid fa-user-shield me-1"></i> Phân quyền các phân hệ Menu Admin Web mới
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="dashboard" class="perm-cb" checked> Trang chủ (Dashboard)
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="map_editor" class="perm-cb" checked> Biên tập Bản đồ số
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="map_tree" class="perm-cb" checked> Sơ đồ Cây bản đồ
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="banners" class="perm-cb" checked> Nội dung Website (Banner, Tin tức, Liên hệ)
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="users" class="perm-cb"> Quản lý Tài khoản Web
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="roles" class="perm-cb"> Phân quyền Hệ thống
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary">Tạo tài khoản</button>
            </div>
        </form>
    </div>
</div>

<script>
function applyRolePerms(roleVal) {
    var area = document.getElementById('permissionsArea');
    if (roleVal === 'admin') {
        area.style.opacity = '0.5';
        area.style.pointerEvents = 'none';
        document.querySelectorAll('.perm-cb').forEach(cb => cb.checked = true);
    } else {
        area.style.opacity = '1';
        area.style.pointerEvents = 'auto';
        var selOpt = document.querySelector('#role option:checked');
        var permsStr = selOpt ? (selOpt.getAttribute('data-perms') || '') : '';
        if (permsStr) {
            var perms = permsStr.split(',').map(s => s.trim());
            document.querySelectorAll('.perm-cb').forEach(cb => {
                cb.checked = perms.includes('all') || perms.includes(cb.value);
            });
        }
    }
}
</script>
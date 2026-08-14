<?php
$currentPerms = array_filter(array_map('trim', explode(',', $user['permissions'] ?? '')));
$isAdmin = ($user['role'] ?? '') === 'admin' || in_array('all', $currentPerms);
?>

<div style="margin-bottom: 20px;">
    <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách tài khoản
    </a>
</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600;">Chỉnh sửa tài khoản & Phân quyền Web</div>
    </div>
    <div class="card-body" style="padding: 24px;">
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px; background-color: rgba(234, 67, 53, 0.1); color: #EA4335; border: 1px solid rgba(234, 67, 53, 0.2); border-radius: 4px;">
                <?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>admin/user_edit/<?php echo $user['id']; ?>" method="POST">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 500;">Tên đăng nhập (Cố định)</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly style="background-color: var(--bg-surface-secondary);">
                </div>

                <div class="form-group">
                    <label class="form-label" for="fullname" style="font-weight: 500;">Họ tên người dùng <span style="color: var(--red)">*</span></label>
                    <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="email" style="font-weight: 500;">Địa chỉ Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone" style="font-weight: 500;">Số điện thoại</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
                <div class="form-group">
                    <label class="form-label" for="password" style="font-weight: 500;">Mật khẩu mới (Bỏ trống nếu không đổi)</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label class="form-label" for="role" style="font-weight: 500;">Vai trò hệ thống Web <span style="color: var(--red)">*</span></label>
                    <select id="role" name="role" class="form-control" required onchange="applyRolePerms(this.value)">
                        <option value="editor" <?php echo (($user['role'] ?? '') === 'editor') ? 'selected' : ''; ?>>Biên tập viên Web (Editor)</option>
                        <option value="admin" <?php echo (($user['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Quản trị viên Web (Admin - Full Access)</option>
                        <?php if (!empty($webRoles)): foreach ($webRoles as $r): ?>
                            <?php if (!in_array($r['role_code'], ['admin', 'editor'])): ?>
                                <option value="<?php echo htmlspecialchars($r['role_code']); ?>" <?php echo (($user['role'] ?? '') === $r['role_code']) ? 'selected' : ''; ?> data-perms="<?php echo htmlspecialchars($r['permissions']); ?>">
                                    <?php echo htmlspecialchars($r['role_name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status" style="font-weight: 500;">Trạng thái tài khoản</label>
                    <select id="status" name="status" class="form-control">
                        <option value="1" <?php echo (($user['status'] ?? 1) == 1) ? 'selected' : ''; ?>>Hoạt động bình thường</option>
                        <option value="0" <?php echo (($user['status'] ?? 1) == 0) ? 'selected' : ''; ?>>Tạm khóa tài khoản</option>
                    </select>
                </div>
            </div>

            <!-- PHÂN QUYỀN MODULE CHI TIẾT (MENU ADMIN WEB MỚI) -->
            <div id="permissionsArea" style="margin-bottom: 24px; padding: 20px; background: var(--bg-surface-secondary, #f8f9fa); border: 1px solid var(--border-color); border-radius: 8px; <?php echo $isAdmin ? 'opacity:0.5;pointer-events:none;' : ''; ?>">
                <label class="form-label" style="font-weight: 700; margin-bottom: 12px; display: block; color: var(--text-heading);">
                    <i class="fa-solid fa-user-shield me-1"></i> Phân quyền các phân hệ Menu Admin Web mới
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="dashboard" class="perm-cb" <?php echo (in_array('dashboard', $currentPerms) || $isAdmin) ? 'checked' : ''; ?>> Trang chủ (Dashboard)
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="map_editor" class="perm-cb" <?php echo (in_array('map_editor', $currentPerms) || $isAdmin) ? 'checked' : ''; ?>> Biên tập Bản đồ số
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="map_tree" class="perm-cb" <?php echo (in_array('map_tree', $currentPerms) || $isAdmin) ? 'checked' : ''; ?>> Sơ đồ Cây bản đồ
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="banners" class="perm-cb" <?php echo (in_array('banners', $currentPerms) || $isAdmin) ? 'checked' : ''; ?>> Quản lý Banner
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="registrations" class="perm-cb" <?php echo (in_array('registrations', $currentPerms) || $isAdmin) ? 'checked' : ''; ?>> Đăng ký Thuê Sạp
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="feedbacks" class="perm-cb" <?php echo (in_array('feedbacks', $currentPerms) || $isAdmin) ? 'checked' : ''; ?>> Khiếu nại & Góp ý
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="users" class="perm-cb" <?php echo (in_array('users', $currentPerms) || $isAdmin) ? 'checked' : ''; ?>> Quản lý Tài khoản Web
                    </label>
                    <label class="form-check" style="cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="roles" class="perm-cb" <?php echo (in_array('roles', $currentPerms) || $isAdmin) ? 'checked' : ''; ?>> Phân quyền Hệ thống
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
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

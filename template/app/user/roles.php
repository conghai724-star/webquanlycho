<?php
$moduleNames = [
    'dashboard'     => 'Trang chủ (Dashboard)',
    'map_editor'    => 'Biên tập Bản đồ số',
    'map_tree'      => 'Sơ đồ Cây sạp chợ',
    'banners'       => 'Nội dung Website (Banner, Tin tức, Liên hệ)',
    'users'         => 'Quản lý Tài khoản Web',
    'roles'         => 'Quản lý Phân quyền'
];
?>

<div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-users"></i> Danh sách Tài khoản Web
        </a>
        <a href="<?php echo BASE_URL; ?>admin/permissions" class="btn btn-outline" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-user-shield"></i> Ma trận Phân quyền
        </a>
        <a href="<?php echo BASE_URL; ?>admin/roles" class="btn btn-primary" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-users-gear"></i> Quản lý Vai trò & Mẫu quyền
        </a>
    </div>
</div>


<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start;">
    <!-- CỘT TRÁI: DANH SÁCH VAI TRÒ VÀ MẪU PHÂN QUYỀN -->
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <div class="card-title" style="font-size: 15px; font-weight: 700; color: var(--text-heading);">
                <i class="fa-solid fa-user-shield me-2" style="color: var(--primary);"></i>Danh sách vai trò và mẫu phân quyền
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 12px 16px; width: 40px;">ID</th>
                            <th style="padding: 12px 16px; width: 180px;">TÊN VAI TRÒ</th>
                            <th style="padding: 12px 16px;">QUYỀN MẶC ĐỊNH</th>
                            <th style="padding: 12px 16px; width: 180px;">MÔ TẢ CHI TIẾT</th>
                            <th style="padding: 12px 16px; width: 100px;">TRẠNG THÁI</th>
                            <th style="padding: 12px 16px; text-align: right; width: 110px;">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($roles)): ?>
                            <?php foreach ($roles as $r): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 14px 16px; font-weight: 600; color: var(--text-muted);">
                                        <?php echo $r['id']; ?>
                                    </td>
                                    <td style="padding: 14px 16px; font-weight: 700; color: var(--text-heading);">
                                        <div><?php echo htmlspecialchars($r['role_name']); ?></div>
                                        <code style="font-size: 11px; font-weight: 400; color: var(--text-muted);"><?php echo htmlspecialchars($r['role_code']); ?></code>
                                    </td>
                                    <td style="padding: 14px 16px;">
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                            <?php 
                                            $perms = $r['permissions'] ?? '';
                                            if ($perms === 'all') {
                                                echo '<span class="chip" style="background: rgba(15, 118, 110, 0.1); color: var(--primary); border: 1px solid rgba(15, 118, 110, 0.2); font-weight: 600;">Tất cả phân hệ (Full Access)</span>';
                                            } else {
                                                $arr = array_filter(array_map('trim', explode(',', $perms)));
                                                if (empty($arr)) {
                                                    echo '<span style="color: var(--text-muted); font-style: italic;">Chưa gán quyền</span>';
                                                } else {
                                                    foreach ($arr as $mCode) {
                                                        $name = $moduleNames[$mCode] ?? $mCode;
                                                        echo '<span class="chip" style="background: rgba(59, 130, 246, 0.08); color: #1d4ed8; border: 1px solid rgba(59, 130, 246, 0.2); font-size: 11px; padding: 2px 8px; border-radius: 12px;">' . htmlspecialchars($name) . '</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td style="padding: 14px 16px; color: var(--text-muted); font-size: 12px; line-height: 1.4;">
                                        <?php echo htmlspecialchars($r['description'] ?: 'Không có mô tả'); ?>
                                    </td>
                                    <td style="padding: 14px 16px;">
                                        <?php if (($r['status'] ?? 1) == 1): ?>
                                            <span class="status status-green" style="background: rgba(46, 125, 50, 0.1); color: #2e7d32; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="status status-red" style="background: rgba(198, 40, 40, 0.1); color: #c62828; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Tạm khóa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 14px 16px; text-align: right;">
                                        <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                            <button onclick='editRole(<?php echo json_encode($r, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' class="btn btn-outline btn-sm" style="padding: 4px 8px; font-size: 11px; color: #1976d2;" title="Sửa">
                                                <i class="fa-solid fa-pen"></i> Sửa
                                            </button>
                                            <?php if ($r['role_code'] !== 'admin'): ?>
                                                <button type="button" onclick="confirmSoftDelete('<?php echo BASE_URL; ?>admin/role_delete/<?php echo $r['id']; ?>', '<?php echo htmlspecialchars(addslashes($r['role_name'])); ?>', 'vai trò')" class="btn btn-outline btn-sm" style="padding: 4px 8px; font-size: 11px; color: #d32f2f; border-color: #fca5a5;" title="Xóa">
                                                    <i class="fa-solid fa-trash"></i> Xóa
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">Chưa có vai trò nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CỘT PHẢI: FORM THÊM / SỬA VAI TRÒ MỚI -->
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <div class="card-title" id="roleFormTitle" style="font-size: 15px; font-weight: 700; color: var(--text-heading);">Thêm Vai Trò Mới</div>
        </div>
        <div class="card-body" style="padding: 20px;">
            <form id="roleForm" action="<?php echo BASE_URL; ?>admin/role_add" method="POST">
                <input type="hidden" id="role_id" name="id" value="">

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600;">Tên vai trò <span style="color: red">*</span></label>
                    <input type="text" id="role_name" name="role_name" class="form-control" placeholder="Ví dụ: Giám sát viên" required>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600;">Mã vai trò <span style="color: red">*</span></label>
                    <input type="text" id="role_code" name="role_code" class="form-control" placeholder="Ví dụ: gsv" required>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 700; color: var(--text-heading); display: block; margin-bottom: 8px;">
                        Gán quyền mặc định (Mẫu phân hệ)
                    </label>
                    <div style="background: var(--bg-surface-secondary, #f8f9fa); border: 1px solid var(--border-color); border-radius: 8px; padding: 14px; display: flex; flex-direction: column; gap: 8px; max-height: 260px; overflow-y: auto;">
                        <?php foreach ($moduleNames as $code => $name): ?>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;">
                                <input type="checkbox" name="permissions[]" value="<?php echo $code; ?>" class="role-perm-checkbox" style="width: 16px; height: 16px; cursor: pointer;">
                                <span><?php echo htmlspecialchars($name); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 600;">Mô tả chi tiết</label>
                    <textarea id="role_description" name="description" class="form-control" rows="3" placeholder="Nhập các trách nhiệm của vai trò này..."></textarea>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="resetRoleForm()" class="btn btn-outline" id="btnCancelRole" style="display: none;">Hủy</button>
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu vai trò</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetRoleForm() {
    document.getElementById('roleFormTitle').innerText = 'Thêm Vai Trò Mới';
    document.getElementById('roleForm').action = '<?php echo BASE_URL; ?>admin/role_add';
    document.getElementById('role_id').value = '';
    document.getElementById('role_name').value = '';
    document.getElementById('role_code').value = '';
    document.getElementById('role_code').readOnly = false;
    document.getElementById('role_description').value = '';
    document.querySelectorAll('.role-perm-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('btnCancelRole').style.display = 'none';
}

function editRole(r) {
    document.getElementById('roleFormTitle').innerText = 'Chỉnh Sửa Vai Trò: ' + r.role_name;
    document.getElementById('roleForm').action = '<?php echo BASE_URL; ?>admin/role_edit';
    document.getElementById('role_id').value = r.id;
    document.getElementById('role_name').value = r.role_name;
    document.getElementById('role_code').value = r.role_code;
    if (r.role_code === 'admin') {
        document.getElementById('role_code').readOnly = true;
    } else {
        document.getElementById('role_code').readOnly = false;
    }
    document.getElementById('role_description').value = r.description || '';

    const perms = (r.permissions || '').split(',').map(s => s.trim());
    document.querySelectorAll('.role-perm-checkbox').forEach(cb => {
        cb.checked = perms.includes('all') || perms.includes(cb.value);
    });

    document.getElementById('btnCancelRole').style.display = 'inline-block';
}
</script>

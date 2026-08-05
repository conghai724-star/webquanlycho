<!-- Giao diện Quản Lý Vai Trò Chợ -->
<div style="margin-bottom: 20px;">
    <a href="<?php echo BASE_URL; ?>system/users" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại tài khoản nhân viên
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px; background-color: rgba(234, 67, 53, 0.1); color: #EA4335; border: 1px solid rgba(234, 67, 53, 0.2); border-radius: 4px;">
        <i class="fa-solid fa-circle-exclamation" style="margin-right: 6px;"></i>
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    
    <!-- CỘT 1: DANH SÁCH VAI TRÒ -->
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <div class="card-title" style="font-size: 16px; font-weight: 600; margin: 0;">Danh sách vai trò và mẫu phân quyền</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 12px 16px; width: 60px;">ID</th>
                            <th style="padding: 12px 16px; width: 180px;">Tên Vai Trò</th>
                            <th style="padding: 12px 16px; width: 260px;">Quyền mặc định</th>
                            <th style="padding: 12px 16px;">Mô Tả Chi Tiết</th>
                            <th style="padding: 12px 16px; width: 100px; text-align: center;">Trạng thái</th>
                            <th style="padding: 12px 16px; width: 80px; text-align: center;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $modulesMapping = [
                            'trader' => 'Tiểu thương',
                            'stall' => 'Sạp chợ',
                            'contract' => 'Hợp đồng',
                            'finance' => 'Tài chính',
                            'foodsafety' => 'An toàn TP'
                        ];
                        
                        if (!empty($roles)): 
                            foreach ($roles as $role): 
                                $isSystem = in_array((int)$role['role_id'], [2, 5, 6, 7]);
                        ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 14px 16px; color: var(--text-muted);">
                                        <?php echo $role['role_id']; ?>
                                    </td>
                                    <td style="padding: 14px 16px; font-weight: 600; color: var(--text-heading);">
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </td>
                                    <td style="padding: 14px 16px;">
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                            <?php 
                                            $perms = array_filter(explode(',', $role['role_permissions'] ?? ''));
                                            if (!empty($perms)):
                                                foreach ($perms as $pCode):
                                                    $friendlyName = $modulesMapping[$pCode] ?? $pCode;
                                            ?>
                                                    <span class="chip" style="background-color: rgba(66, 133, 244, 0.08); color: #4285F4; border: 1px solid rgba(66, 133, 244, 0.15); font-size: 11px; padding: 2px 6px;">
                                                        <?php echo htmlspecialchars($friendlyName); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span style="color: var(--text-muted); font-size: 11px;">Không có quyền</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td style="padding: 14px 16px; color: var(--text-muted); font-size: 12px; line-height: 1.4;">
                                        <?php echo htmlspecialchars($role['role_description'] ?: 'Không có mô tả'); ?>
                                    </td>
                                    <td style="padding: 14px 16px; text-align: center; vertical-align: middle;">
                                        <?php
                                        $isActive = ($role['status_code'] ?? 'active') === 'active';
                                        $statusLabel = $isActive ? 'Hoạt động' : 'Ngừng';
                                        $statusBg = $isActive ? 'rgba(52,168,83,0.1)' : 'rgba(156,163,175,0.15)';
                                        $statusColor = $isActive ? '#34A853' : '#9ca3af';
                                        ?>
                                        <button onclick="toggleRoleStatus(<?php echo $role['role_id']; ?>, '<?php echo $isActive ? 'inactive' : 'active'; ?>')" 
                                                style="background: <?php echo $statusBg; ?>; color: <?php echo $statusColor; ?>; border: 1px solid <?php echo $statusColor; ?>33; padding: 2px 10px; border-radius: 10px; font-size: 11px; cursor: pointer; white-space: nowrap;" 
                                                title="Nhấn để chuyển trạng thái">
                                            <?php echo $statusLabel; ?>
                                        </button>
                                    </td>
                                    <td style="padding: 14px 16px; text-align: center; vertical-align: middle;">
                                        <?php if ($isSystem): ?>
                                            <span style="font-size: 11px; color: var(--text-muted); font-style: italic; background-color: #f1f2f6; padding: 2px 6px; border-radius: 4px;">Hệ thống</span>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL; ?>adminmaster/role_delete/<?php echo $role['role_id']; ?>" 
                                               class="btn btn-sm btn-ghost" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa vai trò này?');" 
                                               style="color: #EA4335; padding: 4px 8px; font-size: 11px; text-decoration: none; display: inline-block;" 
                                               title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i> Xóa
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">Chưa có vai trò nào trong hệ thống.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CỘT 2: THÊM VAI TRÒ MỚI -->
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <div class="card-title" style="font-size: 16px; font-weight: 600; margin: 0;">Thêm Vai Trò Mới</div>
        </div>
        <div class="card-body" style="padding: 20px;">
            <form id="form-add-role" action="<?php echo BASE_URL; ?>adminmaster/role_save" method="POST" data-native-submit="true">
                <?php csrf_field(); ?>
                
                <!-- Tên vai trò -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" for="role_name" style="font-weight: 500; display: block; margin-bottom: 6px;">Tên vai trò <span style="color: var(--red)">*</span></label>
                    <input type="text" id="role_name" name="role_name" class="form-control" placeholder="Ví dụ: Giám sát viên" 
                           value="<?php echo htmlspecialchars($post_data['role_name'] ?? ''); ?>" required>
                </div>

                <!-- Quyền mặc định (Checklist Phân hệ) -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 500; display: block; margin-bottom: 8px;">Gán quyền mặc định (Mẫu phân hệ)</label>
                    <div style="display: flex; flex-direction: column; gap: 8px; padding: 12px; background-color: var(--bg-surface-light, #f8f9fa); border: 1px solid var(--border-color); border-radius: 6px;">
                        <?php foreach ($modulesMapping as $code => $name): ?>
                            <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: normal; cursor: pointer; color: var(--text-color); margin: 0;">
                                <input type="checkbox" name="role_modules[]" value="<?php echo $code; ?>" style="width: 15px; height: 15px; accent-color: var(--primary-color);">
                                <?php echo htmlspecialchars($name); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Mô tả -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" for="role_description" style="font-weight: 500; display: block; margin-bottom: 6px;">Mô tả chi tiết</label>
                    <textarea id="role_description" name="role_description" class="form-control" rows="4" placeholder="Nhập các trách nhiệm của vai trò này..." style="resize: vertical; min-height: 80px;"><?php echo htmlspecialchars($post_data['role_description'] ?? ''); ?></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 38px;">
                        <i class="fa-solid fa-plus"></i> Lưu Vai Trò
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function toggleRoleStatus(roleId, newStatus) {
    $.ajax({
        type: 'POST',
        url: '<?php echo BASE_URL; ?>adminmaster/toggleRoleStatus',
        data: { role_id: roleId, status: newStatus, csrf_token: '<?php echo $_SESSION["csrf_token"] ?? ""; ?>' },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(data) {
            if (data.status === 200) { window.location.reload(); }
            else {
                var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                Swal.fire({ icon: 'error', title: 'Lỗi', text: data.error || 'Thao tác thất bại.', background: isDark ? '#1a2332' : '#fff', color: isDark ? '#fff' : '#0f1623' });
            }
        }
    });
}
</script>

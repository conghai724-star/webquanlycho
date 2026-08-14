<div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-primary" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-users"></i> Danh sách Tài khoản Web
        </a>
        <a href="<?php echo BASE_URL; ?>admin/permissions" class="btn btn-outline" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-user-shield"></i> Ma trận Phân quyền
        </a>
        <a href="<?php echo BASE_URL; ?>admin/roles" class="btn btn-outline" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-users-gear"></i> Quản lý Vai trò & Mẫu quyền
        </a>
    </div>
    
    <div>
        <a href="<?php echo BASE_URL; ?>admin/user_add" class="btn btn-primary" style="font-size: 13px; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: white;">
            <i class="fa-solid fa-user-plus"></i> Tạo tài khoản mới
        </a>
    </div>
</div>


<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<!-- DANH SÁCH TÀI KHOẢN WEB -->
<div id="user-accounts" class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div class="card-title" style="font-size: 15px; font-weight: 600; margin: 0;">Danh sách tài khoản (<?php echo count($users ?? []); ?>)</div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 12px 16px; width: 140px;">Tên đăng nhập</th>
                        <th style="padding: 12px 16px;">Họ và tên</th>
                        <th style="padding: 12px 16px;">Email / Điện thoại</th>
                        <th style="padding: 12px 16px; width: 180px;">Vai trò Web</th>
                        <th style="padding: 12px 16px; width: 130px;">Trạng thái</th>
                        <th style="padding: 12px 16px; text-align: right; width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td class="cell-mono" style="padding: 14px 16px; font-weight: 600; color: var(--text-heading);">
                                    <?php echo htmlspecialchars($u['username']); ?>
                                </td>
                                <td style="padding: 14px 16px; font-weight: 600; color: var(--text-heading);">
                                    <?php echo htmlspecialchars($u['fullname']); ?>
                                </td>
                                <td style="padding: 14px 16px; color: var(--text-muted);">
                                    <div><?php echo htmlspecialchars($u['email'] ?: 'Chưa cập nhật'); ?></div>
                                    <small style="font-size: 11px;"><?php echo htmlspecialchars($u['phone'] ?: ''); ?></small>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="chip" style="background-color: rgba(234, 67, 53, 0.1); color: #EA4335; border: 1px solid rgba(234, 67, 53, 0.2); font-weight: 600;">Quản trị viên Web</span>
                                    <?php else: ?>
                                        <span class="chip" style="background-color: rgba(66, 133, 244, 0.1); color: #4285F4; border: 1px solid rgba(66, 133, 244, 0.2); font-weight: 600;">Biên tập viên Web</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <?php if (($u['status'] ?? 1) == 1): ?>
                                        <span class="status status-green" style="background: rgba(46, 125, 50, 0.1); color: #2e7d32; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="status status-red" style="background: rgba(198, 40, 40, 0.1); color: #c62828; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Bị khóa</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 14px 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                        <a href="<?php echo BASE_URL; ?>admin/user_edit/<?php echo $u['id']; ?>" class="btn btn-outline btn-sm" style="padding: 4px 8px; font-size: 11px; text-decoration: none; color: inherit; display: inline-flex; align-items: center; justify-content: center;" title="Chỉnh sửa">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <?php if ($u['id'] != session::getWebUser('id')): ?>
                                            <button type="button" onclick="confirmSoftDelete('<?php echo BASE_URL; ?>admin/user_delete/<?php echo $u['id']; ?>', '<?php echo htmlspecialchars(addslashes($u['fullname'] . ' (' . $u['username'] . ')')); ?>', 'tài khoản')" class="btn btn-outline btn-sm" style="padding: 4px 8px; font-size: 11px; color: #d32f2f; display: inline-flex; align-items: center; justify-content: center; border-color: #fca5a5;" title="Xóa tài khoản">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">Không có dữ liệu tài khoản Web.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

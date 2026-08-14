<?php
$moduleList = [
    'dashboard'     => ['title' => 'TRANG CHỦ', 'sub' => '(DASHBOARD)'],
    'map_editor'    => ['title' => 'BIÊN TẬP BẢN ĐỒ', 'sub' => "('MAP_EDITOR')"],
    'map_tree'      => ['title' => 'SƠ ĐỒ CÂY', 'sub' => "('MAP_TREE')"],
    'banners'       => ['title' => 'NỘI DUNG WEB', 'sub' => "('BANNERS')"],
    'users'         => ['title' => 'TÀI KHOẢN WEB', 'sub' => "('USERS')"],
    'roles'         => ['title' => 'PHÂN QUYỀN', 'sub' => "('ROLES')"]
];
?>

<!-- Nạp FontAwesome & Thư viện SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* CSS Switch Toggle chuẩn đẹp như screenshot 2 */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 22px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #cbd5e1;
  -webkit-transition: .2s;
  transition: .2s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .2s;
  transition: .2s;
}

input:checked + .slider {
  background-color: #10b981;
}

input:focus + .slider {
  box-shadow: 0 0 1px #10b981;
}

input:checked + .slider:before {
  -webkit-transform: translateX(22px);
  -ms-transform: translateX(22px);
  transform: translateX(22px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-users"></i> Danh sách Tài khoản Web
        </a>
        <a href="<?php echo BASE_URL; ?>admin/permissions" class="btn btn-primary" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-user-shield"></i> Ma trận Phân quyền
        </a>
        <a href="<?php echo BASE_URL; ?>admin/roles" class="btn btn-outline" style="font-size: 13px; padding: 7px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-users-gear"></i> Quản lý Vai trò & Mẫu quyền
        </a>
    </div>
</div>

<div class="card">

    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-user-shield" style="font-size: 18px; color: var(--primary);"></i>
            <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--text-heading);">Danh sách Phân quyền Phân hệ cho Nhân viên</h4>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <input type="text" id="perm-search-input" class="form-control" placeholder="Tìm kiếm nhân viên..." style="width: 220px; font-size: 13px; height: 36px;">
            <button type="button" class="btn btn-outline" style="height: 36px; padding: 0 16px; font-size: 13px;">Tìm kiếm</button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="text-align: center; background-color: #f8fafc; border-bottom: 1px solid var(--border-color); color: #64748b; font-weight: 700; text-transform: uppercase;">
                        <th style="padding: 14px 16px; text-align: left; min-width: 180px;">NHÂN VIÊN</th>
                        <th style="padding: 14px 16px; width: 110px;">VAI TRÒ</th>
                        <th style="padding: 14px 16px; width: 170px;">MẪU GÁN NHANH</th>
                        <?php foreach ($moduleList as $code => $info): ?>
                            <th style="padding: 12px 10px; width: 110px; line-height: 1.3;">
                                <div><?php echo $info['title']; ?></div>
                                <div style="font-size: 10px; font-weight: 500; color: #94a3b8;"><?php echo $info['sub']; ?></div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php 
                        $roleMap = [];
                        if (!empty($webRoles)) {
                            foreach ($webRoles as $r) {
                                $roleMap[$r['role_code']] = $r['role_name'];
                            }
                        }
                        ?>
                        <?php foreach ($users as $u): ?>
                            <?php 
                            $userPerms = array_filter(array_map('trim', explode(',', $u['permissions'] ?? '')));
                            $userRoleCode = $u['role'] ?? 'editor';
                            $userRoleName = $roleMap[$userRoleCode] ?? ($userRoleCode === 'admin' ? 'Quản trị viên' : 'Biên tập viên');
                            $hasAll = in_array('all', $userPerms);
                            ?>
                            <tr style="border-bottom: 1px solid var(--border-color); text-align: center;" class="user-perm-row" data-search="<?php echo htmlspecialchars(mb_strtolower(($u['fullname'] ?? '') . ' ' . ($u['username'] ?? '') . ' ' . ($u['email'] ?? ''))); ?>">
                                <td style="padding: 14px 16px; text-align: left;">
                                    <div style="font-weight: 700; color: var(--text-heading); font-size: 13px;"><?php echo htmlspecialchars($u['fullname']); ?></div>
                                    <div style="color: #64748b; font-size: 11px;">@<?php echo htmlspecialchars($u['username']); ?></div>
                                    <small style="color: #94a3b8; font-size: 11px;"><?php echo htmlspecialchars($u['email'] ?: 'Chưa cập nhật email'); ?></small>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <span id="role_chip_<?php echo $u['id']; ?>" class="chip" style="<?php echo ($userRoleCode === 'admin') ? 'background: rgba(15, 118, 110, 0.1); color: var(--primary); font-weight: 700; border: 1px solid rgba(15, 118, 110, 0.2);' : 'background: rgba(59, 130, 246, 0.1); color: #1d4ed8; font-weight: 600; border: 1px solid rgba(59, 130, 246, 0.2);'; ?>">
                                        <?php echo htmlspecialchars($userRoleName); ?>
                                    </span>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <select id="role_select_<?php echo $u['id']; ?>" class="form-control" style="font-size: 12px; padding: 4px 8px; height: 32px; border-radius: 6px;" onchange="applyRoleTemplate(<?php echo $u['id']; ?>, this.value)">
                                        <option value="">-- Mẫu gán nhanh --</option>
                                        <?php if (!empty($webRoles)): foreach ($webRoles as $r): ?>
                                            <option value="<?php echo htmlspecialchars($r['role_code']); ?>" <?php echo ($userRoleCode === $r['role_code']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($r['role_name']); ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </td>
                                <?php foreach ($moduleList as $code => $info): ?>
                                    <?php $hasAccess = $hasAll || in_array($code, $userPerms); ?>
                                    <td style="padding: 14px 10px;">
                                        <label class="switch">
                                            <input type="checkbox" 
                                                   id="perm_<?php echo $u['id']; ?>_<?php echo $code; ?>" 
                                                   <?php echo $hasAccess ? 'checked' : ''; ?> 
                                                   onchange="toggleUserModulePerm(<?php echo $u['id']; ?>, '<?php echo $code; ?>', this.checked)">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" style="padding: 30px; text-align: center; color: var(--text-muted);">Chưa có tài khoản Web nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Lọc nhân viên theo tìm kiếm
document.getElementById('perm-search-input').addEventListener('input', function() {
    var q = this.value.toLowerCase().trim();
    document.querySelectorAll('.user-perm-row').forEach(function(row) {
        var str = row.getAttribute('data-search') || '';
        if (!q || str.includes(q)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Bật/tắt 1 module permission cho nhân viên bằng AJAX
function toggleUserModulePerm(userId, moduleCode, isChecked) {
    $.ajax({
        type: 'POST',
        url: '<?php echo BASE_URL; ?>admin/update_user_permission',
        data: JSON.stringify({
            user_id: userId,
            module: moduleCode,
            status: isChecked ? 1 : 0
        }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(res) {
            if (res.status === 200) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Đã cập nhật quyền thành công!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: res.message || 'Cập nhật thất bại',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        },
        error: function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Lỗi kết nối máy chủ',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
}

// Áp dụng Mẫu gán nhanh (Role Template) cho nhân viên
function applyRoleTemplate(userId, roleCode) {
    if (!roleCode) return;
    
    $.ajax({
        type: 'POST',
        url: '<?php echo BASE_URL; ?>admin/apply_role_template',
        data: JSON.stringify({
            user_id: userId,
            role_code: roleCode
        }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(res) {
            if (res.status === 200 && res.permissions) {
                var perms = res.permissions;
                var isAdmin = (roleCode === 'admin' || perms.includes('all'));

                // Cập nhật thẻ hiển thị vai trò
                var chip = document.getElementById('role_chip_' + userId);
                if (chip && res.role_name) {
                    chip.innerText = res.role_name;
                    if (isAdmin) {
                        chip.style.background = 'rgba(15, 118, 110, 0.1)';
                        chip.style.color = 'var(--primary)';
                        chip.style.borderColor = 'rgba(15, 118, 110, 0.2)';
                    } else {
                        chip.style.background = 'rgba(59, 130, 246, 0.1)';
                        chip.style.color = '#1d4ed8';
                        chip.style.borderColor = 'rgba(59, 130, 246, 0.2)';
                    }
                }

                // Cập nhật lại các toggle switches trên dòng tương ứng
                <?php echo json_encode(array_keys($moduleList)); ?>.forEach(function(code) {
                    var el = document.getElementById('perm_' + userId + '_' + code);
                    if (el) {
                        el.checked = (perms.includes('all') || perms.includes(code));
                        el.disabled = false;
                    }
                });

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: res.message || 'Đã áp dụng mẫu vai trò thành công!',
                    showConfirmButton: false,
                    timer: 2500
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: res.message || 'Áp dụng thất bại',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        },
        error: function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Lỗi kết nối máy chủ',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
}
</script>

<?php
// ponytail: Cấu hình phân quyền động được tải trực tiếp từ DB (bảng market_roles)
$permissionConfig = [
    'modules' => [
        'dashboard'  => ['name' => 'Trang chủ (Dashboard)', 'icon' => 'fa-chart-pie'],
        'profile'    => ['name' => 'Thông tin cá nhân', 'icon' => 'fa-user-gear'],
        'stall'      => ['name' => 'Quản lý Sạp chợ', 'icon' => 'fa-store'],
        'map_tree'   => ['name' => 'Sơ đồ Cây sạp chợ', 'icon' => 'fa-sitemap'],
        'trader'     => ['name' => 'Quản lý Tiểu thương', 'icon' => 'fa-users'],
        'contract'   => ['name' => 'Hợp đồng Thuê sạp', 'icon' => 'fa-file-signature'],
        'utilities'  => ['name' => 'Chỉ số Điện & Nước', 'icon' => 'fa-bolt-lightning'],
        'finance'    => ['name' => 'Thu - Chi tài chính', 'icon' => 'fa-wallet'],
        'foodsafety' => ['name' => 'An toàn thực phẩm', 'icon' => 'fa-shield-halved'],
        'logs'       => ['name' => 'Nhật ký hoạt động', 'icon' => 'fa-clock-rotate-left'],
        'category'   => ['name' => 'Quản lý Danh mục', 'icon' => 'fa-list']
    ],
    'roles' => []
];

if (!empty($marketRoles)) {
    foreach ($marketRoles as $mr) {
        $rolePerms = [];
        $pCodes = array_filter(explode(',', $mr['role_permissions'] ?? ''));
        foreach ($pCodes as $code) {
            $rolePerms[$code] = true;
        }
        $permissionConfig['roles'][$mr['role_id']] = [
            'name' => $mr['role_name'],
            'permissions' => $rolePerms
        ];
    }
}
?>
<!-- Giao diện Phân Quyền Phân Hệ Nhân Viên -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    .permissions-container {
        font-family: 'Outfit', sans-serif;
        color: #2c3e50;
        background-color: #f4f7f6;
        padding: 4px 0 20px 0;
    }

    .card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.02);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: 1px solid #f1f2f6;
        padding: 20px 24px;
        background-color: #ffffff;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 24px;
    }

    /* Table styling */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .perm-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .perm-table th {
        background-color: #f8f9fa;
        color: #7f8c8d;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px 20px;
        border-bottom: 2px solid #e2e8f0;
    }

    .perm-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f2f6;
        font-size: 14.5px;
        vertical-align: middle;
    }

    .perm-table tr:hover {
        background-color: #fafbfb;
    }

    .staff-info {
        display: flex;
        flex-direction: column;
    }

    .staff-info .name {
        font-weight: 600;
        color: #2c3e50;
    }

    .staff-info .email {
        font-size: 12px;
        color: #95a5a6;
        margin-top: 2px;
    }

    .market-badge {
        display: inline-block;
        padding: 6px 12px;
        background-color: rgba(58, 123, 213, 0.08);
        color: #3a7bd5;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12.5px;
    }

    /* Custom Checkbox Toggle Switch style */
    .switch-label {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        position: relative;
    }

    .switch-input {
        display: none;
    }

    .switch-toggle {
        width: 44px;
        height: 22px;
        background-color: #cbd5e1;
        border-radius: 11px;
        position: relative;
        transition: background-color 0.2s ease;
        margin-right: 8px;
    }

    .switch-toggle::before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: white;
        top: 2px;
        left: 2px;
        transition: transform 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .switch-input:checked + .switch-toggle {
        background-color: #2ecc71;
    }

    .switch-input:checked + .switch-toggle::before {
        transform: translateX(22px);
    }

    .switch-text {
        font-size: 13.5px;
        color: #2c3e50;
    }
</style>

<div class="permissions-container">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <h2 class="card-title" style="margin: 0; font-size: 16px; font-weight: 600;">
                <i class="fa-solid fa-user-shield" style="color: #3a7bd5;"></i>
                Danh sách Phân quyền Phân hệ cho Nhân viên
            </h2>
            <form method="GET" action="<?php echo BASE_URL; ?>system/permissions" style="display: flex; gap: 8px; flex-wrap: wrap;" data-native-submit="true">
                <select name="market_id" style="padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; min-width: 180px; background-color: var(--bg-surface, #ffffff); color: var(--text-color);">
                    <option value="">-- Tất cả chợ --</option>
                    <?php foreach ($filterMarkets as $m): ?>
                        <option value="<?php echo $m['market_id']; ?>" <?php echo ($selectedMarket == $m['market_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['market_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="q" placeholder="Tìm kiếm nhân viên..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; width: 220px; background-color: var(--bg-surface, #ffffff); color: var(--text-color);">
                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; height: 34px; display: inline-flex; align-items: center;">Tìm kiếm</button>
            </form>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="padding: 12px 16px; background-color: rgba(234, 67, 53, 0.1); color: #EA4335; border: 1px solid rgba(234, 67, 53, 0.2); border-radius: 6px;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php elseif (empty($staffList)): ?>
                <div style="text-align: center; padding: 40px; color: #7f8c8d;">
                    <i class="fa-regular fa-folder-open" style="font-size: 48px; margin-bottom: 12px; color: #bdc3c7;"></i>
                    <p style="font-size: 16px; margin: 0;">Không tìm thấy tài khoản nhân viên vận hành (Staff) nào thuộc các chợ bạn quản lý.</p>
                    <a href="<?php echo BASE_URL; ?>system/user_add" class="btn btn-primary" style="margin-top: 15px; text-decoration: none; color: white; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-user-plus"></i> Tạo nhân viên mới
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="perm-table">
                        <thead>
                            <tr>
                                <th>Nhân viên</th>
                                <th>Chợ áp dụng</th>
                                <th style="width: 170px;">Mẫu gán nhanh</th>
                                <?php foreach ($permissionConfig['modules'] as $code => $mod): ?>
                                    <th><?php echo htmlspecialchars($mod['name']); ?> (`<?php echo htmlspecialchars($code); ?>`)</th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($staffList as $staff): 
                                foreach ($managedMarkets as $market):
                                    // Kiểm tra xem nhân viên này có liên kết với chợ này không
                                    $db = database::getInstance();
                                    $isLinked = $db->selectOne("
                                        SELECT 1 FROM user_markets 
                                        WHERE user_market_user_id = :u_id AND user_market_market_id = :m_id
                                    ", ['u_id' => $staff['id'], 'm_id' => $market['market_id']]);

                                    if (!$isLinked) continue;
                            ?>
                                <tr>
                                    <td>
                                        <div class="staff-info">
                                            <span class="name"><?php echo htmlspecialchars($staff['fullname']); ?></span>
                                            <span class="email">@<?php echo htmlspecialchars($staff['username']); ?> | <?php echo htmlspecialchars($staff['email']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="market-badge">
                                            <i class="fa-solid fa-store" style="margin-right: 4px;"></i>
                                            <?php echo htmlspecialchars($market['market_name']); ?>
                                        </span>
                                    </td>
                                    <!-- Mẫu gán nhanh -->
                                    <td>
                                        <select class="form-control quick-role-select" style="padding: 4px 8px; font-size: 12.5px; height: 30px; border-radius: 6px; width: 160px; cursor: pointer; border: 1px solid var(--border-color);">
                                            <option value="">-- Chọn vai trò --</option>
                                            <?php foreach ($permissionConfig['roles'] as $roleCode => $tpl): ?>
                                                <option value="<?php echo htmlspecialchars($roleCode); ?>"><?php echo htmlspecialchars($tpl['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <!-- Dynamic Module Switch Checkboxes -->
                                    <?php foreach ($permissionConfig['modules'] as $code => $mod): ?>
                                    <td>
                                        <label class="switch-label">
                                            <input type="checkbox" class="switch-input perm-toggle" 
                                                   data-user="<?php echo $staff['id']; ?>" 
                                                   data-market="<?php echo $market['market_id']; ?>" 
                                                   data-module="<?php echo htmlspecialchars($code); ?>"
                                                   <?php echo isset($permissions[$staff['id']][$market['market_id']][$code]) ? 'checked' : ''; ?>>
                                            <span class="switch-toggle"></span>
                                        </label>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php 
                                endforeach;
                            endforeach; 
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(function() {
        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        var swalBg = isDark ? '#1a2332' : '#ffffff';
        var swalColor = isDark ? '#ffffff' : '#0f1623';

        // Hàm tính toán và cập nhật lại giá trị dropdown dựa trên trạng thái thực tế của các checkbox trong dòng
        function updateDropdownFromCheckboxes(row) {
            var templates = <?php echo json_encode(array_combine(array_keys($permissionConfig['roles']), array_column($permissionConfig['roles'], 'permissions')), JSON_UNESCAPED_UNICODE); ?>;
            var currentPerms = {};
            
            row.find('.perm-toggle').each(function() {
                var cb = $(this);
                if (cb.is(':checked')) {
                    currentPerms[cb.data('module')] = true;
                }
            });

            var matchedRole = '';
            $.each(templates, function(roleCode, rolePerms) {
                var isMatch = true;
                var allModules = <?php echo json_encode(array_keys($permissionConfig['modules'])); ?>;
                
                $.each(allModules, function(i, mod) {
                    var expected = !!rolePerms[mod];
                    var actual = !!currentPerms[mod];
                    if (expected !== actual) {
                        isMatch = false;
                        return false; // break loop
                    }
                });
                
                if (isMatch) {
                    matchedRole = roleCode;
                    return false; // break loop
                }
            });

            row.find('.quick-role-select').val(matchedRole);
        }

        // Khởi tạo trạng thái ban đầu cho toàn bộ dropdown trên từng dòng
        $('.perm-table tbody tr').each(function() {
            updateDropdownFromCheckboxes($(this));
        });

        // Xử lý sự kiện khi thay đổi mẫu gán nhanh
        $('.quick-role-select').on('change', function() {
            var select = $(this);
            var role = select.val();
            if (!role) {
                // Nếu chọn rỗng, bỏ tích tất cả các quyền trên hàng
                var row = select.closest('tr');
                row.find('.perm-toggle').each(function() {
                    var cb = $(this);
                    if (cb.is(':checked')) {
                        cb.prop('checked', false).trigger('change');
                    }
                });
                return;
            }

            var row = select.closest('tr');
            var templates = <?php echo json_encode(array_combine(array_keys($permissionConfig['roles']), array_column($permissionConfig['roles'], 'permissions')), JSON_UNESCAPED_UNICODE); ?>;
            var targetPermissions = templates[role] || {};

            row.find('.perm-toggle').each(function() {
                var cb = $(this);
                var module = cb.data('module');
                var expected = !!targetPermissions[module];

                if (cb.is(':checked') !== expected) {
                    cb.prop('checked', expected).trigger('change');
                }
            });
        });

        $('.perm-toggle').on('change', function() {
            var input = $(this);
            var userId = input.data('user');
            var marketId = input.data('market');
            var module = input.data('module');
            var checked = input.is(':checked') ? 1 : 0;
            var row = input.closest('tr');

            // Vô hiệu hóa tạm thời để tránh bấm loạn xạ khi đang gọi AJAX
            input.prop('disabled', true);

             $.ajax({
                url: '<?php echo BASE_URL; ?>system/save_permissions',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo security::getToken(); ?>'
                },
                data: {
                    user_id: userId,
                    market_id: marketId,
                    module: module,
                    checked: checked
                },
                dataType: 'json',
                success: function(res) {
                    input.prop('disabled', false);
                    if (!res.success) {
                        // Trả lại trạng thái cũ nếu lỗi
                        input.prop('checked', !checked);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi phân quyền',
                            text: res.message || 'Thao tác thất bại.',
                            background: swalBg,
                            color: swalColor
                        });
                        updateDropdownFromCheckboxes(row);
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: res.message || 'Cập nhật phân quyền thành công!',
                            timer: 1500,
                            showConfirmButton: false,
                            background: swalBg,
                            color: swalColor
                        });
                        updateDropdownFromCheckboxes(row);
                    }
                },
                error: function(xhr) {
                    input.prop('disabled', false);
                    input.prop('checked', !checked);
                    var msg = 'Không thể kết nối với máy chủ.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi kết nối',
                        text: msg,
                        background: swalBg,
                        color: swalColor
                    });
                    updateDropdownFromCheckboxes(row);
                }
            });
        });
    });
</script>

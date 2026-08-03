<!-- Phân loại Tab & Nút thêm mới -->
<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <!-- Nút chuyển đổi Tab -->
    <div class="segmented" role="radiogroup" style="max-width: 380px;">
        <label><input type="radio" name="user-mode" value="accounts" checked><span>Tài khoản & Phân quyền</span></label>
    </div>
    
    <a href="<?php echo BASE_URL; ?>system/user_add" class="btn btn-primary" style="height: 36px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: white;">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 14px; height: 14px;"><path d="M8 2v12M2 8h12"/></svg>
        Tạo tài khoản mới
    </a>
</div>

<!-- TAB 1: DANH SÁCH TÀI KHOẢN -->
<div id="user-accounts" class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600; margin: 0;">Danh sách tài khoản nhân viên BQL (<?php echo $totalRecords; ?>)</div>
        <form method="GET" action="<?php echo BASE_URL; ?>system/users" style="display: flex; gap: 8px; flex-wrap: wrap;" data-native-submit="true">
            <select name="market_id" style="padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; min-width: 180px; background-color: var(--bg-surface, #ffffff); color: var(--text-color);">
                <option value="">-- Tất cả chợ --</option>
                <?php foreach ($marketsList as $m): ?>
                    <option value="<?php echo $m['id']; ?>" <?php echo ($selectedMarket == $m['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="q" placeholder="Tìm theo tên, họ tên, email..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; width: 220px; background-color: var(--bg-surface, #ffffff); color: var(--text-color);">
            <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; height: 34px; display: inline-flex; align-items: center;">Tìm kiếm</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 12px 16px; width: 150px;">Tên đăng nhập</th>
                        <th style="padding: 12px 16px;">Họ tên nhân viên</th>
                        <th style="padding: 12px 16px;">Địa chỉ Email</th>
                        <th style="padding: 12px 16px; width: 180px;">Vai trò hệ thống</th>
                        <th style="padding: 12px 16px; width: 140px;">Trạng thái</th>
                        <th style="padding: 12px 16px; text-align: right; width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td class="cell-mono" style="padding: 14px 16px; font-weight: 600; color: var(--text-heading);">
                                    <?php echo htmlspecialchars($user['username']); ?>
                                </td>
                                <td style="padding: 14px 16px; font-weight: 600; color: var(--text-heading);">
                                    <?php echo htmlspecialchars($user['fullname']); ?>
                                </td>
                                <td style="padding: 14px 16px; color: var(--text-muted);">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <?php 
                                    $actorCode = $user['actor_code'] ?? 'admin';
                                    $actorName = $user['actor_name'] ?? 'Nhân viên';
                                    if ($actorCode === 'super_market'): 
                                    ?>
                                        <span class="chip" style="background-color: rgba(234, 67, 53, 0.1); color: #EA4335; border: 1px solid rgba(234, 67, 53, 0.2); font-weight: 600;"><?php echo htmlspecialchars($actorName); ?></span>
                                    <?php elseif ($actorCode === 'admin_market'): ?>
                                        <span class="chip" style="background-color: rgba(155, 89, 182, 0.1); color: #9b59b6; border: 1px solid rgba(155, 89, 182, 0.2); font-weight: 600;"><?php echo htmlspecialchars($actorName); ?></span>
                                    <?php else: ?>
                                        <span class="chip" style="background-color: rgba(66, 133, 244, 0.1); color: #4285F4; border: 1px solid rgba(66, 133, 244, 0.2); font-weight: 600;"><?php echo htmlspecialchars($actorName); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 14px 16px;" id="status-col-<?php echo $user['user_id']; ?>">
                                    <?php if ($user['is_active'] == 1): ?>
                                        <span class="status status-green">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="status status-red">Bị khóa</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 14px 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                        <!-- Sửa tài khoản -->
                                        <a href="<?php echo BASE_URL; ?>system/user_edit/<?php echo $user['user_id']; ?>" class="btn btn-outline btn-sm" style="padding: 4px 8px; font-size: 11px; text-decoration: none; color: inherit; display: inline-flex; align-items: center; justify-content: center;" title="Sửa tài khoản">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <!-- Khóa/Mở khóa tài khoản (Mục F.8) -->
                                        <button class="btn btn-outline btn-sm" onclick="App.user.toggleLockUser(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars($user['fullname']); ?>')" style="padding: 4px 8px; font-size: 11px;" title="Khóa / Mở khóa tài khoản">
                                            <i class="fa-solid fa-user-shield"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">Không có dữ liệu tài khoản.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Phân trang -->
        <div style="padding: 10px 20px; border-top: 1px solid var(--border-color);">
            <?php
            $baseUrl = BASE_URL . 'system/users';
            $queryParams = [];
            if (!empty($search)) $queryParams['q'] = $search;
            if (!empty($selectedMarket)) $queryParams['market_id'] = $selectedMarket;
            echo general::getPaginationHtml($page, $totalPages, $baseUrl, $queryParams);
            ?>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const swalBg = isDark ? '#1a2332' : '#ffffff';
    const swalColor = isDark ? '#ffffff' : '#0f1623';

    window.App = window.App || {};
    window.App.user = {
    // Khóa / Mở khóa tài khoản
        toggleLockUser: function(id, name) {
            Swal.fire({
                title: 'Khóa/Mở khóa tài khoản?',
                text: "Xác nhận thay đổi trạng thái hoạt động của tài khoản '" + name + "'?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
                confirmButtonColor: '#EA4335',
                cancelButtonColor: '#a0aec0',
                background: swalBg,
                color: swalColor
            }).then((result) => {
                if (result.isConfirmed) {
                    // App.utils.ajaxRequest('POST', '<?php echo BASE_URL; ?>system/user_toggle_status/' + id, {}, (res) => { ... });
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo BASE_URL; ?>system/user_toggle_status/' + id,
                        data: JSON.stringify({}),
                        contentType: 'application/json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '<?php echo security::getToken(); ?>'
                        },
                        dataType: 'json',
                        success: function(res) {
                            if (res.success) {
                                const statusCol = document.getElementById('status-col-' + id);
                                if (res.new_status === 1) {
                                    statusCol.innerHTML = '<span class="status status-green">Hoạt động</span>';
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Đã kích hoạt lại tài khoản!',
                                        timer: 1500,
                                        showConfirmButton: false,
                                        background: swalBg,
                                        color: swalColor
                                    });
                                } else {
                                    statusCol.innerHTML = '<span class="status status-red">Bị khóa</span>';
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Đã khóa tài khoản!',
                                        timer: 1500,
                                        showConfirmButton: false,
                                        background: swalBg,
                                        color: swalColor
                                    });
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thao tác thất bại!',
                                    text: res.message || 'Có lỗi xảy ra.',
                                    confirmButtonColor: '#EA4335',
                                    background: swalBg,
                                    color: swalColor
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Thao tác thất bại!',
                                text: 'Không thể kết nối đến máy chủ.',
                                confirmButtonColor: '#EA4335',
                                background: swalBg,
                                color: swalColor
                            });
                        }
                    });
                }
            });
        }
    };
});
</script>



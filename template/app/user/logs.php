<!-- Giao diện Nhật ký hoạt động hệ thống -->
<div style="font-size: 20px; font-weight: 700; color: var(--text-heading); margin-bottom: 20px;">Nhật Ký Hoạt Động</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600; margin: 0;">Lịch sử hoạt động của hệ thống (<?php echo $totalRecords; ?>)</div>
        
        <!-- Form Tìm kiếm & Lọc nhật ký -->
        <form method="GET" action="<?php echo BASE_URL; ?>system/logs" style="display: flex; gap: 8px; flex-wrap: wrap;" data-native-submit="true">
            <!-- Lọc theo loại hoạt động -->
            <select name="action_type" style="padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; min-width: 180px; background-color: var(--bg-surface, #ffffff); color: var(--text-color);">
                <option value="">-- Tất cả thao tác --</option>
                <?php foreach ($actionTypes as $typeKey => $typeName): ?>
                    <option value="<?php echo $typeKey; ?>" <?php echo ($selectedActionType === $typeKey) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($typeName); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <!-- Tìm kiếm từ khóa -->
            <input type="text" name="q" placeholder="Tìm theo tên, nội dung, IP..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; width: 250px; background-color: var(--bg-surface, #ffffff); color: var(--text-color);">
            
            <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; height: 34px; display: inline-flex; align-items: center;">Tìm kiếm</button>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-vcenter" style="margin: 0; font-size: 13.5px; border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color); background-color: var(--bg-surface-secondary, #f8f9fa);">
                        <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: var(--text-muted); width: 160px;">Thời gian</th>
                        <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: var(--text-muted); width: 220px;">Tài khoản</th>
                        <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: var(--text-muted); width: 180px;">Loại thao tác</th>
                        <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: var(--text-muted);">Nội dung chi tiết</th>
                        <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: var(--text-muted); width: 150px;">IP kết nối</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr style="border-bottom: 1px solid var(--border-color); vertical-align: top;">
                                <!-- Thời gian -->
                                <td style="padding: 12px 20px; color: var(--text-color); font-weight: 500;">
                                    <?php echo date('d-m-Y H:i:s', strtotime($log['log_created_at'])); ?>
                                </td>
                                
                                <!-- Người thực hiện -->
                                <td style="padding: 12px 20px;">
                                    <?php if (!empty($log['fullname'])): ?>
                                        <div style="font-weight: 600; color: var(--text-heading);"><?php echo htmlspecialchars($log['fullname']); ?></div>
                                        <div style="font-size: 11.5px; color: var(--text-muted);">
                                            @<?php echo htmlspecialchars($log['username']); ?> | 
                                            <span style="font-weight: 500;"><?php echo htmlspecialchars($log['actor_name'] ?: 'Nhân viên'); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div style="color: var(--text-muted); font-style: italic;">Hệ thống / Ẩn danh</div>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Loại thao tác (Badge) -->
                                <td style="padding: 12px 20px;">
                                    <?php
                                    $actionTypesMapping = [
                                        'login' => ['Đăng nhập', 'background-color: rgba(37, 99, 235, 0.08); color: #2563eb; border: 1px solid rgba(37, 99, 235, 0.15);'],
                                        'login_failed' => ['Đăng nhập lỗi', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);'],
                                        'create_user' => ['Thêm nhân viên', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'update_user' => ['Sửa nhân viên', 'background-color: rgba(217, 119, 6, 0.08); color: #d97706; border: 1px solid rgba(217, 119, 6, 0.15);'],
                                        'toggle_user_status' => ['Khóa/Mở khóa', 'background-color: rgba(8, 145, 178, 0.08); color: #0891b2; border: 1px solid rgba(8, 145, 178, 0.15);'],
                                        'update_permissions' => ['Phân quyền', 'background-color: rgba(107, 114, 128, 0.08); color: #4b5563; border: 1px solid rgba(107, 114, 128, 0.15);'],
                                        'update_profile' => ['Sửa hồ sơ', 'background-color: rgba(124, 58, 237, 0.08); color: #7c3aed; border: 1px solid rgba(124, 58, 237, 0.15);'],
                                        'create_trader' => ['Thêm tiểu thương', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'update_trader' => ['Sửa tiểu thương', 'background-color: rgba(217, 119, 6, 0.08); color: #d97706; border: 1px solid rgba(217, 119, 6, 0.15);'],
                                        'delete_trader' => ['Xóa tiểu thương', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);'],
                                        'create_stall' => ['Thêm sạp', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'update_stall' => ['Sửa sạp', 'background-color: rgba(217, 119, 6, 0.08); color: #d97706; border: 1px solid rgba(217, 119, 6, 0.15);'],
                                        'delete_stall' => ['Xóa sạp', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);'],
                                        'assign_stall' => ['Gán sạp nhanh', 'background-color: rgba(8, 145, 178, 0.08); color: #0891b2; border: 1px solid rgba(8, 145, 178, 0.15);'],
                                        'transfer_stall' => ['Chuyển nhượng', 'background-color: rgba(124, 58, 237, 0.08); color: #7c3aed; border: 1px solid rgba(124, 58, 237, 0.15);'],
                                        'create_contract' => ['Tạo HĐ', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'update_contract' => ['Sửa HĐ', 'background-color: rgba(217, 119, 6, 0.08); color: #d97706; border: 1px solid rgba(217, 119, 6, 0.15);'],
                                        'delete_contract' => ['Xóa mềm HĐ', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);'],
                                        'renew_contract' => ['Gia hạn HĐ', 'background-color: rgba(8, 145, 178, 0.08); color: #0891b2; border: 1px solid rgba(8, 145, 178, 0.15);'],
                                        'activate_contract' => ['Kích hoạt HĐ', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'liquidate_contract' => ['Thanh lý HĐ', 'background-color: rgba(107, 114, 128, 0.08); color: #4b5563; border: 1px solid rgba(107, 114, 128, 0.15);'],
                                        'terminate_contract' => ['Chấm dứt HĐ', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);'],
                                        'reactivate_contract' => ['Khôi phục HĐ', 'background-color: rgba(124, 58, 237, 0.08); color: #7c3aed; border: 1px solid rgba(124, 58, 237, 0.15);'],
                                        'create_appendix' => ['Tạo phụ lục', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'create_certificate' => ['Tạo ATTP', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'update_certificate' => ['Sửa ATTP', 'background-color: rgba(217, 119, 6, 0.08); color: #d97706; border: 1px solid rgba(217, 119, 6, 0.15);'],
                                        'delete_certificate' => ['Xóa ATTP', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);'],
                                        'save_map' => ['Sơ đồ chợ', 'background-color: rgba(124, 58, 237, 0.08); color: #7c3aed; border: 1px solid rgba(124, 58, 237, 0.15);'],
                                        'create_category' => ['Tạo danh mục', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'update_category' => ['Sửa danh mục', 'background-color: rgba(217, 119, 6, 0.08); color: #d97706; border: 1px solid rgba(217, 119, 6, 0.15);'],
                                        'delete_category' => ['Xóa danh mục', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);'],
                                        'create_market' => ['Tạo chợ', 'background-color: rgba(22, 163, 74, 0.08); color: #16a34a; border: 1px solid rgba(22, 163, 74, 0.15);'],
                                        'update_market' => ['Sửa chợ', 'background-color: rgba(217, 119, 6, 0.08); color: #d97706; border: 1px solid rgba(217, 119, 6, 0.15);'],
                                        'delete_market' => ['Xóa chợ', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);'],
                                        'save_voucher' => ['Phiếu thu chi', 'background-color: rgba(8, 145, 178, 0.08); color: #0891b2; border: 1px solid rgba(8, 145, 178, 0.15);'],
                                        'delete_voucher' => ['Xóa phiếu TC', 'background-color: rgba(220, 38, 38, 0.08); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.15);']
                                    ];
                                    
                                    $mapped = $actionTypesMapping[$log['log_action_type']] ?? [$log['log_action_type'], 'background-color: #f1f3f5; color: #495057; border: 1px solid #dee2e6;'];
                                    $actionName = $mapped[0];
                                    $badgeStyle = $mapped[1];
                                    ?>
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 100px; font-size: 11.5px; font-weight: 600; <?php echo $badgeStyle; ?>">
                                        <?php echo htmlspecialchars($actionName); ?>
                                    </span>
                                </td>
                                
                                <!-- Nội dung chi tiết -->
                                <td style="padding: 12px 20px; color: var(--text-color); font-weight: 400; line-height: 1.5; max-width: 400px; word-wrap: break-word;">
                                    <?php echo htmlspecialchars($log['log_action_description']); ?>
                                </td>
                                
                                <!-- IP Address & User Agent -->
                                <td style="padding: 12px 20px;">
                                    <div style="font-family: monospace; font-weight: 500; color: var(--text-heading);"><?php echo htmlspecialchars($log['log_ip_address'] ?: '127.0.0.1'); ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted); cursor: help;" title="<?php echo htmlspecialchars($log['log_user_agent']); ?>">
                                        <?php 
                                        $ua = $log['log_user_agent'] ?: '';
                                        if (preg_match('/Chrome/i', $ua)) echo 'Chrome / Windows';
                                        elseif (preg_match('/Safari/i', $ua)) echo 'Safari / macOS';
                                        elseif (preg_match('/Firefox/i', $ua)) echo 'Firefox / Linux';
                                        else echo 'Browser / Device';
                                        ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #7f8c8d;">
                                <i class="fa-regular fa-folder-open" style="font-size: 48px; margin-bottom: 12px; color: #bdc3c7;"></i>
                                <p style="font-size: 16px; margin: 0;">Không tìm thấy nhật ký hoạt động nào phù hợp.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Phân trang -->
        <div style="padding: 10px 20px; border-top: 1px solid var(--border-color);">
            <?php
            $baseUrl = BASE_URL . 'system/logs';
            $queryParams = [];
            if (!empty($search)) $queryParams['q'] = $search;
            if (!empty($selectedActionType)) $queryParams['action_type'] = $selectedActionType;
            echo general::getPaginationHtml($page, $totalPages, $baseUrl, $queryParams);
            ?>
        </div>
    </div>
</div>

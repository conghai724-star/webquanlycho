<!-- contract_config/index.php - Danh sách mẫu in hợp đồng -->
<?php $marketId = $market['market_id']; $marketName = htmlspecialchars($market['market_name']); ?>
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <a href="<?php echo BASE_URL; ?>system/markets" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách chợ
    </a>
    <a href="<?php echo BASE_URL; ?>system/market_contract_config_add/<?php echo $marketId; ?>" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-plus"></i> Thêm mẫu in
    </a>
</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600;">
            <i class="fa-solid fa-print" style="margin-right: 6px; color: var(--primary);"></i>
            Quản lý Mẫu In Hợp Đồng - <?php echo $marketName; ?>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($configs)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                <i class="fa-solid fa-file-circle-question" style="font-size: 32px; margin-bottom: 12px; opacity: 0.5;"></i>
                <p>Chưa có mẫu in hợp đồng nào cho chợ này.</p>
                <a href="<?php echo BASE_URL; ?>system/market_contract_config_add/<?php echo $marketId; ?>" class="btn btn-primary" style="text-decoration: none; margin-top: 8px;">Thêm mẫu in đầu tiên</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Tên mẫu</th>
                            <th>Cơ quan chủ quản</th>
                            <th>Đại diện Bên A</th>
                            <th style="width: 90px; text-align: center;">Mặc định</th>
                            <th style="width: 110px; text-align: center;">Trạng thái</th>
                            <th style="width: 120px; text-align: center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($configs as $i => $cfg): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($cfg['template_name']); ?></td>
                                <td>
                                    <div style="font-size: 13px;"><?php echo htmlspecialchars($cfg['gov_agency_1']); ?></div>
                                    <div style="font-size: 12px; color: var(--text-secondary);"><?php echo htmlspecialchars($cfg['gov_agency_2']); ?></div>
                                </td>
                                <td>
                                    <div style="font-size: 13px;"><?php echo htmlspecialchars($cfg['rep_a_name_1']); ?> - <?php echo htmlspecialchars($cfg['rep_a_position_1']); ?></div>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <?php if ($cfg['is_default']): ?>
                                        <span style="background: var(--primary); color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 11px; white-space: nowrap; display: inline-block;">Mặc định</span>
                                    <?php else: ?>
                                        <span style="color: var(--text-secondary); font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <?php
                                    $isActive = ($cfg['status_code'] ?? 'active') === 'active';
                                    $statusLabel = $isActive ? 'Đang dùng' : 'Ngừng';
                                    $statusBg = $isActive ? 'rgba(52,168,83,0.1)' : 'rgba(156,163,175,0.15)';
                                    $statusColor = $isActive ? '#34A853' : '#9ca3af';
                                    ?>
                                    <button onclick="toggleConfigStatus(<?php echo $cfg['config_id']; ?>, '<?php echo $isActive ? 'inactive' : 'active'; ?>')" 
                                            style="background: <?php echo $statusBg; ?>; color: <?php echo $statusColor; ?>; border: 1px solid <?php echo $statusColor; ?>33; padding: 2px 10px; border-radius: 10px; font-size: 11px; cursor: pointer; white-space: nowrap;" 
                                            title="Nhấn để chuyển trạng thái">
                                        <?php echo $statusLabel; ?>
                                    </button>
                                </td>
                                <td style="text-align: center; vertical-align: middle; white-space: nowrap;">
                                    <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                        <a href="<?php echo BASE_URL; ?>system/market_contract_config_edit/<?php echo $cfg['config_id']; ?>" class="btn btn-outline btn-sm" style="padding: 4px 8px; display: inline-flex; align-items: center; gap: 4px;" title="Sửa">
                                            <i class="fa-solid fa-pen-to-square"></i> Sửa
                                        </a>
                                        <button onclick="deleteConfig(<?php echo $cfg['config_id']; ?>, <?php echo $marketId; ?>)" class="btn btn-outline btn-sm" style="padding: 4px 8px; color: var(--red); border-color: var(--red); display: inline-flex; align-items: center; justify-content: center;" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteConfig(configId, marketId) {
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    var swalBg = isDark ? '#1a2332' : '#ffffff';
    var swalColor = isDark ? '#ffffff' : '#0f1623';

    Swal.fire({
        title: 'Xác nhận xóa?',
        text: 'Mẫu in hợp đồng này sẽ bị xóa vĩnh viễn.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonText: 'Hủy',
        confirmButtonText: 'Xóa',
        background: swalBg,
        color: swalColor
    }).then(function(result) {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL; ?>admin/deleteContractConfig',
                data: { config_id: configId, csrf_token: '<?php echo $_SESSION["csrf_token"] ?? ""; ?>' },
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(data) {
                    if (data.status === 200) {
                        Swal.fire({ icon: 'success', title: 'Đã xóa!', timer: 1200, showConfirmButton: false, background: swalBg, color: swalColor }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: data.error || 'Xóa thất bại.', background: swalBg, color: swalColor });
                    }
                },
                error: function(xhr) {
                    var msg = 'Có lỗi xảy ra.';
                    if (xhr.responseJSON && xhr.responseJSON.error) msg = xhr.responseJSON.error;
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: msg, background: swalBg, color: swalColor });
                }
            });
        }
    });
}

function toggleConfigStatus(configId, newStatus) {
    $.ajax({
        type: 'POST',
        url: '<?php echo BASE_URL; ?>admin/toggleContractConfigStatus',
        data: { config_id: configId, status: newStatus, csrf_token: '<?php echo $_SESSION["csrf_token"] ?? ""; ?>' },
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
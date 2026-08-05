<!-- Giao diện Tổng hợp Mẫu In Hợp Đồng -->
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div style="font-size: 18px; font-weight: 700; color: var(--text-heading);">
        <i class="fa-solid fa-print" style="margin-right: 6px; color: var(--primary);"></i>
        Quản lý Mẫu In Hợp Đồng
    </div>
    
    <?php if (!empty($markets)): ?>
        <div style="display: inline-flex; align-items: center; gap: 8px;">
            <select id="select-add-market" class="form-control" style="width: 220px; height: 38px; font-size: 13.5px; padding: 4px 10px;">
                <option value="">-- Chọn chợ để thêm mẫu --</option>
                <?php foreach ($markets as $m): ?>
                    <option value="<?php echo $m['market_id']; ?>"><?php echo htmlspecialchars($m['market_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button onclick="goToAddConfig()" class="btn btn-primary" style="height: 38px; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-plus"></i> Thêm mẫu in
            </button>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
        <div class="card-title" style="font-size: 14px; font-weight: 600; color: var(--text-secondary);">
            Danh sách tất cả mẫu in thuộc các chợ bạn quản lý
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($configs)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                <i class="fa-solid fa-file-circle-question" style="font-size: 32px; margin-bottom: 12px; opacity: 0.5;"></i>
                <p>Chưa có mẫu in hợp đồng nào được cấu hình.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Tên mẫu</th>
                            <th>Thuộc Chợ</th>
                            <th>Cơ quan chủ quản</th>
                            <th>Đại diện Bên A</th>
                            <th style="width: 90px; text-align: center;">Mặc định</th>
                            <th style="width: 120px; text-align: center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($configs as $i => $cfg): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td style="font-weight: 600; color: var(--text-heading);"><?php echo htmlspecialchars($cfg['template_name']); ?></td>
                                <td>
                                    <span style="font-weight: 500; background: var(--bg-surface-hover); padding: 4px 8px; border-radius: 6px; font-size: 12px; border: 1px solid var(--border-color-light);">
                                        🏪 <?php echo htmlspecialchars($cfg['market_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 13px; font-weight: 500;"><?php echo htmlspecialchars($cfg['gov_agency_1']); ?></div>
                                    <div style="font-size: 12px; color: var(--text-secondary);"><?php echo htmlspecialchars($cfg['gov_agency_2']); ?></div>
                                </td>
                                <td>
                                    <div style="font-size: 13px;"><?php echo htmlspecialchars($cfg['rep_a_name_1']); ?></div>
                                    <div style="font-size: 11px; color: var(--text-secondary);"><?php echo htmlspecialchars($cfg['rep_a_position_1']); ?></div>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <?php if ($cfg['is_default']): ?>
                                        <span style="background: var(--primary); color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 11px; white-space: nowrap; display: inline-block;">Mặc định</span>
                                    <?php else: ?>
                                        <span style="color: var(--text-secondary); font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; vertical-align: middle; white-space: nowrap;">
                                    <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                        <a href="<?php echo BASE_URL; ?>system/market_contract_config_edit/<?php echo $cfg['config_id']; ?>" class="btn btn-outline btn-sm" style="padding: 4px 8px; display: inline-flex; align-items: center; gap: 4px;" title="Sửa">
                                            <i class="fa-solid fa-pen-to-square"></i> Sửa
                                        </a>
                                        <?php if (marketService::isSuperAdmin()): ?>
                                            <button onclick="deleteConfig(<?php echo $cfg['config_id']; ?>)" class="btn btn-outline btn-sm" style="padding: 4px 8px; color: var(--red); border-color: var(--red); display: inline-flex; align-items: center; justify-content: center;" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
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
function goToAddConfig() {
    var marketId = document.getElementById('select-add-market').value;
    if (!marketId) {
        Swal.fire({
            icon: 'warning',
            title: 'Chú ý',
            text: 'Vui lòng chọn một chợ để thêm mẫu in.',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1a2332' : '#ffffff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#ffffff' : '#0f1623'
        });
        return;
    }
    window.location.href = '<?php echo BASE_URL; ?>system/market_contract_config_add/' + marketId;
}

function deleteConfig(configId) {
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    var swalBg = isDark ? '#1a2332' : '#ffffff';
    var swalColor = isDark ? '#ffffff' : '#0f1623';

    Swal.fire({
        title: 'Xác nhận xóa?',
        text: 'Mẫu in hợp đồng này sẽ bị xóa vĩnh viễn khỏi chợ tương ứng.',
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
</script>

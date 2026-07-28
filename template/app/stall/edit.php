<!-- Form Chỉnh sửa Sạp chợ -->
<div style="margin-bottom: 20px;">
    <a href="<?php echo BASE_URL; ?>admin/stalls" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách sạp
    </a>
</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600;">Chỉnh sửa sạp chợ: <?php echo htmlspecialchars($stall['stall_code']); ?></div>
    </div>
    <div class="card-body" style="padding: 24px;">
        <!-- Thông báo lỗi nếu xảy ra lỗi validate (Dành cho Fallback Submit) -->
        <?php if (!empty($error)): ?>
            <div style="background-color: rgba(211, 47, 47, 0.1); border: 1px solid rgba(211, 47, 47, 0.2); color: #d32f2f; padding: 12px; border-radius: 6px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form id="form-edit-stall" action="<?php echo BASE_URL; ?>api/editStall" method="POST">
            <?php csrf_field(); ?>
            <!-- Hidden ID -->
            <input type="hidden" name="id" value="<?php echo $stall['stall_id']; ?>">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Mã sạp -->
                <div class="form-group">
                    <label class="form-label" for="stall_code" style="font-weight: 500; color: var(--text-muted);">Mã sạp (Không thể sửa)</label>
                    <input type="text" id="stall_code" name="stall_code" class="form-control" value="<?php echo htmlspecialchars($stall['stall_code']); ?>" style="background-color: var(--bg-surface-secondary); cursor: not-allowed;" readonly>
                </div>

                <!-- Phân khu -->
                <div class="form-group">
                    <label class="form-label" for="area_id" style="font-weight: 500;">Phân khu chợ (Khu vực) <span style="color: var(--red)">*</span></label>
                    <select id="area_id" name="area_id" class="form-control" required>
                        <option value="">-- Chọn khu vực --</option>
                        <?php if (!empty($areas)): ?>
                            <?php foreach ($areas as $a): ?>
                                <option value="<?php echo $a['area_id']; ?>" <?php echo $stall['stall_area_id'] == $a['area_id'] ? 'selected' : ''; ?>>
                                    <?php 
                                    $displayText = $a['area_name'];
                                    if (!empty($a['area_block'])) $displayText .= ' - ' . $a['area_block'];
                                    if (!empty($a['area_lot'])) $displayText .= ' - ' . $a['area_lot'];
                                    echo htmlspecialchars($displayText); 
                                    ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>



            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Loại sạp -->
                <div class="form-group">
                    <label class="form-label" for="stall_type_id" style="font-weight: 500;">Loại sạp chợ <span style="color: var(--red)">*</span></label>
                    <select id="stall_type_id" name="stall_type_id" class="form-control" required>
                        <option value="">-- Chọn loại sạp --</option>
                        <?php if (!empty($stallTypes)): ?>
                            <?php foreach ($stallTypes as $st): ?>
                                <option value="<?php echo ($st['stall_type_id'] ?? $st['status_id']); ?>" <?php echo $stall['stall_type_id'] == ($st['stall_type_id'] ?? $st['status_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($st['stall_type_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Kích thước & Diện tích -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1.2fr; gap: 10px;">
                    <div class="form-group">
                        <label class="form-label" for="x" style="font-weight: 500;">Chiều dài (m) <span style="color: var(--red)">*</span></label>
                        <input type="number" step="0.01" min="0.01" id="x" name="x" class="form-control" placeholder="Dài" value="<?php echo htmlspecialchars($stall['stall_map_coordinate_x'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="y" style="font-weight: 500;">Chiều rộng (m) <span style="color: var(--red)">*</span></label>
                        <input type="number" step="0.01" min="0.01" id="y" name="y" class="form-control" placeholder="Rộng" value="<?php echo htmlspecialchars($stall['stall_map_coordinate_y'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="area_size" style="font-weight: 500;">Diện tích (m²)</label>
                        <input type="text" id="area_size" class="form-control" style="background-color: var(--bg-card); cursor: not-allowed;" placeholder="Tự tính" readonly value="<?php echo htmlspecialchars($stall['stall_area_size']); ?>">
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Đơn giá thuê -->
                <div class="form-group">
                    <label class="form-label" for="base_price" style="font-weight: 500;">Đơn giá thuê / tháng (VNĐ) <span style="color: var(--red)">*</span></label>
                    <input type="number" min="0" id="base_price" name="base_price" class="form-control" placeholder="Nhập giá cho thuê mỗi tháng" value="<?php echo htmlspecialchars($stall['stall_base_price']); ?>" required>
                </div>

                <!-- Trạng thái -->
                <div class="form-group">
                    <label class="form-label" for="status" style="font-weight: 500;">Trạng thái sạp</label>
                    <select id="status" name="status" class="form-control">
                        <?php if (!empty($statuses)): ?>
                            <?php foreach ($statuses as $st): ?>
                                <option value="<?php echo htmlspecialchars(($st['stall_type_id'] ?? $st['status_id'])); ?>" <?php echo $stall['stall_status_id'] == ($st['stall_type_id'] ?? $st['status_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($st['status_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border-color-light); margin: 24px 0;">

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="<?php echo BASE_URL; ?>admin/stalls" class="btn btn-outline" style="text-decoration: none;">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-check"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card" style="margin-top: 24px;">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-clock-rotate-left" style="color: var(--primary);"></i>
        <div class="card-title" style="font-size: 15px; font-weight: 600;">Lịch sử thuê sạp</div>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color); text-align: left;">
                    <th style="padding: 12px 20px; font-weight: 600; font-size: 13px; color: var(--text-muted);">Số Hợp đồng</th>
                    <th style="padding: 12px 20px; font-weight: 600; font-size: 13px; color: var(--text-muted);">Tên hợp đồng</th>
                    <th style="padding: 12px 20px; font-weight: 600; font-size: 13px; color: var(--text-muted);">Tiểu thương</th>
                    <th style="padding: 12px 20px; font-weight: 600; font-size: 13px; color: var(--text-muted);">Ngày bắt đầu</th>
                    <th style="padding: 12px 20px; font-weight: 600; font-size: 13px; color: var(--text-muted);">Ngày kết thúc</th>
                    <th style="padding: 12px 20px; font-weight: 600; font-size: 13px; color: var(--text-muted);">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rentalHistory)): ?>
                    <?php foreach ($rentalHistory as $h): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="cell-mono" style="padding: 14px 20px; font-weight: 600; color: var(--text-heading);"><?php echo htmlspecialchars($h['contract_number']); ?></td>
                            <td style="padding: 14px 20px;"><?php echo htmlspecialchars($h['contract_name']); ?></td>
                            <td style="padding: 14px 20px;">
                                <div style="font-weight: 500; color: var(--text-heading);"><?php echo htmlspecialchars($h['trader_fullname']); ?></div>
                                <small style="color: var(--text-muted); font-size: 11px;"><?php echo htmlspecialchars($h['trader_phone']); ?></small>
                            </td>
                            <td style="padding: 14px 20px; color: var(--text-muted);"><?php echo date('d/m/Y', strtotime($h['contract_start_date'])); ?></td>
                            <td style="padding: 14px 20px; color: var(--text-muted);"><?php echo date('d/m/Y', strtotime($h['contract_end_date'])); ?></td>
                            <td style="padding: 14px 20px;">
                                <span class="status <?php echo htmlspecialchars($h['color_class'] ?: 'status-gray'); ?>">
                                    <?php echo htmlspecialchars($h['status_name']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted); font-style: italic;">Sạp này chưa từng phát sinh lịch sử hợp đồng thuê.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    var swalBg = isDark ? '#1a2332' : '#ffffff';
    var swalColor = isDark ? '#ffffff' : '#0f1623';

    // 1. Submit form bằng AJAX
    // App.utils.handleFormSubmit('form-edit-stall', '<?php echo BASE_URL; ?>admin/stalls');
    $('#form-edit-stall').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        var $form = $(this);
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Đang lưu thay đổi...',
            allowOutsideClick: false,
            background: swalBg,
            color: swalColor,
            didOpen: function() { Swal.showLoading(); }
        });

        App.utils.saveFormDraft('form-edit-stall');

        $.ajax({
            type: "POST",
            url: $form.attr('action'),
            data: new FormData(form),
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(data) {
                Swal.close();
                if (data.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        background: swalBg,
                        color: swalColor
                    }).then(function() {
                        App.utils.clearFormDraft('form-edit-stall');
                        window.location.href = '<?php echo BASE_URL; ?>admin/stalls';
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message, background: swalBg, color: swalColor });
                }
            },
            error: function() {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.', background: swalBg, color: swalColor });
            }
        });
    });

    // Hàm phụ phục vụ kiểm tra trùng lặp thời gian thực bằng jQuery
    function setupUniqueCheck(inputId, type, message, excludeId) {
        var input = document.getElementById(inputId);
        if (!input || input.readOnly) return;
        var $input = $(input);
        var $errorEl = $input.parent().find('.realtime-error-msg');
        if (!$errorEl.length) {
            $errorEl = $('<small class="realtime-error-msg" style="color: #e74c3c; font-size: 11px; margin-top: 4px; display: none;"></small>');
            $input.parent().append($errorEl);
        }

        $input.on('blur', function() {
            var val = $input.val().trim();
            if (!val) {
                $errorEl.hide();
                input.style.borderColor = '';
                input.setCustomValidity('');
                return;
            }

            $.ajax({
                type: 'GET',
                url: '<?php echo BASE_URL; ?>api/checkExists',
                data: { type: type, value: val, exclude_id: excludeId },
                dataType: 'json',
                success: function(data) {
                    if (data.exists) {
                        $errorEl.text(message).show();
                        input.style.borderColor = '#e74c3c';
                        input.setCustomValidity(message);
                    } else {
                        $errorEl.hide();
                        input.style.borderColor = '';
                        input.setCustomValidity('');
                    }
                }
            });
        });

        $input.on('input', function() {
            $errorEl.hide();
            input.style.borderColor = '';
            input.setCustomValidity('');
        });
    }

    // 2. Kiểm tra trùng mã sạp thời gian thực (loại trừ ID hiện tại)
    var currentId = $('input[name="id"]').val() || '';
    setupUniqueCheck('stall_code', 'stall_code', 'Mã sạp này đã tồn tại trên hệ thống.', currentId);

    // 3. Tự động tính diện tích sạp khi nhập dài/rộng
    function calculateArea() {
        var x = parseFloat($('#x').val());
        var y = parseFloat($('#y').val());
        if (!isNaN(x) && !isNaN(y)) {
            var area = (x * y).toFixed(2);
            $('#area_size').val(area);
        } else {
            $('#area_size').val('');
        }
    }
    $('#x, #y').on('input', calculateArea);
});
</script>


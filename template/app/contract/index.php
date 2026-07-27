<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div style="display: flex; gap: 8px; align-items: center;">
        <form id="form-filter-contracts" action="<?php echo BASE_URL; ?>admin/contracts" method="GET" style="display: flex; gap: 8px; margin: 0;">
            <input type="text" name="q" value="<?php echo htmlspecialchars($search ?? ''); ?>" class="form-control" placeholder="Tìm số HĐ, tên HĐ, tiểu thương..." style="width: 250px; height: 36px; font-size: 13px;">
            <select name="status" class="form-control" style="width: 180px; height: 36px; font-size: 13px;">
                <option value="">Tất cả trạng thái</option>
                <?php if (!empty($statuses)): ?>
                    <?php foreach ($statuses as $st): ?>
                        <option value="<?php echo htmlspecialchars($st['status_code']); ?>" <?php echo (($status_filter ?? '') === $st['status_code']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($st['status_name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button type="button" id="btn-filter-contracts" class="btn btn-outline" style="height: 36px; padding: 0 16px;">Lọc</button>
            <?php if (!empty($search) || !empty($status_filter)): ?>
                <a href="<?php echo BASE_URL; ?>admin/contracts" class="btn btn-ghost" style="height: 36px; padding: 0 12px; display: inline-flex; align-items: center; text-decoration: none; color: var(--text-muted);">Xóa bộ lọc</a>
            <?php endif; ?>
        </form>
    </div>
    
    <a href="<?php echo BASE_URL; ?>admin/contract_add" class="btn btn-primary" style="height: 36px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: white;">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 14px; height: 14px;"><path d="M8 2v12M2 8h12"/></svg>
        Lập Hợp đồng mới
    </a>
</div>

<?php if (session::get('success_message')): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.2); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-check"></i>
        <span><?php echo session::flash('success_message'); ?></span>
    </div>
<?php endif; ?>

<?php if (session::get('error_message')): ?>
    <div style="background-color: rgba(211, 47, 47, 0.1); border: 1px solid rgba(211, 47, 47, 0.2); color: #d32f2f; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span><?php echo session::flash('error_message'); ?></span>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600;">Hồ sơ Hợp đồng thuê mặt bằng sạp (<span id="filter-total-contracts"><?php echo count($contracts); ?></span>)</div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 12px 16px; width: 120px;">Số Hợp đồng</th>
                        <th style="padding: 12px 16px;">Tên hợp đồng</th>
                        <th style="padding: 12px 16px;">Tiểu thương</th>
                        <th style="padding: 12px 16px; width: 90px;">Mã sạp</th>
                        <th style="padding: 12px 16px; width: 100px;">Ngày ký</th>
                        <th style="padding: 12px 16px; width: 100px;">Hạn hợp đồng</th>
                        <th style="padding: 12px 16px; width: 110px; text-align: center;">Sắp hết hạn</th>
                        <th style="padding: 12px 16px; width: 120px;">Giá thuê/tháng</th>
                        <th style="padding: 12px 16px; width: 110px;">Đặt cọc</th>
                        <th style="padding: 12px 16px; width: 80px; text-align: center;">File HĐ</th>
                        <th style="padding: 12px 16px; width: 95px; text-align: center;">Phụ lục</th>
                        <th style="padding: 12px 16px; width: 110px;">Trạng thái</th>
                        <th style="padding: 12px 16px; text-align: right; width: 180px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="table-body-contracts">
                    <?php require DIR_TEMPLATE . '/contract/table_rows.php'; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal xem và quản lý phụ lục hợp đồng -->
<div id="modal-appendices" class="custom-modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
    <div class="card" style="width: 90%; max-width: 700px; margin: auto; max-height: 85vh; display: flex; flex-direction: column;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <div class="card-title" style="font-size: 15px; font-weight: 600;">Danh sách phụ lục hợp đồng: <span id="modal-contract-number" style="color: var(--primary);"></span></div>
            <button onclick="App.contract.closeAppendicesModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <div class="card-body" style="padding: 20px; overflow-y: auto; flex-grow: 1;">
            
            <!-- Danh sách phụ lục hiện có -->
            <div id="appendices-list-container" style="margin-bottom: 24px;">
                <h5 style="margin-bottom: 10px; font-weight: 600; font-size: 13px;">Phụ lục đã ký</h5>
                <div id="appendices-list" style="display: flex; flex-direction: column; gap: 8px;">
                    <!-- Sẽ được điền bằng AJAX JS -->
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 20px 0;">

            <!-- Form thêm phụ lục mới -->
            <form id="form-add-appendix" enctype="multipart/form-data">
                <input type="hidden" name="contract_id" id="appendix-contract-id">
                <h5 style="margin-bottom: 14px; font-weight: 600; font-size: 13px; color: var(--text-heading);">Thêm phụ lục hợp đồng mới</h5>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 11px; font-weight: 500;">Số phụ lục *</label>
                        <input type="text" name="appendix_number" class="form-control" placeholder="Ví dụ: PL-SA01-2026-02" required style="height: 34px; font-size: 12px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size: 11px; font-weight: 500;">Tên phụ lục *</label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Phụ lục đổi diện tích" required style="height: 34px; font-size: 12px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 11px; font-weight: 500;">Ngày ký *</label>
                        <input type="date" name="sign_date" class="form-control" required style="height: 34px; font-size: 12px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size: 11px; font-weight: 500;">Ngày hiệu lực *</label>
                        <input type="date" name="effect_date" class="form-control" required style="height: 34px; font-size: 12px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-size: 11px; font-weight: 500;">Nội dung chi tiết phụ lục *</label>
                    <textarea name="content" class="form-control" rows="3" placeholder="Nhập các điều khoản bổ sung, thay đổi đơn giá, thời gian..." required style="font-size: 12px; resize: vertical;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-size: 11px; font-weight: 500;">Tài liệu đính kèm (Ảnh hoặc PDF - Hỗ trợ chọn nhiều file)</label>
                    <input type="file" name="appendix_files[]" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" multiple style="font-size: 12px; padding: 4px 10px; height: 32px;">
                </div>

                <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; height: 36px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px;">
                    <i class="fa-solid fa-paperclip"></i> Ký phụ lục hợp đồng
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal xem lịch sử hợp đồng -->
<div id="modal-history" class="custom-modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
    <div class="card" style="width: 90%; max-width: 600px; margin: auto; max-height: 80vh; display: flex; flex-direction: column;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <div class="card-title" style="font-size: 15px; font-weight: 600;">Lịch sử thay đổi hợp đồng: <span id="modal-history-contract-number" style="color: var(--primary);"></span></div>
            <button onclick="App.contract.closeHistoryModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <div class="card-body" style="padding: 24px; overflow-y: auto; flex-grow: 1;">
            <div id="history-timeline" style="position: relative; padding-left: 20px; border-left: 2px solid var(--border-color); display: flex; flex-direction: column; gap: 20px; margin-left: 10px;">
                <!-- Sẽ được tải bằng AJAX JS -->
            </div>
        </div>
    </div>
</div>

<!-- CSRF Token phục vụ AJAX -->
<?php csrf_field(); ?>

<!-- Nạp JS xử lý AJAX & Form hợp đồng -->
<script>
$(document).ready(function() {
    const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
    const swalBg = isDarkTheme ? '#1a2332' : '#ffffff';
    const swalColor = isDarkTheme ? '#ffffff' : '#0f1623';
    const csrfToken = '<?php echo $_SESSION['csrf_token'] ?? ''; ?>';

    window.App = window.App || {};
    window.App.contract = {
        // 0. Kích hoạt hợp đồng
        activateContract: async function(contractId, contractNumber, startDate, endDate, deposit, contractFile) {
            if (!contractId) return;

            const result = await Swal.fire({
                title: 'Cấu hình & Kích hoạt hợp đồng ' + contractNumber,
                html: `<div style="text-align: left; display: flex; flex-direction: column; gap: 12px;">
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Số hợp đồng:</label>
                             <input type="text" id="swal-contract-number" class="form-control" value="${contractNumber}" style="margin-top: 4px;">
                         </div>
                         <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                             <div>
                                 <label style="font-weight: 500; font-size: 13px;">Ngày bắt đầu:</label>
                                 <input type="date" id="swal-start-date" class="form-control" value="${startDate}" style="margin-top: 4px;">
                             </div>
                             <div>
                                 <label style="font-weight: 500; font-size: 13px;">Ngày kết thúc:</label>
                                 <input type="date" id="swal-end-date" class="form-control" value="${endDate}" style="margin-top: 4px;">
                             </div>
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Tiền đặt cọc (VNĐ):</label>
                             <input type="number" id="swal-deposit" class="form-control" value="${deposit}" style="margin-top: 4px;">
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Tài liệu đã đính kèm:</label>
                             <div id="swal-files-container" style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 6px;"></div>
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Tải lên Hợp đồng ký kết (PDF/Hình ảnh/Word - Chọn nhiều file):</label>
                              <input type="file" id="swal-files" class="form-control" style="margin-top: 4px;" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" multiple>
                         </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Xác nhận Kích hoạt',
                cancelButtonText: 'Hủy bỏ',
                confirmButtonColor: '#1ABB9C',
                cancelButtonColor: '#a0aec0',
                background: swalBg,
                color: swalColor,
                didOpen: () => {
                    let files = [];
                    if (contractFile) {
                        try {
                            if (contractFile.startsWith('[') && contractFile.endsWith(']')) {
                                files = JSON.parse(contractFile);
                            } else {
                                files = [contractFile];
                            }
                        } catch (e) {
                            files = [contractFile];
                        }
                    }

                    const renderChips = () => {
                        const container = document.getElementById('swal-files-container');
                        if (!container) return;
                        if (files.length === 0) {
                            container.innerHTML = '<div style="color: var(--text-muted); font-size: 12px; font-style: italic;">Chưa có tài liệu nào.</div>';
                            return;
                        }
                        let html = '';
                        files.forEach((f, idx) => {
                            const ext = f.split('.').pop().toLowerCase();
                            const icon = ext === 'pdf' ? 'fa-file-pdf' : (ext === 'doc' || ext === 'docx' ? 'fa-file-word' : 'fa-file-image');
                            html += `<div class="file-chip" style="display: inline-flex; align-items: center; gap: 8px; background-color: var(--bg-surface-secondary); border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 20px; font-size: 11px; margin-right: 6px; margin-bottom: 6px;">
                                       <i class="fa-solid ${icon}"></i>
                                       <span style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${f}</span>
                                       <button type="button" class="btn-remove-file" data-idx="${idx}" style="background: none; border: none; color: var(--red); cursor: pointer; font-weight: bold; font-size: 13px; padding: 0 2px; line-height: 1;">&times;</button>
                                    </div>`;
                        });
                        container.innerHTML = html;

                        container.querySelectorAll('.btn-remove-file').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const index = parseInt(this.getAttribute('data-idx'));
                                files.splice(index, 1);
                                renderChips();
                            });
                        });
                    };

                    renderChips();
                    Swal.remainingFiles = files;
                },
                preConfirm: () => {
                    const number = document.getElementById('swal-contract-number').value.trim();
                    const sDate = document.getElementById('swal-start-date').value;
                    const eDate = document.getElementById('swal-end-date').value;
                    const dep = document.getElementById('swal-deposit').value;
                    const fileInput = document.getElementById('swal-files');
                    const remaining = Swal.remainingFiles || [];

                    if (!number) {
                        Swal.showValidationMessage('Vui lòng nhập số hợp đồng!');
                        return false;
                    }
                    if (!sDate) {
                        Swal.showValidationMessage('Vui lòng chọn ngày bắt đầu!');
                        return false;
                    }
                    if (!eDate) {
                        Swal.showValidationMessage('Vui lòng chọn ngày kết thúc!');
                        return false;
                    }
                    if (new Date(sDate) >= new Date(eDate)) {
                        Swal.showValidationMessage('Ngày kết thúc phải sau ngày bắt đầu!');
                        return false;
                    }
                    if (dep === '' || parseFloat(dep) < 0) {
                        Swal.showValidationMessage('Số tiền đặt cọc không hợp lệ!');
                        return false;
                    }

                    return { number, sDate, eDate, dep, fileInput, remaining };
                }
            });

            if (result.isConfirmed && result.value) {
                App.alert.loading('Đang kích hoạt hợp đồng...');
                const fd = new FormData();
                fd.append('contract_id', contractId);
                fd.append('contract_number', result.value.number);
                fd.append('contract_start_date', result.value.sDate);
                fd.append('contract_end_date', result.value.eDate);
                fd.append('contract_deposit', result.value.dep);
                fd.append('remaining_files', JSON.stringify(result.value.remaining));
                if (result.value.fileInput.files.length > 0) {
                    for (let i = 0; i < result.value.fileInput.files.length; i++) {
                        fd.append('contract_files[]', result.value.fileInput.files[i]);
                    }
                }
                fd.append('csrf_token', csrfToken);

                $.ajax({
                    type: "POST",
                    url: '<?php echo BASE_URL; ?>api/activateContract',
                    data: fd,
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
                                location.reload();
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message, background: swalBg, color: swalColor });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.', background: swalBg, color: swalColor });
                    }
                });
            }
        },

        // 1. Gia hạn hợp đồng
        renewContract: async function(contractId, contractNumber, currentEndDate) {
            if (!contractId) return;

            const result = await Swal.fire({
                title: 'Gia hạn hợp đồng ' + contractNumber,
                html: `<div style="text-align: left;">
                         <p style="margin-bottom: 8px;">Hạn hiện tại: <strong>${currentEndDate}</strong></p>
                         <label style="font-weight: 500; font-size: 13px;">Chọn ngày hết hạn mới:</label>
                         <input type="date" id="swal-new-end-date" class="form-control" style="margin-top: 6px;">
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Xác nhận gia hạn',
                cancelButtonText: 'Đóng',
                confirmButtonColor: '#1ABB9C',
                cancelButtonColor: '#a0aec0',
                background: swalBg,
                color: swalColor,
                preConfirm: () => {
                    const val = document.getElementById('swal-new-end-date').value;
                    if (!val) {
                        Swal.showValidationMessage('Bạn cần chọn ngày hết hạn mới!');
                        return false;
                    }
                    return val;
                }
            });

            if (result.isConfirmed && result.value) {
                App.alert.loading('Đang xử lý gia hạn...');
                const fd = new FormData();
                fd.append('contract_id', contractId);
                fd.append('new_end_date', result.value);
                fd.append('csrf_token', csrfToken);

                // App.utils.apiPost('<?php echo BASE_URL; ?>api/renewContract', fd, { onSuccess: () => { location.reload(); } });
                $.ajax({
                    type: "POST",
                    url: '<?php echo BASE_URL; ?>api/renewContract',
                    data: fd,
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
                                location.reload();
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message, background: swalBg, color: swalColor });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.', background: swalBg, color: swalColor });
                    }
                });
            }
        },

        // 2. Thanh lý hợp đồng
        liquidateContract: async function(contractId, contractNumber) {
            if (!contractId) return;

            const result = await Swal.fire({
                title: 'Thanh lý hợp đồng ' + contractNumber + '?',
                text: "Xác nhận thực hiện thanh lý hợp đồng thuê sạp này? Toàn bộ công nợ liên quan phải được tất toán.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff9800',
                cancelButtonColor: '#a0aec0',
                confirmButtonText: 'Đồng ý thanh lý',
                cancelButtonText: 'Hủy bỏ',
                background: swalBg,
                color: swalColor
            });

            if (result.isConfirmed) {
                App.alert.loading('Đang xử lý thanh lý...');
                const fd = new FormData();
                fd.append('contract_id', contractId);
                fd.append('csrf_token', csrfToken);

                // App.utils.apiPost('<?php echo BASE_URL; ?>api/liquidateContract', fd, { onSuccess: () => { location.reload(); } });
                $.ajax({
                    type: "POST",
                    url: '<?php echo BASE_URL; ?>api/liquidateContract',
                    data: fd,
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
                                location.reload();
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message, background: swalBg, color: swalColor });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.', background: swalBg, color: swalColor });
                    }
                });
            }
        },

        // 3. Chấm dứt trước hạn
        terminateContract: async function(contractId, contractNumber) {
            if (!contractId) return;

            const result = await Swal.fire({
                title: 'Chấm dứt hợp đồng ' + contractNumber + '?',
                text: "Xác nhận chấm dứt hợp đồng thuê sạp trước thời hạn? Trạng thái sạp sẽ được chuyển về Trống.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EA4335',
                cancelButtonColor: '#a0aec0',
                confirmButtonText: 'Xác nhận chấm dứt',
                cancelButtonText: 'Hủy bỏ',
                background: swalBg,
                color: swalColor
            });

            if (result.isConfirmed) {
                App.alert.loading('Đang chấm dứt hợp đồng...');
                const fd = new FormData();
                fd.append('contract_id', contractId);
                fd.append('csrf_token', csrfToken);

                // App.utils.apiPost('<?php echo BASE_URL; ?>api/terminateContract', fd, { onSuccess: () => { location.reload(); } });
                $.ajax({
                    type: "POST",
                    url: '<?php echo BASE_URL; ?>api/terminateContract',
                    data: fd,
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
                                location.reload();
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message, background: swalBg, color: swalColor });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.', background: swalBg, color: swalColor });
                    }
                });
            }
        },

        // 4. Xóa mềm hợp đồng
        deleteContract: async function(contractId, contractNumber) {
            if (!contractId) return;

            const result = await Swal.fire({
                title: 'Xóa hợp đồng ' + contractNumber + '?',
                text: "Hợp đồng sẽ được ẩn đi (xóa mềm). Nếu hợp đồng đang hoạt động, sạp cũng sẽ được giải phóng về trạng thái 'Trống'.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#ff5252',
                cancelButtonColor: '#a0aec0',
                background: swalBg,
                color: swalColor
            });

            if (result.isConfirmed) {
                App.alert.loading('Đang xử lý xóa...');
                const fd = new FormData();
                fd.append('contract_id', contractId);
                fd.append('csrf_token', csrfToken);

                // App.utils.apiPost('<?php echo BASE_URL; ?>api/deleteContract', fd, { onSuccess: () => { location.reload(); } });
                $.ajax({
                    type: "POST",
                    url: '<?php echo BASE_URL; ?>api/deleteContract',
                    data: fd,
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
                                location.reload();
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message, background: swalBg, color: swalColor });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.', background: swalBg, color: swalColor });
                    }
                });
            }
        },

        // 5. Hiển thị modal quản lý phụ lục hợp đồng
        viewAppendices: function(contractId, contractNumber) {
            if (!contractId) return;

            document.getElementById('modal-contract-number').textContent = contractNumber;
            document.getElementById('appendix-contract-id').value = contractId;
            
            loadAppendices(contractId);
            
            const modal = document.getElementById('modal-appendices');
            if (modal) modal.style.display = 'flex';
        },

        // 6. Đóng modal phụ lục
        closeAppendicesModal: function() {
            const modal = document.getElementById('modal-appendices');
            if (modal) modal.style.display = 'none';
            location.reload();
        },

        // 7. Xem lịch sử hợp đồng
        viewHistory: async function(contractId, contractNumber) {
            if (!contractId) return;

            document.getElementById('modal-history-contract-number').textContent = contractNumber;
            const container = document.getElementById('history-timeline');
            container.innerHTML = '<div style="color: var(--text-muted); font-size: 12px; padding: 10px 0;"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải lịch sử...</div>';

            const modal = document.getElementById('modal-history');
            if (modal) modal.style.display = 'flex';

            try {
                const res = await fetch('<?php echo BASE_URL; ?>api/getContractHistory?contract_id=' + contractId);
                const resData = await res.json();
                
                if (resData && resData.status === 200 && Array.isArray(resData.data)) {
                    const list = resData.data;
                    if (list.length === 0) {
                        container.innerHTML = '<div style="color: var(--text-muted); font-size: 12px; padding: 10px 0; font-style: italic;">Chưa có lịch sử thay đổi nào ghi nhận.</div>';
                        return;
                    }

                    let html = '';
                    list.forEach(item => {
                        let actionText = '';
                        let color = '#7f8c8d'; // gray
                        switch(item.history_action) {
                            case 'create':
                                actionText = 'Lập hợp đồng mới';
                                color = '#2980b9'; // blue
                                break;
                            case 'update':
                                actionText = 'Chỉnh sửa hợp đồng';
                                color = '#2980b9'; // blue
                                break;
                            case 'activate':
                                actionText = 'Kích hoạt hợp đồng';
                                color = '#27ae60'; // green
                                break;
                            case 'renew':
                                actionText = 'Gia hạn hợp đồng';
                                color = '#8e44ad'; // purple
                                break;
                            case 'liquidate':
                                actionText = 'Thanh lý hợp đồng';
                                color = '#e67e22'; // orange
                                break;
                            case 'terminate':
                                actionText = 'Chấm dứt trước hạn';
                                color = '#c0392b'; // red
                                break;
                            case 'appendix_add':
                                actionText = 'Thêm phụ lục';
                                color = '#16a085'; // teal
                                break;
                        }

                        let diffHtml = '';
                        if (item.history_action === 'update' && item.history_changes) {
                            try {
                                const diff = JSON.parse(item.history_changes);
                                if (typeof diff === 'object' && diff !== null) {
                                    const keys = Object.keys(diff);
                                    // Check if this is the new format (has at least one key with 'label')
                                    const isNewFormat = keys.length > 0 && typeof diff[keys[0]] === 'object' && diff[keys[0]] !== null && 'label' in diff[keys[0]];
                                    
                                    if (isNewFormat) {
                                        diffHtml = '<div style="margin-top: 6px; margin-bottom: 6px; padding: 8px 12px; background: var(--bg-surface-secondary); border-left: 3px solid var(--primary); border-radius: 4px; font-size: 11.5px; border: 1px solid var(--border-color);">';
                                        for (const key in diff) {
                                            const change = diff[key];
                                            
                                            const parseFiles = (val) => {
                                                if (!val) return '<span style="color: var(--text-muted); font-style: italic;">(không có)</span>';
                                                if (val.startsWith('[') && val.endsWith(']')) {
                                                    try {
                                                        const arr = JSON.parse(val);
                                                        return arr.length > 0 ? arr.join(', ') : '<span style="color: var(--text-muted); font-style: italic;">(không có)</span>';
                                                    } catch (e) {
                                                        return val;
                                                    }
                                                }
                                                return val;
                                            };
                                            
                                            let oldDisplay = key === 'contract_file' ? parseFiles(change.old) : (change.old || '<span style="color: var(--text-muted); font-style: italic;">(trống)</span>');
                                            let newDisplay = key === 'contract_file' ? parseFiles(change.new) : (change.new || '<span style="color: var(--text-muted); font-style: italic;">(trống)</span>');
                                            
                                            diffHtml += `<div style="margin-bottom: 4px; line-height: 1.5;">
                                                            <strong>${change.label}</strong>: 
                                                            <span style="color: #c0392b; text-decoration: line-through;">${oldDisplay}</span> 
                                                            <i class="fa-solid fa-arrow-right" style="margin: 0 6px; font-size: 10px; color: var(--text-muted);"></i> 
                                                            <span style="color: #27ae60; font-weight: 600;">${newDisplay}</span>
                                                         </div>`;
                                        }
                                        diffHtml += '</div>';
                                    }
                                    diffHtml += '</div>';
                                }
                            } catch (e) {
                                console.error("Error parsing history_changes:", e);
                            }
                        }

                        const date = new Date(item.history_created_at);
                        const dateFormatted = date.toLocaleString('vi-VN');

                        html += `<div style="position: relative; padding-bottom: 12px; margin-bottom: 12px; border-left: 2px solid var(--border-color); padding-left: 16px; margin-left: 5px;">
                                    <div style="position: absolute; left: -6px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background-color: ${color}; border: 2px solid var(--bg-surface);"></div>
                                    <div style="font-weight: 600; font-size: 13px; color: var(--text-heading);">${actionText}</div>
                                    <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 4px;">Người thực hiện: <strong>${item.user_name || 'Hệ thống'}</strong> - ${dateFormatted}</div>
                                    ${diffHtml ? diffHtml : `<div style="font-size: 12px; background-color: var(--bg-surface-secondary); padding: 8px 12px; border-radius: 4px; border: 1px solid var(--border-color); color: var(--text-body);">${item.history_note || 'Không có ghi chú thêm.'}</div>`}
                                 </div>`;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div style="color: var(--red); font-size: 12px; padding: 10px 0;">Không thể tải dữ liệu lịch sử.</div>';
                }
            } catch(e) {
                container.innerHTML = '<div style="color: var(--red); font-size: 12px; padding: 10px 0;">Có lỗi xảy ra khi gọi API.</div>';
            }
        },
        // 8. Chỉnh sửa thông tin hợp đồng
        editContract: async function(contractId, contractNumber, contractName, startDate, endDate, deposit, description, contractFile) {
            if (!contractId) return;

            const result = await Swal.fire({
                title: 'Chỉnh sửa hợp đồng ' + contractNumber,
                html: `<div style="text-align: left; display: flex; flex-direction: column; gap: 12px;">
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Số hợp đồng *:</label>
                             <input type="text" id="swal-edit-number" class="form-control" value="${contractNumber}" style="margin-top: 4px;">
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Tên hợp đồng *:</label>
                             <input type="text" id="swal-edit-name" class="form-control" value="${contractName}" style="margin-top: 4px;">
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Ngày bắt đầu *:</label>
                             <input type="date" id="swal-edit-start-date" class="form-control" value="${startDate}" style="margin-top: 4px;">
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Ngày kết thúc *:</label>
                             <input type="date" id="swal-edit-end-date" class="form-control" value="${endDate}" style="margin-top: 4px;">
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Tiền đặt cọc (VNĐ):</label>
                             <input type="number" id="swal-edit-deposit" class="form-control" value="${deposit}" style="margin-top: 4px;">
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Mô tả / Ghi chú:</label>
                             <textarea id="swal-edit-description" class="form-control" rows="2" style="margin-top: 4px; resize: vertical;">${description}</textarea>
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Tài liệu đã đính kèm:</label>
                             <div id="swal-edit-files-container" style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 6px;"></div>
                         </div>
                         <div>
                             <label style="font-weight: 500; font-size: 13px;">Tải lên Tài liệu mới (PDF/Ảnh/Word - Chọn nhiều file):</label>
                              <input type="file" id="swal-edit-files" class="form-control" style="margin-top: 4px;" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" multiple>
                         </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Lưu thay đổi',
                cancelButtonText: 'Hủy',
                background: swalBg,
                color: swalColor,
                didOpen: () => {
                    let files = [];
                    if (contractFile) {
                        try {
                            if (contractFile.startsWith('[') && contractFile.endsWith(']')) {
                                files = JSON.parse(contractFile);
                            } else {
                                files = [contractFile];
                            }
                        } catch (e) {
                            files = [contractFile];
                        }
                    }

                    const renderChips = () => {
                        const container = document.getElementById('swal-edit-files-container');
                        if (!container) return;
                        if (files.length === 0) {
                            container.innerHTML = '<div style="color: var(--text-muted); font-size: 12px; font-style: italic;">Chưa có tài liệu nào.</div>';
                            return;
                        }
                        let html = '';
                        files.forEach((f, idx) => {
                            const ext = f.split('.').pop().toLowerCase();
                            const icon = ext === 'pdf' ? 'fa-file-pdf' : (ext === 'doc' || ext === 'docx' ? 'fa-file-word' : 'fa-file-image');
                            html += `<div class="file-chip" style="display: inline-flex; align-items: center; gap: 8px; background-color: var(--bg-surface-secondary); border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 20px; font-size: 11px; margin-right: 6px; margin-bottom: 6px;">
                                       <i class="fa-solid ${icon}"></i>
                                       <span style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${f}</span>
                                       <button type="button" class="btn-remove-file" data-idx="${idx}" style="background: none; border: none; color: var(--red); cursor: pointer; font-weight: bold; font-size: 13px; padding: 0 2px; line-height: 1;">&times;</button>
                                    </div>`;
                        });
                        container.innerHTML = html;

                        container.querySelectorAll('.btn-remove-file').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const index = parseInt(this.getAttribute('data-idx'));
                                files.splice(index, 1);
                                renderChips();
                            });
                        });
                    };

                    renderChips();
                    Swal.remainingEditFiles = files;
                },
                preConfirm: () => {
                    const number = document.getElementById('swal-edit-number').value.trim();
                    const name = document.getElementById('swal-edit-name').value.trim();
                    const start = document.getElementById('swal-edit-start-date').value;
                    const end = document.getElementById('swal-edit-end-date').value;
                    const dep = document.getElementById('swal-edit-deposit').value;
                    const desc = document.getElementById('swal-edit-description').value;
                    const fileInput = document.getElementById('swal-edit-files');
                    const remaining = Swal.remainingEditFiles || [];
                    
                    if (!number) {
                        Swal.showValidationMessage('Số hợp đồng không được để trống!');
                        return false;
                    }
                    if (!name) {
                        Swal.showValidationMessage('Tên hợp đồng không được để trống!');
                        return false;
                    }
                    if (!start) {
                        Swal.showValidationMessage('Vui lòng chọn ngày bắt đầu!');
                        return false;
                    }
                    if (!end) {
                        Swal.showValidationMessage('Vui lòng chọn ngày kết thúc!');
                        return false;
                    }
                    
                    const fd = new FormData();
                    fd.append('contract_id', contractId);
                    fd.append('contract_number', number);
                    fd.append('contract_name', name);
                    fd.append('contract_start_date', start);
                    fd.append('contract_end_date', end);
                    fd.append('contract_deposit', dep);
                    fd.append('contract_description', desc);
                    fd.append('remaining_files', JSON.stringify(remaining));
                    if (fileInput.files.length > 0) {
                        for (let i = 0; i < fileInput.files.length; i++) {
                            fd.append('contract_files[]', fileInput.files[i]);
                        }
                    }
                    
                    return fd;
                }
            });

            if (result.isConfirmed) {
                App.alert.loading('Đang lưu thay đổi...');
                const fd = result.value;
                fd.append('csrf_token', csrfToken);

                $.ajax({
                    type: "POST",
                    url: '<?php echo BASE_URL; ?>api/editContract',
                    data: fd,
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
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: data.message || 'Có lỗi xảy ra',
                                background: swalBg,
                                color: swalColor
                            });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi kết nối',
                            text: 'Không thể gửi yêu cầu chỉnh sửa.',
                            background: swalBg,
                            color: swalColor
                        });
                    }
                });
            }
        },
        closeHistoryModal: function() {
            const modal = document.getElementById('modal-history');
            if (modal) modal.style.display = 'none';
        }
    };

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return dateStr;
    }

    async function loadAppendices(contractId) {
        const container = document.getElementById('appendices-list');
        if (!container) return;

        container.innerHTML = '<div style="color: var(--text-muted); font-size: 12px; padding: 10px 0;">Đang tải danh sách phụ lục...</div>';

        try {
            const res = await fetch('<?php echo BASE_URL; ?>api/getContractAppendices?contract_id=' + contractId);
            const resData = await res.json();
            
            if (resData && resData.status === 200 && Array.isArray(resData.data)) {
                const list = resData.data;
                if (list.length === 0) {
                    container.innerHTML = '<div style="color: var(--text-muted); font-size: 12px; padding: 10px 0; font-style: italic;">Chưa có phụ lục hợp đồng nào.</div>';
                    return;
                }

                let html = '';
                list.forEach(app => {
                    let fileLink = '';
                    if (app.file) {
                        let files = [];
                        try {
                            if (app.file.startsWith('[') && app.file.endsWith(']')) {
                                files = JSON.parse(app.file);
                            } else {
                                files = [app.file];
                            }
                        } catch (e) {
                            files = [app.file];
                        }
                        
                        if (Array.isArray(files) && files.length > 0) {
                            fileLink = '<div style="margin-top: 6px; display: flex; flex-direction: column; gap: 4px;">';
                            files.forEach((file, index) => {
                                const ext = file.split('.').pop().toLowerCase();
                                let icon = 'fa-file-image';
                                if (ext === 'pdf') {
                                    icon = 'fa-file-pdf';
                                } else if (ext === 'doc' || ext === 'docx') {
                                    icon = 'fa-file-word';
                                }
                                fileLink += `<a href="<?php global $upload_path; echo $upload_path; ?>/contracts/appendices/${file}" target="_blank" style="color: var(--primary); font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;" title="Tải file ${index + 1}">
                                                <i class="fa-solid ${icon}"></i> Tài liệu đính kèm ${index + 1} (.${ext.toUpperCase()})
                                             </a>`;
                            });
                            fileLink += '</div>';
                        }
                    }
                    
                    html += `
                        <div style="background-color: var(--bg-surface-secondary); border: 1px solid var(--border-color); padding: 12px; border-radius: 6px; font-size: 12px; margin-bottom: 8px;">
                            <div style="display: flex; justify-content: space-between; font-weight: 600; color: var(--text-heading); margin-bottom: 4px;">
                               <span>${app.name} (${app.appendix_number})</span>
                               <span style="color: var(--text-muted); font-weight: normal; font-size: 11px;">Ký: ${formatDate(app.sign_date)}</span>
                           </div>
                           <div style="color: var(--text-heading); margin-bottom: 6px;">
                               Hiệu lực từ ngày: <strong>${formatDate(app.effect_date)}</strong>
                           </div>
                           <div style="color: var(--text-muted); background: var(--bg-surface); padding: 8px; border-radius: 4px; border: 1px solid var(--border-color-light); white-space: pre-wrap;">${app.content}</div>
                           ${fileLink}
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div style="color: var(--red); font-size: 12px; padding: 10px 0;">Không thể tải danh sách phụ lục.</div>';
            }
        } catch (error) {
            container.innerHTML = '<div style="color: var(--red); font-size: 12px; padding: 10px 0;">Lỗi kết nối mạng.</div>';
        }
    }

    // Submit form Phụ lục
    $('#form-add-appendix').on('submit', function(e) {
        e.preventDefault();
        
        App.alert.loading('Đang xử lý thêm phụ lục...');
        const fd = new FormData(this);
        fd.append('csrf_token', csrfToken);

        // App.utils.apiPost('<?php echo BASE_URL; ?>api/addContractAppendix', fd, { ... });
        $.ajax({
            type: "POST",
            url: '<?php echo BASE_URL; ?>api/addContractAppendix',
            data: fd,
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
                        text: 'Đã thêm phụ lục hợp đồng thành công!',
                        timer: 1500,
                        showConfirmButton: false,
                        background: swalBg,
                        color: swalColor
                    }).then(function() {
                        const contractId = document.getElementById('appendix-contract-id').value;
                        loadAppendices(contractId);
                        document.getElementById('form-add-appendix').reset();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message, background: swalBg, color: swalColor });
                }
            },
            error: function() {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.', background: swalBg, color: swalColor });
            }
        });
    });

    // Lọc danh sách hợp đồng qua AJAX
    // App.utils.initFilterFormAjax({ buttonId: 'btn-filter-contracts', tbodyId: 'table-body-contracts', totalId: 'filter-total-contracts', apiUrl: 'api/filterContracts', pagePath: 'admin/contracts' });
    (function() {
        var btn = $('#btn-filter-contracts');
        if (!btn.length) return;
        var form = btn.closest('form');
        var inputs = form.find('input[name], select[name]');
        var tbody = $('#table-body-contracts');
        var totalEl = $('#filter-total-contracts');

        function doFilter() {
            var params = {};
            inputs.each(function() {
                var value = $(this).val().trim();
                if (value !== '') {
                    params[$(this).attr('name')] = value;
                }
            });
            var query = $.param(params);
            if (tbody.length) {
                tbody.css('opacity', '0.5');
            }
            $.ajax({
                type: 'GET',
                url: '<?php echo BASE_URL; ?>api/filterContracts',
                data: params,
                dataType: 'json',
                success: function(data) {
                    if (tbody.length) {
                        tbody.html(data.html).css('opacity', '1');
                    }
                    if (totalEl.length && typeof data.total !== 'undefined') {
                        totalEl.text(data.total);
                    }
                    var newUrl = '<?php echo BASE_URL; ?>admin/contracts' + (query ? '?' + query : '');
                    window.history.pushState({ path: newUrl }, '', newUrl);
                },
                error: function() {
                    if (tbody.length) tbody.css('opacity', '1');
                }
            });
        }

        btn.on('click', doFilter);
        form.find('input[type="text"]').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                doFilter();
            }
        });
    })();

    // Đóng modal khi bấm ra ngoài
    $(window).on('click', function(e) {
        const modal = document.getElementById('modal-appendices');
        if (e.target === modal) {
            App.contract.closeAppendicesModal();
        }
        const modalHistory = document.getElementById('modal-history');
        if (e.target === modalHistory) {
            App.contract.closeHistoryModal();
        }
    });
});
</script>


<!-- Giao diện Thêm Mẫu In Hợp Đồng -->
<?php $marketId = $market['market_id']; $marketName = htmlspecialchars($market['market_name']); ?>
<div style="margin-bottom: 20px;">
    <a href="<?php echo BASE_URL; ?>system/market_contract_configs/<?php echo $marketId; ?>" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách mẫu in
    </a>
</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600;">
            Thêm Mẫu In Hợp Đồng - Chợ <?php echo $marketName; ?>
        </div>
    </div>
    <div class="card-body" style="padding: 24px;">
        <form id="form-add-config" method="POST" action="<?php echo BASE_URL; ?>admin/addContractConfig">
            <?php csrf_field(); ?>
            <input type="hidden" name="market_id" value="<?php echo $marketId; ?>">

            <!-- Tên mẫu & Mặc định -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="template_name" style="font-weight: 500;">Tên mẫu in <span style="color: var(--red)">*</span></label>
                    <input type="text" id="template_name" name="template_name" class="form-control" placeholder="Ví dụ: Mẫu chuẩn 2026, Mẫu Thuê Ngắn Hạn..." required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="is_default" style="font-weight: 500;">Đặt làm mặc định</label>
                    <select id="is_default" name="is_default" class="form-control">
                        <option value="0">Không</option>
                        <option value="1">Có (Thay thế mẫu cũ làm mặc định)</option>
                    </select>
                </div>
            </div>

            <!-- Cấp chủ quản -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="gov_agency_1" style="font-weight: 500;">Cơ quan quản lý cấp 1 (Dòng trên)</label>
                    <textarea id="gov_agency_1" name="gov_agency_1" class="form-control" rows="2" style="resize: vertical;" placeholder="Ví dụ: ỦY BAN NHÂN DÂN QUẬN 1"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="gov_agency_2" style="font-weight: 500;">Đơn vị chủ quản cấp 2 (Dòng dưới)</label>
                    <textarea id="gov_agency_2" name="gov_agency_2" class="form-control" rows="2" style="resize: vertical;" placeholder="Ví dụ: BAN QUẢN LÝ CHỢ BẾN THÀNH"></textarea>
                </div>
            </div>

            <!-- Hậu tố tên hợp đồng & Tiêu đề đại diện A -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="contract_title_suffix" style="font-weight: 500;">Hậu tố tiêu đề hợp đồng</label>
                    <textarea id="contract_title_suffix" name="contract_title_suffix" class="form-control" rows="2" style="resize: vertical;" placeholder="Ví dụ: TẠI CHỢ BẾN THÀNH"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="rep_a_header" style="font-weight: 500;">Tiêu đề bên A</label>
                    <textarea id="rep_a_header" name="rep_a_header" class="form-control" rows="2" style="resize: vertical;" placeholder="Ví dụ: Đại diện Bên A (gọi tắt là Bên A):"></textarea>
                </div>
            </div>

            <h4 style="margin: 24px 0 12px 0; padding-bottom: 6px; border-bottom: 1px solid var(--border-color); font-weight: 600; font-size: 14px;">Thông tin Đại diện ký kết (Bên A)</h4>

            <!-- Người đại diện 1 -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="rep_a_name_1" style="font-weight: 500;">Họ tên đại diện 1</label>
                    <input type="text" id="rep_a_name_1" name="rep_a_name_1" class="form-control" placeholder="Ví dụ: Nguyễn Văn A">
                </div>
                <div class="form-group">
                    <label class="form-label" for="rep_a_position_1" style="font-weight: 500;">Chức vụ đại diện 1 (Dùng để in dưới phần ký tên)</label>
                    <input type="text" id="rep_a_position_1" name="rep_a_position_1" class="form-control" placeholder="Ví dụ: Trưởng Ban, Tổ Trưởng">
                </div>
            </div>

            <!-- Người đại diện 2 -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="rep_a_name_2" style="font-weight: 500;">Họ tên đại diện 2 (Nếu có)</label>
                    <input type="text" id="rep_a_name_2" name="rep_a_name_2" class="form-control" placeholder="Ví dụ: Trần Văn B">
                </div>
                <div class="form-group">
                    <label class="form-label" for="rep_a_position_2" style="font-weight: 500;">Chức vụ đại diện 2 (Nếu có)</label>
                    <input type="text" id="rep_a_position_2" name="rep_a_position_2" class="form-control" placeholder="Ví dụ: Phó Ban">
                </div>
            </div>

            <!-- Địa chỉ, Điện thoại, Fax -->
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="rep_a_address" style="font-weight: 500;">Địa chỉ liên hệ</label>
                    <input type="text" id="rep_a_address" name="rep_a_address" class="form-control" placeholder="Ví dụ: Số 1 Đường Lê Lợi, Phường Bến Thành...">
                </div>
                <div class="form-group">
                    <label class="form-label" for="rep_a_phone" style="font-weight: 500;">Số điện thoại</label>
                    <input type="text" id="rep_a_phone" name="rep_a_phone" class="form-control" placeholder="Ví dụ: 028xxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label" for="rep_a_fax" style="font-weight: 500;">Fax</label>
                    <input type="text" id="rep_a_fax" name="rep_a_fax" class="form-control" placeholder="Ví dụ: 028xxxxxxx">
                </div>
            </div>

            <!-- Tài khoản ngân hàng -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="rep_a_bank_account" style="font-weight: 500;">Tài khoản số</label>
                    <input type="text" id="rep_a_bank_account" name="rep_a_bank_account" class="form-control" placeholder="Ví dụ: 1900xxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label" for="rep_a_bank_name" style="font-weight: 500;">Tại Ngân hàng / Kho bạc</label>
                    <input type="text" id="rep_a_bank_name" name="rep_a_bank_name" class="form-control" placeholder="Ví dụ: Agribank Chi nhánh Sài Gòn, KBNN Quận 1">
                </div>
            </div>

            <h4 style="margin: 24px 0 12px 0; padding-bottom: 6px; border-bottom: 1px solid var(--border-color); font-weight: 600; font-size: 14px;">Thiết lập thanh toán</h4>

            <!-- Ngày thanh toán & Thời hạn gia hạn -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" for="payment_due_day" style="font-weight: 500;">Ngày thanh toán hàng tháng <span style="color: var(--red)">*</span></label>
                    <input type="text" id="payment_due_day" name="payment_due_day" class="form-control" placeholder="Ví dụ: 10" value="10" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="payment_grace_period" style="font-weight: 500;">Số ngày gia hạn nộp trễ <span style="color: var(--red)">*</span></label>
                    <input type="text" id="payment_grace_period" name="payment_grace_period" class="form-control" placeholder="Ví dụ: 10" value="10" required>
                </div>
            </div>

            <!-- Căn cứ pháp lý -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" for="legal_grounds" style="font-weight: 500;">Căn cứ pháp lý (Mỗi dòng một căn cứ)</label>
                <textarea id="legal_grounds" name="legal_grounds" class="form-control" rows="5" placeholder="Căn cứ Luật Thương mại số 36/2005/QH11 ngày 14 tháng 6 năm 2005;
Căn cứ Nghị định số 02/2003/NĐ-CP ngày 14 tháng 01 năm 2003 của Chính phủ về phát triển và quản lý chợ;"></textarea>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border-color-light); margin: 24px 0;">

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="<?php echo BASE_URL; ?>system/market_contract_configs/<?php echo $marketId; ?>" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; height: 38px;">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary" style="height: 38px; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fa-solid fa-check"></i> Lưu lại
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    var swalBg = isDark ? '#1a2332' : '#ffffff';
    var swalColor = isDark ? '#ffffff' : '#0f1623';

    $('#form-add-config').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var form = this;
        var $form = $(this);
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Đang lưu thông tin...',
            allowOutsideClick: false,
            background: swalBg,
            color: swalColor,
            didOpen: function() { Swal.showLoading(); }
        });

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
                        text: data.message || 'Thêm mẫu in hợp đồng thành công.',
                        timer: 1500,
                        showConfirmButton: false,
                        background: swalBg,
                        color: swalColor
                    }).then(function() {
                        window.location.href = '<?php echo BASE_URL; ?>system/market_contract_configs/<?php echo $marketId; ?>';
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Thất bại', text: data.error || data.message || 'Thao tác thất bại.', background: swalBg, color: swalColor });
                }
            },
            error: function(xhr) {
                Swal.close();
                var errorMsg = 'Có lỗi xảy ra khi kết nối máy chủ.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                } else if (xhr.responseText) {
                    try {
                        var res = JSON.parse(xhr.responseText);
                        if (res.error) errorMsg = res.error;
                    } catch(e) {}
                }
                Swal.fire({ icon: 'error', title: 'Thất bại', text: errorMsg, background: swalBg, color: swalColor });
            }
        });
    });
});
</script>

<!-- Nạp FontAwesome & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.switch-box {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 28px;
}
.switch-box input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 28px;
}
.switch-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
input:checked + .switch-slider {
    background-color: var(--primary, #0f766e);
}
input:checked + .switch-slider:before {
    transform: translateX(22px);
}
.setting-card {
    background: var(--bg-surface, #ffffff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    margin-bottom: 24px;
}
.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 0;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
}
.setting-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.setting-info {
    max-width: 80%;
}
.setting-title {
    font-weight: 600;
    font-size: 15px;
    color: var(--text-heading, #0f172a);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.setting-desc {
    font-size: 13px;
    color: var(--text-muted, #64748b);
    margin: 0;
    line-height: 1.5;
}
.sync-benefit-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13.5px;
    color: #334155;
    margin-bottom: 10px;
}
.sync-benefit-item i {
    color: #0f766e;
    font-size: 16px;
}
</style>

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Cấu Hình Đồng Bộ Dữ Liệu & Quyền Riêng Tư</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">
            Tùy chọn ẩn/hiển thị tên tiểu thương, giá sạp trên Website và cập nhật dữ liệu mới nhất từ phần mềm quản lý chợ.
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?php echo BASE_URL; ?>home/map" target="_blank" class="btn btn-outline-secondary" style="height: 38px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-map"></i> Xem Bản Đồ Số
        </a>
        <a href="<?php echo BASE_URL; ?>home/traders" target="_blank" class="btn btn-outline-secondary" style="height: 38px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-users"></i> Xem Danh Bạ Tiểu Thương
        </a>
    </div>
</div>

<div class="row" style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 24px;">

    <!-- CỘT 1: CẤU HÌNH ẨN / HIỆN THÔNG TIN CÔNG KHAI -->
    <div>
        <div class="setting-card">
            <h5 style="margin-top: 0; margin-bottom: 18px; font-weight: 700; color: var(--primary, #0f766e); display: flex; align-items: center; gap: 8px; border-bottom: 2px solid var(--border-color); padding-bottom: 12px;">
                <i class="fa-solid fa-user-shield"></i> Cấu Hình Ẩn / Hiện Trên Website
            </h5>

            <form id="formPrivacySettings">
                <!-- NÚT 1: ẨN TÊN TIỂU THƯƠNG -->
                <div class="setting-item">
                    <div class="setting-info">
                        <div class="setting-title">
                            <i class="fa-solid fa-id-card-clip text-primary"></i> Ẩn không hiện tên tiểu thương
                        </div>
                        <p class="setting-desc">
                            Khi <strong>BẬT</strong>: Trên Bản đồ số, Sơ đồ cây và Danh bạ công khai sẽ ẩn/che tên thật của tiểu thương (hiển thị <em>"Hộ kinh doanh (Mã)"</em> hoặc <em>"Đang kinh doanh"</em>) nhằm bảo mật danh tính.
                        </p>
                    </div>
                    <div>
                        <label class="switch-box">
                            <input type="checkbox" id="hide_trader_name" name="hide_trader_name" value="1" <?php echo ($settings['hide_trader_name'] == '1') ? 'checked' : ''; ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- NÚT 2: ẨN GIÁ SẠP -->
                <div class="setting-item">
                    <div class="setting-info">
                        <div class="setting-title">
                            <i class="fa-solid fa-money-bill-wave text-warning"></i> Ẩn giá thuê sạp
                        </div>
                        <p class="setting-desc">
                            Khi <strong>BẬT</strong>: Mức giá thuê sạp / m² trên Bản đồ số, Sơ đồ cây, Trang chủ và Trang Đăng ký thuê sạp sẽ được thay thế bằng thông điệp <em>"Liên hệ Ban Quản Lý"</em>.
                        </p>
                    </div>
                    <div>
                        <label class="switch-box">
                            <input type="checkbox" id="hide_stall_price" name="hide_stall_price" value="1" <?php echo ($settings['hide_stall_price'] == '1') ? 'checked' : ''; ?>>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>

                <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-color); text-align: right;">
                    <button type="button" id="btnSavePrivacy" class="btn btn-primary" style="padding: 10px 24px; font-weight: 600;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cài Đặt Hiển Thị
                    </button>
                </div>
            </form>
        </div>

        <!-- THÔNG BÁO TÌNH TRẠNG HIỆN TẠI -->
        <div class="setting-card" style="background: #f8fafc;">
            <h6 style="margin-top: 0; font-weight: 700; color: var(--text-heading); margin-bottom: 12px;">
                <i class="fa-solid fa-circle-info text-info me-1"></i> Tình trạng hiển thị hiện tại trên Web
            </h6>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: var(--text-muted); line-height: 1.8;">
                <li>Tên tiểu thương: <strong id="lbl_trader_status" class="<?php echo ($settings['hide_trader_name'] == '1') ? 'text-danger' : 'text-success'; ?>"><?php echo ($settings['hide_trader_name'] == '1') ? 'Đang ẨN (Bảo mật danh tính)' : 'Đang HIỆN công khai'; ?></strong></li>
                <li>Giá thuê sạp: <strong id="lbl_price_status" class="<?php echo ($settings['hide_stall_price'] == '1') ? 'text-danger' : 'text-success'; ?>"><?php echo ($settings['hide_stall_price'] == '1') ? 'Đang ẨN (Liên hệ Ban Quản Lý)' : 'Đang HIỆN giá chi tiết'; ?></strong></li>
            </ul>
        </div>
    </div>

    <!-- CỘT 2: ĐỒNG BỘ DỮ LIỆU TỪ HỆ THỐNG QUẢN LÝ -->
    <div>
        <div class="setting-card">
            <h5 style="margin-top: 0; margin-bottom: 16px; font-weight: 700; color: var(--primary, #0f766e); display: flex; align-items: center; gap: 8px; border-bottom: 2px solid var(--border-color); padding-bottom: 12px;">
                <i class="fa-solid fa-arrows-rotate"></i> Đồng Bộ Dữ Liệu Từ Phần Mềm Quản Lý
            </h5>

            <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 18px; line-height: 1.6;">
                Hệ thống sẽ tự động đọc cơ sở dữ liệu từ phần mềm quản lý chợ và cập nhật đồng bộ sang website các nội dung:
            </p>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 16px; margin-bottom: 24px;">
                <div class="sync-benefit-item"><i class="fa-solid fa-circle-check"></i> Danh sách chợ & vị trí tọa độ bản đồ số</div>
                <div class="sync-benefit-item"><i class="fa-solid fa-circle-check"></i> Các phân khu chức năng, nhà lồng, dãy, lô</div>
                <div class="sync-benefit-item"><i class="fa-solid fa-circle-check"></i> Danh mục ngành hàng kinh doanh</div>
                <div class="sync-benefit-item"><i class="fa-solid fa-circle-check"></i> Toàn bộ sạp chợ, diện tích và trạng thái sử dụng</div>
                <div class="sync-benefit-item" style="margin-bottom:0;"><i class="fa-solid fa-circle-check"></i> Danh bạ tiểu thương & hộ kinh doanh</div>
            </div>

            <!-- NÚT ĐỒNG BỘ NỔI BẬT -->
            <div style="text-align: center; margin-bottom: 24px;">
                <button type="button" id="btnRunSync" class="btn btn-success" style="width: 100%; height: 48px; font-size: 16px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);">
                    <i class="fa-solid fa-arrows-rotate" id="iconSync"></i> <span id="textSync">Đồng Bộ Dữ Liệu Ngay</span>
                </button>
                <small style="color: #64748b; font-size: 12px; margin-top: 8px; display: block;">
                    Quá trình đồng bộ diễn ra hoàn toàn tự động chỉ trong vài giây.
                </small>
            </div>

            <!-- LỊCH SỬ & KẾT QUẢ ĐỒNG BỘ -->
            <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 16px;">
                <div style="font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 6px; display: flex; justify-content: space-between; align-items: center;">
                    <span><i class="fa-solid fa-clock-rotate-left me-1"></i> Lần đồng bộ gần nhất:</span>
                    <span id="displaySyncTime" style="color: #0f766e; font-weight: 800;"><?php echo !empty($settings['last_sync_time']) ? htmlspecialchars($settings['last_sync_time']) : 'Chưa thực hiện'; ?></span>
                </div>
                <div id="displaySyncLog" style="font-size: 13px; color: #1e293b; line-height: 1.5; background: white; padding: 10px 12px; border-radius: 6px; border: 1px solid #e2e8f0; margin-top: 6px;">
                    <?php echo htmlspecialchars($settings['last_sync_log']); ?>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. XỬ LÝ LƯU CÀI ĐẶT ẨN / HIỆN
    document.getElementById('btnSavePrivacy').addEventListener('click', function() {
        const btn = this;
        const hideTrader = document.getElementById('hide_trader_name').checked ? 1 : 0;
        const hidePrice = document.getElementById('hide_stall_price').checked ? 1 : 0;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang lưu...';

        const formData = new FormData();
        formData.append('hide_trader_name', hideTrader);
        formData.append('hide_stall_price', hidePrice);

        fetch('<?php echo BASE_URL; ?>admin/ajax_save_sync_settings', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cài Đặt Hiển Thị';

            if (data.status === 200 || data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã lưu cấu hình hiển thị / ẩn thông tin.',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Cập nhật nhãn trạng thái
                const lblTrader = document.getElementById('lbl_trader_status');
                const lblPrice = document.getElementById('lbl_price_status');
                if (hideTrader) {
                    lblTrader.className = 'text-danger';
                    lblTrader.innerText = 'Đang ẨN (Bảo mật danh tính)';
                } else {
                    lblTrader.className = 'text-success';
                    lblTrader.innerText = 'Đang HIỆN công khai';
                }

                if (hidePrice) {
                    lblPrice.className = 'text-danger';
                    lblPrice.innerText = 'Đang ẨN (Liên hệ Ban Quản Lý)';
                } else {
                    lblPrice.className = 'text-success';
                    lblPrice.innerText = 'Đang HIỆN giá chi tiết';
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: data.message || 'Không thể lưu cài đặt.'
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cài Đặt Hiển Thị';
            Swal.fire({ icon: 'error', title: 'Lỗi kết nối', text: 'Không thể kết nối đến máy chủ.' });
        });
    });

    // 2. XỬ LÝ ĐỒNG BỘ CSDL NGAY (TỰ ĐỘNG DÙNG CẤU HÌNH BACKEND)
    document.getElementById('btnRunSync').addEventListener('click', function() {
        const btn = this;
        const icon = document.getElementById('iconSync');
        const text = document.getElementById('textSync');

        Swal.fire({
            title: 'Xác nhận đồng bộ?',
            text: 'Dữ liệu Chợ, Sạp, Tiểu thương, Ngành hàng và Khu vực từ phần mềm quản lý sẽ được cập nhật sang Website.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0f766e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Đồng ý đồng bộ',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.disabled = true;
                icon.classList.add('fa-spin');
                text.innerText = 'Đang đồng bộ dữ liệu...';

                fetch('<?php echo BASE_URL; ?>admin/ajax_run_sync_db', {
                    method: 'POST'
                })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    icon.classList.remove('fa-spin');
                    text.innerText = 'Đồng Bộ Dữ Liệu Ngay';

                    if (data.success) {
                        document.getElementById('displaySyncTime').innerText = data.time;
                        document.getElementById('displaySyncLog').innerText = data.message;

                        Swal.fire({
                            icon: 'success',
                            title: 'Đồng bộ hoàn tất!',
                            text: data.message,
                            confirmButtonColor: '#0f766e'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Đồng bộ thất bại!',
                            text: data.message || 'Lỗi trong quá trình đồng bộ.'
                        });
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    icon.classList.remove('fa-spin');
                    text.innerText = 'Đồng Bộ Dữ Liệu Ngay';
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra khi thực hiện đồng bộ.' });
                });
            }
        });
    });

});
</script>

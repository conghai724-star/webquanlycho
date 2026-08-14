<!-- Nạp FontAwesome & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Cấu Hình Thông Tin Liên Hệ Website</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Tùy chỉnh thông tin địa chỉ văn phòng, hotline, số điện thoại, email và thời gian hoạt động của Ban Quản Lý Chợ.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?php echo BASE_URL; ?>home/contact" target="_blank" class="btn btn-primary" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-eye"></i> Xem Trang Liên Hệ
        </a>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<div class="card" style="background: var(--bg-surface, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <form action="<?php echo BASE_URL; ?>admin/contact_settings" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- CỘT TRÁI -->
            <div>
                <h5 style="margin-top: 0; margin-bottom: 16px; font-weight: 700; color: var(--primary, #0f766e); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <i class="fa-solid fa-building-columns me-1"></i> Thông Tin Văn Phòng & Trụ Sở
                </h5>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Địa chỉ văn phòng BQL <span style="color:red">*</span></label>
                    <div style="position: relative;">
                        <input type="text" name="settings[contact_office_address]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_office_address'] ?? '123 Đường Nguyễn Huệ, Phường Trung Tâm, Thành phố Quảng Ngãi'); ?>" required placeholder="Ví dụ: 123 Đường Nguyễn Huệ, Phường Trung Tâm...">
                    </div>
                    <small style="color: var(--text-muted); font-size: 11px;">Hiển thị trên trang Liên hệ và chân trang (Footer).</small>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Lời giới thiệu / Thông điệp đầu trang liên hệ</label>
                    <textarea name="settings[contact_intro]" class="form-control" rows="3" placeholder="Nhập lời thông điệp hỗ trợ..."><?php echo htmlspecialchars($settings['contact_intro'] ?? 'Ban Quản lý Chợ Trung Tâm sẵn sàng hỗ trợ người dân và tiểu thương trong giờ làm việc.'); ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Giờ mở cửa & hoạt động của chợ <span style="color:red">*</span></label>
                    <input type="text" name="settings[contact_open_hours]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_open_hours'] ?? '5:00 – 19:00, tất cả các ngày trong tuần'); ?>" required placeholder="Ví dụ: 5:00 – 19:00, tất cả các ngày trong tuần">
                </div>
            </div>

            <!-- CỘT PHẢI -->
            <div>
                <h5 style="margin-top: 0; margin-bottom: 16px; font-weight: 700; color: var(--primary, #0f766e); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <i class="fa-solid fa-phone-volume me-1"></i> Đường Dây Nóng & Kênh Tiếp Nhận
                </h5>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Hotline / Hỗ trợ khẩn cấp <span style="color:red">*</span></label>
                    <input type="text" name="settings[contact_hotline]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_hotline'] ?? '1900 1234 (Từ 7:30 đến 17:00)'); ?>" required placeholder="Ví dụ: 1900 1234 (Từ 7:30 đến 17:00)">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Số điện thoại bàn / Văn phòng</label>
                    <input type="text" name="settings[contact_phone]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? '0255 3822 123'); ?>" placeholder="Ví dụ: 0255 3822 123">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Hộp thư điện tử (Email tiếp nhận) <span style="color:red">*</span></label>
                    <input type="email" name="settings[contact_email]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? 'bqlcho.trungtam@thanhpho.gov.vn'); ?>" required placeholder="Ví dụ: bqlcho.trungtam@thanhpho.gov.vn">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="font-weight: 600; font-size: 13px;">Trang Facebook</label>
                        <input type="text" name="settings[contact_facebook]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_facebook'] ?? ''); ?>" placeholder="https://facebook.com/...">
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="font-weight: 600; font-size: 13px;">Kênh Zalo OA / SĐT</label>
                        <input type="text" name="settings[contact_zalo]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_zalo'] ?? ''); ?>" placeholder="https://zalo.me/...">
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; border-top: 1px solid var(--border-color); padding-top: 20px;">
            <a href="<?php echo BASE_URL; ?>admin/dashboard" class="btn btn-outline">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary" style="padding: 9px 28px; font-weight: 700;">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thông Tin Liên Hệ
            </button>
        </div>
    </form>
</div>

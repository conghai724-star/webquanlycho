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
    <form action="<?php echo BASE_URL; ?>admin/contact_settings" method="POST" enctype="multipart/form-data">
        
        <!-- KHỐI CẤU HÌNH THƯƠNG HIỆU & TÊN CHỢ & LOGO -->
        <div style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 2px dashed var(--border-color);">
            <h5 style="margin-top: 0; margin-bottom: 16px; font-weight: 700; color: var(--primary, #0f766e); padding-bottom: 4px;">
                <i class="fa-solid fa-store me-1"></i> Tên Cổng Thông Tin, Thương Hiệu & Logo Website (Header & Footer)
            </h5>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Tên Chợ / Tên Website Chính <span style="color:red">*</span></label>
                    <input type="text" id="input_website_name" name="settings[website_name]" class="form-control" value="<?php echo htmlspecialchars($settings['website_name'] ?? 'CHỢ TRUNG TÂM THÀNH PHỐ'); ?>" required placeholder="Ví dụ: CHỢ TRUNG TÂM THÀNH PHỐ">
                    <small style="color: var(--text-muted); font-size: 11px;">Hiển thị ở dòng chữ in hoa lớn trên thanh menu đầu trang (Header) và chân trang (Footer).</small>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Đơn vị quản lý / Dòng phụ đề <span style="color:red">*</span></label>
                    <input type="text" id="input_website_subtitle" name="settings[website_subtitle]" class="form-control" value="<?php echo htmlspecialchars($settings['website_subtitle'] ?? 'Ban Quản lý Chợ'); ?>" required placeholder="Ví dụ: Ban Quản lý Chợ hoặc UBND Phường...">
                    <small style="color: var(--text-muted); font-size: 11px;">Hiển thị dòng chữ nhỏ ngay bên dưới tên chợ.</small>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 8px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Logo Biểu Trưng Website</label>
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="flex: 1;">
                        <input type="file" id="website_logo_file" name="website_logo_file" class="form-control" accept="image/*">
                        <input type="hidden" id="input_website_logo" name="settings[website_logo]" value="<?php echo htmlspecialchars($settings['website_logo'] ?? ''); ?>">
                        <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 4px;">Hỗ trợ: PNG, JPG, WEBP, SVG (Khuyên dùng ảnh nền trong suốt, tỉ lệ vuông 1:1).</small>
                    </div>
                    <?php if (!empty($settings['website_logo'])): ?>
                        <div style="display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px;">
                            <img src="<?php echo htmlspecialchars($settings['website_logo']); ?>" alt="Logo" style="width: 32px; height: 32px; object-fit: contain; border-radius: 4px;">
                            <label style="font-size: 12px; color: #dc2626; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; margin: 0;">
                                <input type="checkbox" name="remove_website_logo" value="1"> 
                                <span>Xóa logo (Dùng mặc định)</span>
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

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

        <!-- KHỐI CẤU HÌNH BẢN ĐỒ VỊ TRÍ GOOGLE MAPS (CHỈ ĐƯỜNG) -->
        <div style="margin-top: 24px; padding-top: 20px; border-top: 2px dashed var(--border-color);">
            <h5 style="margin-top: 0; margin-bottom: 16px; font-weight: 700; color: var(--primary, #0f766e); padding-bottom: 4px;">
                <i class="fa-solid fa-map-location-dot me-1"></i> Cấu Hình Bản Đồ Vị Trí / Chỉ Đường Google Maps (Trang Liên Hệ)
            </h5>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 20px;">
                <div>
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label class="form-label" style="font-weight: 600; font-size: 13px;">Dán mã nhúng iframe hoặc link từ Google Maps <span style="color:red">*</span></label>
                        <textarea id="contact_map_iframe" name="settings[contact_map_iframe]" class="form-control" rows="3" placeholder='Dán mã <iframe src="https://www.google.com/maps/embed?..."></iframe> vào đây' style="font-family: monospace; font-size: 12px;" oninput="onIframeInput(this.value)"><?php echo htmlspecialchars($settings['contact_map_iframe'] ?? ''); ?></textarea>
                        <small style="color: var(--text-muted); font-size: 11px;">Mở Google Maps &rarr; Tìm địa điểm &rarr; Bấm "Chia sẻ" &rarr; Chọn "Nhúng bản đồ" &rarr; Copy toàn bộ mã dán vào đây.</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 13px;">Hoặc nhập Địa chỉ tìm kiếm dự phòng</label>
                        <input type="text" id="contact_map_address" name="settings[contact_map_address]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_map_address'] ?? ''); ?>" placeholder="Ví dụ: 49 Ngô Gia Tự, Diên Hồng, Gia Lai" oninput="updateGoogleMapEmbed(this.value)">
                        <small style="color: var(--text-muted); font-size: 11px;">Dùng khi bạn không có mã iframe Google Maps.</small>
                    </div>
                </div>

                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Xem trước Bản đồ hiển thị:</label>
                    <div style="border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; height: 210px; background: #f1f5f9;">
                        <?php 
                        $rawIframe = trim($settings['contact_map_iframe'] ?? '');
                        $cleanMapUrl = '';
                        if (!empty($rawIframe)) {
                            if (preg_match('/src=[\"\']([^\"\']+)[\"\']/i', $rawIframe, $m)) {
                                $cleanMapUrl = $m[1];
                            } elseif (preg_match('/^https?:\/\//i', $rawIframe)) {
                                $cleanMapUrl = $rawIframe;
                            }
                        }
                        if (empty($cleanMapUrl)) {
                            $curMapAddr = !empty($settings['contact_map_address']) ? $settings['contact_map_address'] : ($settings['contact_office_address'] ?? '123 Đường Nguyễn Huệ, Phường Trung Tâm, Thành phố Quảng Ngãi');
                            $cleanMapUrl = 'https://maps.google.com/maps?q=' . urlencode($curMapAddr) . '&t=&z=16&ie=UTF8&iwloc=&output=embed';
                        }
                        ?>
                        <iframe id="googleMapPreviewIframe" width="100%" height="100%" style="border:0;" src="<?php echo htmlspecialchars($cleanMapUrl); ?>" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; border-top: 1px solid var(--border-color); padding-top: 20px;">
            <a href="<?php echo BASE_URL; ?>admin/dashboard" class="btn btn-outline">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary" style="padding: 9px 28px; font-weight: 700;">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cấu Hình & Thông Tin
            </button>
        </div>
    </form>
</div>

<script>
function onIframeInput(val) {
    var raw = (val || '').trim();
    var url = '';
    var match = raw.match(/src=[\"\']([^\"\']+)[\"\']/i);
    if (match && match[1]) {
        url = match[1];
    } else if (/^https?:\/\//i.test(raw)) {
        url = raw;
    } else if (raw.length > 0) {
        url = 'https://maps.google.com/maps?q=' + encodeURIComponent(raw) + '&t=&z=16&ie=UTF8&iwloc=&output=embed';
    } else {
        var addr = (document.getElementById('contact_map_address').value || '').trim();
        url = 'https://maps.google.com/maps?q=' + encodeURIComponent(addr || 'Việt Nam') + '&t=&z=16&ie=UTF8&iwloc=&output=embed';
    }
    var iframe = document.getElementById('googleMapPreviewIframe');
    if (iframe) iframe.src = url;
}

function updateGoogleMapEmbed(val) {
    var iframeInput = (document.getElementById('contact_map_iframe').value || '').trim();
    if (iframeInput) return; // Ưu tiên mã iframe nếu có
    var query = (val || '').trim();
    if (!query) query = 'Việt Nam';
    var iframe = document.getElementById('googleMapPreviewIframe');
    if (iframe) {
        iframe.src = 'https://maps.google.com/maps?q=' + encodeURIComponent(query) + '&t=&z=16&ie=UTF8&iwloc=&output=embed';
    }
}
</script>

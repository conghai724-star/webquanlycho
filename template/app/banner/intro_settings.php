<!-- Nạp FontAwesome & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Cấu Hình Phần Giới Thiệu Chợ (Trang Chủ)</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Tùy chỉnh tiêu đề, hình ảnh minh họa, đoạn mô tả và 5 tiêu chí nổi bật của chợ hiển thị ở khối Giới thiệu trên Trang chủ.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?php echo BASE_URL; ?>#gioithieu" target="_blank" class="btn btn-primary" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-eye"></i> Xem Khối Giới Thiệu
        </a>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<div class="card" style="background: var(--bg-surface, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <form action="<?php echo BASE_URL; ?>admin/intro_settings" method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 24px;">
            <!-- CỘT TRÁI: HÌNH ẢNH & TIÊU ĐỀ CHÍNH -->
            <div>
                <h5 style="margin-top: 0; margin-bottom: 16px; font-weight: 700; color: var(--primary, #0f766e); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <i class="fa-solid fa-image me-1"></i> Hình Ảnh & Thông Điệp Giới Thiệu
                </h5>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Tiêu đề phụ (Eyebrow)</label>
                    <input type="text" name="settings[home_intro_eyebrow]" class="form-control" value="<?php echo htmlspecialchars($settings['home_intro_eyebrow'] ?? 'Giới thiệu chợ'); ?>" required placeholder="Ví dụ: Giới thiệu chợ">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Tiêu đề chính nổi bật <span style="color:red">*</span></label>
                    <input type="text" name="settings[home_intro_title]" class="form-control" value="<?php echo htmlspecialchars($settings['home_intro_title'] ?? 'Hơn 40 năm gắn bó với đời sống người dân thành phố'); ?>" required placeholder="Ví dụ: Hơn 40 năm gắn bó với đời sống người dân...">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Đoạn văn mô tả tổng quan <span style="color:red">*</span></label>
                    <textarea name="settings[home_intro_desc]" class="form-control" rows="4" required placeholder="Nhập đoạn văn mô tả lịch sử, vị trí và ý nghĩa của chợ..."><?php echo htmlspecialchars($settings['home_intro_desc'] ?? 'Hình thành từ năm 1985, chợ Trung Tâm Thành Phố là đầu mối buôn bán sầm uất, quy tụ hàng nghìn tiểu thương thuộc nhiều ngành hàng khác nhau, đồng hành cùng quá trình chuyển đổi số của địa phương.'); ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Hình ảnh minh họa toàn cảnh chợ</label>
                    <div style="margin-bottom: 10px;">
                        <input type="file" name="intro_image_file" class="form-control" accept="image/*" onchange="previewIntroImage(this)">
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">Hoặc dán URL hình ảnh:</div>
                    <input type="text" id="introImageUrlInput" name="settings[home_intro_image]" class="form-control" value="<?php echo htmlspecialchars($settings['home_intro_image'] ?? ''); ?>" placeholder="https://..." oninput="document.getElementById('introPreviewImg').src = this.value">
                    
                    <div style="margin-top: 12px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); max-height: 220px; display: flex; align-items: center; justify-content: center; background: #f8fafc;">
                        <img id="introPreviewImg" src="<?php echo htmlspecialchars($settings['home_intro_image'] ?? 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?q=80&w=1000&auto=format&fit=crop'); ?>" style="width: 100%; height: 200px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1488459716781-31db52582fe9?q=80&w=1000&auto=format&fit=crop'">
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: 5 ĐIỂM NỔI BẬT CỦA CHỢ -->
            <div>
                <h5 style="margin-top: 0; margin-bottom: 16px; font-weight: 700; color: var(--primary, #0f766e); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <i class="fa-solid fa-list-check me-1"></i> 5 Điểm Nổi Bật / Đặc Điểm Của Chợ (Dấu Tích Xanh)
                </h5>

                <?php 
                $points = [
                    1 => ['title' => 'Lịch sử hình thành', 'desc' => 'Xây dựng từ năm 1985, trải qua 3 lần cải tạo, nâng cấp cơ sở hạ tầng.'],
                    2 => ['title' => 'Quy mô', 'desc' => '8 khu vực, 1.240 sạp kinh doanh trên diện tích hơn 12.000m².'],
                    3 => ['title' => 'Vai trò đối với địa phương', 'desc' => 'Đầu mối cung ứng hàng hóa thiết yếu cho hơn 50.000 hộ dân trong khu vực.'],
                    4 => ['title' => 'Ngành hàng kinh doanh', 'desc' => 'Thực phẩm tươi sống, bách hóa, thời trang, ăn uống và dịch vụ.'],
                    5 => ['title' => 'Mục tiêu chuyển đổi số', 'desc' => 'Số hóa 100% sơ đồ sạp và thủ tục đăng ký trực tuyến trong năm 2026.']
                ];
                ?>

                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div style="background: var(--bg-hover, #f8fafc); border: 1px solid var(--border-color); border-radius: 6px; padding: 12px; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span style="background: var(--primary, #0f766e); color: white; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;"><?php echo $i; ?></span>
                            <input type="text" name="settings[home_intro_point_<?php echo $i; ?>_title]" class="form-control" style="font-weight: 600; font-size: 13px;" value="<?php echo htmlspecialchars($settings["home_intro_point_{$i}_title"] ?? $points[$i]['title']); ?>" placeholder="Tiêu đề mục <?php echo $i; ?> (vd: Lịch sử, Quy mô...)">
                        </div>
                        <textarea name="settings[home_intro_point_<?php echo $i; ?>_desc]" class="form-control" rows="2" style="font-size: 13px;" placeholder="Nội dung mô tả ngắn cho mục <?php echo $i; ?>..."><?php echo htmlspecialchars($settings["home_intro_point_{$i}_desc"] ?? $points[$i]['desc']); ?></textarea>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
            <button type="reset" class="btn btn-outline-secondary" style="height: 38px;">
                <i class="fa-solid fa-rotate-left me-1"></i> Khôi phục ban đầu
            </button>
            <button type="submit" class="btn btn-primary" style="height: 38px; min-width: 160px; font-weight: 600;">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cấu Hình
            </button>
        </div>
    </form>
</div>

<script>
function previewIntroImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('introPreviewImg').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

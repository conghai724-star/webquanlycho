<!-- Nạp FontAwesome & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* CSS Switch Toggle chuẩn đẹp cho trạng thái */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 22px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #cbd5e1;
  -webkit-transition: .2s;
  transition: .2s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .2s;
  transition: .2s;
}

input:checked + .slider {
  background-color: #10b981;
}

input:focus + .slider {
  box-shadow: 0 0 1px #10b981;
}

input:checked + .slider:before {
  -webkit-transform: translateX(22px);
  -ms-transform: translateX(22px);
  transform: translateX(22px);
}

.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.filter-tab-btn {
  font-size: 13px;
  padding: 6px 14px;
  border-radius: 6px;
  cursor: pointer;
  border: 1px solid var(--border-color);
  background: var(--bg-surface);
  color: var(--text-color);
  font-weight: 500;
  transition: all 0.2s;
}

.filter-tab-btn.active {
  background: var(--primary, #0f766e);
  color: white;
  border-color: var(--primary, #0f766e);
  font-weight: 600;
}

/* Nav Main Tabs */
.main-feature-tab {
  padding: 10px 20px;
  font-size: 14px;
  font-weight: 600;
  border: none;
  background: transparent;
  color: var(--text-muted, #64748b);
  border-bottom: 3px solid transparent;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s ease;
}

.main-feature-tab:hover {
  color: var(--primary, #0f766e);
}

.main-feature-tab.active {
  color: var(--primary, #0f766e);
  border-bottom-color: var(--primary, #0f766e);
}
</style>

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Quản Lý Banner & Giới Thiệu Chợ</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Quản lý hình ảnh banner quảng cáo và thông tin giới thiệu hoạt động của Chợ trên Trang chủ.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?php echo BASE_URL; ?>#gioithieu" target="_blank" class="btn btn-outline" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-eye"></i> Xem Khối Giới Thiệu
        </a>
        <button id="addBannerBtnTop" class="btn btn-primary" onclick="openBannerModal()" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus"></i> Thêm Banner Mới
        </button>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<!-- THANH TAB CHUYỂN ĐỔI CHÍNH -->
<div style="border-bottom: 1px solid var(--border-color, #e2e8f0); margin-bottom: 20px; display: flex; gap: 8px;">
    <button type="button" class="main-feature-tab <?php echo ($activeTab ?? 'banners') !== 'intro' ? 'active' : ''; ?>" onclick="switchMainTab('banners', this)">
        <i class="fa-solid fa-images"></i> 1. Danh Sách Banner Quảng Cáo (<?php echo count($banners ?? []); ?>)
    </button>
    <button type="button" class="main-feature-tab <?php echo ($activeTab ?? 'banners') === 'intro' ? 'active' : ''; ?>" onclick="switchMainTab('intro', this)">
        <i class="fa-solid fa-circle-info"></i> 2. Cấu Hình Giới Thiệu Chợ (Trang Chủ)
    </button>
</div>

<!-- ========================================================================= -->
<!-- PHẦN 1: QUẢN LÝ DANH SÁCH BANNER QUẢNG CÁO -->
<!-- ========================================================================= -->
<div id="tabContentBanners" style="<?php echo ($activeTab ?? 'banners') === 'intro' ? 'display: none;' : 'display: block;'; ?>">
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div class="card-title" style="font-size: 15px; font-weight: 600; margin: 0;">Danh sách Banner (<?php echo count($banners ?? []); ?>)</div>
            
            <!-- BỘ LỌC VỊ TRÍ TRỰC QUAN -->
            <div style="display: flex; gap: 6px;">
                <button type="button" class="filter-tab-btn active" onclick="filterBanners('all', this)">Tất cả vị trí</button>
                <button type="button" class="filter-tab-btn" onclick="filterBanners('home', this)"><i class="fa-solid fa-house me-1"></i> Trang chủ</button>
                <button type="button" class="filter-tab-btn" onclick="filterBanners('about', this)"><i class="fa-solid fa-building-user me-1"></i> Giới thiệu BQL Chợ</button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 12px 16px; width: 120px;">Hình ảnh</th>
                            <th style="padding: 12px 16px;">Tiêu đề Banner</th>
                            <th style="padding: 12px 16px; width: 180px;">Vị trí trang</th>
                            <th style="padding: 12px 16px; width: 90px; text-align: center;">Thứ tự</th>
                            <th style="padding: 12px 16px; width: 110px; text-align: center;">Hiển thị</th>
                            <th style="padding: 12px 16px; text-align: right; width: 110px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($banners)): ?>
                            <?php foreach ($banners as $b): ?>
                                <?php $bPage = $b['banner_page'] ?? 'home'; ?>
                                <tr class="banner-row" data-page="<?php echo htmlspecialchars($bPage); ?>" style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 12px 16px;">
                                        <img src="<?php echo htmlspecialchars($b['banner_image']); ?>" alt="Banner" style="width: 110px; height: 55px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color); background: #f8fafc;" onerror="this.src='https://placehold.co/110x55?text=No+Image'">
                                    </td>
                                    <td style="padding: 12px 16px;">
                                        <div style="font-weight: 600; color: var(--text-heading); font-size: 14px;"><?php echo htmlspecialchars($b['banner_title']); ?></div>
                                        <?php if (!empty($b['banner_description'])): ?>
                                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 3px; max-width: 450px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?php echo htmlspecialchars($b['banner_description']); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($b['banner_link'])): ?>
                                            <div style="font-size: 11px; color: var(--primary); margin-top: 3px;">
                                                <i class="fa-solid fa-link me-1"></i> <a href="<?php echo htmlspecialchars($b['banner_link']); ?>" target="_blank" style="color: inherit; text-decoration: none;"><?php echo htmlspecialchars($b['banner_link']); ?></a>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px 16px;">
                                        <?php if ($bPage === 'about'): ?>
                                            <span class="badge" style="background-color: rgba(59, 130, 246, 0.1); color: #2563eb; border: 1px solid rgba(59, 130, 246, 0.2); padding: 4px 8px; border-radius: 4px; font-weight: 500;">
                                                <i class="fa-solid fa-building-user me-1"></i> Giới thiệu BQL Chợ
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="background-color: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); padding: 4px 8px; border-radius: 4px; font-weight: 500;">
                                                <i class="fa-solid fa-house me-1"></i> Trang chủ
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px 16px; text-align: center; font-weight: 600;">
                                        <?php echo (int)($b['banner_order'] ?? 1); ?>
                                    </td>
                                    <td style="padding: 12px 16px; text-align: center;">
                                        <label class="switch">
                                            <input type="checkbox" onchange="toggleBannerStatus(<?php echo (int)$b['id']; ?>, this.checked)" <?php echo ($b['banner_status'] == 1) ? 'checked' : ''; ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td style="padding: 12px 16px; text-align: right;">
                                        <div style="display: inline-flex; gap: 4px;">
                                            <button type="button" class="btn btn-sm btn-outline-primary" style="padding: 4px 8px; border-radius: 4px;" onclick='editBanner(<?php echo json_encode($b, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)' title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" style="padding: 4px 8px; border-radius: 4px;" onclick="confirmSoftDelete('<?php echo BASE_URL; ?>admin/banner_delete?id=<?php echo (int)$b['id']; ?>', '<?php echo htmlspecialchars(addslashes($b['banner_title'])); ?>', 'banner')" title="Xóa banner">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="padding: 40px 16px; text-align: center; color: var(--text-muted);">
                                    <i class="fa-solid fa-images" style="font-size: 32px; margin-bottom: 10px; opacity: 0.4;"></i>
                                    <div>Chưa có banner nào. Hãy bấm "Thêm Banner Mới" để bắt đầu!</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- PHẦN 2: CẤU HÌNH GIỚI THIỆU CHỢ TRANG CHỦ -->
<!-- ========================================================================= -->
<div id="tabContentIntro" style="<?php echo ($activeTab ?? 'banners') === 'intro' ? 'display: block;' : 'display: none;'; ?>">
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

                <!-- CỘT PHẢI: CÁC ĐIỂM NỔI BẬT CỦA CHỢ -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                        <h5 style="margin: 0; font-weight: 700; color: var(--primary, #0f766e);">
                            <i class="fa-solid fa-list-check me-1"></i> Các Điểm Nổi Bật Của Chợ (Dấu Tích Xanh)
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addIntroPoint()" style="font-size: 12px; font-weight: 600; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fa-solid fa-plus"></i> Thêm Tiêu Chí
                        </button>
                    </div>

                    <?php 
                    $introPoints = [];
                    if (!empty($settings['home_intro_points'])) {
                        $introPoints = json_decode($settings['home_intro_points'], true) ?: [];
                    }
                    if (empty($introPoints)) {
                        for ($i = 1; $i <= 10; $i++) {
                            $pTitle = $settings["home_intro_point_{$i}_title"] ?? '';
                            $pDesc = $settings["home_intro_point_{$i}_desc"] ?? '';
                            if (!empty($pTitle) || !empty($pDesc)) {
                                $introPoints[] = ['title' => $pTitle, 'desc' => $pDesc];
                            }
                        }
                    }
                    if (empty($introPoints)) {
                        $introPoints = [
                            ['title' => 'Lịch sử hình thành', 'desc' => 'Xây dựng từ năm 1985, trải qua 3 lần cải tạo, nâng cấp cơ sở hạ tầng.'],
                            ['title' => 'Quy mô', 'desc' => '8 khu vực, 1.240 sạp kinh doanh trên diện tích hơn 12.000m².'],
                            ['title' => 'Vai trò đối với địa phương', 'desc' => 'Đầu mối cung ứng hàng hóa thiết yếu cho hơn 50.000 hộ dân trong khu vực.'],
                            ['title' => 'Ngành hàng kinh doanh', 'desc' => 'Thực phẩm tươi sống, bách hóa, thời trang, ăn uống và dịch vụ.'],
                            ['title' => 'Mục tiêu chuyển đổi số', 'desc' => 'Số hóa 100% sơ đồ sạp và thủ tục đăng ký trực tuyến trong năm 2026.']
                        ];
                    }
                    ?>

                    <div id="introPointsList">
                        <?php foreach ($introPoints as $idx => $pt): ?>
                            <div class="intro-point-item" style="background: var(--bg-hover, #f8fafc); border: 1px solid var(--border-color); border-radius: 6px; padding: 12px; margin-bottom: 12px; position: relative;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                                    <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                                        <span class="point-index-badge" style="background: var(--primary, #0f766e); color: white; border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;"><?php echo $idx + 1; ?></span>
                                        <input type="text" name="intro_points[<?php echo $idx; ?>][title]" class="form-control" style="font-weight: 600; font-size: 13px;" value="<?php echo htmlspecialchars($pt['title'] ?? ''); ?>" placeholder="Tiêu đề tiêu chí (vd: Lịch sử, Quy mô,...)">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeIntroPoint(this)" style="padding: 4px 8px; font-size: 12px; height: 32px;" title="Xóa tiêu chí này">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                                <textarea name="intro_points[<?php echo $idx; ?>][desc]" class="form-control" rows="2" style="font-size: 13px;" placeholder="Nội dung mô tả ngắn cho tiêu chí..."><?php echo htmlspecialchars($pt['desc'] ?? ''); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary" onclick="addIntroPoint()" style="width: 100%; margin-top: 4px; padding: 8px; font-size: 13px; font-weight: 600; border-style: dashed; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="fa-solid fa-circle-plus"></i> Thêm Tiêu Chí Nổi Bật Mới
                    </button>
                </div>
            </div>

            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
                <button type="reset" class="btn btn-outline-secondary" style="height: 38px;">
                    <i class="fa-solid fa-rotate-left me-1"></i> Khôi phục ban đầu
                </button>
                <button type="submit" class="btn btn-primary" style="height: 38px; min-width: 160px; font-weight: 600;">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cấu Hình Giới Thiệu
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL THÊM / CHỈNH SỬA BANNER QUẢNG CÁO -->
<!-- ========================================================================= -->
<div id="bannerModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-surface, #ffffff); width: 100%; max-width: 580px; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2); animation: fadeIn 0.2s;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h5 id="modalTitle" style="margin: 0; font-weight: 700;">Thêm Banner Mới</h5>
            <button type="button" onclick="closeBannerModal()" style="border: none; background: transparent; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form id="bannerForm" action="<?php echo BASE_URL; ?>admin/banner_add" method="POST" enctype="multipart/form-data" style="padding: 20px;">
            <input type="hidden" id="banner_id" name="id" value="">

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Tiêu đề Banner <span style="color:red">*</span></label>
                <input type="text" id="banner_title" name="banner_title" class="form-control" required placeholder="Nhập tiêu đề hoặc tên chiến dịch...">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Đoạn văn mô tả ngắn</label>
                <textarea id="banner_description" name="banner_description" class="form-control" rows="2" placeholder="Mô tả phụ ngắn hiển thị kèm trên banner..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Vị trí hiển thị <span style="color:red">*</span></label>
                    <select id="banner_page" name="banner_page" class="form-control" required>
                        <option value="home">Trang chủ (Homepage)</option>
                        <option value="about">Trang Giới thiệu BQL Chợ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Thứ tự hiển thị</label>
                    <input type="number" id="banner_order" name="banner_order" class="form-control" value="1" min="1">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Liên kết khi bấm vào (Link)</label>
                <input type="text" id="banner_link" name="banner_link" class="form-control" placeholder="Ví dụ: home/register hoặc https://quangngai.gov.vn">
                <small style="color: var(--text-muted); font-size: 11px;">Nhập đường dẫn nội bộ (như <code>home/register</code>, <code>home/map</code>, <code>home/posts</code>) hoặc link ngoài (<code>https://...</code>)</small>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Tải tệp ảnh trực tiếp</label>
                <input type="file" id="banner_file" name="banner_file" class="form-control" accept="image/*" onchange="previewBannerImage(this)">
                <small style="color: var(--text-muted); font-size: 11px;">Hỗ trợ định dạng: JPG, PNG, WEBP, GIF, SVG (Khuyên dùng tỉ lệ 16:9 hoặc 21:9)</small>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Hoặc nhập URL hình ảnh Online</label>
                <input type="text" id="banner_image" name="banner_image" class="form-control" placeholder="https://..." oninput="updatePreviewFromUrl(this.value)">
            </div>

            <div id="imagePreviewContainer" style="display: none; margin-bottom: 16px; border: 1px solid var(--border-color); border-radius: 6px; padding: 6px; text-align: center; background: #f8fafc;">
                <img id="bannerPreviewImg" src="" alt="Preview" style="max-height: 140px; max-width: 100%; border-radius: 4px; object-fit: contain;">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Trạng thái</label>
                <select id="banner_status" name="banner_status" class="form-control">
                    <option value="1">Hiển thị ngay</option>
                    <option value="0">Tạm ẩn</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary" onclick="closeBannerModal()">Đóng</button>
                <button type="submit" class="btn btn-primary">Lưu Banner</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchMainTab(tabKey, btn) {
    document.querySelectorAll('.main-feature-tab').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');

    if (tabKey === 'banners') {
        document.getElementById('tabContentBanners').style.display = 'block';
        document.getElementById('tabContentIntro').style.display = 'none';
        document.getElementById('addBannerBtnTop').style.display = 'inline-flex';
    } else {
        document.getElementById('tabContentBanners').style.display = 'none';
        document.getElementById('tabContentIntro').style.display = 'block';
        document.getElementById('addBannerBtnTop').style.display = 'none';
    }
}

function filterBanners(page, btn) {
    document.querySelectorAll('.filter-tab-btn').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');

    const rows = document.querySelectorAll('.banner-row');
    rows.forEach(row => {
        const rowPage = row.getAttribute('data-page');
        if (page === 'all' || rowPage === page) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function toggleBannerStatus(bannerId, isChecked) {
    const status = isChecked ? 1 : 0;
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>admin/banner_toggle',
        type: 'POST',
        data: { id: bannerId, status: status },
        dataType: 'json',
        success: function(res) {
            if (res.status === 200) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Đã cập nhật trạng thái banner!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: res.message || 'Cập nhật thất bại',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        },
        error: function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Lỗi kết nối máy chủ',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
}

function openBannerModal() {
    document.getElementById('modalTitle').innerText = 'Thêm Banner Mới';
    document.getElementById('bannerForm').action = '<?php echo BASE_URL; ?>admin/banner_add';
    document.getElementById('banner_id').value = '';
    document.getElementById('banner_title').value = '';
    document.getElementById('banner_description').value = '';
    document.getElementById('banner_image').value = '';
    document.getElementById('banner_file').value = '';
    document.getElementById('banner_link').value = '';
    document.getElementById('banner_page').value = 'home';
    document.getElementById('banner_order').value = '1';
    document.getElementById('banner_status').value = '1';
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('bannerModal').style.display = 'flex';
}

function closeBannerModal() {
    document.getElementById('bannerModal').style.display = 'none';
}

function editBanner(b) {
    document.getElementById('modalTitle').innerText = 'Chỉnh Sửa Banner';
    document.getElementById('bannerForm').action = '<?php echo BASE_URL; ?>admin/banner_edit';
    document.getElementById('banner_id').value = b.id;
    document.getElementById('banner_title').value = b.banner_title;
    document.getElementById('banner_description').value = b.banner_description || '';
    document.getElementById('banner_image').value = b.banner_image;
    document.getElementById('banner_file').value = '';
    document.getElementById('banner_link').value = b.banner_link || '';
    document.getElementById('banner_page').value = b.banner_page || 'home';
    document.getElementById('banner_order').value = b.banner_order || 1;
    document.getElementById('banner_status').value = b.banner_status || 1;

    if (b.banner_image) {
        updatePreviewFromUrl(b.banner_image);
    } else {
        document.getElementById('imagePreviewContainer').style.display = 'none';
    }

    document.getElementById('bannerModal').style.display = 'flex';
}

function previewBannerImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('bannerPreviewImg').src = e.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updatePreviewFromUrl(url) {
    if (url && url.trim().length > 5) {
        document.getElementById('bannerPreviewImg').src = url.trim();
        document.getElementById('imagePreviewContainer').style.display = 'block';
    } else {
        document.getElementById('imagePreviewContainer').style.display = 'none';
    }
}

function previewIntroImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('introPreviewImg').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function addIntroPoint() {
    var container = document.getElementById('introPointsList');
    var currentItems = container.querySelectorAll('.intro-point-item');
    var newIdx = currentItems.length;

    var itemHtml = `
        <div class="intro-point-item" style="background: var(--bg-hover, #f8fafc); border: 1px solid var(--border-color); border-radius: 6px; padding: 12px; margin-bottom: 12px; position: relative; animation: fadeIn 0.2s;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <span class="point-index-badge" style="background: var(--primary, #0f766e); color: white; border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;">${newIdx + 1}</span>
                    <input type="text" name="intro_points[${newIdx}][title]" class="form-control" style="font-weight: 600; font-size: 13px;" placeholder="Tiêu đề tiêu chí mới...">
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeIntroPoint(this)" style="padding: 4px 8px; font-size: 12px; height: 32px;" title="Xóa tiêu chí này">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
            <textarea name="intro_points[${newIdx}][desc]" class="form-control" rows="2" style="font-size: 13px;" placeholder="Nội dung mô tả ngắn cho tiêu chí..."></textarea>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', itemHtml);
    renumberIntroPoints();
}

function removeIntroPoint(btn) {
    var item = btn.closest('.intro-point-item');
    if (item) {
        item.remove();
        renumberIntroPoints();
    }
}

function renumberIntroPoints() {
    var items = document.querySelectorAll('#introPointsList .intro-point-item');
    items.forEach(function(el, index) {
        var badge = el.querySelector('.point-index-badge');
        if (badge) badge.innerText = (index + 1);

        var titleInput = el.querySelector('input[type="text"]');
        if (titleInput) titleInput.name = `intro_points[${index}][title]`;

        var descTextarea = el.querySelector('textarea');
        if (descTextarea) descTextarea.name = `intro_points[${index}][desc]`;
    });
}
</script>

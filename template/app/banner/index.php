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
</style>

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Quản Lý Banner Quảng Cáo & Truyền Thông</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Quản lý hình ảnh banner hiển thị tại Trang chủ và Trang giới thiệu Ban Quản Lý Chợ.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?php echo BASE_URL; ?>admin/about_settings" class="btn btn-outline" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-pen-to-square"></i> Sửa Bài Giới Thiệu Chợ
        </a>
        <button class="btn btn-primary" onclick="openBannerModal()" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus"></i> Thêm Banner Mới
        </button>
    </div>

</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div class="card-title" style="font-size: 15px; font-weight: 600; margin: 0;">Danh sách Banner (<?php echo count($banners ?? []); ?>)</div>
        
        <!-- BỘ LỌC VỊ TRÍ HÀNG VĂN TRỰC QUAN -->
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
                                    <img src="<?php echo htmlspecialchars($b['banner_image']); ?>" alt="Banner" style="width: 110px; height: 55px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color); background: #f8fafc;">
                                </td>
                                <td style="padding: 12px 16px; font-weight: 600; color: var(--text-heading);">
                                    <div style="font-size: 14px;"><?php echo htmlspecialchars($b['banner_title']); ?></div>
                                    <?php if (!empty($b['banner_description'])): ?>
                                        <div style="font-weight: 400; color: var(--text-muted); font-size: 11px; margin-top: 2px; line-height: 1.3;">
                                            <i class="fa-solid fa-align-left me-1" style="font-size: 10px;"></i><?php echo htmlspecialchars($b['banner_description']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <small style="font-weight: 400; color: #64748b; display: block; margin-top: 2px;">
                                        <i class="fa-solid fa-link" style="font-size:10px;"></i> <?php echo htmlspecialchars($b['banner_link'] ?: 'Chuyển tới sơ đồ chợ'); ?>
                                    </small>
                                </td>

                                <td style="padding: 12px 16px;">
                                    <?php 
                                    if ($bPage === 'home') {
                                        echo '<span class="chip" style="background: rgba(15, 118, 110, 0.1); color: var(--primary, #0f766e); font-weight: 600;"><i class="fa-solid fa-house me-1"></i> Trang chủ</span>';
                                    } elseif ($bPage === 'about') {
                                        echo '<span class="chip" style="background: rgba(59, 130, 246, 0.1); color: #1d4ed8; font-weight: 600;"><i class="fa-solid fa-building-user me-1"></i> Giới thiệu BQL Chợ</span>';
                                    } else {
                                        echo '<span class="chip" style="background: rgba(139, 92, 246, 0.1); color: #7c3aed; font-weight: 600;"><i class="fa-solid fa-globe me-1"></i> Tất cả các trang</span>';
                                    }
                                    ?>
                                </td>
                                <td style="padding: 12px 16px; font-weight: 700; text-align: center;">
                                    <?php echo (int)($b['banner_order'] ?? 0); ?>
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <label class="switch">
                                        <input type="checkbox" <?php echo (($b['banner_status'] ?? 1) == 1) ? 'checked' : ''; ?> onchange="toggleBannerStatus(<?php echo $b['id']; ?>, this.checked)">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td style="padding: 12px 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                        <button type="button" onclick='editBanner(<?php echo json_encode($b, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' class="btn btn-outline btn-sm" style="padding: 4px 8px; font-size: 11px; color: #1976d2;" title="Chỉnh sửa">
                                            <i class="fa-solid fa-pen"></i> Sửa
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>admin/banner_delete/<?php echo $b['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?');" class="btn btn-outline btn-sm" style="padding: 4px 8px; font-size: 11px; color: #d32f2f;" title="Xóa">
                                            <i class="fa-solid fa-trash"></i> Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">Chưa có banner nào được khởi tạo.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa Banner Hỗ Trợ Tải Ảnh Từ Máy Tính -->
<div id="bannerModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-surface, #ffffff); width: 100%; max-width: 520px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
            <h5 id="modalTitle" style="margin: 0; font-weight: 700; color: var(--text-heading);">Thêm Banner Mới</h5>
            <button type="button" onclick="closeBannerModal()" style="background:none; border:none; cursor:pointer; font-size:18px; color:var(--text-muted);">&times;</button>
        </div>

        <form id="bannerForm" action="<?php echo BASE_URL; ?>admin/banner_add" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="banner_id" name="id" value="">
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600;">Tiêu đề Banner <span style="color:red">*</span></label>
                <input type="text" id="banner_title" name="banner_title" class="form-control" required placeholder="Ví dụ: Chào mừng đến với Chợ Tỉnh Quảng Ngãi">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600;">Mô tả ngắn / Phụ đề Banner</label>
                <textarea id="banner_description" name="banner_description" class="form-control" rows="2" placeholder="Ví dụ: Tra cứu sơ đồ chợ, tìm kiếm vị trí sạp, xem thông tin tiểu thương và đăng ký thuê sạp trực tuyến — nhanh chóng, minh bạch, mọi lúc mọi nơi."></textarea>
            </div>


            <!-- TẢI ẢNH TỪ MÁY TÍNH HOẶC NHẬP URL -->
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600;">Chọn tệp ảnh từ máy tính (Upload File)</label>
                <input type="file" id="banner_file" name="banner_file" class="form-control" accept="image/*" onchange="previewBannerImage(this)">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600;">hoặc Đường dẫn URL hình ảnh ngoài</label>
                <input type="text" id="banner_image" name="banner_image" class="form-control" placeholder="https://images.unsplash.com/photo-..." oninput="updatePreviewFromUrl(this.value)">
            </div>

            <!-- Khung Xem Trước Ảnh Banner -->
            <div id="imagePreviewContainer" style="margin-bottom: 16px; display: none; text-align: center; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px dashed #cbd5e1;">
                <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 6px;">Xem trước hình ảnh:</div>
                <img id="bannerPreviewImg" src="" alt="Preview" style="max-width: 100%; max-height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600;">Đường dẫn khi nhấp chuột (Link URL)</label>
                <input type="text" id="banner_link" name="banner_link" class="form-control" placeholder="http://localhost/quanlycho.vn/home/map">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">Vị trí trang hiển thị</label>
                    <select id="banner_page" name="banner_page" class="form-control">
                        <option value="home">Trang chủ (Homepage)</option>
                        <option value="about">Giới thiệu BQL Chợ</option>
                        <option value="all">Tất cả các trang</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">Thứ tự ưu tiên</label>
                    <input type="number" id="banner_order" name="banner_order" class="form-control" value="1" min="1">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="font-weight: 600;">Trạng thái ban đầu</label>
                <select id="banner_status" name="banner_status" class="form-control">
                    <option value="1">Hiển thị công khai</option>
                    <option value="0">Tạm ẩn</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                <button type="button" onclick="closeBannerModal()" class="btn btn-outline">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu Banner</button>
            </div>
        </form>
    </div>
</div>

<script>
// Lọc danh sách banner theo Vị trí trang
function filterBanners(pageCode, btnEl) {
    document.querySelectorAll('.filter-tab-btn').forEach(b => b.classList.remove('active'));
    if (btnEl) btnEl.classList.add('active');

    document.querySelectorAll('.banner-row').forEach(row => {
        var p = row.getAttribute('data-page') || 'home';
        if (pageCode === 'all' || p === pageCode || p === 'all') {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Bật / tắt trạng thái hiển thị bằng AJAX
function toggleBannerStatus(bannerId, isChecked) {
    $.ajax({
        type: 'POST',
        url: '<?php echo BASE_URL; ?>admin/update_banner_status',
        data: JSON.stringify({
            id: bannerId,
            status: isChecked ? 1 : 0
        }),
        contentType: 'application/json',
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
</script>

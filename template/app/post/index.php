<!-- Nạp FontAwesome & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Switch Toggle Style */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #cbd5e1;
  transition: .25s;
  border-radius: 24px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .25s;
  border-radius: 50%;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
input:checked + .slider {
  background-color: var(--primary, #0f766e);
}
input:checked + .slider:before {
  transform: translateX(20px);
}
</style>

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Quản Lý Tin Tức & Thông Báo BQL</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Đăng tải, chỉnh sửa tin tức, bài viết hướng dẫn và thông báo chính thức trên cổng thông tin chợ.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?php echo BASE_URL; ?>home/posts" target="_blank" class="btn btn-outline" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-eye"></i> Xem Trang Tin Tức
        </a>
        <a href="<?php echo BASE_URL; ?>admin/post_add" class="btn btn-primary" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus"></i> Thêm Bài Viết Mới
        </a>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div class="card-title" style="font-size: 15px; font-weight: 600; margin: 0;">Danh sách Bài viết (<?php echo count($posts ?? []); ?>)</div>
        
        <!-- Bộ lọc và tìm kiếm -->
        <form action="<?php echo BASE_URL; ?>admin/posts" method="GET" style="display: flex; gap: 8px; align-items: center;">
            <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword ?? ''); ?>" class="form-control form-control-sm" placeholder="Tìm theo tiêu đề..." style="width: 200px; height: 32px;">
            <select name="status" class="form-control form-control-sm" style="width: 140px; height: 32px;" onchange="this.form.submit()">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="1" <?php echo ($statusFilter === '1') ? 'selected' : ''; ?>>🟢 Đã xuất bản</option>
                <option value="0" <?php echo ($statusFilter === '0') ? 'selected' : ''; ?>>⚪ Bản nháp</option>
            </select>
            <button type="submit" class="btn btn-outline btn-sm" style="height: 32px;"><i class="fa-solid fa-magnifying-glass"></i></button>
            <?php if (!empty($keyword) || $statusFilter !== ''): ?>
                <a href="<?php echo BASE_URL; ?>admin/posts" class="btn btn-outline btn-sm" style="height: 32px; color: #dc2626;" title="Xóa bộ lọc"><i class="fa-solid fa-xmark"></i></a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 12px 16px; width: 80px;">Hình ảnh</th>
                        <th style="padding: 12px 16px;">Tiêu đề & Tóm tắt bài viết</th>
                        <th style="padding: 12px 16px; width: 140px;">Ngày đăng</th>
                        <th style="padding: 12px 16px; width: 140px; text-align: center;">Trạng thái</th>
                        <th style="padding: 12px 16px; text-align: right; width: 150px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $p): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 14px 16px;">
                                    <?php 
                                    $img = !empty($p['post_image']) ? $p['post_image'] : 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=800';
                                    ?>
                                    <div style="width: 60px; height: 42px; border-radius: 6px; overflow: hidden; background: #e2e8f0; border: 1px solid var(--border-color);">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 700; color: var(--text-heading); font-size: 14px; margin-bottom: 4px;">
                                        <a href="<?php echo BASE_URL; ?>home/post_detail/<?php echo $p['post_slug']; ?>" target="_blank" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='inherit'">
                                            <?php echo htmlspecialchars($p['post_title']); ?> <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px; opacity: 0.6;"></i>
                                        </a>
                                    </div>
                                    <div style="color: var(--text-muted); font-size: 12px; max-width: 480px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?php echo htmlspecialchars($p['post_summary'] ?: 'Chưa có tóm tắt...'); ?>
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; color: var(--text-muted); font-size: 12px;">
                                    <i class="fa-regular fa-calendar me-1"></i><?php echo date('d/m/Y H:i', strtotime($p['created_at'])); ?>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <label class="switch" title="Bật / Tắt hiển thị trên website">
                                        <input type="checkbox" onchange="togglePostStatus(<?php echo $p['id']; ?>, this)" <?php echo ($p['post_status'] == 1) ? 'checked' : ''; ?>>
                                        <span class="slider"></span>
                                    </label>
                                    <div style="font-size: 11px; margin-top: 2px; color: <?php echo ($p['post_status'] == 1) ? '#0f766e' : '#94a3b8'; ?>; font-weight: 600;">
                                        <?php echo ($p['post_status'] == 1) ? 'Xuất bản' : 'Bản nháp'; ?>
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                        <a href="<?php echo BASE_URL; ?>admin/post_edit/<?php echo $p['id']; ?>" class="btn btn-outline btn-sm" title="Chỉnh sửa">
                                            <i class="fa-solid fa-pen-to-square"></i> Sửa
                                        </a>
                                        <button type="button" onclick="confirmSoftDelete('<?php echo BASE_URL; ?>admin/post_delete/<?php echo $p['id']; ?>', '<?php echo htmlspecialchars(addslashes($p['post_title'])); ?>', 'bài viết')" class="btn btn-outline btn-sm" style="color: #dc2626; border-color: #fca5a5;" title="Xóa bài viết">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align: center; color: var(--text-muted);">
                                <i class="fa-solid fa-newspaper" style="font-size: 32px; opacity: 0.3; margin-bottom: 10px; display: block;"></i>
                                Chưa có bài viết nào trong hệ thống. Hãy bấm <b>"Thêm Bài Viết Mới"</b> để đăng tải tin tức.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function togglePostStatus(id, checkbox) {
    var newStatus = checkbox.checked ? 1 : 0;
    fetch('<?php echo BASE_URL; ?>admin/post_toggle/' + id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(newStatus)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 200) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: newStatus ? 'Đã xuất bản bài viết' : 'Đã chuyển thành bản nháp',
                showConfirmButton: false,
                timer: 2000
            });
            var label = checkbox.parentElement.nextElementSibling;
            if (label) {
                label.innerText = newStatus ? 'Xuất bản' : 'Bản nháp';
                label.style.color = newStatus ? '#0f766e' : '#94a3b8';
            }
        } else {
            checkbox.checked = !checkbox.checked;
            alert(data.message || 'Lỗi khi cập nhật trạng thái.');
        }
    })
    .catch(err => {
        checkbox.checked = !checkbox.checked;
        alert('Lỗi kết nối máy chủ.');
    });
}
</script>

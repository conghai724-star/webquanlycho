<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Quản Lý Khiếu Nại & Góp Ý</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Tiếp nhận phản ánh, khiếu nại và góp ý từ tiểu thương và người dân.</p>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div class="card-title" style="font-size: 15px; font-weight: 600; margin: 0;">Danh sách Phản ánh (<?php echo count($feedbacks ?? []); ?>)</div>
        <div style="display: flex; gap: 8px;">
            <a href="<?php echo BASE_URL; ?>admin/feedbacks" class="btn btn-outline btn-sm">Tất cả</a>
            <a href="<?php echo BASE_URL; ?>admin/feedbacks?type=complaint" class="btn btn-outline btn-sm" style="color: #d32f2f;">Khiếu nại</a>
            <a href="<?php echo BASE_URL; ?>admin/feedbacks?type=feedback" class="btn btn-outline btn-sm" style="color: #1976d2;">Góp ý</a>
            <a href="<?php echo BASE_URL; ?>admin/feedbacks?type=other" class="btn btn-outline btn-sm" style="color: #7c3aed;">Ý kiến khác</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 12px 16px; width: 130px;">Phân loại</th>
                        <th style="padding: 12px 16px;">Tiêu đề & Nội dung phản ánh</th>
                        <th style="padding: 12px 16px;">Người gửi</th>
                        <th style="padding: 12px 16px; width: 140px;">Trạng thái</th>
                        <th style="padding: 12px 16px; width: 140px;">Thời gian gửi</th>
                        <th style="padding: 12px 16px; text-align: right; width: 140px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $f): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 14px 16px;">
                                    <?php 
                                    $tp = $f['type'] ?? 'feedback';
                                    if ($tp === 'complaint'): ?>
                                        <span class="chip" style="background: rgba(211, 47, 47, 0.1); color: #d32f2f; border: 1px solid rgba(211, 47, 47, 0.2); font-weight: 600;">Khiếu nại</span>
                                    <?php elseif ($tp === 'other'): ?>
                                        <span class="chip" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed; border: 1px solid rgba(124, 58, 237, 0.2); font-weight: 600;">Ý kiến khác</span>
                                    <?php else: ?>
                                        <span class="chip" style="background: rgba(25, 118, 210, 0.1); color: #1976d2; border: 1px solid rgba(25, 118, 210, 0.2); font-weight: 600;">Góp ý</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 600; color: var(--text-heading);"><?php echo htmlspecialchars($f['title'] ?: 'Không tiêu đề'); ?></div>
                                    <div style="color: var(--text-muted); font-size: 12px; max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?php echo htmlspecialchars($f['content'] ?? $f['fb_content'] ?? ''); ?>
                                    </div>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 600;"><?php echo htmlspecialchars($f['fullname'] ?? $f['fb_fullname'] ?? 'Ẩn danh'); ?></div>
                                    <small style="color: var(--primary);"><?php echo htmlspecialchars($f['phone'] ?? $f['fb_phone'] ?? ''); ?></small>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <?php 
                                    $st = $f['status'] ?? ($f['fb_status'] == 1 ? 'new' : 'resolved');
                                    if ($st === 'new' || $st == 1) echo '<span class="status" style="background: rgba(245, 124, 0, 0.1); color: #f57c00; padding: 4px 8px; border-radius: 4px;">Mới tiếp nhận</span>';
                                    elseif ($st === 'processing') echo '<span class="status" style="background: rgba(25, 118, 210, 0.1); color: #1976d2; padding: 4px 8px; border-radius: 4px;">Đang xử lý</span>';
                                    else echo '<span class="status" style="background: rgba(56, 142, 60, 0.1); color: #388e3c; padding: 4px 8px; border-radius: 4px;">Đã xử lý</span>';
                                    ?>
                                </td>
                                <td style="padding: 14px 16px; color: var(--text-muted); font-size: 12px;">
                                    <?php echo date('d/m/Y H:i', strtotime($f['created_at'])); ?>
                                </td>
                                <td style="padding: 14px 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                        <button onclick='viewFeedbackDetail(<?php echo json_encode($f, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' class="btn btn-outline btn-sm" title="Xem & Phản hồi">
                                            <i class="fa-solid fa-reply"></i> Phản hồi
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>admin/feedback_delete/<?php echo $f['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa phản ánh này?');" class="btn btn-outline btn-sm" style="color: #d32f2f;" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">Chưa có khiếu nại hay góp ý nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Phản hồi Khiếu nại / Góp ý -->
<div id="fbModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-surface, #ffffff); width: 100%; max-width: 550px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <h5 style="margin-top: 0; font-weight: 700; color: var(--text-heading);">Chi Tiết & Phản Hồi Ý Kiến</h5>
        <form action="<?php echo BASE_URL; ?>admin/feedback_status" method="POST">
            <input type="hidden" id="fb_id" name="id" value="">
            
            <div style="background: var(--bg-surface-secondary, #f8f9fa); padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 13px;">
                <div style="margin-bottom: 4px;"><strong>Phân loại:</strong> <span id="fb_type_view"></span></div>
                <div><strong>Người gửi:</strong> <span id="fb_fullname_view"></span></div>
                <div><strong>Điện thoại:</strong> <span id="fb_phone_view"></span> | <strong>Email:</strong> <span id="fb_email_view"></span></div>
                <div style="margin-top: 4px;"><strong>Tiêu đề:</strong> <span id="fb_title_view" style="font-weight: 600;"></span></div>
                <div style="margin-top: 6px; padding: 8px; background: white; border-radius: 4px; border: 1px solid var(--border-color);">
                    <span id="fb_content_view"></span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Trạng thái Xử lý</label>
                <select id="fb_status_select" name="status" class="form-control">
                    <option value="new">Mới tiếp nhận</option>
                    <option value="processing">Đang xử lý</option>
                    <option value="resolved">Đã xử lý & Phản hồi</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Nội dung Phản hồi / Kết quả xử lý</label>
                <textarea id="reply_content" name="reply_content" class="form-control" rows="3" placeholder="Nhập nội dung phản hồi gửi tới người dân/tiểu thương..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeFbModal()" class="btn btn-outline">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary">Lưu phản hồi</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewFeedbackDetail(f) {
    document.getElementById('fb_id').value = f.id;
    var tp = f.type || 'feedback';
    if (tp === 'complaint') {
        document.getElementById('fb_type_view').innerHTML = '<span style="color: #dc2626; font-weight: 700; background: #fee2e2; padding: 2px 8px; border-radius: 4px;">⚠️ Khiếu nại</span>';
    } else if (tp === 'other') {
        document.getElementById('fb_type_view').innerHTML = '<span style="color: #7c3aed; font-weight: 700; background: #f3e8ff; padding: 2px 8px; border-radius: 4px;">📋 Ý kiến khác</span>';
    } else {
        document.getElementById('fb_type_view').innerHTML = '<span style="color: #2563eb; font-weight: 700; background: #dbeafe; padding: 2px 8px; border-radius: 4px;">💡 Góp ý</span>';
    }
    document.getElementById('fb_fullname_view').innerText = f.fullname || f.fb_fullname || 'Ẩn danh';
    document.getElementById('fb_phone_view').innerText = f.phone || f.fb_phone || 'Chưa có';
    document.getElementById('fb_email_view').innerText = f.email || f.fb_email || 'Chưa có';
    document.getElementById('fb_title_view').innerText = f.title || 'Phản ánh';
    document.getElementById('fb_content_view').innerText = f.content || f.fb_content || '';
    document.getElementById('fb_status_select').value = f.status || (f.fb_status == 1 ? 'new' : 'resolved');
    document.getElementById('reply_content').value = f.reply_content || '';
    document.getElementById('fbModal').style.display = 'flex';
}
function closeFbModal() {
    document.getElementById('fbModal').style.display = 'none';
}
</script>



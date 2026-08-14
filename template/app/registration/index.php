<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Quản Lý Đăng Ký Thuê Sạp Chợ</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Tiếp nhận và xử lý các yêu cầu đăng ký thuê sạp công khai từ người dân / tiểu thương.</p>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div class="card-title" style="font-size: 15px; font-weight: 600; margin: 0;">Danh sách Đăng ký (<?php echo count($registrations ?? []); ?>)</div>
        <div style="display: flex; gap: 8px;">
            <a href="<?php echo BASE_URL; ?>admin/registrations" class="btn btn-outline btn-sm">Tất cả</a>
            <a href="<?php echo BASE_URL; ?>admin/registrations?status=pending" class="btn btn-outline btn-sm" style="color: #f57c00;">Chờ xử lý</a>
            <a href="<?php echo BASE_URL; ?>admin/registrations?status=contacted" class="btn btn-outline btn-sm" style="color: #1976d2;">Đã liên hệ</a>
            <a href="<?php echo BASE_URL; ?>admin/registrations?status=approved" class="btn btn-outline btn-sm" style="color: #388e3c;">Đã duyệt</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="text-align: left; background-color: var(--bg-surface-secondary); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 12px 16px;">Họ tên người đăng ký</th>
                        <th style="padding: 12px 16px;">Điện thoại / Email</th>
                        <th style="padding: 12px 16px;">Ngành hàng kinh doanh</th>
                        <th style="padding: 12px 16px; width: 140px;">Trạng thái</th>
                        <th style="padding: 12px 16px; width: 140px;">Ngày đăng ký</th>
                        <th style="padding: 12px 16px; text-align: right; width: 140px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($registrations)): ?>
                        <?php foreach ($registrations as $r): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 14px 16px; font-weight: 600; color: var(--text-heading);">
                                    <div><?php echo htmlspecialchars($r['fullname']); ?></div>
                                    <small style="font-weight: 400; color: var(--text-muted);">CCCD: <?php echo htmlspecialchars($r['cccd'] ?: 'Chưa nhập'); ?></small>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 600; color: var(--primary);"><?php echo htmlspecialchars($r['phone']); ?></div>
                                    <small style="color: var(--text-muted);"><?php echo htmlspecialchars($r['email'] ?: ''); ?></small>
                                </td>
                                <td style="padding: 14px 16px; color: var(--text-muted);">
                                    <?php echo htmlspecialchars($r['business_item'] ?: 'Chưa khai báo'); ?>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <?php 
                                    $st = $r['status'] ?? 'pending';
                                    if ($st === 'pending') echo '<span class="status" style="background: rgba(245, 124, 0, 0.1); color: #f57c00; padding: 4px 8px; border-radius: 4px;">Chờ xử lý</span>';
                                    elseif ($st === 'contacted') echo '<span class="status" style="background: rgba(25, 118, 210, 0.1); color: #1976d2; padding: 4px 8px; border-radius: 4px;">Đã liên hệ</span>';
                                    elseif ($st === 'approved') echo '<span class="status" style="background: rgba(56, 142, 60, 0.1); color: #388e3c; padding: 4px 8px; border-radius: 4px;">Đã duyệt</span>';
                                    else echo '<span class="status" style="background: rgba(211, 47, 47, 0.1); color: #d32f2f; padding: 4px 8px; border-radius: 4px;">Từ chối</span>';
                                    ?>
                                </td>
                                <td style="padding: 14px 16px; color: var(--text-muted); font-size: 12px;">
                                    <?php echo date('d/m/Y H:i', strtotime($r['created_at'])); ?>
                                </td>
                                <td style="padding: 14px 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                        <button onclick='viewRegDetail(<?php echo json_encode($r, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' class="btn btn-outline btn-sm" title="Xem & Xử lý">
                                            <i class="fa-solid fa-eye"></i> Xử lý
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>admin/registration_delete/<?php echo $r['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa yêu cầu này?');" class="btn btn-outline btn-sm" style="color: #d32f2f;" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">Không có yêu cầu đăng ký nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Xử lý Đăng ký -->
<div id="regModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-surface, #ffffff); width: 100%; max-width: 550px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <h5 style="margin-top: 0; font-weight: 700; color: var(--text-heading);">Chi Tiết Yêu Cầu Đăng Ký Thuê Sạp</h5>
        <form action="<?php echo BASE_URL; ?>admin/registration_status" method="POST">
            <input type="hidden" id="reg_id" name="id" value="">
            
            <div style="background: var(--bg-surface-secondary, #f8f9fa); padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 13px;">
                <div><strong>Họ tên:</strong> <span id="view_fullname"></span></div>
                <div><strong>Điện thoại:</strong> <span id="view_phone"></span> | <strong>Email:</strong> <span id="view_email"></span></div>
                <div><strong>CCCD:</strong> <span id="view_cccd"></span></div>
                <div><strong>Ngành hàng:</strong> <span id="view_business"></span></div>
                <div style="margin-top: 6px; font-style: italic; color: var(--text-muted);">" <span id="view_note"></span> "</div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Cập nhật Trạng thái Xử lý</label>
                <select id="reg_status" name="status" class="form-control">
                    <option value="pending">Chờ xử lý</option>
                    <option value="contacted">Đã liên hệ tư vấn</option>
                    <option value="approved">Đã chấp thuận / Duyệt thuê</option>
                    <option value="rejected">Từ chối yêu cầu</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Ghi chú của BQL</label>
                <textarea id="admin_note" name="admin_note" class="form-control" rows="3" placeholder="Ghi chú quá trình liên hệ, thương thảo sạp..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeRegModal()" class="btn btn-outline">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary">Lưu trạng thái</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewRegDetail(r) {
    document.getElementById('reg_id').value = r.id;
    document.getElementById('view_fullname').innerText = r.fullname;
    document.getElementById('view_phone').innerText = r.phone;
    document.getElementById('view_email').innerText = r.email || 'Chưa có';
    document.getElementById('view_cccd').innerText = r.cccd || 'Chưa có';
    document.getElementById('view_business').innerText = r.business_item || 'Chưa khai báo';
    document.getElementById('view_note').innerText = r.note || 'Không có ghi chú';
    document.getElementById('reg_status').value = r.status || 'pending';
    document.getElementById('admin_note').value = r.admin_note || '';
    document.getElementById('regModal').style.display = 'flex';
}
function closeRegModal() {
    document.getElementById('regModal').style.display = 'none';
}
</script>

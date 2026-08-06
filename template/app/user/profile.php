<!-- Giao diện Trang cá nhân & Đổi mật khẩu của người dùng -->
<div style="font-size: 20px; font-weight: 700; color: var(--text-heading); margin-bottom: 20px;">Hồ Sơ Cá Nhân</div>

<div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; align-items: start; @media(max-width: 992px){grid-template-columns: 1fr;}">
    <!-- Cột bên trái: Cập nhật thông tin & mật khẩu -->
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <div class="card-title" style="font-size: 16px; font-weight: 600;">Thông tin cá nhân & Bảo mật</div>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form id="form-update-profile" action="<?php echo BASE_URL; ?>api/updateProfile" method="POST" data-native-submit="true">
                <?php csrf_field(); ?>
                
                <!-- Tên đăng nhập (Khóa) -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" for="username" style="font-weight: 500; color: var(--text-muted);">Tên đăng nhập (Không thể đổi)</label>
                    <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled style="background-color: var(--bg-surface-secondary, #f1f3f5); cursor: not-allowed;">
                </div>

                <!-- Vai trò hệ thống (Khóa) -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" for="role_name" style="font-weight: 500; color: var(--text-muted);">Vai trò tài khoản</label>
                    <input type="text" id="role_name" class="form-control" value="<?php echo htmlspecialchars($user['actor_name'] ?: 'Nhân viên vận hành'); ?>" disabled style="background-color: var(--bg-surface-secondary, #f1f3f5); cursor: not-allowed;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <!-- Họ tên -->
                    <div class="form-group">
                        <label class="form-label" for="fullname" style="font-weight: 500;">Họ tên của bạn <span style="color: var(--red, #ea4335)">*</span></label>
                        <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email" style="font-weight: 500;">Địa chỉ Email <span style="color: var(--red, #ea4335)">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 24px 0;">
                <div style="font-size: 14px; font-weight: 600; margin-bottom: 16px; color: var(--text-heading);">Thay đổi mật khẩu (Để trống nếu giữ nguyên)</div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <!-- Mật khẩu mới -->
                    <div class="form-group">
                        <label class="form-label" for="password" style="font-weight: 500;">Mật khẩu mới</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
                    </div>

                    <!-- Xác nhận mật khẩu mới -->
                    <div class="form-group">
                        <label class="form-label" for="confirm_password" style="font-weight: 500;">Xác nhận mật khẩu</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="••••••••">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" style="height: 38px; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-save"></i> Cập Nhật Hồ Sơ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cột bên phải: Chợ đang quản lý & Quyền hạn -->
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
            <div class="card-title" style="font-size: 16px; font-weight: 600;">Chợ trực thuộc & Quyền hạn</div>
        </div>
        <div class="card-body" style="padding: 24px;">
            <?php if ($user['actor_code'] === 'super_market'): ?>
                <div style="text-align: center; padding: 24px;">
                    <div style="background-color: rgba(234, 67, 53, 0.1); color: #EA4335; border-radius: 50%; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fa-solid fa-user-shield" style="font-size: 24px;"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 15px; color: var(--text-heading); margin-bottom: 4px;">Tài khoản Quản trị tối cao</div>
                    <p style="color: var(--text-muted); font-size: 13px; margin: 0;">Bạn có toàn quyền truy cập và thao tác đối với tất cả các chợ và mọi phân hệ chức năng trên hệ thống.</p>
                </div>
            <?php elseif ($user['actor_code'] === 'admin_market'): ?>
                <div style="margin-bottom: 16px; font-weight: 500; font-size: 13px; color: var(--text-muted);">
                    Dưới vai trò **Quản lý chợ**, bạn có toàn quyền quản trị nội bộ đối với các chợ trực thuộc sau:
                </div>
                <div style="display: grid; gap: 12px;">
                    <?php if (!empty($assignedMarkets)): ?>
                        <?php foreach ($assignedMarkets as $m): ?>
                            <div style="padding: 12px 16px; border: 1px solid var(--border-color); border-radius: 8px; display: flex; align-items: center; gap: 12px; background-color: var(--bg-surface-secondary, #f8f9fa);">
                                <i class="fa-solid fa-store" style="color: var(--primary-color, #2563eb); font-size: 16px;"></i>
                                <span style="font-weight: 600; color: var(--text-heading); font-size: 13.5px;"><?php echo htmlspecialchars($m['market_name']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="color: var(--text-muted); text-align: center; padding: 12px;">Chưa được liên kết với chợ nào.</div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 16px; font-weight: 500; font-size: 13px; color: var(--text-muted);">
                    Quyền hạn cụ thể của bạn (Nhân viên vận hành) đối với từng chợ trực thuộc:
                </div>
                <div style="display: grid; gap: 16px;">
                    <?php if (!empty($assignedMarkets)): ?>
                        <?php foreach ($assignedMarkets as $m): ?>
                            <div style="padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-surface-secondary, #f8f9fa);">
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--text-heading); font-size: 14px; margin-bottom: 12px; border-bottom: 1px solid var(--border-color-light, #e9ecef); padding-bottom: 8px;">
                                    <i class="fa-solid fa-store" style="color: var(--primary-color, #2563eb);"></i>
                                    <?php echo htmlspecialchars($m['market_name']); ?>
                                </div>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                    <?php 
                                    $marketPerms = $permissions[$m['market_id']] ?? [];
                                    if (!empty($marketPerms)):
                                        foreach ($marketPerms as $mod):
                                    ?>
                                            <span class="chip" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary-color, #2563eb); border: 1px solid rgba(37, 99, 235, 0.15); font-size: 12px; font-weight: 500; padding: 4px 10px; border-radius: 100px;">
                                                <?php echo htmlspecialchars($moduleNames[$mod] ?? $mod); ?>
                                            </span>
                                        <?php 
                                        endforeach;
                                    else:
                                    ?>
                                        <span style="color: #7f8c8d; font-size: 12px; font-style: italic;">Chưa được phân quyền phân hệ nào tại chợ này.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="color: var(--text-muted); text-align: center; padding: 12px;">Chưa được liên kết với chợ nào.</div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        var swalBg = isDark ? '#1a2332' : '#ffffff';
        var swalColor = isDark ? '#ffffff' : '#0f1623';

        $('#form-update-profile').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var form = this;
            var $form = $(this);
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            var password = $('#password').val();
            var confirmPassword = $('#confirm_password').val();

            if (password !== '' && password.length < 6) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Mật khẩu yếu',
                    text: 'Mật khẩu mới phải có ít nhất 6 ký tự.',
                    background: swalBg,
                    color: swalColor
                });
                return;
            }

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Mật khẩu không khớp',
                    text: 'Xác nhận mật khẩu mới không trùng khớp.',
                    background: swalBg,
                    color: swalColor
                });
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
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                success: function(res) {
                    Swal.close();
                    if (res.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: res.message || 'Cập nhật thông tin hồ sơ thành công!',
                            timer: 1500,
                            showConfirmButton: false,
                            background: swalBg,
                            color: swalColor
                        }).then(function() {
                            // Reset các trường mật khẩu
                            $('#password').val('');
                            $('#confirm_password').val('');
                            
                            // Cập nhật lại họ tên hiển thị trên trang nếu cần thiết
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại',
                            text: res.error || 'Cập nhật hồ sơ thất bại.',
                            background: swalBg,
                            color: swalColor
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    var msg = 'Không thể kết nối đến máy chủ.';
                    if (xhr.responseJSON) {
                        msg = xhr.responseJSON.error || xhr.responseJSON.message || msg;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Thông báo',
                        text: msg,
                        background: swalBg,
                        color: swalColor
                    });
                }
            });
        });
    });
</script>

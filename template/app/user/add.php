<!-- Form Khai Báo Nhân Viên Mới -->
<div style="margin-bottom: 20px;">
    <a href="<?php echo BASE_URL; ?>system/users" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại tài khoản
    </a>
</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
        <div class="card-title" style="font-size: 16px; font-weight: 600;">Thông tin tài khoản nhân viên BQL mới</div>
    </div>
    <div class="card-body" style="padding: 24px;">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px; background-color: rgba(234, 67, 53, 0.1); color: #EA4335; border: 1px solid rgba(234, 67, 53, 0.2); border-radius: 4px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form id="form-add-user" action="<?php echo BASE_URL; ?>api/addUser" method="POST" data-native-submit="true">
            <?php csrf_field(); ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Tên đăng nhập -->
                <div class="form-group">
                    <label class="form-label" for="username" style="font-weight: 500;">Tên đăng nhập <span style="color: var(--red)">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Ví dụ: ketoan_nga" value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" required>
                </div>

                <!-- Họ tên nhân viên -->
                <div class="form-group">
                    <label class="form-label" for="fullname" style="font-weight: 500;">Họ tên nhân viên <span style="color: var(--red)">*</span></label>
                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Nhập đầy đủ họ tên" value="<?php echo htmlspecialchars($data['fullname'] ?? ''); ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email" style="font-weight: 500;">Địa chỉ Email <span style="color: var(--red)">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="nga.lt@market.com" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" required>
                </div>

                <!-- Mật khẩu khởi tạo -->
                <div class="form-group">
                    <label class="form-label" for="password" style="font-weight: 500;">Mật khẩu khởi tạo <span style="color: var(--red)">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
                <!-- Vai trò hệ thống -->
                <div class="form-group">
                    <label class="form-label" for="role" style="font-weight: 500;">Vai trò hệ thống <span style="color: var(--red)">*</span></label>
                    <select id="role" name="role" class="form-control" required>
                        <?php if (marketService::isSuperAdmin() && !empty($actorsList)): ?>
                            <?php foreach ($actorsList as $actor): ?>
                                <option value="<?php echo htmlspecialchars($actor['actor_code']); ?>" <?php echo (($data['role'] ?? '') === $actor['actor_code']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($actor['actor_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="admin" selected>Nhân viên vận hành (Staff)</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Trạng thái hoạt động -->
                <div class="form-group">
                    <label class="form-label" for="status" style="font-weight: 500;">Trạng thái kích hoạt</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active" <?php echo (($data['status'] ?? '') === 'active') ? 'selected' : ''; ?>>Kích hoạt hoạt động</option>
                        <option value="inactive" <?php echo (($data['status'] ?? '') === 'inactive') ? 'selected' : ''; ?>>Khóa tạm thời</option>
                    </select>
                </div>
            </div>

            <!-- Danh sách chợ liên kết -->
            <?php if (!empty($marketsList)): ?>
                <div id="markets-container" class="form-group" style="margin-bottom: 24px; padding: 16px; background-color: var(--bg-surface-light, #f8f9fa); border: 1px solid var(--border-color); border-radius: 6px;">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 12px; display: block;">Chọn chợ trực thuộc quản lý & Vai trò <span style="color: var(--red)">*</span></label>
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 8px;">
                        <?php foreach ($marketsList as $m): ?>
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 6px 12px; background: var(--bg-surface, #ffffff); border-radius: 6px; border: 1px solid var(--border-color); gap: 12px; max-width: 500px;">
                                <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: normal; cursor: pointer; color: var(--text-color); margin: 0; flex-grow: 1;">
                                    <input type="checkbox" name="markets[]" value="<?php echo $m['id']; ?>" class="market-checkbox" style="width: 16px; height: 16px; accent-color: var(--primary-color);">
                                    <?php echo htmlspecialchars($m['name']); ?>
                                </label>
                                <div class="market-role-select-wrapper" style="display: none;">
                                    <select name="market_roles[<?php echo $m['id']; ?>]" class="form-control market-role-select" style="width: 180px; padding: 4px 8px; font-size: 13px; height: auto;" disabled>
                                        <?php if (!empty($marketRolesList)): ?>
                                            <?php foreach ($marketRolesList as $mr): ?>
                                                <option value="<?php echo $mr['role_id']; ?>"><?php echo htmlspecialchars($mr['role_name']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <small style="color: #7f8c8d; margin-top: 8px; display: block;">
                        <?php echo marketService::isSuperAdmin() ? 'Chọn các chợ gán cho tài khoản Quản lý hoặc Nhân viên.' : 'Nhân viên mới sẽ chỉ được phân quyền tại các chợ được chọn.'; ?>
                    </small>
                </div>
            <?php endif; ?>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(function() {
                    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                    var swalBg = isDark ? '#1a2332' : '#ffffff';
                    var swalColor = isDark ? '#ffffff' : '#0f1623';

                    function updateMarketRolesVisibility() {
                        var mainRole = $('#role').val();
                        if (mainRole === 'admin') {
                            $('.market-role-select-wrapper').show();
                            $('.market-checkbox').each(function() {
                                var $select = $(this).closest('div').find('.market-role-select');
                                if ($(this).is(':checked')) {
                                    $select.prop('disabled', false);
                                } else {
                                    $select.prop('disabled', true);
                                }
                            });
                        } else {
                            $('.market-role-select-wrapper').hide();
                            $('.market-role-select').prop('disabled', true);
                        }
                    }

                    $('#role').on('change', function() {
                        if (this.value === 'super_market') {
                            $('#markets-container').hide().find('input[type="checkbox"]').prop('checked', false);
                        } else {
                            $('#markets-container').show();
                        }
                        updateMarketRolesVisibility();
                    }).trigger('change');

                    $(document).on('change', '.market-checkbox', function() {
                        updateMarketRolesVisibility();
                    });

                    $('#form-add-user').on('submit', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var form = this;
                        var $form = $(this);
                        if (!form.checkValidity()) {
                            form.reportValidity();
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
                            type: "POST",
                            url: $form.attr('action'),
                            data: new FormData(form),
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            success: function(data) {
                                Swal.close();
                                if (data.status === 200) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Thành công',
                                        text: data.message,
                                        timer: 1500,
                                        showConfirmButton: false,
                                        background: swalBg,
                                        color: swalColor
                                    }).then(function() {
                                        window.location.href = '<?php echo BASE_URL; ?>system/users';
                                    });
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message, background: swalBg, color: swalColor });
                                }
                            },
                            error: function() {
                                Swal.close();
                                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.', background: swalBg, color: swalColor });
                            }
                        });
                    });
                });
            </script>

            <hr style="border: 0; border-top: 1px solid var(--border-color-light); margin: 24px 0;">

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="<?php echo BASE_URL; ?>system/users" class="btn btn-outline" style="text-decoration: none;">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-user-plus"></i> Tạo tài khoản
                </button>
            </div>
        </form>
    </div>
</div>
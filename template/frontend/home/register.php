    <!-- ================= HERO ĐĂNG KÝ ================= -->
    <section class="hero" style="padding: 60px 0 80px;">
        <div class="hero-grid-pattern"></div>
        <div class="container hero-content" style="grid-template-columns: 1fr; text-align: center;">
            <div>
                <div class="hero-eyebrow" style="justify-content: center;"><span class="dot"></span> Thuê sạp online</div>
                <h1>Đăng ký thuê sạp trực tuyến</h1>
                <p style="margin: 0 auto; max-width: 90% !important;">Nộp hồ sơ trực tuyến, Ban quản lý chợ sẽ xét duyệt và liên hệ lại trong vòng 24h làm việc.</p>
            </div>
        </div>
    </section>

    <!-- ================= FORM ĐĂNG KÝ ================= -->
    <section style="padding: 60px 0;">
        <div class="container">
            <?php 
            $areas = $this->data['areas'] ?? [];
            $stalls = $this->data['stalls'] ?? [];
            $success = $this->data['success'] ?? false;
            $error = $this->data['error'] ?? '';
            ?>

            <?php if ($success): ?>
                <div style="background: #E8F5E9; border: 1px solid #C8E6C9; padding: 40px 30px; border-radius: var(--radius-lg); text-align: center; margin-bottom: 40px; box-shadow: var(--shadow-sm); max-width: 90%; margin: 0 auto;">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: #2E7D32; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 16px;">✓</div>
                    <h3 style="font-size: 22px; font-weight: 800; color: #2E7D32; margin-bottom: 8px;">Nộp hồ sơ đăng ký thành công!</h3>
                    <p style="color: var(--gray-600); margin-bottom: 24px; font-size: 15px; line-height: 1.6;">Hồ sơ thuê sạp của bạn đã được ghi nhận vào hệ thống Quản lý Chợ. Ban quản lý sẽ liên hệ trực tiếp qua số điện thoại để hướng dẫn ký hợp đồng và bàn giao sạp.</p>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Quay lại Trang chủ</a>
                </div>
            <?php else: ?>
                <div class="form-card" style="padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--gray-300); max-width: 90%; margin: 0 auto; background: #fff; box-shadow: var(--shadow-sm);">
                    <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 8px; color: var(--gray-900);">Thông tin đăng ký thuê sạp</h3>
                    <p style="color: var(--gray-600); font-size: 14.5px; margin-bottom: 30px;">Vui lòng điền chính xác thông tin để chúng tôi liên hệ hỗ trợ.</p>
                    
                    <?php if (!empty($error)): ?>
                        <div style="background: #FFEBEE; border: 1px solid #FFCDD2; color: #C62828; padding: 12px 16px; border-radius: 6px; font-size: 13.5px; margin-bottom: 20px;">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>home/register" method="POST">
                        <input type="hidden" id="areaNameInput" name="area_name" value="">
                        
                        <div class="form-row">
                            <div class="field">
                                <label>Họ và tên người đăng ký <span style="color:red">*</span></label>
                                <input type="text" name="fullname" placeholder="Ví dụ: Nguyễn Văn A" required>
                            </div>
                            <div class="field">
                                <label>Số điện thoại liên hệ <span style="color:red">*</span></label>
                                <input type="tel" name="phone" placeholder="Ví dụ: 0912 345 678" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="field">
                                <label>Số CMND/CCCD <span style="color:red">*</span></label>
                                <input type="text" name="cccd" placeholder="Nhập số CCCD 12 số" required>
                            </div>
                            <div class="field">
                                <label>Email nhận thông báo</label>
                                <input type="email" name="email" placeholder="example@gmail.com">
                            </div>
                        </div>

                        <!-- CHỈ HIỆN CÁC KHU VỰC CÒN SẠP TRỐNG (CHƯA THUÊ) -->
                        <div class="form-row">
                            <div class="field">
                                <label>Khu vực mong muốn (Còn sạp trống) <span style="color:red">*</span></label>
                                <select id="areaSelect" name="area_id" onchange="onAreaChanged(this.value)" required style="width: 100%; height: 44px; border: 1px solid var(--gray-300); border-radius: var(--radius-sm); padding: 0 12px; font-size: 14px; background: #fff; color: var(--gray-900);">
                                    <?php if (!empty($areas)): ?>
                                        <option value="">-- Chọn Khu vực còn sạp trống --</option>
                                        <?php foreach ($areas as $a): ?>
                                            <option value="<?php echo $a['area_id']; ?>" data-name="<?php echo htmlspecialchars($a['area_name']); ?>">
                                                <?php echo htmlspecialchars($a['area_name']); ?> (<?php echo (int)($a['empty_count'] ?? 1); ?> sạp còn trống)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">-- Hiện tại tất cả các khu vực đã thuê kín --</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="field">
                                <label>Sạp còn trống trong khu vực <span style="color:red">*</span></label>
                                <select id="stallSelect" name="stall_code" onchange="onStallChanged()" required style="width: 100%; height: 44px; border: 1px solid var(--gray-300); border-radius: var(--radius-sm); padding: 0 12px; font-size: 14px; background: #fff; color: var(--gray-900);">
                                    <option value="">-- Vui lòng chọn Khu vực trước --</option>
                                </select>
                            </div>
                        </div>

                        <!-- THÔNG TIN DIỆN TÍCH SẠP & MẶT HÀNG KINH DOANH -->
                        <div class="form-row">
                            <div class="field">
                                <label>Diện tích sạp (m²)</label>
                                <input type="number" id="areaSizeInput" name="area_size" step="0.01" readonly style="background-color: #f8fafc; font-weight: 600; color: #0f172a;" placeholder="Tự động hiển thị theo sạp đã chọn">
                            </div>
                            <div class="field">
                                <label>Mặt hàng kinh doanh dự kiến <span style="color:red">*</span></label>
                                <input type="text" name="business_item" placeholder="Ví dụ: Rau củ quả tươi sống, Quần áo, Đồ gia dụng..." required>
                            </div>
                        </div>

                        <!-- KHUNG THÔNG TIN SẠP ĐƯỢC CHỌN TRỰC QUAN -->
                        <div id="stallDetailBox" style="display: none; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; font-size: 13.5px; color: #166534;">
                            <div style="font-weight: 700; margin-bottom: 4px;" id="boxStallTitle">Thông tin sạp đã chọn</div>
                            <div id="boxStallDesc" style="color: #15803d; line-height: 1.5;"></div>
                        </div>

                        <div class="field">
                            <label>Ghi chú hoặc câu hỏi dành cho BQL Chợ</label>
                            <textarea id="noteTextarea" name="note" rows="3" placeholder="Nhập yêu cầu đặc biệt của bạn nếu có..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 10px; height: 46px; font-size: 15px; font-weight: 700;">Gửi Hồ Sơ Đăng Ký Thuê Sạp</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // Dữ liệu toàn bộ sạp còn trống từ cơ sở dữ liệu
        var allEmptyStalls = <?php echo json_encode($stalls ?? []); ?>;

        function onAreaChanged(selectedAreaId) {
            var stallSelect = document.getElementById('stallSelect');
            var areaSizeInput = document.getElementById('areaSizeInput');
            var stallDetailBox = document.getElementById('stallDetailBox');
            var areaSelect = document.getElementById('areaSelect');
            var areaNameInput = document.getElementById('areaNameInput');

            // Cập nhật tên khu vực
            if (areaSelect.selectedIndex > 0) {
                areaNameInput.value = areaSelect.options[areaSelect.selectedIndex].getAttribute('data-name') || '';
            } else {
                areaNameInput.value = '';
            }

            stallSelect.innerHTML = '';
            stallDetailBox.style.display = 'none';
            areaSizeInput.value = '';

            if (!selectedAreaId) {
                stallSelect.innerHTML = '<option value="">-- Vui lòng chọn Khu vực trước --</option>';
                return;
            }

            // Lọc danh sách sạp trống theo khu vực đã chọn
            var filtered = allEmptyStalls.filter(function(s) {
                return s.stall_area_id == selectedAreaId;
            });

            if (filtered.length === 0) {
                stallSelect.innerHTML = '<option value="">Khu vực này hiện không còn sạp trống</option>';
                return;
            }

            var defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.innerText = '-- Chọn Sạp còn trống --';
            stallSelect.appendChild(defaultOpt);

            filtered.forEach(function(s) {
                var opt = document.createElement('option');
                opt.value = s.stall_code;
                var sizeText = (s.stall_area_size && parseFloat(s.stall_area_size) > 0) ? (parseFloat(s.stall_area_size) + ' m²') : 'Chưa có thông số';
                opt.innerText = s.stall_code + ' — Diện tích: ' + sizeText + ' (Trống)';
                opt.setAttribute('data-size', s.stall_area_size || '');
                opt.setAttribute('data-price', s.stall_base_price || 0);
                opt.setAttribute('data-status', s.status_name || 'Trống');
                stallSelect.appendChild(opt);
            });
        }

        function onStallChanged() {
            var stallSelect = document.getElementById('stallSelect');
            var areaSizeInput = document.getElementById('areaSizeInput');
            var stallDetailBox = document.getElementById('stallDetailBox');
            var boxStallTitle = document.getElementById('boxStallTitle');
            var boxStallDesc = document.getElementById('boxStallDesc');

            if (stallSelect.selectedIndex <= 0) {
                stallDetailBox.style.display = 'none';
                areaSizeInput.value = '';
                return;
            }

            var selectedOpt = stallSelect.options[stallSelect.selectedIndex];
            var size = selectedOpt.getAttribute('data-size');
            var price = selectedOpt.getAttribute('data-price');
            var status = selectedOpt.getAttribute('data-status');
            var code = selectedOpt.value;

            if (size && parseFloat(size) > 0) {
                areaSizeInput.value = parseFloat(size);
            } else {
                areaSizeInput.value = '';
            }

            // Hiển thị khung thông tin sạp trực quan
            boxStallTitle.innerHTML = '✓ Đã chọn: <b>Sạp ' + code + '</b>';
            var formattedPrice = (price && parseFloat(price) > 0) ? (new Intl.NumberFormat('vi-VN').format(price) + ' đ/m²/tháng') : 'Theo quy định BQL';
            
            boxStallDesc.innerHTML = '• <b>Diện tích sạp:</b> ' + (size ? (parseFloat(size) + ' m²') : 'Đang cập nhật') + 
                                     '<br>• <b>Đơn giá tham khảo:</b> ' + formattedPrice + 
                                     '<br>• <b>Trạng thái:</b> ' + status + ' (Có thể nhận sạp và ký hợp đồng ngay)';
            stallDetailBox.style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const zoneParam = urlParams.get('zone') || urlParams.get('area_name');
            const stallCodeParam = urlParams.get('stall_code') || urlParams.get('stall');

            // Tự động chọn khu vực nếu có URL param
            if (zoneParam) {
                var areaSelect = document.getElementById('areaSelect');
                if (areaSelect) {
                    for (var i = 0; i < areaSelect.options.length; i++) {
                        if (areaSelect.options[i].text.toLowerCase().includes(zoneParam.toLowerCase())) {
                            areaSelect.selectedIndex = i;
                            onAreaChanged(areaSelect.value);
                            break;
                        }
                    }
                }
            }

            // Tự động chọn sạp nếu có URL param
            if (stallCodeParam) {
                setTimeout(function() {
                    var stallSelect = document.getElementById('stallSelect');
                    if (stallSelect) {
                        for (var i = 0; i < stallSelect.options.length; i++) {
                            if (stallSelect.options[i].value === stallCodeParam) {
                                stallSelect.selectedIndex = i;
                                onStallChanged();
                                break;
                            }
                        }
                    }
                }, 200);
            }
        });
    </script>

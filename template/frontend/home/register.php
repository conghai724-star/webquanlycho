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
            $markets = $this->data['markets'] ?? [];
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
                <div id="registerFormContainer" class="form-card" style="padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--gray-300); max-width: 90%; margin: 0 auto 50px auto; background: #fff; box-shadow: var(--shadow-sm);">
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

                        <!-- CHỌN CHỢ TRỰC THUỘC -->
                        <div class="form-row">
                            <div class="field" style="grid-column: 1 / -1;">
                                <label>Chợ mong muốn đăng ký kinh doanh <span style="color:red">*</span></label>
                                <select id="marketSelect" name="market_id" onchange="onMarketChanged(this.value)" required style="width: 100%; height: 44px; border: 1.5px solid var(--blue-700); border-radius: var(--radius-sm); padding: 0 12px; font-size: 14px; font-weight: 600; background: #fff; color: var(--gray-900);">
                                    <option value="">-- Chọn Chợ --</option>
                                    <?php foreach ($markets as $m): ?>
                                        <option value="<?php echo $m['market_id']; ?>">
                                            🏪 <?php echo htmlspecialchars($m['market_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- CHỌN KHU VỰC VÀ SẠP CÒN TRỐNG THEO CHỢ -->
                        <div class="form-row">
                            <div class="field">
                                <label>Khu vực còn sạp trống <span style="color:red">*</span></label>
                                <select id="areaSelect" name="area_id" onchange="onAreaChanged(this.value)" required style="width: 100%; height: 44px; border: 1px solid var(--gray-300); border-radius: var(--radius-sm); padding: 0 12px; font-size: 14px; background: #fff; color: var(--gray-900);">
                                    <option value="">-- Vui lòng chọn Chợ trước --</option>
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
                            <label>Ghi chú hoặc yêu cầu thêm</label>
                            <textarea name="note" rows="3" placeholder="Nhập yêu cầu về vị trí, nguồn điện, nước hoặc thời gian dự kiến bắt đầu kinh doanh..."></textarea>
                        </div>

                        <div style="margin-top: 30px;">
                            <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; font-size: 16px; font-weight: 700;">Gửi hồ sơ đăng ký thuê sạp</button>
                        </div>
                    </form>
                </div>

                <!-- DANH SÁCH CÁC SẠP CÒN TRỐNG TRỰC TIẾP VỚI PHÂN TRANG & BỘ LỌC (DƯỚI FORM) -->
                <?php 
                $vacantCards = $this->data['vacantStallsCardList'] ?? [];
                if (!empty($vacantCards)): 
                ?>
                <div style="margin-top: 60px; padding-top: 40px; border-top: 1px dashed var(--gray-300);" id="vacantStallsSection">
                    <div style="text-align: center; margin-bottom: 24px;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: var(--primary, #0f766e); background: #f0fdf4; padding: 4px 14px; border-radius: 20px; border: 1px solid #bbf7d0;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span> Sẵn sàng bàn giao kinh doanh
                        </span>
                        <h2 style="font-size: 24px; font-weight: 800; color: var(--gray-900); margin-top: 8px; margin-bottom: 6px;">Danh sách sạp còn trống trên toàn hệ thống</h2>
                        <p style="color: var(--gray-600); font-size: 14.5px;">Bấm nút <b>"Chọn thuê sạp này"</b> để hệ thống tự động điền vào mẫu hồ sơ bên trên</p>
                    </div>

                    <!-- BỘ LỌC CHỢ & TÌM KIẾM TRỰC QUAN -->
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;" id="vacantMarketTabs">
                            <button type="button" class="btn-tab-vacant active" onclick="filterVacantMarket(0, this)" style="padding: 7px 16px; border-radius: 20px; border: 1px solid #0f766e; background: #0f766e; color: #fff; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s;">Tất cả chợ</button>
                            <?php foreach ($markets as $m): ?>
                                <button type="button" class="btn-tab-vacant" onclick="filterVacantMarket(<?php echo $m['market_id']; ?>, this)" data-market-id="<?php echo $m['market_id']; ?>" style="padding: 7px 16px; border-radius: 20px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                    🏪 <?php echo htmlspecialchars($m['market_name']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <div style="position: relative; min-width: 240px;">
                            <input type="text" id="vacantSearchInput" oninput="onVacantSearch(this.value)" placeholder="🔍 Tìm mã sạp, khu vực..." style="width: 100%; height: 38px; border: 1px solid #cbd5e1; border-radius: 20px; padding: 0 16px; font-size: 13px; outline: none;">
                        </div>
                    </div>

                    <div id="vacantStallsGrid" class="stalls-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                        <?php foreach ($vacantCards as $vc): 
                            $uPrice = (float)($vc['stall_base_price'] ?? 0);
                            $aSize = (float)($vc['stall_area_size'] ?? 0);
                            $tPrice = ($uPrice > 0 && $aSize > 0) ? ($uPrice * $aSize) : $uPrice;
                        ?>
                        <div class="stall-card-item" data-market-id="<?php echo (int)$vc['market_id']; ?>" data-keywords="<?php echo htmlspecialchars(mb_strtolower($vc['stall_code'] . ' ' . $vc['area_name'] . ' ' . $vc['market_name'], 'UTF-8')); ?>" style="box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200); background: #ffffff; padding: 22px; border-radius: var(--radius-lg); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s;">
                            <div class="stall-top" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px;">
                                <div>
                                    <div class="stall-code" style="font-size: 18px; font-weight: 800; color: var(--gray-900);"><?php echo htmlspecialchars($vc['stall_code']); ?></div>
                                    <div style="font-size: 12.5px; color: var(--gray-600); margin-top: 3px;">
                                        <b>Khu:</b> <?php echo htmlspecialchars($vc['area_name']); ?>
                                    </div>
                                    <div style="font-size: 12px; color: var(--primary, #0f766e); font-weight: 700; margin-top: 2px;">
                                        <i class="fa-solid fa-store" style="font-size: 11px;"></i> <?php echo htmlspecialchars($vc['market_name']); ?>
                                    </div>
                                </div>
                                <span class="badge badge-vacant" style="background-color: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">Còn trống</span>
                            </div>
                            <div class="stall-meta" style="border-top: 1px solid var(--gray-200); border-bottom: 1px solid var(--gray-200); padding: 10px 0; margin-bottom: 14px; font-size: 13px; color: var(--gray-600);">
                                <div style="margin-bottom: 5px;">Diện tích: <b style="color: var(--gray-900);"><?php echo $vc['stall_area_size']; ?> m²</b></div>
                                <?php if (($settings['hide_stall_price'] ?? '0') === '1'): ?>
                                    <div style="margin-bottom: 3px;">
                                        Giá thuê: <b style="color: var(--primary, #0f766e); font-size: 14px;">Liên hệ BQL</b>
                                    </div>
                                <?php else: ?>
                                    <div style="margin-bottom: 3px;">
                                        Giá thuê: <b style="color: var(--primary, #0f766e); font-size: 14px;"><?php echo number_format($tPrice, 0, ',', '.'); ?> đ</b>/tháng
                                    </div>
                                    <div style="font-size: 11px; color: var(--gray-500);">(Đơn giá: <?php echo number_format($uPrice, 0, ',', '.'); ?> đ/m²)</div>
                                <?php endif; ?>
                            </div>
                            <button type="button" onclick="selectStallCard('<?php echo htmlspecialchars($vc['stall_code']); ?>', <?php echo (int)$vc['market_id']; ?>, <?php echo (int)$vc['area_id']; ?>)" class="btn btn-primary btn-sm" style="width: 100%; font-size: 13px; font-weight: 700; padding: 9px 0; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="fa-solid fa-check"></i> Chọn thuê sạp này
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- THÔNG BÁO KHI KHÔNG TÌM THẤY SẠP -->
                    <div id="vacantNoResults" style="display: none; text-align: center; padding: 40px 20px; background: #fff; border-radius: var(--radius-lg); border: 1px dashed var(--gray-300); margin-top: 15px;">
                        <i class="fa-solid fa-store-slash" style="font-size: 36px; color: var(--gray-400); margin-bottom: 10px;"></i>
                        <p style="color: var(--gray-600); font-size: 14px; margin: 0;">Không tìm thấy sạp trống nào phù hợp với bộ lọc hiện tại.</p>
                    </div>

                    <!-- THANH PHÂN TRANG -->
                    <div id="vacantPaginationContainer" style="display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 28px; flex-wrap: wrap;"></div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Script xử lý chọn theo cấp: Chợ -> Khu vực -> Sạp -->
    <script>
        var allAreas = <?php echo json_encode($areas); ?>;
        var allEmptyStalls = <?php echo json_encode($stalls); ?>;

        function onMarketChanged(marketId) {
            var areaSelect = document.getElementById('areaSelect');
            var stallSelect = document.getElementById('stallSelect');
            var areaSizeInput = document.getElementById('areaSizeInput');
            var stallDetailBox = document.getElementById('stallDetailBox');

            areaSelect.innerHTML = '';
            stallSelect.innerHTML = '<option value="">-- Vui lòng chọn Khu vực trước --</option>';
            areaSizeInput.value = '';
            stallDetailBox.style.display = 'none';

            if (!marketId) {
                areaSelect.innerHTML = '<option value="">-- Vui lòng chọn Chợ trước --</option>';
                return;
            }

            var filteredAreas = allAreas.filter(function(a) {
                return a.area_market_id == marketId;
            });

            if (filteredAreas.length === 0) {
                areaSelect.innerHTML = '<option value="">Chợ này hiện chưa có khu vực nào còn sạp trống</option>';
                return;
            }

            var defOpt = document.createElement('option');
            defOpt.value = '';
            defOpt.innerText = '-- Chọn Khu vực còn sạp trống --';
            areaSelect.appendChild(defOpt);

            filteredAreas.forEach(function(a) {
                var opt = document.createElement('option');
                opt.value = a.area_id;
                opt.setAttribute('data-name', a.area_name);
                opt.innerText = a.area_name + ' (' + (a.empty_count || 1) + ' sạp còn trống)';
                areaSelect.appendChild(opt);
            });
        }

        function onAreaChanged(selectedAreaId) {
            var stallSelect = document.getElementById('stallSelect');
            var areaSizeInput = document.getElementById('areaSizeInput');
            var stallDetailBox = document.getElementById('stallDetailBox');
            var areaSelect = document.getElementById('areaSelect');
            var areaNameInput = document.getElementById('areaNameInput');

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
            var unitPrice = (price && parseFloat(price) > 0) ? parseFloat(price) : 0;
            var numSize = (size && parseFloat(size) > 0) ? parseFloat(size) : 0;
            var totalMonthly = (unitPrice > 0 && numSize > 0) ? (unitPrice * numSize) : unitPrice;

            var isHidePrice = <?php echo (($settings['hide_stall_price'] ?? '0') === '1') ? 'true' : 'false'; ?>;
            var formattedTotalPrice = isHidePrice ? 'Liên hệ Ban Quản Lý' : (totalMonthly > 0 ? (new Intl.NumberFormat('vi-VN').format(totalMonthly) + ' đ/tháng') : 'Theo quy định BQL');
            var formattedUnitPrice = (isHidePrice || unitPrice <= 0) ? '' : (new Intl.NumberFormat('vi-VN').format(unitPrice) + ' đ/m²/tháng');
            
            boxStallDesc.innerHTML = '• <b>Diện tích sạp:</b> ' + (numSize > 0 ? (numSize + ' m²') : 'Đang cập nhật') + 
                                     '<br>• <b>Giá thuê dự kiến:</b> <span style="font-size: 15px; color: #166534; font-weight: 800;">' + formattedTotalPrice + '</span>' + (formattedUnitPrice ? ' <span style="color:#64748b; font-size:12px;">(Đơn giá: ' + formattedUnitPrice + ')</span>' : '') +
                                     '<br>• <b>Trạng thái:</b> ' + status + ' (Có thể nhận sạp và ký hợp đồng ngay)';
            stallDetailBox.style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Mặc định tự động chọn Chợ đầu tiên nếu có
            var marketSelect = document.getElementById('marketSelect');
            if (marketSelect && marketSelect.options.length > 1) {
                marketSelect.selectedIndex = 1;
                onMarketChanged(marketSelect.value);
            }

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

            // Khởi tạo phân trang danh sách sạp trống
            renderVacantPagination();
        });

        // ================= XỬ LÝ PHÂN TRANG & BỘ LỌC SẠP TRỐNG =================
        var vacantCurrentMarket = 0;
        var vacantSearchKeyword = '';
        var vacantCurrentPage = 1;
        var vacantPageSize = 6; // 6 sạp mỗi trang

        function getFilteredVacantCards() {
            var items = Array.from(document.querySelectorAll('.stall-card-item'));
            return items.filter(function (card) {
                var mId = parseInt(card.getAttribute('data-market-id') || 0);
                var kwords = (card.getAttribute('data-keywords') || '').toLowerCase();

                var matchMarket = (vacantCurrentMarket === 0 || mId === vacantCurrentMarket);
                var matchKeyword = (!vacantSearchKeyword || kwords.includes(vacantSearchKeyword));
                return matchMarket && matchKeyword;
            });
        }

        function renderVacantPagination() {
            var allItems = document.querySelectorAll('.stall-card-item');
            allItems.forEach(function (card) { card.style.display = 'none'; });

            var filtered = getFilteredVacantCards();
            var noResEl = document.getElementById('vacantNoResults');
            var paginationEl = document.getElementById('vacantPaginationContainer');

            if (filtered.length === 0) {
                if (noResEl) noResEl.style.display = 'block';
                if (paginationEl) paginationEl.innerHTML = '';
                return;
            }

            if (noResEl) noResEl.style.display = 'none';

            var totalPages = Math.ceil(filtered.length / vacantPageSize);
            if (vacantCurrentPage > totalPages) vacantCurrentPage = 1;
            if (vacantCurrentPage < 1) vacantCurrentPage = 1;

            var start = (vacantCurrentPage - 1) * vacantPageSize;
            var end = start + vacantPageSize;

            filtered.slice(start, end).forEach(function (card) {
                card.style.display = 'flex';
            });

            // Render thanh số trang
            if (!paginationEl) return;
            if (totalPages <= 1) {
                paginationEl.innerHTML = '<span style="font-size: 13px; color: #64748b; font-weight: 500;">Hiển thị toàn bộ ' + filtered.length + ' sạp còn trống</span>';
                return;
            }

            var html = '';
            // Nút Trước
            if (vacantCurrentPage > 1) {
                html += '<button type="button" onclick="goToVacantPage(' + (vacantCurrentPage - 1) + ')" style="padding: 6px 14px; border: 1px solid #cbd5e1; background: #fff; border-radius: 8px; font-size: 13px; cursor: pointer; font-weight: 600; color: #334155; transition: all 0.2s;">&laquo; Trước</button>';
            }

            for (var p = 1; p <= totalPages; p++) {
                if (p === vacantCurrentPage) {
                    html += '<button type="button" style="padding: 6px 14px; border: 1px solid #0f766e; background: #0f766e; color: #fff; border-radius: 8px; font-size: 13px; font-weight: 700;">' + p + '</button>';
                } else {
                    html += '<button type="button" onclick="goToVacantPage(' + p + ')" style="padding: 6px 14px; border: 1px solid #cbd5e1; background: #fff; border-radius: 8px; font-size: 13px; cursor: pointer; font-weight: 600; color: #334155; transition: all 0.2s;">' + p + '</button>';
                }
            }

            // Nút Sau
            if (vacantCurrentPage < totalPages) {
                html += '<button type="button" onclick="goToVacantPage(' + (vacantCurrentPage + 1) + ')" style="padding: 6px 14px; border: 1px solid #cbd5e1; background: #fff; border-radius: 8px; font-size: 13px; cursor: pointer; font-weight: 600; color: #334155; transition: all 0.2s;">Sau &raquo;</button>';
            }

            paginationEl.innerHTML = html;
        }

        function goToVacantPage(p) {
            vacantCurrentPage = p;
            renderVacantPagination();
            var section = document.getElementById('vacantStallsSection');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function filterVacantMarket(mId, btn) {
            vacantCurrentMarket = parseInt(mId || 0);
            vacantCurrentPage = 1;

            var tabBtns = document.querySelectorAll('#vacantMarketTabs .btn-tab-vacant');
            tabBtns.forEach(function (b) {
                b.style.background = '#fff';
                b.style.color = '#475569';
                b.style.borderColor = '#cbd5e1';
                b.style.fontWeight = '600';
            });

            if (btn) {
                btn.style.background = '#0f766e';
                btn.style.color = '#fff';
                btn.style.borderColor = '#0f766e';
                btn.style.fontWeight = '700';
            }

            renderVacantPagination();
        }

        function onVacantSearch(val) {
            vacantSearchKeyword = (val || '').trim().toLowerCase();
            vacantCurrentPage = 1;
            renderVacantPagination();
        }

        function selectStallCard(stallCode, marketId, areaId) {
            var marketSelect = document.getElementById('marketSelect');
            if (marketSelect) {
                marketSelect.value = marketId;
                onMarketChanged(marketId);

                setTimeout(function () {
                    var areaSelect = document.getElementById('areaSelect');
                    if (areaSelect && areaId) {
                        areaSelect.value = areaId;
                        onAreaChanged(areaId);

                        setTimeout(function () {
                            var stallSelect = document.getElementById('stallSelect');
                            if (stallSelect) {
                                stallSelect.value = stallCode;
                                onStallChanged();
                            }
                        }, 50);
                    }
                }, 50);
            }

            var formEl = document.getElementById('registerFormContainer');
            if (formEl) {
                formEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    </script>

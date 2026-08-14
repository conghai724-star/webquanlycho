    <!-- ================= HERO TIỂU THƯƠNG ================= -->
    <section class="hero" style="padding: 60px 0 80px;">
        <div class="hero-grid-pattern"></div>
        <div class="container hero-content" style="grid-template-columns: 1fr; text-align: center;">
            <div>
                <div class="hero-eyebrow" style="justify-content: center;"><span class="dot"></span> Danh bạ chợ</div>
                <h1>Danh sách Tiểu thương</h1>
                <?php 
                $selectedMarketName = $this->data['selectedMarketName'] ?? '';
                ?>
                <p style="margin: 0 auto; max-width: 90% !important;">
                    Tra cứu thông tin tiểu thương kinh doanh chính thức tại <b><?php echo !empty($selectedMarketName) ? htmlspecialchars($selectedMarketName) : 'các khu chợ trên địa bàn'; ?></b>.
                </p>
            </div>
        </div>
    </section>

    <!-- ================= DANH SÁCH & BỘ LỌC ================= -->
    <section style="padding: 60px 0;">
        <div class="container">
            
            
            <?php 
            $markets = $this->data['markets'] ?? [];
            $businessLines = $this->data['businessLines'] ?? [];
            $areas = $this->data['areas'] ?? [];
            $marketId = (int)($this->data['marketId'] ?? 0);
            $businessLineId = (int)($this->data['businessLineId'] ?? 0);
            $areaId = (int)($this->data['areaId'] ?? 0);
            $searchQuery = $this->data['searchQuery'] ?? '';
            $hasActiveFilter = ($marketId > 0 || $businessLineId > 0 || $areaId > 0 || $searchQuery !== '');
            ?>

            <!-- ================= BỘ LỌC ĐA NĂNG TIỂU THƯƠNG ================= -->
            <div style="background: #ffffff; border: 1px solid var(--gray-300, #cbd5e1); border-radius: 16px; padding: 24px; margin: 0 auto 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); max-width: 1000px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 16px;">
                    <!-- Lọc Chợ -->
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--gray-800, #1e293b); margin-bottom: 6px;">
                            <i class="fa-solid fa-store" style="color: var(--blue-700);"></i> Chọn Chợ
                        </label>
                        <select id="marketFilterSelect" class="form-control" style="width: 100%; height: 42px; border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 0 12px; font-weight: 600; font-size: 13.5px; background: #f8fafc; color: var(--gray-900);" onchange="onMarketFilterChange()">
                            <option value="0" <?php echo ($marketId === 0) ? 'selected' : ''; ?>>🏪 Tất cả các chợ</option>
                            <?php foreach ($markets as $m): ?>
                                <option value="<?php echo $m['market_id']; ?>" <?php echo ($marketId === (int)$m['market_id']) ? 'selected' : ''; ?>>
                                    🏪 <?php echo htmlspecialchars($m['market_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Lọc Ngành hàng -->
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--gray-800, #1e293b); margin-bottom: 6px;">
                            <i class="fa-solid fa-tags" style="color: #ea580c;"></i> Ngành Hàng
                        </label>
                        <select id="businessLineFilterSelect" class="form-control" style="width: 100%; height: 42px; border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 0 12px; font-weight: 600; font-size: 13.5px; background: #f8fafc; color: var(--gray-900);" onchange="applyFilters()">
                            <option value="0" <?php echo ($businessLineId === 0) ? 'selected' : ''; ?>>🏷️ Tất cả ngành hàng</option>
                            <?php foreach ($businessLines as $bl): ?>
                                <option value="<?php echo $bl['line_id']; ?>" <?php echo ($businessLineId === (int)$bl['line_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($bl['line_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Lọc Khu vực -->
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--gray-800, #1e293b); margin-bottom: 6px;">
                            <i class="fa-solid fa-layer-group" style="color: #0284c7;"></i> Phân Khu / Khu Vực
                        </label>
                        <select id="areaFilterSelect" class="form-control" style="width: 100%; height: 42px; border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 0 12px; font-weight: 600; font-size: 13.5px; background: #f8fafc; color: var(--gray-900);" onchange="applyFilters()">
                            <option value="0" <?php echo ($areaId === 0) ? 'selected' : ''; ?>>📍 Tất cả phân khu</option>
                            <?php 
                            if (!empty($areas)): 
                                if ($marketId === 0) {
                                    $groupedAreas = [];
                                    foreach ($areas as $a) {
                                        $mName = $a['market_name'] ?? 'Chợ khác';
                                        $groupedAreas[$mName][] = $a;
                                    }
                                    foreach ($groupedAreas as $mName => $aList):
                            ?>
                                        <optgroup label="🏪 <?php echo htmlspecialchars($mName); ?>">
                                            <?php foreach ($aList as $a): ?>
                                                <option value="<?php echo $a['area_id']; ?>" <?php echo ($areaId === (int)$a['area_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($a['area_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                            <?php 
                                    endforeach;
                                } else {
                                    foreach ($areas as $a): 
                            ?>
                                        <option value="<?php echo $a['area_id']; ?>" <?php echo ($areaId === (int)$a['area_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($a['area_name']); ?>
                                        </option>
                            <?php 
                                    endforeach;
                                }
                            endif; 
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Dòng tìm kiếm và nút thao tác -->
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 260px; position: relative;">
                        <input type="text" id="traderSearchInput" placeholder="Nhập tên tiểu thương, số điện thoại, mã sạp..." style="width: 100%; height: 44px; border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 0 14px 0 40px; font-size: 14px; background: #ffffff; color: var(--gray-900); outline: none;" value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    </div>
                    <button class="btn btn-primary btn-sm" style="height: 44px; padding: 0 20px; border-radius: 10px; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;" onclick="applyFilters()">
                        <i class="fa-solid fa-filter"></i> Lọc kết quả
                    </button>
                    <?php if ($hasActiveFilter): ?>
                        <a href="<?php echo BASE_URL; ?>home/traders" class="btn btn-outline btn-sm" style="height: 44px; padding: 0 16px; border-radius: 10px; font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px; color: #ef4444; border-color: #fca5a5;">
                            <i class="fa-solid fa-rotate-left"></i> Đặt lại
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Thống kê kết quả -->
                <div style="margin-top: 14px; font-size: 13px; color: var(--gray-600); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; border-top: 1px dashed #e2e8f0; padding-top: 10px;">
                    <div>
                        Tìm thấy <b style="color: var(--blue-700); font-size: 14px;"><?php echo (int)($this->data['totalTraders'] ?? 0); ?></b> tiểu thương phù hợp
                    </div>
                    <?php if ($hasActiveFilter): ?>
                        <span style="font-size: 12px; color: #64748b;"><i class="fa-solid fa-circle-check" style="color:#22c55e;"></i> Đang áp dụng bộ lọc nâng cao</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (empty($traders)): ?>
                <div style="text-align: center; padding: 50px 20px; border: 1.5px dashed var(--gray-300); border-radius: var(--radius-md); color: var(--gray-600); background: #ffffff;">
                    <i class="fa-solid fa-user-slash" style="font-size: 36px; color: #94a3b8; margin-bottom: 12px;"></i>
                    <p style="font-size: 15px; font-weight: 600; color: #334155; margin-bottom: 6px;">Không tìm thấy tiểu thương nào phù hợp với bộ lọc hiện tại.</p>
                    <p style="font-size: 13.5px; color: #64748b; margin-bottom: 16px;">Vui lòng thử chọn ngành hàng hoặc phân khu khác, hoặc xóa bớt từ khóa tìm kiếm.</p>
                    <a href="<?php echo BASE_URL; ?>home/traders" class="btn btn-primary btn-sm" style="border-radius: 8px;">Xem tất cả tiểu thương</a>
                </div>
            <?php else: ?>
                <div class="stalls-grid" id="traderGrid">
                    <?php foreach ($traders as $t): ?>
                        <div class="stall-card trader-card" data-fullname="<?php echo htmlspecialchars(mb_strtolower($t['fullname'], 'UTF-8')); ?>" data-line="<?php echo htmlspecialchars(mb_strtolower($t['business_line_name'] ?? '', 'UTF-8')); ?>">
                            <div class="stall-top" style="margin-bottom: 8px;">
                                <div>
                                    <div class="stall-code" style="color: var(--blue-700); font-size: 17px;"><?php echo htmlspecialchars($t['fullname']); ?></div>
                                    <div class="stall-zone" style="margin-top: 4px; font-weight: 600; font-size: 13px;">Mã: <?php echo htmlspecialchars($t['trader_code']); ?></div>
                                </div>
                                <span class="badge" style="background: #E8F5E9; color: #2E7D32;">Hoạt động</span>
                            </div>
                            <div class="stall-meta" style="margin-top: 14px; margin-bottom: 0; font-size: 13.5px; border-top: 1px solid var(--gray-100); padding-top: 12px;">
                                <div style="margin-bottom: 8px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    <span style="background: #e0f2fe; color: #0284c7; font-size: 11.5px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                        🏪 <?php echo htmlspecialchars($t['market_name'] ?? 'Chợ chung'); ?>
                                    </span>
                                    <?php if (!empty($t['area_name'])): ?>
                                        <span style="background: #f1f5f9; color: #475569; font-size: 11.5px; font-weight: 600; padding: 2px 8px; border-radius: 4px;">
                                            📍 <?php echo htmlspecialchars($t['area_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div>Ngành hàng: <b style="color: var(--blue-700);"><?php echo htmlspecialchars($t['business_line_name'] ?? 'Chưa cập nhật'); ?></b></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- ================= PHÂN TRANG ================= -->
            <?php if (($this->data['totalPages'] ?? 0) > 1): ?>
                <?php 
                $currentPage = $this->data['currentPage'] ?? 1;
                $totalPages = $this->data['totalPages'] ?? 1;
                $baseUrl = BASE_URL . 'home/traders?';
                if ($marketId > 0) {
                    $baseUrl .= 'market_id=' . $marketId . '&';
                }
                if ($businessLineId > 0) {
                    $baseUrl .= 'business_line_id=' . $businessLineId . '&';
                }
                if ($areaId > 0) {
                    $baseUrl .= 'area_id=' . $areaId . '&';
                }
                if ($searchQuery !== '') {
                    $baseUrl .= 'search=' . urlencode($searchQuery) . '&';
                }
                ?>
                <div class="pagination" style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 40px; flex-wrap: wrap;">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?php echo $baseUrl; ?>page=<?php echo $currentPage - 1; ?>" class="btn btn-outline btn-sm" style="border-radius: 99px; padding: 6px 16px;">&larr; Trước</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?php echo $baseUrl; ?>page=<?php echo $i; ?>" class="btn btn-sm <?php echo $i === $currentPage ? 'btn-primary' : 'btn-outline'; ?>" style="border-radius: 50%; width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; padding: 0;">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?php echo $baseUrl; ?>page=<?php echo $currentPage + 1; ?>" class="btn btn-outline btn-sm" style="border-radius: 99px; padding: 6px 16px;">Sau &rarr;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div style="text-align: center; margin-top: 40px;">
                <a href="<?php echo BASE_URL; ?>" class="btn btn-outline">Quay lại Trang chủ</a>
            </div>
        </div>
    </section>

    <!-- Script gom tất cả bộ lọc vào 1 luồng xử lý đồng bộ -->
    <script>
        function onMarketFilterChange() {
            const areaSelect = document.getElementById('areaFilterSelect');
            if (areaSelect) areaSelect.value = '0';
            applyFilters();
        }

        function applyFilters() {
            const mId = document.getElementById('marketFilterSelect') ? document.getElementById('marketFilterSelect').value : 0;
            const blId = document.getElementById('businessLineFilterSelect') ? document.getElementById('businessLineFilterSelect').value : 0;
            const aId = document.getElementById('areaFilterSelect') ? document.getElementById('areaFilterSelect').value : 0;
            const query = document.getElementById('traderSearchInput') ? document.getElementById('traderSearchInput').value.trim() : '';

            let params = [];
            if (mId > 0) params.push('market_id=' + encodeURIComponent(mId));
            if (blId > 0) params.push('business_line_id=' + encodeURIComponent(blId));
            if (aId > 0) params.push('area_id=' + encodeURIComponent(aId));
            if (query !== '') params.push('search=' + encodeURIComponent(query));

            let url = '<?php echo BASE_URL; ?>home/traders';
            if (params.length > 0) {
                url += '?' + params.join('&');
            }
            window.location.href = url;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('traderSearchInput');
            if (searchInput) {
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        applyFilters();
                    }
                });
            }
        });
    </script>

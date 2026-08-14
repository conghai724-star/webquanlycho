    <!-- ================= HERO TIỂU THƯƠNG ================= -->
    <section class="hero" style="padding: 60px 0 80px;">
        <div class="hero-grid-pattern"></div>
        <div class="container hero-content" style="grid-template-columns: 1fr; text-align: center;">
            <div>
                <div class="hero-eyebrow" style="justify-content: center;"><span class="dot"></span> Danh bạ chợ</div>
                <h1>Danh sách Tiểu thương</h1>
                <p style="margin: 0 auto; max-width: 90% !important;">Tra cứu thông tin tiểu thương kinh doanh chính thức tại Chợ Trung Tâm.</p>
            </div>
        </div>
    </section>

    <!-- ================= DANH SÁCH & BỘ LỌC ================= -->
    <section style="padding: 60px 0;">
        <div class="container">
            <?php if (!($this->data['isLoggedIn'] ?? false)): ?>
                <div style="background: var(--blue-50); border: 1.5px solid var(--blue-700); padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 24px; font-size: 14px; color: var(--blue-900); text-align: center; font-weight: 500;">
                    🔒 Để đảm bảo an toàn thông tin, số điện thoại và địa chỉ của tiểu thương đã được thu gọn. 
                    Vui lòng <a href="<?php echo BASE_URL; ?>home/login" style="font-weight: 700; text-decoration: underline; color: var(--blue-700);">Đăng nhập tài khoản BQL</a> để xem đầy đủ chi tiết.
                </div>
            <?php endif; ?>
            
            <div style="max-width: 600px; margin: 0 auto 40px;">
                <div class="map-search" style="background: var(--gray-100); border: 1px solid var(--gray-300); padding: 6px; margin: 0;">
                    <input type="text" id="traderSearchInput" placeholder="Nhập tên tiểu thương, số điện thoại hoặc ngành hàng..." style="color: var(--gray-900);" value="<?php echo htmlspecialchars($this->data['searchQuery'] ?? ''); ?>">
                    <button class="btn btn-primary btn-sm" style="border-radius: 10px;">Tìm kiếm</button>
                </div>
            </div>

            <?php if (empty($traders)): ?>
                <div style="text-align: center; padding: 40px; border: 1.5px dashed var(--gray-300); border-radius: var(--radius-md); color: var(--gray-600);">
                    Không có dữ liệu tiểu thương hoạt động.
                </div>
            <?php else: ?>
                <div class="stalls-grid" id="traderGrid">
                    <?php foreach ($traders as $t): ?>
                        <div class="stall-card trader-card" data-fullname="<?php echo htmlspecialchars(mb_strtolower($t['fullname'], 'UTF-8')); ?>" data-line="<?php echo htmlspecialchars(mb_strtolower($t['business_line_name'] ?? '', 'UTF-8')); ?>">
                            <div class="stall-top" style="margin-bottom: 8px;">
                                <div>
                                    <div class="stall-code" style="color: var(--blue-700);"><?php echo htmlspecialchars($t['fullname']); ?></div>
                                    <div class="stall-zone" style="margin-top: 4px; font-weight: 600;">Mã: <?php echo htmlspecialchars($t['trader_code']); ?></div>
                                </div>
                                <span class="badge" style="background: #E8F5E9; color: #2E7D32;">Hoạt động</span>
                            </div>
                            <div class="stall-meta" style="margin-top: 14px; margin-bottom: 0; font-size: 13.5px; border-top: 1px solid var(--gray-100); padding-top: 12px;">
                                <div style="margin-bottom: 6px;">Ngành hàng: <b><?php echo htmlspecialchars($t['business_line_name'] ?? 'Chưa cập nhật'); ?></b></div>
                                <div style="margin-bottom: 6px;">Điện thoại: <b><?php echo htmlspecialchars($t['phone']); ?></b></div>
                                <div>Địa chỉ: <b><?php echo htmlspecialchars($t['address'] ?? 'Tại chợ'); ?></b></div>
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
                $searchQuery = $this->data['searchQuery'] ?? '';
                $baseUrl = BASE_URL . 'home/traders?';
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('traderSearchInput');
            const searchBtn = document.querySelector('.map-search button');
            
            function doSearch() {
                const query = searchInput ? searchInput.value.trim() : '';
                window.location.href = '<?php echo BASE_URL; ?>home/traders?search=' + encodeURIComponent(query);
            }
            
            if (searchBtn) {
                searchBtn.addEventListener('click', doSearch);
            }
            if (searchInput) {
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        doSearch();
                    }
                });
            }
        });
    </script>

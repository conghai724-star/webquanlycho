    <!-- ================= FOOTER ================= -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <div class="brand-mark" style="width:38px;height:38px;"><svg width="20" height="20"
                                viewBox="0 0 24 24" fill="none">
                                <path d="M3 9L12 3L21 9V20C21 20.55 20.55 21 20 21H4C3.45 21 3 20.55 3 20V9Z"
                                    stroke="white" stroke-width="1.8" stroke-linejoin="round" />
                            </svg></div>
                        <strong>Chợ Trung Tâm Thành Phố</strong>
                    </div>
                    <p class="desc">Cổng thông tin điện tử phục vụ người dân, tiểu thương và công tác quản lý chợ truyền
                        thống.</p>
                    <?php 
                    $fCfg = $this->data['settings'] ?? [];
                    $fbUrl = !empty($fCfg['contact_facebook']) ? $fCfg['contact_facebook'] : '#';
                    $zaloUrl = !empty($fCfg['contact_zalo']) ? $fCfg['contact_zalo'] : '#';
                    ?>
                    <div class="social-row" style="margin-top:20px;">
                        <a href="<?php echo htmlspecialchars($fbUrl); ?>" target="_blank" aria-label="Facebook">f</a>
                        <a href="<?php echo htmlspecialchars($zaloUrl); ?>" target="_blank" aria-label="Zalo">Z</a>
                    </div>
                </div>

                <div>
                    <h4>Liên kết nhanh</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>home/about">Giới thiệu</a></li>
                        <li><a href="<?php echo BASE_URL; ?>home/map">Sơ đồ chợ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>home/posts">Tin tức</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Dịch vụ</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>home/register">Đăng ký thuê sạp</a></li>
                        <li><a href="<?php echo BASE_URL; ?>home/map">Tra cứu sạp</a></li>
                        <li><a href="<?php echo BASE_URL; ?>home/traders">Tiểu thương</a></li>
                        <li><a href="<?php echo BASE_URL; ?>home/contact">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Thông tin liên hệ</h4>
                    <?php 
                    $fCfg = $this->data['settings'] ?? [];
                    $fAddr = $fCfg['contact_office_address'] ?? '123 Đường Nguyễn Huệ, TP.';
                    $fHotline = $fCfg['contact_hotline'] ?? 'Hotline: 1900 1234';
                    $fEmail = $fCfg['contact_email'] ?? 'bqlcho.trungtam@thanhpho.gov.vn';
                    ?>
                    <ul>
                        <li><?php echo htmlspecialchars($fAddr); ?></li>
                        <li>Hotline: <?php echo htmlspecialchars($fHotline); ?></li>
                        <li><?php echo htmlspecialchars($fEmail); ?></li>
                    </ul>
                </div>

            </div>
            <div class="footer-bottom">
                <span>© 2026 Ban Quản lý Chợ. All Rights Reserved.</span>
                <span>Lượt truy cập: <strong><?php echo number_format($this->data['total_views'] ?? 3541); ?></strong></span>
                <span>Thiết kế theo chuẩn cổng thông tin điện tử</span>
            </div>
        </div>
    </footer>

    <script>
        const header = document.getElementById('siteHeader');
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 8);
        });

        const menuToggle = document.getElementById('menuToggle');
        const mobileNav = document.getElementById('mobileNav');
        menuToggle.addEventListener('click', () => {
            const isOpen = mobileNav.classList.toggle('open');
            menuToggle.classList.toggle('open', isOpen);
            menuToggle.setAttribute('aria-expanded', isOpen);
        });
        mobileNav.addEventListener('click', (e) => {
            if (e.target === mobileNav || e.target.tagName === 'A') {
                mobileNav.classList.remove('open');
                menuToggle.classList.remove('open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });

        const reveals = document.querySelectorAll('[data-reveal]');
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
        }, { threshold: 0.12 });
        reveals.forEach(el => io.observe(el));

        document.querySelectorAll('.gallery-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.gallery-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });
    </script>
</body>

</html>

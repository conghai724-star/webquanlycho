    <!-- ================= FOOTER ================= -->
    <?php 
    $fCfg = $settings ?? $this->view->data['settings'] ?? $this->data['settings'] ?? [];
    $fbUrl = !empty($fCfg['contact_facebook']) ? $fCfg['contact_facebook'] : 'https://facebook.com';
    $zaloUrl = !empty($fCfg['contact_zalo']) ? $fCfg['contact_zalo'] : 'https://zalo.me';
    if (($zaloUrl === 'https://zalo.me' || $zaloUrl === 'https://zalo.me/') && !empty($fCfg['contact_phone'])) {
        $cleanPhone = preg_replace('/[^0-9]/', '', $fCfg['contact_phone']);
        if (!empty($cleanPhone)) {
            $zaloUrl = 'https://zalo.me/' . $cleanPhone;
        }
    }
    $fAddr = $fCfg['contact_office_address'] ?? '123 Đường Nguyễn Huệ, TP. Quảng Ngãi';
    $rawHotline = $fCfg['contact_hotline'] ?? '1900 1234';
    $cleanHotline = trim(preg_replace('/^Hotline:\s*/i', '', $rawHotline));
    $fEmail = $fCfg['contact_email'] ?? 'bqlcho.trungtam@thanhpho.gov.vn';
    ?>
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="<?php echo BASE_URL; ?>" class="footer-brand" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                        <div class="brand-mark" style="width:38px;height:38px; overflow:hidden;">
                            <?php if (!empty($fCfg['website_logo'])): ?>
                                <img src="<?php echo htmlspecialchars($fCfg['website_logo']); ?>" alt="Logo" style="width:100%; height:100%; object-fit:contain;">
                            <?php else: ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M3 9L12 3L21 9V20C21 20.55 20.55 21 20 21H4C3.45 21 3 20.55 3 20V9Z"
                                        stroke="white" stroke-width="1.8" stroke-linejoin="round" />
                                </svg>
                            <?php endif; ?>
                        </div>
                        <strong><?php echo htmlspecialchars($fCfg['website_name'] ?? 'Chợ Trung Tâm Thành Phố'); ?></strong>
                    </a>
                    <p class="desc">Cổng thông tin điện tử phục vụ người dân, tiểu thương và công tác quản lý chợ truyền
                        thống.</p>
                    <div class="social-row" style="margin-top:20px; display:flex; gap:10px;">
                        <a href="<?php echo htmlspecialchars($fbUrl); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#1877F2; color:#fff; text-decoration:none; box-shadow: 0 2px 6px rgba(24,119,242,0.3); transition: transform 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#ffffff">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="<?php echo htmlspecialchars($zaloUrl); ?>" target="_blank" rel="noopener noreferrer" aria-label="Zalo" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#0068FF; color:#fff; text-decoration:none; font-family:Arial, sans-serif; font-size:12px; font-weight:900; letter-spacing:-0.5px; box-shadow: 0 2px 6px rgba(0,104,255,0.3); transition: transform 0.2s;">
                            Zalo
                        </a>
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
                    <ul>
                        <li><?php echo htmlspecialchars($fAddr); ?></li>
                        <li>Hotline: <a href="tel:<?php echo preg_replace('/[^0-9]/', '', $cleanHotline); ?>" style="color:inherit; text-decoration:none; font-weight:600;"><?php echo htmlspecialchars($cleanHotline); ?></a></li>
                        <li><a href="mailto:<?php echo htmlspecialchars($fEmail); ?>" style="color:inherit; text-decoration:none;"><?php echo htmlspecialchars($fEmail); ?></a></li>
                    </ul>
                </div>

            </div>
            <div class="footer-bottom">
                <span>© 2026 Ban Quản lý Chợ. All Rights Reserved.</span>
                <span>Lượt truy cập: <strong><?php echo number_format($this->view->data['total_views'] ?? $this->data['total_views'] ?? 3541); ?></strong></span>
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

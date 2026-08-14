    <!-- ================= HERO ================= -->
    <?php 
    $banners = !empty($this->data['banners']) ? $this->data['banners'] : [
        [
            'banner_title' => 'Hệ Thống Quản Lý Chợ Tỉnh Quảng Ngãi',
            'banner_image' => 'https://images.unsplash.com/photo-1533900298318-6b8da08a523e?q=80&w=1200&auto=format&fit=crop',
            'banner_link'  => BASE_URL . 'home/map'
        ]
    ];
    $activeBanner = $banners[0];
    ?>
    <section class="hero">
        <div class="hero-grid-pattern"></div>
        <div class="container hero-content">
            <div>
                <div class="hero-eyebrow"><span class="dot"></span> Cổng thông tin điện tử chợ truyền thống</div>
                <h1 id="hero-banner-title"><?php echo htmlspecialchars($activeBanner['banner_title']); ?></h1>
                <p id="hero-banner-desc"><?php echo htmlspecialchars(!empty($activeBanner['banner_description']) ? $activeBanner['banner_description'] : 'Tra cứu sơ đồ chợ, tìm kiếm vị trí sạp, xem thông tin tiểu thương và đăng ký thuê sạp trực tuyến — nhanh chóng, minh bạch, mọi lúc mọi nơi.'); ?></p>
                <div class="hero-actions">

                    <a href="<?php echo BASE_URL; ?>home/map" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M3 6L9 4L15 6L21 4V18L15 20L9 18L3 20V6Z" stroke="white" stroke-width="1.8"
                                stroke-linejoin="round" />
                            <path d="M9 4V18M15 6V20" stroke="white" stroke-width="1.8" />
                        </svg>
                        Xem sơ đồ chợ
                    </a>
                    <a href="<?php echo BASE_URL; ?>home/register" class="btn btn-secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5V19M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        Đăng ký thuê sạp
                    </a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-visual-card" style="position: relative; overflow: hidden;">
                    <?php
                    $bLink = $activeBanner['banner_link'] ?? '';
                    if (empty($bLink)) {
                        $initBannerUrl = BASE_URL . 'home/map';
                    } elseif (preg_match('/^(https?:\/\/|\/\/|#)/i', $bLink)) {
                        $initBannerUrl = $bLink;
                    } else {
                        $initBannerUrl = BASE_URL . ltrim($bLink, '/');
                    }
                    ?>
                    <a id="hero-banner-link" href="<?php echo htmlspecialchars($initBannerUrl); ?>" style="display:block; width:100%; height:100%;">
                        <img id="hero-banner-img" src="<?php echo htmlspecialchars($activeBanner['banner_image']); ?>" alt="<?php echo htmlspecialchars($activeBanner['banner_title']); ?>" style="transition: opacity 0.4s ease-in-out;">
                    </a>
                    <?php if (count($banners) > 1): ?>
                        <div style="position: absolute; bottom: 12px; left: 0; right: 0; display: flex; justify-content: center; gap: 6px; z-index: 5;">
                            <?php foreach ($banners as $idx => $b): ?>
                                <button type="button" class="banner-dot <?php echo $idx === 0 ? 'active' : ''; ?>" onclick="setHeroBanner(<?php echo $idx; ?>)" style="width: 10px; height: 10px; border-radius: 50%; border: none; background: <?php echo $idx === 0 ? '#ffffff' : 'rgba(255,255,255,0.5)'; ?>; cursor: pointer; padding: 0; transition: all 0.2s;"></button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script>
    var heroBanners = <?php echo json_encode($banners); ?>;
    var currentBannerIdx = 0;

    function resolveBannerUrl(url) {
        if (!url) return '<?php echo BASE_URL; ?>home/map';
        if (/^(https?:\/\/|\/\/|#)/i.test(url)) return url;
        return '<?php echo BASE_URL; ?>' + url.replace(/^\/+/, '');
    }

    function setHeroBanner(idx) {
        if (!heroBanners || heroBanners.length === 0) return;
        currentBannerIdx = idx;
        var b = heroBanners[idx];
        var imgEl = document.getElementById('hero-banner-img');
        var titleEl = document.getElementById('hero-banner-title');
        var descEl = document.getElementById('hero-banner-desc');
        var linkEl = document.getElementById('hero-banner-link');

        if (imgEl) {
            imgEl.style.opacity = 0;
            setTimeout(function() {
                imgEl.src = b.banner_image;
                imgEl.alt = b.banner_title;
                imgEl.style.opacity = 1;
            }, 200);
        }
        if (titleEl) titleEl.innerText = b.banner_title;
        if (descEl && b.banner_description) descEl.innerText = b.banner_description;
        if (linkEl) linkEl.href = resolveBannerUrl(b.banner_link);


        document.querySelectorAll('.banner-dot').forEach(function(dot, dIdx) {
            if (dIdx === idx) {
                dot.style.background = '#ffffff';
                dot.style.transform = 'scale(1.3)';
            } else {
                dot.style.background = 'rgba(255,255,255,0.5)';
                dot.style.transform = 'scale(1)';
            }
        });
    }

    if (heroBanners && heroBanners.length > 1) {
        setInterval(function() {
            var nextIdx = (currentBannerIdx + 1) % heroBanners.length;
            setHeroBanner(nextIdx);
        }, 5000);
    }
    </script>


    <!-- ================= STATS ================= -->
    <div class="container stats-wrap">
        <div class="stats-grid" data-reveal>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M3 9L12 3L21 9V20C21 20.55 20.55 21 20 21H4C3.45 21 3 20.55 3 20V9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <path d="M9 21V12H15V21" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div class="stat-num"><?php echo (int)($this->data['total_markets'] ?? 0); ?></div>
                    <div class="stat-label">Tổng số chợ</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M4 4H10V10H4V4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <path d="M14 4H20V10H14V4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <path d="M4 14H10V20H4V14Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <path d="M14 14H20V20H14V14Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div class="stat-num"><?php echo $this->data['total_areas'] ?? 0; ?></div>
                    <div class="stat-label">Tổng số khu vực</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.8" />
                        <rect x="14" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.8" />
                        <rect x="3" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.8" />
                        <rect x="14" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.8" />
                    </svg></div>
                <div>
                    <div class="stat-num"><?php echo number_format($this->data['total_stalls'] ?? 0); ?></div>
                    <div class="stat-label">Tổng số sạp</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <circle cx="9" cy="8" r="3.2" stroke="currentColor" stroke-width="1.8" />
                        <path d="M3 20C3 16.5 5.7 14 9 14C12.3 14 15 16.5 15 20" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" />
                        <circle cx="17.5" cy="9" r="2.4" stroke="currentColor" stroke-width="1.8" />
                        <path d="M15.2 14.4C18 14.7 20 16.9 20 20" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg></div>
                <div>
                    <div class="stat-num"><?php echo number_format($this->data['total_traders'] ?? 0); ?></div>
                    <div class="stat-label">Tiểu thương đang hoạt động</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M12 21C12 21 5 15.5 5 10.2C5 6.8 7.7 4.5 10.5 4.5C11.7 4.5 12 5.1 12 5.1C12 5.1 12.3 4.5 13.5 4.5C16.3 4.5 19 6.8 19 10.2C19 15.5 12 21 12 21Z"
                            stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    </svg></div>
                <div>
                    <div class="stat-num"><?php echo number_format($this->data['total_vacant_stalls'] ?? 0); ?></div>
                    <div class="stat-label">Sạp còn trống</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= GIỚI THIỆU ================= -->
    <section id="gioithieu">
        <div class="container intro-grid">
            <div class="intro-img" data-reveal>
                <img src="<?php echo htmlspecialchars($settings['home_intro_image'] ?? 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?q=80&w=1000&auto=format&fit=crop'); ?>"
                    alt="Toàn cảnh chợ" onerror="this.src='https://images.unsplash.com/photo-1488459716781-31db52582fe9?q=80&w=1000&auto=format&fit=crop'">
            </div>
            <div data-reveal>
                <div class="eyebrow"><?php echo htmlspecialchars($settings['home_intro_eyebrow'] ?? 'Giới thiệu chợ'); ?></div>
                <h2 class="section-title"><?php echo htmlspecialchars($settings['home_intro_title'] ?? 'Hơn 40 năm gắn bó với đời sống người dân thành phố'); ?></h2>
                <p class="section-desc"><?php echo nl2br(htmlspecialchars($settings['home_intro_desc'] ?? 'Hình thành từ năm 1985, chợ Trung Tâm Thành Phố là đầu mối buôn bán sầm uất, quy tụ hàng nghìn tiểu thương thuộc nhiều ngành hàng khác nhau, đồng hành cùng quá trình chuyển đổi số của địa phương.')); ?></p>
                <?php 
                $introPoints = [];
                if (!empty($settings['home_intro_points'])) {
                    $introPoints = json_decode($settings['home_intro_points'], true) ?: [];
                }
                if (empty($introPoints)) {
                    for ($i = 1; $i <= 10; $i++) {
                        $pTitle = $settings["home_intro_point_{$i}_title"] ?? '';
                        $pDesc = $settings["home_intro_point_{$i}_desc"] ?? '';
                        if (!empty($pTitle) || !empty($pDesc)) {
                            $introPoints[] = ['title' => $pTitle, 'desc' => $pDesc];
                        }
                    }
                }
                ?>
                <ul class="intro-list">
                    <?php foreach ($introPoints as $pt): ?>
                        <li><span class="check">✓</span>
                            <div><b><?php echo htmlspecialchars($pt['title'] ?? ''); ?></b><span><?php echo htmlspecialchars($pt['desc'] ?? ''); ?></span></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo BASE_URL; ?>home/about" class="btn btn-outline">Xem thêm</a>
            </div>
        </div>
    </section>

    <!-- ================= CHỨC NĂNG NỔI BẬT ================= -->
    <section class="bg-gray">
        <div class="container">
            <div style="text-align:center; max-width:90%; margin:0 auto 48px;" data-reveal>
                <div class="eyebrow" style="justify-content:center;">Chức năng nổi bật</div>
                <h2 class="section-title">Mọi thứ bạn cần, trong một cổng thông tin</h2>
                <p class="section-desc" style="margin-left:auto; margin-right:auto;">Các công cụ tra cứu và dịch vụ trực
                    tuyến dành cho người dân, tiểu thương và ban quản lý.</p>
            </div>
            <div class="features-grid" data-reveal>
                <div class="feature-card">
                    <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M3 6L9 4L15 6L21 4V18L15 20L9 18L3 20V6Z" stroke="currentColor" stroke-width="1.8"
                                stroke-linejoin="round" />
                        </svg></div>
                    <h3>Tra cứu sơ đồ chợ</h3>
                    <p>Xem bản đồ số của toàn bộ khu vực và vị trí sạp một cách trực quan.</p>
                    <a href="<?php echo BASE_URL; ?>home/map" class="feature-link">Xem bản đồ →</a>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                            <path d="M21 21L16.5 16.5" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg></div>
                    <h3>Tra cứu vị trí sạp</h3>
                    <p>Tìm kiếm nhanh theo mã sạp hoặc khu vực chỉ trong vài giây.</p>
                    <a href="<?php echo BASE_URL; ?>home/map" class="feature-link">Tra cứu ngay →</a>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <circle cx="9" cy="8" r="3.2" stroke="currentColor" stroke-width="1.8" />
                            <path d="M3 20C3 16.5 5.7 14 9 14C12.3 14 15 16.5 15 20" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" />
                        </svg></div>
                    <h3>Thông tin tiểu thương</h3>
                    <p>Tra cứu thông tin tiểu thương được công khai minh bạch.</p>
                    <a href="<?php echo BASE_URL; ?>home/traders" class="feature-link">Xem danh sách →</a>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg></div>
                    <h3>Đăng ký thuê sạp</h3>
                    <p>Đăng ký trực tuyến các sạp còn trống, theo dõi tiến trình xét duyệt.</p>
                    <a href="<?php echo BASE_URL; ?>home/register" class="feature-link">Đăng ký ngay →</a>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M4 5H20M4 12H20M4 19H14" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg></div>
                    <h3>Tin tức</h3>
                    <p>Cập nhật thông báo mới nhất từ Ban quản lý chợ.</p>
                    <a href="<?php echo BASE_URL; ?>home/posts" class="feature-link">Đọc tin tức →</a>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M21 11.5C21 16.75 12 21 12 21C12 21 3 16.75 3 11.5C3 6.8 7 4 12 4C17 4 21 6.8 21 11.5Z"
                                stroke="currentColor" stroke-width="1.8" />
                            <path d="M9 11L11 13L15 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg></div>
                    <h3>Liên hệ</h3>
                    <p>Gửi góp ý và phản ánh trực tuyến đến Ban quản lý.</p>
                    <a href="<?php echo BASE_URL; ?>home/contact" class="feature-link">Gửi phản ánh →</a>
                </div>
            </div>
        </div>
    </section>


    <!-- ================= SẠP CÒN TRỐNG ================= -->
    <section>
        <div class="container">
            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:36px; flex-wrap:wrap; gap:16px;"
                data-reveal>
                <div>
                    <div class="eyebrow">Cơ hội kinh doanh</div>
                    <h2 class="section-title" style="margin-bottom:6px;">Danh sách sạp còn trống</h2>
                    <p style="color:var(--gray-600); font-size:15px;">Cập nhật mới nhất từ hệ thống bản đồ số</p>
                </div>
            </div>
            <div class="stalls-grid" data-reveal>
                <?php 
                $vacantStalls = $this->data['vacantStalls'] ?? [];
                if (!empty($vacantStalls)):
                    foreach ($vacantStalls as $st): 
                ?>
                    <div class="stall-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200); background: #ffffff; padding: 24px; border-radius: var(--radius-lg); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s;">
                        <div class="stall-top" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                            <div>
                                <div class="stall-code" style="font-size: 18px; font-weight: 800; color: var(--gray-900);"><?php echo htmlspecialchars($st['stall_code']); ?></div>
                                <div class="stall-zone" style="font-size: 12.5px; color: var(--gray-600); margin-top: 4px;">
                                    <b>Khu vực:</b> <?php echo htmlspecialchars($st['area_name']); ?>
                                </div>
                                <div style="font-size: 12px; color: var(--primary, #0f766e); font-weight: 700; margin-top: 2px;">
                                    <i class="fa-solid fa-store" style="font-size: 11px;"></i> <?php echo htmlspecialchars($st['market_name']); ?>
                                </div>
                            </div>
                            <span class="badge badge-vacant" style="background-color: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">Còn trống</span>
                        </div>
                        <?php 
                        $unitPrice = (float)($st['stall_base_price'] ?? 0);
                        $areaSize = (float)($st['stall_area_size'] ?? 0);
                        $totalPrice = ($unitPrice > 0 && $areaSize > 0) ? ($unitPrice * $areaSize) : $unitPrice;
                        ?>
                        <div class="stall-meta" style="border-top: 1px solid var(--gray-200); border-bottom: 1px solid var(--gray-200); padding: 12px 0; margin-bottom: 16px; font-size: 13.5px; color: var(--gray-600);">
                            <div style="margin-bottom: 6px;">Diện tích: <b style="color: var(--gray-900);"><?php echo $st['stall_area_size']; ?> m²</b></div>
                            <div style="margin-bottom: 4px;">
                                Giá thuê: <b style="color: var(--primary, #0f766e); font-size: 14.5px;"><?php echo number_format($totalPrice, 0, ',', '.'); ?> đ</b>/tháng
                            </div>
                            <div style="font-size: 11.5px; color: var(--gray-500);">(Đơn giá: <?php echo number_format($unitPrice, 0, ',', '.'); ?> đ/m²)</div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <a href="<?php echo BASE_URL; ?>home/map" class="btn btn-outline btn-sm" style="flex: 1; padding: 8px 0; font-size: 12px; text-align: center;"><i class="fa-solid fa-location-dot"></i> Bản đồ</a>
                            <a href="<?php echo BASE_URL; ?>home/register?stall_code=<?php echo urlencode($st['stall_code']); ?>&market_id=<?php echo (int)$st['market_id']; ?>&area=<?php echo $st['stall_area_size']; ?>" class="btn btn-primary btn-sm" style="flex: 1.3; font-size: 12px; text-align: center; font-weight: 700;">Đăng ký thuê</a>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else: 
                ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #ffffff; border-radius: var(--radius-lg); border: 1px dashed var(--gray-300);">
                        <i class="fa-solid fa-store-slash" style="font-size: 40px; color: var(--gray-400); margin-bottom: 12px;"></i>
                        <p style="color: var(--gray-600); font-size: 14.5px;">Hiện tại toàn bộ các sạp đều đã có tiểu thương kinh doanh. Xin vui lòng liên hệ BQL để đăng ký danh sách chờ.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="section-footer-cta" data-reveal style="margin-top: 30px;">
                <a href="<?php echo BASE_URL; ?>home/register" class="btn btn-primary" style="padding: 12px 28px; font-weight: 700;">
                    <i class="fa-solid fa-store" style="margin-right: 6px;"></i> Xem tất cả sạp trống & Đăng ký thuê
                </a>
            </div>
        </div>
    </section>

    <!-- ================= TIN TỨC ================= -->
    <section class="bg-gray" id="tintuc">
        <div class="container">
            <div style="text-align:center; max-width:90%; margin:0 auto 48px;" data-reveal>
                <div class="eyebrow" style="justify-content:center;">Tin tức</div>
                <h2 class="section-title">Thông báo từ Ban quản lý</h2>
            </div>
            <div class="news-grid" data-reveal>
                <?php if (!empty($this->data['posts'])): ?>
                    <?php foreach ($this->data['posts'] as $post): ?>
                        <?php 
                        $postDate = date('d/m/Y', strtotime($post['created_at']));
                        $postImg = !empty($post['post_image']) ? htmlspecialchars($post['post_image']) : 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=800';
                        $postLink = BASE_URL . 'home/post_detail/' . $post['post_slug'];
                        ?>
                        <div class="news-card">
                            <div class="news-img"><img src="<?php echo $postImg; ?>" alt="<?php echo htmlspecialchars($post['post_title']); ?>"></div>
                            <div class="news-body">
                                <div class="news-date"><?php echo $postDate; ?></div>
                                <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
                                <p><?php echo htmlspecialchars($post['post_summary']); ?></p>
                                <a href="<?php echo $postLink; ?>" class="news-read">Đọc tiếp →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; grid-column: 1/-1; color: var(--gray-600); padding: 20px 0;">Hiện chưa có tin tức hoặc thông báo nào mới.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ================= LIÊN HỆ ================= -->
    <section class="bg-gray" id="lienhe">
        <div class="container contact-grid">
            <div class="contact-info-card" data-reveal>
                <h3>Thông tin liên hệ</h3>
                <p>Ban Quản lý sẵn sàng hỗ trợ người dân và tiểu thương trong giờ hành chính.</p>
                <div class="contact-row">
                    <div class="contact-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 21C12 21 5 15.5 5 10.2C5 6.8 7.7 4.5 10.5 4.5C11.7 4.5 12 5.1 12 5.1C12 5.1 12.3 4.5 13.5 4.5C16.3 4.5 19 6.8 19 10.2C19 15.5 12 21 12 21Z"
                                stroke="white" stroke-width="1.6" />
                        </svg></div>
                    <div><b>Địa chỉ</b><span>123 Đường Nguyễn Huệ, Phường Trung Tâm, Thành phố</span></div>
                </div>
                <div class="contact-row">
                    <div class="contact-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M4 5C4 14.5 9.5 20 19 20L21 16L15.5 14L14 16.5C11.5 15.3 9.5 13.3 8 10.5L10.5 9L8.5 4L4 5Z"
                                stroke="white" stroke-width="1.5" stroke-linejoin="round" />
                        </svg></div>
                    <div><b>Điện thoại</b><span>1900 1234 (giờ hành chính)</span></div>
                </div>
                <div class="contact-row">
                    <div class="contact-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="5" width="18" height="14" rx="2" stroke="white" stroke-width="1.6" />
                            <path d="M3 6L12 13L21 6" stroke="white" stroke-width="1.6" />
                        </svg></div>
                    <div><b>Email</b><span>bqlcho.trungtam@thanhpho.gov.vn</span></div>
                </div>
                <div class="contact-row">
                    <div class="contact-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="8.5" stroke="white" stroke-width="1.6" />
                            <path d="M12 7V12L15.5 14" stroke="white" stroke-width="1.6" stroke-linecap="round" />
                        </svg></div>
                    <div><b>Giờ làm việc</b><span>5:00 – 19:00, tất cả các ngày trong tuần</span></div>
                </div>
            </div>
            <div class="form-card" data-reveal>
                <h3 style="font-size:20px; font-weight:800; margin-bottom:6px;">Gửi góp ý / phản ánh</h3>
                <p style="color:var(--gray-600); font-size:14px; margin-bottom:24px;">Chúng tôi tiếp nhận và phản hồi
                    trong vòng 48 giờ làm việc.</p>
                <div class="form-row">
                    <div class="field"><label>Họ tên</label><input type="text" placeholder="Nguyễn Văn A"></div>
                    <div class="field"><label>Số điện thoại</label><input type="tel" placeholder="09xx xxx xxx"></div>
                </div>
                <div class="field"><label>Email</label><input type="email" placeholder="email@vidu.com"></div>
                <div class="field"><label>Nội dung góp ý</label><textarea
                        placeholder="Nhập nội dung góp ý, phản ánh của bạn..."></textarea></div>
                <button class="btn btn-primary btn-block">Gửi phản ánh</button>
            </div>
        </div>
    </section>

    <!-- ================= GOOGLE MAP ================= -->
    <?php 
    $idxCfg = $settings ?? $this->data['settings'] ?? $this->view->data['settings'] ?? [];
    $idxRawIframe = trim($idxCfg['contact_map_iframe'] ?? '');
    $idxMapUrl = '';
    if (!empty($idxRawIframe)) {
        if (preg_match('/src=[\"\']([^\"\']+)[\"\']/i', $idxRawIframe, $m)) {
            $idxMapUrl = $m[1];
        } elseif (preg_match('/^https?:\/\//i', $idxRawIframe)) {
            $idxMapUrl = $idxRawIframe;
        }
    }
    if (empty($idxMapUrl)) {
        $idxMapSearch = !empty($idxCfg['contact_map_address']) ? $idxCfg['contact_map_address'] : ($idxCfg['contact_office_address'] ?? '123 Đường Nguyễn Huệ, Phường Trung Tâm, Thành phố Quảng Ngãi');
        $idxMapUrl = 'https://maps.google.com/maps?q=' . urlencode($idxMapSearch) . '&t=&z=16&ie=UTF8&iwloc=&output=embed';
    }
    ?>
    <iframe class="map-embed" loading="lazy"
        src="<?php echo htmlspecialchars($idxMapUrl); ?>" style="border:0;" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>

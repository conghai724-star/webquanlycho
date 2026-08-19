    <style>
    /* CSS Tùy biến trang Liên hệ & Phản ánh */
    .contact-page-hero {
        position: relative;
        padding: 60px 0 80px;
        text-align: center;
    }
    .contact-page-hero h1 {
        font-size: clamp(24px, 4vw, 36px);
        font-weight: 800;
        color: #fff;
        margin: 12px 0 16px;
        line-height: 1.25;
    }
    .contact-page-hero p {
        font-size: clamp(14px, 2vw, 16px);
        color: rgba(255, 255, 255, 0.88);
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 32px;
        align-items: start;
    }

    .contact-info-card {
        background: linear-gradient(135deg, var(--blue-900, #0c4a6e), var(--blue-700, #0369a1));
        padding: 36px;
        border-radius: var(--radius-lg, 16px);
        color: #fff;
        box-shadow: 0 10px 25px rgba(12, 74, 110, 0.15);
    }

    .contact-info-card h3 {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 14px;
        border-bottom: 1px solid rgba(255,255,255,0.18);
        padding-bottom: 12px;
        color: #fff;
    }

    .contact-row {
        display: flex;
        gap: 14px;
        margin-bottom: 20px;
        align-items: flex-start;
    }

    .contact-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }

    .form-card {
        padding: 36px;
        border-radius: var(--radius-lg, 16px);
        border: 1px solid var(--gray-300, #e2e8f0);
        background: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .form-card h3 {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--gray-900, #0f172a);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .contact-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .contact-field label {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--gray-800, #1e293b);
    }

    .contact-field input,
    .contact-field select,
    .contact-field textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 14px;
        border: 1.5px solid var(--gray-300, #cbd5e1);
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        background: #fff;
        color: var(--gray-900, #0f172a);
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .contact-field input:focus,
    .contact-field select:focus,
    .contact-field textarea:focus {
        border-color: var(--primary, #0f766e);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .contact-map-container {
        border-radius: var(--radius-lg, 16px);
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid var(--gray-300, #e2e8f0);
        height: 450px;
        background: #f1f5f9;
    }

    /* ================= RESPONSIVE TRÊN MOBILE & TABLET ================= */
    @media (max-width: 991px) {
        .contact-grid {
            display: flex !important;
            flex-direction: column !important;
            grid-template-columns: 1fr !important;
            gap: 28px !important;
            width: 100% !important;
        }
        .contact-info-card,
        .form-card {
            width: 100% !important;
            box-sizing: border-box !important;
            padding: 26px !important;
        }
        .contact-map-container {
            height: 340px !important;
        }
    }

    @media (max-width: 640px) {
        .contact-page-hero {
            padding: 40px 16px 50px !important;
        }
        .contact-grid {
            display: flex !important;
            flex-direction: column !important;
            grid-template-columns: 1fr !important;
            gap: 24px !important;
            width: 100% !important;
        }
        .form-row {
            display: flex !important;
            flex-direction: column !important;
            grid-template-columns: 1fr !important;
            gap: 14px !important;
            margin-bottom: 14px !important;
            width: 100% !important;
        }
        .contact-info-card,
        .form-card {
            width: 100% !important;
            box-sizing: border-box !important;
            padding: 20px 16px !important;
            border-radius: 12px !important;
        }
        .contact-map-container {
            height: 280px !important;
        }
    }
    </style>

    <!-- ================= HERO LIÊN HỆ ================= -->
    <section class="hero contact-page-hero">
        <div class="hero-grid-pattern"></div>
        <div class="container hero-content" style="display: block;">
            <div>
                <div class="hero-eyebrow" style="justify-content: center;"><span class="dot"></span> Liên hệ &amp; Góp ý</div>
                <h1>Liên hệ với Ban quản lý chợ</h1>
                <p>Chúng tôi luôn sẵn sàng lắng nghe ý kiến đóng góp, phản ánh và hỗ trợ giải quyết khó khăn của bà con tiểu thương và người dân.</p>
            </div>
        </div>
    </section>

    <!-- ================= NỘI DUNG LIÊN HỆ ================= -->
    <section style="padding: 50px 0;">
        <div class="container">
            <?php if (isset($this->data['success']) && $this->data['success']): ?>
                <div style="background: #E8F5E9; border: 1px solid #C8E6C9; padding: 40px 20px; border-radius: var(--radius-lg); text-align: center; max-width: 680px; margin: 0 auto; box-shadow: var(--shadow-md);">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #2E7D32; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 30px; margin-bottom: 16px;">✓</div>
                    <h3 style="font-size: 22px; font-weight: 800; color: #2E7D32; margin-bottom: 12px;">Gửi phản ánh thành công!</h3>
                    <p style="color: var(--gray-600); margin-bottom: 24px; font-size: 15px; line-height: 1.6;">Cảm ơn bạn đã gửi ý kiến đóng góp. Ban quản lý sẽ tiếp nhận, xác minh thông tin và phản hồi sớm nhất thông qua số điện thoại hoặc email liên hệ của bạn.</p>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Quay về Trang chủ</a>
                </div>
            <?php else: ?>
                <div class="contact-grid">
                    <?php 
                    $cfg = $this->data['settings'] ?? [];
                    $addr = $cfg['contact_office_address'] ?? '123 Đường Nguyễn Huệ, Phường Trung Tâm, Thành phố Quảng Ngãi';
                    $hotline = $cfg['contact_hotline'] ?? '1900 1234 (Từ 7:30 đến 17:00)';
                    $phone = $cfg['contact_phone'] ?? '';
                    $email = $cfg['contact_email'] ?? 'bqlcho.trungtam@thanhpho.gov.vn';
                    $hours = $cfg['contact_open_hours'] ?? '5:00 – 19:00, tất cả các ngày trong tuần';
                    $intro = $cfg['contact_intro'] ?? 'Ban Quản lý Chợ Trung Tâm sẵn sàng hỗ trợ người dân và tiểu thương trong giờ làm việc.';
                    ?>
                    <div class="contact-info-card">
                        <h3>Thông tin liên hệ</h3>
                        <p style="opacity: 0.88; font-size: 14px; line-height: 1.6; margin-bottom: 24px;"><?php echo htmlspecialchars($intro); ?></p>
                        
                        <div class="contact-row">
                            <div class="contact-icon">📍</div>
                            <div style="flex: 1; min-width: 0;">
                                <b style="display: block; font-size: 13.5px; opacity: 0.85; margin-bottom: 2px;">Địa chỉ văn phòng</b>
                                <span style="font-size: 14.5px; font-weight: 600; line-height: 1.4; word-break: break-word;"><?php echo htmlspecialchars($addr); ?></span>
                            </div>
                        </div>

                        <div class="contact-row">
                            <div class="contact-icon">📞</div>
                            <div style="flex: 1; min-width: 0;">
                                <b style="display: block; font-size: 13.5px; opacity: 0.85; margin-bottom: 2px;">Hotline / Hỗ trợ</b>
                                <span style="font-size: 14.5px; font-weight: 600; line-height: 1.4; word-break: break-word;"><?php echo htmlspecialchars($hotline); ?></span>
                                <?php if (!empty($phone)): ?>
                                    <div style="font-size: 13px; opacity: 0.85; margin-top: 2px;">Điện thoại bàn: <?php echo htmlspecialchars($phone); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="contact-row">
                            <div class="contact-icon">✉️</div>
                            <div style="flex: 1; min-width: 0;">
                                <b style="display: block; font-size: 13.5px; opacity: 0.85; margin-bottom: 2px;">Hộp thư điện tử</b>
                                <span style="font-size: 14.5px; font-weight: 600; line-height: 1.4; word-break: break-all;"><?php echo htmlspecialchars($email); ?></span>
                            </div>
                        </div>

                        <div class="contact-row">
                            <div class="contact-icon">🕒</div>
                            <div style="flex: 1; min-width: 0;">
                                <b style="display: block; font-size: 13.5px; opacity: 0.85; margin-bottom: 2px;">Giờ mở cửa chợ</b>
                                <span style="font-size: 14.5px; font-weight: 600; line-height: 1.4; word-break: break-word;"><?php echo htmlspecialchars($hours); ?></span>
                            </div>
                        </div>

                        <?php 
                        $fb = $cfg['contact_facebook'] ?? '';
                        $zalo = $cfg['contact_zalo'] ?? '';
                        if (!empty($fb) || !empty($zalo)): 
                        ?>
                            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid rgba(255,255,255,0.2); display: flex; gap: 10px; flex-wrap: wrap;">
                                <?php if (!empty($fb)): ?>
                                    <a href="<?php echo htmlspecialchars($fb); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 13.5px; text-decoration: none; font-weight: 600; transition: background 0.2s;">
                                        <i class="fa-brands fa-facebook"></i> Fanpage Facebook
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($zalo)): ?>
                                    <a href="<?php echo htmlspecialchars($zalo); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 13.5px; text-decoration: none; font-weight: 600; transition: background 0.2s;">
                                        <i class="fa-solid fa-comment-dots"></i> Nhắn tin Zalo
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-card">
                        <h3>Gửi góp ý / phản ánh trực tuyến</h3>
                        <p style="color: var(--gray-600, #64748b); font-size: 14px; margin-bottom: 20px; line-height: 1.5;">Chúng tôi tiếp nhận, xác minh và phản hồi trong vòng 48 giờ làm việc.</p>
                        
                        <?php if (!empty($this->data['error'])): ?>
                            <div style="background: #FFEBEE; border: 1px solid #FFCDD2; color: #C62828; padding: 12px; border-radius: 6px; font-size: 13.5px; margin-bottom: 20px;">
                                <?php echo htmlspecialchars($this->data['error']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo BASE_URL; ?>home/contact" method="POST">
                            <!-- PHÂN LOẠI Ý KIẾN -->
                            <div class="contact-field" style="margin-bottom: 16px;">
                                <label>Loại ý kiến phản ánh <span style="color:red">*</span></label>
                                <select name="type" required>
                                    <option value="complaint">⚠️ Khiếu nại (Phản ánh bất cập, tranh chấp, vệ sinh, sự cố...)</option>
                                    <option value="feedback" selected>💡 Góp ý (Đóng góp xây dựng, đề xuất cải tiến dịch vụ chợ...)</option>
                                    <option value="other">📋 Khác (Hỏi đáp thông tin, thủ tục, thắc mắc chung...)</option>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="contact-field">
                                    <label>Họ tên người gửi <span style="color:red">*</span></label>
                                    <input type="text" name="fullname" placeholder="Nguyễn Văn A" required>
                                </div>
                                <div class="contact-field">
                                    <label>Số điện thoại liên hệ <span style="color:red">*</span></label>
                                    <input type="tel" name="phone" placeholder="09xx xxx xxx" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="contact-field">
                                    <label>Tiêu đề ý kiến <span style="color:red">*</span></label>
                                    <input type="text" name="title" placeholder="Ví dụ: Vệ sinh khu vực dãy B..." required>
                                </div>
                                <div class="contact-field">
                                    <label>Email nhận phản hồi</label>
                                    <input type="email" name="email" placeholder="example@gmail.com">
                                </div>
                            </div>

                            <div class="contact-field" style="margin-bottom: 24px;">
                                <label>Nội dung chi tiết <span style="color:red">*</span></label>
                                <textarea name="content" required placeholder="Nhập chi tiết nội dung bạn muốn đóng góp hoặc phản ánh đến Ban quản lý..." style="min-height: 130px; resize: vertical;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" style="height: 48px; font-size: 15px; font-weight: 700; border-radius: 8px;">Gửi Ý Kiến Phản Ánh</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ================= BẢN ĐỒ VỊ TRÍ GOOGLE MAPS ================= -->
            <div style="margin-top: 50px;" id="googleMapsSection">
                <div style="text-align: center; margin-bottom: 20px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: var(--primary, #0f766e); background: #f0fdf4; padding: 4px 14px; border-radius: 20px; border: 1px solid #bbf7d0;">
                        📍 Bản đồ chỉ đường
                    </span>
                    <h3 style="font-size: clamp(20px, 3vw, 24px); font-weight: 800; color: var(--gray-900); margin-top: 8px; margin-bottom: 6px;">Vị trí Ban Quản Lý &amp; Địa Điểm Chợ</h3>
                    <p style="color: var(--gray-600); font-size: 14px;">Bấm vào bản đồ để xem hình ảnh thực tế hoặc nhận chỉ đường từ Google Maps</p>
                </div>
                
                <div class="contact-map-container">
                    <?php 
                    $rawIframe = trim($cfg['contact_map_iframe'] ?? '');
                    $cleanMapUrl = '';
                    if (!empty($rawIframe)) {
                        if (preg_match('/src=[\"\']([^\"\']+)[\"\']/i', $rawIframe, $m)) {
                            $cleanMapUrl = $m[1];
                        } elseif (preg_match('/^https?:\/\//i', $rawIframe)) {
                            $cleanMapUrl = $rawIframe;
                        }
                    }
                    if (empty($cleanMapUrl)) {
                        $mapSearch = !empty($cfg['contact_map_address']) ? $cfg['contact_map_address'] : ($cfg['contact_office_address'] ?? '123 Đường Nguyễn Huệ, Phường Trung Tâm, Thành phố Quảng Ngãi');
                        $cleanMapUrl = 'https://maps.google.com/maps?q=' . urlencode($mapSearch) . '&t=&z=16&ie=UTF8&iwloc=&output=embed';
                    }
                    ?>
                    <iframe width="100%" height="100%" style="border:0;" src="<?php echo htmlspecialchars($cleanMapUrl); ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

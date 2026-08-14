    <!-- ================= HERO LIÊN HỆ ================= -->
    <section class="hero" style="padding: 60px 0 80px;">
        <div class="hero-grid-pattern"></div>
        <div class="container hero-content" style="grid-template-columns: 1fr; text-align: center;">
            <div>
                <div class="hero-eyebrow" style="justify-content: center;"><span class="dot"></span> Liên hệ &amp; Góp ý</div>
                <h1>Liên hệ với Ban quản lý chợ</h1>
                <p style="margin: 0 auto; max-width: 90% !important;">Chúng tôi luôn sẵn sàng lắng nghe ý kiến đóng góp, phản ánh và hỗ trợ giải quyết khó khăn của bà con tiểu thương và người dân.</p>
            </div>
        </div>
    </section>

    <!-- ================= NỘI DUNG LIÊN HỆ ================= -->
    <section style="padding: 60px 0;">
        <div class="container">
            <?php if (isset($this->data['success']) && $this->data['success']): ?>
                <div style="background: #E8F5E9; border: 1px solid #C8E6C9; padding: 40px; border-radius: var(--radius-lg); text-align: center; max-width: 90%; margin: 0 auto; box-shadow: var(--shadow-md);">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #2E7D32; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 30px; margin-bottom: 16px;">✓</div>
                    <h3 style="font-size: 22px; font-weight: 800; color: #2E7D32; margin-bottom: 12px;">Gửi phản ánh thành công!</h3>
                    <p style="color: var(--gray-600); margin-bottom: 24px; font-size: 15px; line-height: 1.6;">Cảm ơn bạn đã gửi ý kiến đóng góp. Ban quản lý sẽ tiếp nhận, xác minh thông tin và phản hồi sớm nhất thông qua số điện thoại hoặc email liên hệ của bạn.</p>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Quay về Trang chủ</a>
                </div>
            <?php else: ?>
                <div class="contact-grid" style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px; align-items: start;">
                    <?php 
                    $cfg = $this->data['settings'] ?? [];
                    $addr = $cfg['contact_office_address'] ?? '123 Đường Nguyễn Huệ, Phường Trung Tâm, Thành phố Quảng Ngãi';
                    $hotline = $cfg['contact_hotline'] ?? '1900 1234 (Từ 7:30 đến 17:00)';
                    $phone = $cfg['contact_phone'] ?? '';
                    $email = $cfg['contact_email'] ?? 'bqlcho.trungtam@thanhpho.gov.vn';
                    $hours = $cfg['contact_open_hours'] ?? '5:00 – 19:00, tất cả các ngày trong tuần';
                    $intro = $cfg['contact_intro'] ?? 'Ban Quản lý Chợ Trung Tâm sẵn sàng hỗ trợ người dân và tiểu thương trong giờ làm việc.';
                    ?>
                    <div class="contact-info-card" style="background: linear-gradient(135deg, var(--blue-900), var(--blue-700)); padding: 40px; border-radius: var(--radius-lg); color: #fff; box-shadow: var(--shadow-md);">
                        <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 12px;">Thông tin liên hệ</h3>
                        <p style="opacity: 0.85; font-size: 14.5px; line-height: 1.6; margin-bottom: 30px;"><?php echo htmlspecialchars($intro); ?></p>
                        
                        <div class="contact-row" style="display: flex; gap: 16px; margin-bottom: 24px;">
                            <div class="contact-icon" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                📍
                            </div>
                            <div>
                                <b style="display: block; font-size: 14.5px; opacity: 0.9;">Địa chỉ văn phòng</b>
                                <span style="font-size: 15px; font-weight: 500;"><?php echo htmlspecialchars($addr); ?></span>
                            </div>
                        </div>

                        <div class="contact-row" style="display: flex; gap: 16px; margin-bottom: 24px;">
                            <div class="contact-icon" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                📞
                            </div>
                            <div>
                                <b style="display: block; font-size: 14.5px; opacity: 0.9;">Hotline / Hỗ trợ</b>
                                <span style="font-size: 15px; font-weight: 500;"><?php echo htmlspecialchars($hotline); ?></span>
                                <?php if (!empty($phone)): ?>
                                    <div style="font-size: 13.5px; opacity: 0.85; margin-top: 2px;">Điện thoại bàn: <?php echo htmlspecialchars($phone); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="contact-row" style="display: flex; gap: 16px; margin-bottom: 24px;">
                            <div class="contact-icon" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                ✉️
                            </div>
                            <div>
                                <b style="display: block; font-size: 14.5px; opacity: 0.9;">Hộp thư điện tử</b>
                                <span style="font-size: 15px; font-weight: 500;"><?php echo htmlspecialchars($email); ?></span>
                            </div>
                        </div>

                        <div class="contact-row" style="display: flex; gap: 16px;">
                            <div class="contact-icon" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                🕒
                            </div>
                            <div>
                                <b style="display: block; font-size: 14.5px; opacity: 0.9;">Giờ mở cửa chợ</b>
                                <span style="font-size: 15px; font-weight: 500;"><?php echo htmlspecialchars($hours); ?></span>
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



                    <div class="form-card" style="padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--gray-300); background: #fff; box-shadow: var(--shadow-sm);">
                        <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 8px; color: var(--gray-900);">Gửi góp ý / phản ánh trực tuyến</h3>
                        <p style="color: var(--gray-600); font-size: 14.5px; margin-bottom: 24px;">Chúng tôi tiếp nhận, xác minh và phản hồi trong vòng 48 giờ làm việc.</p>
                        
                        <?php if (!empty($this->data['error'])): ?>
                            <div style="background: #FFEBEE; border: 1px solid #FFCDD2; color: #C62828; padding: 12px; border-radius: 6px; font-size: 13.5px; margin-bottom: 20px;">
                                <?php echo htmlspecialchars($this->data['error']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo BASE_URL; ?>home/contact" method="POST">
                            <!-- PHÂN LOẠI Ý KIẾN: DROPDOWN SELECT (KHIẾU NẠI, GÓP Ý, KHÁC) -->
                            <div class="field" style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px;">
                                <label style="font-size: 14px; font-weight: 600; color: var(--gray-900);">Loại ý kiến phản ánh <span style="color:red">*</span></label>
                                <select name="type" required style="padding: 12px; border: 1.5px solid var(--gray-300); border-radius: 8px; font-size: 14.5px; outline: none; background: #fff; color: var(--gray-900); font-weight: 600; cursor: pointer; transition: border-color 0.15s;">
                                    <option value="complaint">⚠️ Khiếu nại (Phản ánh bất cập, tranh chấp, vệ sinh, sự cố...)</option>
                                    <option value="feedback" selected>💡 Góp ý (Đóng góp xây dựng, đề xuất cải tiến dịch vụ chợ...)</option>
                                    <option value="other">📋 Khác (Hỏi đáp thông tin, thủ tục, thắc mắc chung...)</option>
                                </select>
                            </div>


                            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                <div class="field" style="display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 14px; font-weight: 600; color: var(--gray-900);">Họ tên người gửi <span style="color:red">*</span></label>
                                    <input type="text" name="fullname" placeholder="Nguyễn Văn A" required style="padding: 12px; border: 1.5px solid var(--gray-300); border-radius: 8px; font-size: 14.5px; outline: none; transition: border-color 0.15s;">
                                </div>
                                <div class="field" style="display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 14px; font-weight: 600; color: var(--gray-900);">Số điện thoại liên hệ <span style="color:red">*</span></label>
                                    <input type="tel" name="phone" placeholder="09xx xxx xxx" required style="padding: 12px; border: 1.5px solid var(--gray-300); border-radius: 8px; font-size: 14.5px; outline: none; transition: border-color 0.15s;">
                                </div>
                            </div>
                            
                            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                <div class="field" style="display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 14px; font-weight: 600; color: var(--gray-900);">Tiêu đề ý kiến <span style="color:red">*</span></label>
                                    <input type="text" name="title" placeholder="Ví dụ: Vệ sinh khu vực dãy B / Đề xuất lắp đèn..." required style="padding: 12px; border: 1.5px solid var(--gray-300); border-radius: 8px; font-size: 14.5px; outline: none; transition: border-color 0.15s;">
                                </div>
                                <div class="field" style="display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 14px; font-weight: 600; color: var(--gray-900);">Email nhận phản hồi</label>
                                    <input type="email" name="email" placeholder="example@gmail.com" style="padding: 12px; border: 1.5px solid var(--gray-300); border-radius: 8px; font-size: 14.5px; outline: none; transition: border-color 0.15s;">
                                </div>
                            </div>

                            <div class="field" style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 24px;">
                                <label style="font-size: 14px; font-weight: 600; color: var(--gray-900);">Nội dung chi tiết <span style="color:red">*</span></label>
                                <textarea name="content" required placeholder="Nhập chi tiết nội dung bạn muốn đóng góp hoặc phản ánh đến Ban quản lý..." style="padding: 12px; border: 1.5px solid var(--gray-300); border-radius: 8px; font-size: 14.5px; outline: none; min-height: 140px; resize: vertical; font-family: inherit; transition: border-color 0.15s;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" style="height: 46px; font-size: 15px; font-weight: 700;">Gửi Ý Kiến Phản Ánh</button>
                        </form>

                    </div>
                </div>
            <?php endif; ?>

            <!-- ================= BẢN ĐỒ VỊ TRÍ GOOGLE MAPS ================= -->
            <div style="margin-top: 60px;" id="googleMapsSection">
                <div style="text-align: center; margin-bottom: 24px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: var(--primary, #0f766e); background: #f0fdf4; padding: 4px 14px; border-radius: 20px; border: 1px solid #bbf7d0;">
                        📍 Bản đồ chỉ đường
                    </span>
                    <h3 style="font-size: 24px; font-weight: 800; color: var(--gray-900); margin-top: 8px; margin-bottom: 6px;">Vị trí Ban Quản Lý & Địa Điểm Chợ</h3>
                    <p style="color: var(--gray-600); font-size: 14.5px;">Bấm vào bản đồ để phóng to, xem hình ảnh thực tế hoặc nhận chỉ đường từ Google Maps</p>
                </div>
                
                <div style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); border: 1px solid var(--gray-300); height: 450px; background: #f1f5f9;">
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

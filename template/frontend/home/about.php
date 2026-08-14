    <?php 
    $aboutBanner = !empty($this->data['banners']) ? $this->data['banners'][0] : null; 
    $aboutTitle = $aboutBanner ? htmlspecialchars($aboutBanner['banner_title']) : 'Giới thiệu Ban Quản Lý Chợ';
    $aboutDesc = $aboutBanner && !empty($aboutBanner['banner_description']) ? htmlspecialchars($aboutBanner['banner_description']) : 'Lịch sử hình thành, quy mô hoạt động và định hướng phát triển chuyển đổi số.';
    $aboutImg = $aboutBanner ? htmlspecialchars($aboutBanner['banner_image']) : 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?q=80&w=1200&auto=format&fit=crop';
    ?>
    <section class="hero" style="padding: 60px 0 80px;">
        <div class="hero-grid-pattern"></div>
        <div class="container hero-content" style="grid-template-columns: 1fr; text-align: center;">
            <div>
                <div class="hero-eyebrow" style="justify-content: center;"><span class="dot"></span> Về Ban Quản Lý Chợ</div>
                <h1><?php echo $aboutTitle; ?></h1>
                <p style="margin: 0 auto; max-width: 90% !important;"><?php echo $aboutDesc; ?></p>
            </div>
        </div>
    </section>


    <!-- ================= NỘI DUNG CHI TIẾT ================= -->
    <section style="padding: 60px 0;">
        <div class="container" style="max-width: 90% !important; width: 100% !important;">
            <div class="intro-img" style="margin-bottom: 40px; height: 380px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <img src="<?php echo $aboutImg; ?>"
                    alt="<?php echo $aboutTitle; ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>


            <?php 
            $s = $this->data['settings'] ?? [];
            $history = !empty($s['about_history']) ? $s['about_history'] : 'Hình thành từ năm 1985, chợ Trung Tâm Thành Phố là đầu mối buôn bán sầm uất, quy tụ hàng nghìn tiểu thương thuộc nhiều ngành hàng khác nhau, đồng hành cùng quá trình chuyển đổi số của địa phương. Trải qua hơn 40 năm hoạt động và phát triển, chợ đã thực hiện 3 lần cải tạo lớn nâng cấp toàn bộ hệ thống cơ sở hạ tầng, đáp ứng nhu cầu giao thương an toàn, văn minh của nhân dân.';
            $scale = !empty($s['about_scale']) ? $s['about_scale'] : 'Với tổng diện tích hơn 12.000m², Chợ được quy hoạch bài bản thành 8 phân khu chức năng riêng biệt nhằm đảm bảo vệ sinh an toàn thực phẩm và trật tự kinh doanh. Hiện chợ có 1.240 sạp kinh doanh hoạt động ổn định, cung cấp đầy đủ các mặt hàng thiết yếu cho đời sống người dân.';
            $bizLines = !empty($s['about_business_lines']) ? $s['about_business_lines'] : "Thực phẩm tươi sống: Rau củ quả, thịt cá tươi sống được kiểm định chất lượng mỗi ngày.\nBách hóa & Đồ gia dụng: Đầy đủ các mặt hàng tiêu dùng thiết yếu phục vụ đời sống gia đình.\nThời trang & Mỹ phẩm: Quần áo, giày dép, phụ kiện đa dạng về mẫu mã và giá cả hợp lý.\nKhu ẩm thực & Dịch vụ ăn uống: Nơi hội tụ các món ăn truyền thống vùng miền đạt chuẩn vệ sinh an toàn thực phẩm.";
            $digitization = !empty($s['about_digitization']) ? $s['about_digitization'] : 'Thực hiện chủ trương chuyển đổi số của Thành phố, Ban quản lý Chợ đang nỗ lực số hóa 100% sơ đồ sạp chợ, hỗ trợ đăng ký thuê sạp trực tuyến, tra cứu thông tin tiểu thương minh bạch và thúc đẩy thanh toán không dùng tiền mặt (chợ 4.0), mang lại trải nghiệm tiện ích nhất cho cả người bán lẫn người mua.';
            ?>
            <div style="line-height: 1.8; font-size: 16px;">
                <?php 
                $aboutSections = $this->data['about_sections'] ?? [];
                if (!empty($aboutSections)): 
                    foreach ($aboutSections as $sec): 
                        $content = $sec['section_content'];
                        $allowedTags = '<b><i><u><strong><em><ul><li><ol><p><br><span><h1><h2><h3><h4><h5><h6><div><a>';
                        $safeHtml = strip_tags($content, $allowedTags);
                        $hasHtmlTags = (strlen($safeHtml) !== strlen(strip_tags($content)));
                ?>
                        <h2 style="font-size: 22px; font-weight: 800; margin-bottom: 14px; color: var(--text-heading, #0f172a);">
                            <?php echo strip_tags($sec['section_title'], '<b><i><u><strong><em><span>'); ?>
                        </h2>
                        <div style="color: var(--gray-600, #475569); margin-bottom: 28px; line-height: 1.8;">
                            <?php 
                            if ($hasHtmlTags) {
                                echo $safeHtml;
                            } else {
                                echo nl2br(htmlspecialchars($content));
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: var(--text-muted); text-align: center;">Nội dung trang giới thiệu đang được nâng cấp.</p>
                <?php endif; ?>
                
                <div style="text-align: center; margin-top: 30px;">
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Quay lại Trang chủ</a>
                </div>
            </div>



        </div>
    </section>
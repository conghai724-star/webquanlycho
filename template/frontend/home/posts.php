    <!-- ================= HERO TIN TỨC ================= -->
    <section class="hero" style="padding: 60px 0 80px;">
        <div class="hero-grid-pattern"></div>
        <div class="container hero-content" style="grid-template-columns: 1fr; text-align: center;">
            <div>
                <div class="hero-eyebrow" style="justify-content: center;"><span class="dot"></span> Tin tức &amp; Sự kiện</div>
                <h1>Tin tức &amp; Thông báo từ Ban quản lý</h1>
                <p style="margin: 0 auto; max-width: 90% !important;">Cập nhật các thông tin chính thức mới nhất về hoạt động chợ, kế hoạch nâng cấp và hướng dẫn dành cho tiểu thương.</p>
            </div>
        </div>
    </section>

    <!-- ================= DANH SÁCH TIN TỨC ================= -->
    <section style="padding: 60px 0;">
        <div class="container">
            <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
                <?php if (!empty($this->data['posts'])): ?>
                    <?php foreach ($this->data['posts'] as $post): ?>
                        <?php 
                        $postDate = date('d/m/Y', strtotime($post['created_at']));
                        $postImg = !empty($post['post_image']) ? htmlspecialchars($post['post_image']) : 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=800';
                        $postLink = BASE_URL . 'home/post_detail/' . $post['post_slug'];
                        ?>
                        <div class="news-card" style="background: #fff; border: 1px solid var(--gray-300); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; transition: transform .2s ease, box-shadow .2s ease;">
                            <div class="news-img" style="height: 200px; overflow: hidden; position: relative;">
                                <img src="<?php echo $postImg; ?>" alt="<?php echo htmlspecialchars($post['post_title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="news-body" style="padding: 24px; display: flex; flex-direction: column; flex-grow: 1;">
                                <div class="news-date" style="font-size: 13px; color: var(--gray-600); margin-bottom: 8px; font-weight: 500; font-family: 'Roboto Mono', monospace;"><?php echo $postDate; ?></div>
                                <h3 style="font-size: 17.5px; font-weight: 700; margin-bottom: 12px; color: var(--gray-900); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 49px;"><?php echo htmlspecialchars($post['post_title']); ?></h3>
                                <p style="font-size: 14.5px; color: var(--gray-600); margin-bottom: 20px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; flex-grow: 1;"><?php echo htmlspecialchars($post['post_summary']); ?></p>
                                <a href="<?php echo $postLink; ?>" class="btn btn-outline btn-sm" style="align-self: flex-start;">Đọc tiếp →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; grid-column: 1/-1; padding: 60px 0;">
                        <p style="color: var(--gray-600); font-size: 16px;">Hiện chưa có tin tức hoặc thông báo nào được đăng tải.</p>
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary" style="margin-top: 16px;">Quay về Trang chủ</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

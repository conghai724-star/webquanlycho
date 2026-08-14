<?php 
$post = $this->data['post'] ?? null;
if (!$post) {
    header("Location: " . BASE_URL);
    exit();
}
$postDate = date('d/m/Y H:i', strtotime($post['created_at']));
$postImg = !empty($post['post_image']) ? htmlspecialchars($post['post_image']) : '';
$recentPosts = $this->data['recentPosts'] ?? [];
?>

<style>
    .post-detail-section {
        padding: 120px 0 80px;
        background: #fdfdfd;
    }
    
    .breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: var(--gray-600);
        margin-bottom: 24px;
        font-weight: 500;
    }
    
    .breadcrumbs a {
        transition: color 0.15s;
    }
    
    .breadcrumbs a:hover {
        color: var(--blue-700);
    }
    
    .post-layout-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 40px;
        align-items: start;
    }
    
    @media (max-width: 991px) {
        .post-layout-grid {
            grid-template-columns: 1fr;
            gap: 50px;
        }
    }
    
    .post-card-container {
        background: #ffffff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 40px;
        box-shadow: var(--shadow-sm);
    }
    
    @media (max-width: 640px) {
        .post-card-container {
            padding: 24px;
        }
    }
    
    .post-header-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--blue-50);
        color: var(--blue-700);
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 6px 14px;
        border-radius: 99px;
        margin-bottom: 16px;
    }
    
    .author-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--gray-100);
    }
    
    .author-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--blue-700);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }
    
    .post-content-body {
        font-size: 16px;
        line-height: 1.85;
        color: var(--gray-900);
    }
    
    .post-content-body p {
        margin-bottom: 20px;
    }
    
    .post-content-body h2, .post-content-body h3 {
        color: var(--blue-900);
        font-weight: 800;
        margin: 32px 0 16px;
    }
    
    .post-content-body blockquote {
        background: var(--gray-100);
        border-left: 4px solid var(--orange);
        padding: 16px 24px;
        font-size: 16.5px;
        font-weight: 500;
        color: var(--gray-600);
        margin: 24px 0;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    }

    .post-content-body figure {
        margin: 28px 0;
        text-align: center;
    }

    .post-content-body figure img {
        max-width: 100%;
        height: auto;
        border-radius: var(--radius-md);
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    .post-content-body figcaption {
        font-size: 13.5px;
        color: var(--gray-600);
        font-style: italic;
        margin-top: 8px;
        text-align: center;
        line-height: 1.4;
    }

    .post-img-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin: 28px 0;
    }

    @media (max-width: 640px) {
        .post-img-grid {
            grid-template-columns: 1fr;
        }
    }


    .sidebar-widget {
        background: #ffffff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--shadow-sm);
    }

    .recent-post-link {
        display: block;
        padding: 16px 0;
        border-bottom: 1px dashed var(--gray-200);
        transition: transform 0.2s;
    }

    .recent-post-link:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .recent-post-link:hover {
        transform: translateX(4px);
    }

    .recent-post-link h4 {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.4;
        margin-bottom: 6px;
        transition: color 0.15s;
    }

    .recent-post-link:hover h4 {
        color: var(--blue-700);
    }
</style>

<!-- ================= CHI TIẾT BÀI VIẾT ================= -->
<section class="post-detail-section">
    <div class="container">
        
        <!-- Đường dẫn Breadcrumbs -->
        <div class="breadcrumbs" data-reveal>
            <a href="<?php echo BASE_URL; ?>">Trang chủ</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--gray-300);"></i>
            <a href="<?php echo BASE_URL; ?>home/posts">Tin tức & Thông báo</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--gray-300);"></i>
            <span style="color: var(--gray-900); font-weight: 600;">Chi tiết</span>
        </div>

        <div class="post-layout-grid">
            <!-- CỘT CHÍNH: CHI TIẾT BÀI VIẾT (70% trên desktop) -->
            <div style="min-width: 0;">
                <div class="post-card-container" data-reveal>
                    <header>
                        <span class="post-header-badge">
                            <i class="fa-solid fa-bullhorn" style="font-size: 11px;"></i> Thông báo BQL
                        </span>
                        
                        <h1 style="font-size: clamp(24px, 3.5vw, 32px); font-weight: 800; color: var(--blue-900); line-height: 1.35; margin-bottom: 20px;">
                            <?php echo htmlspecialchars($post['post_title']); ?>
                        </h1>
                        
                        <!-- Thông tin tác giả & thời gian -->
                        <div class="author-meta">
                            <div class="author-avatar">
                                <i class="fa-solid fa-user-shield" style="font-size: 15px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 14px; color: var(--gray-900);">Ban Quản lý Chợ Trung Tâm</div>
                                <div style="font-size: 12.5px; color: var(--gray-600); font-weight: 500; margin-top: 2px;">
                                    <i class="fa-regular fa-clock" style="margin-right: 4px;"></i> <?php echo $postDate; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Đoạn tóm tắt nổi bật -->
                        <p style="font-size: 17px; line-height: 1.65; color: var(--gray-700); font-weight: 500; border-left: 4.5px solid var(--orange); padding-left: 20px; margin: 0 0 32px;">
                            <?php echo htmlspecialchars($post['post_summary']); ?>
                        </p>
                    </header>

                    <!-- Ảnh bài viết -->
                    <?php if ($postImg !== ''): ?>
                        <div style="margin-bottom: 36px; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); max-height: 500px; background: var(--gray-100);">
                            <img src="<?php echo $postImg; ?>" alt="<?php echo htmlspecialchars($post['post_title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php endif; ?>

                    <!-- Nội dung bài viết -->
                    <div class="post-content-body">
                        <?php 
                        $allowedTags = '<b><strong><i><em><u><h2><h3><h4><h5><p><br><ul><ol><li><a><img><blockquote><div><span><hr><table><thead><tbody><tr><th><td><figure><figcaption>';
                        echo strip_tags($post['post_content'], $allowedTags); 
                        ?>
                    </div>



                    <!-- Footer bài viết -->
                    <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid var(--gray-100); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                        <div style="font-size: 13.5px; color: var(--gray-600); font-weight: 500;">
                            Cổng thông tin Ban Quản lý Chợ truyền thống
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <a href="<?php echo BASE_URL; ?>home/posts" class="btn btn-primary btn-sm" style="border-radius: var(--radius-sm);">
                                <i class="fa-solid fa-list" style="margin-right: 4px;"></i> Danh sách tin
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CỘT PHỤ (SIDEBAR): TIN TỨC LIÊN QUAN (30% trên desktop) -->
            <aside style="display: flex; flex-direction: column; gap: 30px;" data-reveal>
                <div class="sidebar-widget">
                    <h3 style="font-size: 16.5px; font-weight: 800; color: var(--blue-900); margin-bottom: 20px; border-bottom: 2px solid var(--blue-700); padding-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-newspaper" style="color: var(--blue-700);"></i> Tin tức khác
                    </h3>
                    
                    <?php if (empty($recentPosts)): ?>
                        <p style="color: var(--gray-600); font-size: 14px;">Không có bài viết khác.</p>
                    <?php else: ?>
                        <div>
                            <?php foreach ($recentPosts as $rp): ?>
                                <?php $rpDate = date('d/m/Y', strtotime($rp['created_at'])); ?>
                                <a href="<?php echo BASE_URL . 'home/post_detail/' . $rp['post_slug']; ?>" class="recent-post-link">
                                    <h4><?php echo htmlspecialchars($rp['post_title']); ?></h4>
                                    <div style="font-size: 12px; color: var(--gray-600); font-weight: 500;">
                                        <i class="fa-regular fa-calendar" style="margin-right: 4px;"></i> <?php echo $rpDate; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="sidebar-widget" style="background: linear-gradient(135deg, var(--blue-900), var(--blue-700)); color: #fff; border: none;">
                    <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 12px;">Hỗ trợ Tiểu thương</h3>
                    <p style="font-size: 13.5px; line-height: 1.6; color: rgba(255,255,255,0.85); margin-bottom: 20px;">
                        Mọi thắc mắc về đăng ký kinh doanh, hợp đồng thuê sạp hoặc phí dịch vụ, vui lòng liên hệ Ban Quản lý Chợ để được giải quyết.
                    </p>
                    <a href="<?php echo BASE_URL; ?>home/contact" class="btn btn-primary" style="background: var(--orange); color: #fff; border: none; font-size: 13.5px; border-radius: var(--radius-sm); width: 100%; text-align: center; justify-content: center; box-shadow: 0 4px 12px rgba(255,152,0,0.3);">
                        <i class="fa-solid fa-headset" style="margin-right: 6px;"></i> Gửi yêu cầu hỗ trợ
                    </a>
                </div>
            </aside>
        </div>
        
    </div>
</section>

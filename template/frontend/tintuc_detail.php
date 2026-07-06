<?php require 'header.php'; ?>
<?php
$newsTitle = $news_detail->event_name ?? 'Bài viết chi tiết';
$newsDesc = $news_detail->event_description ?? '';
$newsContent = $news_detail->event_content ?? 'Nội dung đang được cập nhật.';
$newsDate = isset($news_detail->event_created_date) ? date('d/m/Y', strtotime($news_detail->event_created_date)) : 'Đang cập nhật';
$newsHot = (int)($news_detail->event_hot ?? 0) === 1;

$type = isset($news_detail->event_type) ? (int)$news_detail->event_type : 0;
if($type === 1){
  $catClass = 'employer';
  $catIcon = 'ti ti-speakerphone';
  $catLabel = 'Nhà tuyển dụng';
} elseif($type === 2){
  $catClass = 'seeker';
  $catIcon = 'ti ti-user-search';
  $catLabel = 'Người tìm việc';
} else {
  $catClass = 'site';
  $catIcon = 'ti ti-building-community';
  $catLabel = 'Tin từ ViecLam.vn';
}

$imageUrl = 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=1200&h=600&fit=crop'; // fallback
if(isset($news_detail->event_image) && trim($news_detail->event_image) !== ''){
  $imagePath = trim($news_detail->event_image);
  if(preg_match('#^(https?:)?//#i', $imagePath) || strpos($imagePath, 'data:') === 0){
    $imageUrl = $imagePath;
  } else {
    $imageUrl = XC_URL.'/uploads/events/'.ltrim($imagePath, '/');
  }
}

// Public comments: only display comment_content and require login to submit
$publicComments = isset($news_comments) && is_array($news_comments) ? $news_comments : array();
$canComment = isset($_SESSION['user']['id']) && intval($_SESSION['user']['id']) > 0;
$commentLoginUrl = XC_URL.'/login';
$commentsByParent = array();
$rootComments = array();
$totalComments = count($publicComments);

if (!function_exists('getInitials')) {
  function getInitials($name) {
      $name = trim((string)$name);
      if ($name === '') return 'U';
      $parts = explode(' ', $name);
      $initials = '';
      foreach ($parts as $part) {
          if ($part !== '') $initials .= mb_substr($part, 0, 1, 'UTF-8');
      }
      return mb_strtoupper(mb_substr($initials ?: $name, 0, 2, 'UTF-8'), 'UTF-8');
  }
}

if (!function_exists('getCommentTime')) {
  function getCommentTime($value) {
      if (!$value) return 'Vừa xong';
      $time = strtotime($value);
      if (!$time) return 'Vừa xong';
      $seconds = max(0, time() - $time);
      $minutes = floor($seconds / 60);
      if ($minutes < 1) return 'Vừa xong';
      if ($minutes < 60) return $minutes.' phút trước';
      $hours = floor($minutes / 60);
      if ($hours < 24) return $hours.' giờ trước';
      $days = floor($hours / 24);
      if ($days < 7) return $days.' ngày trước';
      return date('d/m/Y', $time);
  }
}

$authorName = 'Ban Biên Tập ViecLam.vn';
$authorAvatar = 'BBT';
$authorRole = 'Cổng thông tin tuyển dụng & việc làm';
$authorBio = 'Cập nhật nhanh nhất và chính xác nhất các thông tin về thị trường lao động, xu hướng tuyển dụng và bí quyết phát triển sự nghiệp tại Việt Nam.';

if (isset($news_detail->event_user_created) && intval($news_detail->event_user_created) > 0) {
    $uId = intval($news_detail->event_user_created);
    global $db;
    $db->query("SELECT * FROM hicrm_users WHERE id = '".$uId."' LIMIT 1");
    if ($db->num_row() > 0) {
        $creator = $db->fetch_object(true);
        $authorName = $creator->full_name ?: $creator->full_name ?: $authorName;
        $authorAvatar = getInitials($authorName);
        if ($creator->user_group == 1) {
            $authorRole = 'Quản trị viên · ViecLam.vn';
        }
    }
}

foreach ($publicComments as $comment) {
    $comment->display_name = trim((string)($comment->commenter_name ?? $comment->comment_name ?? ''));
    if ($comment->display_name === '') {
        $comment->display_name = 'An danh';
    }

    $parentId = isset($comment->parent_id) ? (int)$comment->parent_id : 0;
    if ($parentId > 0) {
        if (!isset($commentsByParent[$parentId])) {
            $commentsByParent[$parentId] = array();
        }
        $commentsByParent[$parentId][] = $comment;
    } else {
        $rootComments[] = $comment;
    }
}
?>

<!-- Read Progress Bar -->
<div class="read-progress" id="readProgress"></div>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
  <div class="breadcrumb-inner">
    <a href="<?php echo XC_URL; ?>">Trang chủ</a>
    <i class="ti ti-chevron-right"></i>
    <a href="<?php echo XC_URL; ?>/tin-tuc-su-kien.html">Tin tức</a>
    <i class="ti ti-chevron-right"></i>
    <a href="<?php echo XC_URL; ?>/tin-tuc-su-kien.html?section=<?php echo $catClass; ?>"><?php echo htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8'); ?></a>
    <i class="ti ti-chevron-right"></i>
    <span style="color:#333;font-weight:500"><?php echo htmlspecialchars($newsTitle, ENT_QUOTES, 'UTF-8'); ?></span>
  </div>
</div>

<!-- Floating Share Bar (Desktop) -->
<!-- <div class="floating-share" id="floatingShare">
  <div class="fs-label">CHIA<br>SẺ</div>
  <button class="fs-btn fb" title="Facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href))"><i class="ti ti-brand-facebook"></i></button>
  <button class="fs-btn zalo" title="Zalo" onclick="window.open('https://sp.zalo.me/share?url='+encodeURIComponent(window.location.href))"><i class="ti ti-message-circle"></i></button>
  <button class="fs-btn tw" title="Twitter" onclick="window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(window.location.href)+'&text='+encodeURIComponent(document.title))"><i class="ti ti-brand-twitter"></i></button>
  <button class="fs-btn linkedin" title="LinkedIn" onclick="window.open('https://www.linkedin.com/sharing/share-offsite/?url='+encodeURIComponent(window.location.href))"><i class="ti ti-brand-linkedin"></i></button>
  <button class="fs-btn whatsapp" title="WhatsApp" onclick="window.open('https://api.whatsapp.com/send?text='+encodeURIComponent(window.location.href))"><i class="ti ti-brand-whatsapp"></i></button>
</div> -->

<!-- ===== MAIN PAGE ===== -->
<div class="article-page">

  <!-- ===== ARTICLE CONTENT ===== -->
  <main class="article-main" id="articleMain">

    <!-- Cover Image -->
    <div class="article-cover">
      <img src="<?php echo htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($newsTitle, ENT_QUOTES, 'UTF-8'); ?>"/>
    </div>

    <!-- Meta top -->
    <div class="article-meta-top">
      <span class="article-cat <?php echo $catClass; ?>"><i class="<?php echo $catIcon; ?>"></i> <?php echo htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8'); ?></span>
      <?php if ($newsHot): ?>
        <span class="article-hot-tag"><i class="ti ti-flame"></i> HOT</span>
      <?php endif; ?>
    </div>

    <!-- Title -->
    <h1 class="article-title" id="article-title"><?php echo htmlspecialchars($newsTitle, ENT_QUOTES, 'UTF-8'); ?></h1>

    <!-- Subtitle / lead -->
    <?php if ($newsDesc !== ''): ?>
      <p class="article-subtitle"><?php echo htmlspecialchars($newsDesc, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <!-- Author bar -->
    <div class="author-bar">
      <div class="author-info">
        <div class="author-avatar"><?php echo htmlspecialchars($authorAvatar, ENT_QUOTES, 'UTF-8'); ?></div>
        <div>
          <div class="author-name"><?php echo htmlspecialchars($authorName, ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="author-role"><?php echo htmlspecialchars($authorRole, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      </div>
      <div class="author-stats">
        <div class="author-stat"><i class="ti ti-calendar"></i> <?php echo $newsDate; ?></div>
        <div class="author-stat"><i class="ti ti-message-circle"></i> <strong><?php echo $totalComments; ?></strong> bình luận</div>
      </div>
    </div>

    <!-- ===== ARTICLE BODY ===== -->
    <article class="article-body" id="articleBody">
      <?php echo $newsContent; ?>
    </article><!-- end .article-body -->

    <hr class="article-divider"/>

    <!-- Share + Reactions -->
    <div class="article-actions">
      <h4><i class="ti ti-share"></i> Chia sẻ bài viết này</h4>
      <div class="share-row">
        <span class="share-lbl">Chia sẻ qua:</span>
        <button class="share-btn fb" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href))"><i class="ti ti-brand-facebook"></i>Facebook</button>
         <button class="share-btn copy" onclick="navigator.clipboard.writeText(window.location.href).then(()=>{this.innerHTML='<i class=\'ti ti-check\'></i> Đã sao chép';setTimeout(()=>{this.innerHTML='<i class=\'ti ti-link\'></i> Sao chép'},2000)})"><i class="ti ti-link"></i> Sao chép</button>
      </div>
     
    </div>

    <!-- Author Bio -->
    <div class="author-bio">
      <div class="author-bio-top">
        <div class="author-bio-avatar"><?php echo htmlspecialchars($authorAvatar, ENT_QUOTES, 'UTF-8'); ?></div>
        <div style="flex:1">
          <div class="author-bio-name"><?php echo htmlspecialchars($authorName, ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="author-bio-role"><?php echo htmlspecialchars($authorRole, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      </div>
      <p><?php echo htmlspecialchars($authorBio, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- Related Posts -->
    <!--  -->

    <!-- Comments Section -->
    <div class="comments-section" id="comments">
      <div class="comments-title">
        <i class="ti ti-message-dots"></i>
        Bình luận
        <span class="comment-count-badge"><?php echo $totalComments; ?> bình luận</span>
      </div>

      <!-- Form -->
      <div class="comment-form-wrap">
        <h5><i class="ti ti-pencil"></i>Để lại bình luận của bạn</h5>
        <div id="replying-to-helper" style="display:none;background:#eef6ff;border-left:4px solid #0d4e96;padding:8px 12px;margin-bottom:12px;border-radius:4px;font-size:13px;align-items:center;justify-content:space-between">
          <span>Đang trả lời bình luận của <strong id="replying-to-name"></strong></span>
          <button type="button" onclick="cancelReply()" style="background:none;border:0;color:#c62828;cursor:pointer;font-weight:bold"><i class="ti ti-x"></i> Hủy</button>
        </div>
        <form id="commentForm">
          <input type="hidden" name="event_id" value="<?php echo (int)($news_detail->id ?? 0); ?>"/>
          <input type="hidden" name="parent_id" id="comment_parent_id" value=""/>
          <div class="cf-grid">
            <input class="cf-input" type="text" id="comment_name" name="comment_name" placeholder="Họ và tên *" required/>
            <input class="cf-input" type="email" id="comment_email" name="comment_email" placeholder="Email * (không hiển thị công khai)" required/>
          </div>
          <textarea class="cf-textarea" id="comment_content" name="comment_content" placeholder="Bạn nghĩ gì về bài viết này? Chia sẻ kinh nghiệm hoặc đặt câu hỏi..." required></textarea>
          <div class="cf-footer">
            <span class="cf-hint"><i class="ti ti-shield-check" style="vertical-align:-2px;color:#2e7d32"></i> Bình luận được hiển thị công khai ngay</span>
            <button type="submit" class="cf-submit"><i class="ti ti-send"></i>Gửi bình luận</button>
          </div>
        </form>
      </div>

      <hr class="comment-sep"/>

      <!-- Comment List -->
      <div class="comments-list-wrap">
        <?php if (!empty($rootComments)): ?>
          <?php foreach ($rootComments as $c): ?>
            <!-- Parent Comment -->
            <div class="comment-item" id="comment-<?php echo $c->id; ?>">
              <div class="c-avatar" style="background:#0d4e96"><?php echo htmlspecialchars(getInitials($c->display_name), ENT_QUOTES, 'UTF-8'); ?></div>
              <div class="c-body">
                <div class="c-header">
                  <span class="c-name"><?php echo htmlspecialchars($c->display_name, ENT_QUOTES, 'UTF-8'); ?></span>
                  <span class="c-role">Người đọc</span>
                  <span class="c-date"><i class="ti ti-clock"></i><?php echo getCommentTime($c->created_at); ?></span>
                </div>
                <p class="c-text"><?php echo nl2br(htmlspecialchars($c->comment_content, ENT_QUOTES, 'UTF-8')); ?></p>
                <div class="c-actions">
                  <button class="c-action-btn" onclick="likeComment(<?php echo $c->id; ?>, this)"><i class="ti ti-thumb-up"></i>Thích (<span class="like-count">0</span>)</button>
                  <button class="c-action-btn" onclick="replyComment(<?php echo $c->id; ?>, <?php echo htmlspecialchars(json_encode($c->display_name), ENT_QUOTES, 'UTF-8'); ?>)"><i class="ti ti-message-reply"></i>Trả lời</button>
                </div>
                <?php if (isset($c->admin_reply) && trim((string)$c->admin_reply) !== ''): ?>
                  <div class="comment-reply-item" style="margin-top:14px;padding:14px 16px;background:#f7fbff;border:1px solid #d9e8fb;border-radius:16px;">
                    <div class="cr-avatar" style="background:#2e7d32"><?php echo htmlspecialchars(getInitials($c->reply_user_name ?? 'Admin'), ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="c-body">
                      <div class="c-header">
                        <span class="c-name"><?php echo htmlspecialchars(trim((string)($c->reply_user_name ?? 'Ban quản trị')), ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="c-role" style="background:#e8f5e9;color:#2e7d32">Admin</span>
                        <span class="c-date"><i class="ti ti-clock"></i><?php echo getCommentTime($c->replied_at ?? $c->updated_at ?? $c->created_at); ?></span>
                      </div>
                      <p class="c-text" style="font-size:13px"><?php echo nl2br(htmlspecialchars($c->admin_reply, ENT_QUOTES, 'UTF-8')); ?></p>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Replies -->
            <?php if (isset($commentsByParent[$c->id])): ?>
              <div class="comment-replies" style="margin-left:58px;padding-left:18px;border-left:2px solid #e5edf6;">
                <?php foreach ($commentsByParent[$c->id] as $reply): ?>
                  <div class="comment-reply-item" id="comment-<?php echo $reply->id; ?>">
                    <div class="cr-avatar" style="background:#2e7d32"><?php echo htmlspecialchars(getInitials($reply->display_name), ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="c-body">
                      <div class="c-header">
                        <span class="c-name"><?php echo htmlspecialchars($reply->display_name, ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="c-role">Người đọc</span>
                        <span class="c-date"><i class="ti ti-clock"></i><?php echo getCommentTime($reply->created_at); ?></span>
                      </div>
                      <p class="c-text" style="font-size:13px"><?php echo nl2br(htmlspecialchars($reply->comment_content, ENT_QUOTES, 'UTF-8')); ?></p>
                      <div class="c-actions">
                        <button class="c-action-btn" onclick="likeComment(<?php echo $reply->id; ?>, this)"><i class="ti ti-thumb-up"></i>Thích (<span class="like-count">0</span>)</button>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="no-comments-yet" style="text-align:center;color:#667085;padding:20px 0;">Chưa có bình luận nào cho bài viết này. Hãy là người đầu tiên để lại ý kiến!</p>
        <?php endif; ?>
      </div><!-- end comment list -->
    </div>

  </main><!-- end .article-main -->

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">

    <!-- Table of Contents -->
    <div class="sidebar-widget">
      <div class="widget-head"><i class="ti ti-list"></i>Mục lục bài viết</div>
      <ul class="toc-list" id="tocList">
        <!-- Generated Dynamically by JS -->
      </ul>
    </div>

    <!-- Popular posts -->
    <div class="sidebar-widget">
      <div class="widget-head"><i class="ti ti-flame"></i>Bài viết nổi bật</div>
      <div class="popular-list">
        <?php if(!empty($more_news)): ?>
          <?php foreach(array_slice($more_news, 0, 5) as $index => $item): ?>
            <?php
            $itemTitle = $item->event_name ?? 'Bài viết nổi bật';
            $itemUrl = general::getInstance()->permalink((int)($item->id ?? 0), 'event');
            
            $itemImg = 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=120&h=90&fit=crop';
            if(isset($item->event_image) && trim($item->event_image) !== ''){
              $imgPath = trim($item->event_image);
              if(preg_match('#^(https?:)?//#i', $imgPath) || strpos($imgPath, 'data:') === 0){
                $itemImg = $imgPath;
              } else {
                $itemImg = XC_URL.'/uploads/events/'.ltrim($imgPath, '/');
              }
            }
            ?>
            <div class="pop-item">
              <span class="pop-rank <?php echo $index < 3 ? 't' : ''; ?>"><?php echo $index + 1; ?></span>
              <img class="pop-thumb" src="<?php echo htmlspecialchars($itemImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy"/>
              <div class="pop-info">
                <a href="<?php echo htmlspecialchars($itemUrl, ENT_QUOTES, 'UTF-8'); ?>" class="pop-title" style="text-decoration:none;color:inherit;font-weight:650;font-size:13px;"><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?></a>
                <div class="pop-meta"><i class="ti ti-calendar"></i> <?php echo date('d/m/Y', strtotime($item->event_created_date ?? 'now')); ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="text-align: center; color: #667085; padding: 10px 0;">Chưa có bài viết nổi bật nào.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Social Follow -->
    <div class="sidebar-widget">
      <div class="widget-head"><i class="ti ti-share"></i>Theo dõi chúng tôi </div>
      <div class="social-follow">
        <div class="soc-row fb" onclick="window.open('https://facebook.com')"><span class="soc-row-left"><i class="ti ti-brand-facebook"></i>Facebook</span><span class="soc-count"></span></div>
       </div>
    </div>

  </aside>
</div><!-- end .article-page -->

<!-- ===== HOT NEWS SLIDER ===== -->
<section class="hot-slider-section">
  <div class="hot-slider-inner">
    <div class="hs-header">
      <div class="hs-title"><i class="ti ti-bolt"></i>Tin tức nổi bật khác</div>
      <div class="hs-controls">
        <button class="hs-ctrl" id="hsPrev"><i class="ti ti-chevron-left"></i></button>
        <button class="hs-ctrl" id="hsNext"><i class="ti ti-chevron-right"></i></button>
      </div>
    </div>
    <div class="hs-wrap" id="hsWrap">
      <div class="hs-track" id="hsTrack">
        <?php if(!empty($more_news)): ?>
          <?php foreach($more_news as $index => $item): ?>
            <?php
            $itemTitle = $item->event_name ?? 'Tin tức khác';
            $itemUrl = general::getInstance()->permalink((int)($item->id ?? 0), 'event');
            $itemType = (int)($item->event_type ?? 0);
            $itemCat = $itemType === 1 ? 'NTD' : ($itemType === 2 ? 'Kỹ năng' : 'ViecLam');
            $itemCatClass = $itemType === 1 ? 'emp' : ($itemType === 2 ? 'seek' : 'site');
            
            $itemImg = 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&h=200&fit=crop';
            if(isset($item->event_image) && trim($item->event_image) !== ''){
              $imgPath = trim($item->event_image);
              if(preg_match('#^(https?:)?//#i', $imgPath) || strpos($imgPath, 'data:') === 0){
                $itemImg = $imgPath;
              } else {
                $itemImg = XC_URL.'/uploads/events/'.ltrim($imgPath, '/');
              }
            }
            ?>
            <div class="hs-slide">
              <div class="hs-card">
                <div class="hs-thumb">
                  <img src="<?php echo htmlspecialchars($itemImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy"/>
                  <div class="hs-badge"><?php echo $index + 1; ?></div>
                </div>
                <div class="hs-body">
                  <span class="hs-cat <?php echo $itemCatClass; ?>"><?php echo htmlspecialchars($itemCat, ENT_QUOTES, 'UTF-8'); ?></span>
                  <a href="<?php echo htmlspecialchars($itemUrl, ENT_QUOTES, 'UTF-8'); ?>" class="hs-card-title" style="display:block;text-decoration:none;color:inherit;font-weight:650;font-size:13px;line-height:1.4;margin:5px 0 10px;"><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?></a>
                  <div class="hs-meta">
                    <span><i class="ti ti-calendar"></i><?php echo date('d/m', strtotime($item->event_created_date ?? 'now')); ?></span>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="text-align: center; color: #667085; width: 100%; padding: 20px 0;">Không có tin tức nổi bật khác.</p>
        <?php endif; ?>
      </div><!-- end track -->
    </div><!-- end wrap -->
    <div class="hs-dots" id="hsDots"></div>
  </div>
</section>

<!-- ===== SCRIPTS ===== -->
<script>
// Reading progress bar
window.addEventListener('scroll',function(){
  var body=document.body;
  var html=document.documentElement;
  var scrollTop=window.scrollY;
  var total=Math.max(body.scrollHeight,html.scrollHeight)-window.innerHeight;
  var pct=total>0?(scrollTop/total)*100:0;
  var readProgress = document.getElementById('readProgress');
  if(readProgress) readProgress.style.width=pct+'%';
});

// Floating share bar: show after scrolling 300px
window.addEventListener('scroll',function(){
  var fs=document.getElementById('floatingShare');
  if(fs) fs.style.opacity=window.scrollY>300?'1':'0';
});

// Reactions toggle
function toggleReaction(btn){
  var isActive=btn.classList.contains('active');
  document.querySelectorAll('.reaction-btn').forEach(function(b){b.classList.remove('active')});
  if(!isActive) btn.classList.add('active');
}

// Generate Table of Contents & setup highlight on scroll
(function() {
  var articleBody = document.getElementById('articleBody');
  var tocList = document.getElementById('tocList');
  if (!articleBody || !tocList) return;

  var headings = articleBody.querySelectorAll('h2, h3');
  tocList.innerHTML = ''; // clear existing static list

  headings.forEach(function(heading, index) {
    var id = heading.getAttribute('id');
    if (!id) {
      id = 'heading-' + (index + 1);
      heading.setAttribute('id', id);
    }
    var li = document.createElement('li');
    var a = document.createElement('a');
    a.href = '#' + id;
    a.className = 'toc-link';
    
    var numSpan = document.createElement('span');
    numSpan.className = 'toc-num';
    numSpan.textContent = index + 1;
    
    a.appendChild(numSpan);
    a.appendChild(document.createTextNode(' ' + heading.textContent));
    li.appendChild(a);
    tocList.appendChild(li);
  });

  // Append Comments link
  var commentsLi = document.createElement('li');
  var commentsA = document.createElement('a');
  commentsA.href = '#comments';
  commentsA.className = 'toc-link';
  var iconSpan = document.createElement('span');
  iconSpan.className = 'toc-num';
  var icon = document.createElement('i');
  icon.className = 'ti ti-message-circle';
  icon.style.fontSize = '11px';
  iconSpan.appendChild(icon);
  commentsA.appendChild(iconSpan);
  
  var commentBadge = document.querySelector('.comment-count-badge');
  var totalCommentsText = commentBadge ? commentBadge.textContent : 'Bình luận';
  commentsA.appendChild(document.createTextNode(' ' + totalCommentsText));
  commentsLi.appendChild(commentsA);
  tocList.appendChild(commentsLi);

  // TOC highlight on scroll
  var tocLinks = document.querySelectorAll('.toc-link');
  window.addEventListener('scroll', function() {
    var scrollY = window.scrollY + 120;
    var current = '';
    headings.forEach(function(h) {
      if (h.offsetTop <= scrollY) current = h.id;
    });
    // check if scrolled near bottom for comments highlight
    var commentsSection = document.getElementById('comments');
    if (commentsSection && commentsSection.offsetTop <= scrollY) {
      current = 'comments';
    }
    tocLinks.forEach(function(link) {
      var href = link.getAttribute('href').replace('#', '');
      link.classList.toggle('active', href === current);
    });
  });
})();

// Comment AJAX & Replying logic
document.getElementById('commentForm').addEventListener('submit', function(e) {
  e.preventDefault();
  var name = document.getElementById('comment_name').value.trim();
  var email = document.getElementById('comment_email').value.trim();
  var content = document.getElementById('comment_content').value.trim();
  var parentId = document.getElementById('comment_parent_id').value;
  var eventId = <?php echo (int)($news_detail->id ?? 0); ?>;
  
  if (!name || !email || !content) {
    alert('Vui lòng điền đầy đủ các thông tin bắt buộc.');
    return;
  }
  
  var submitBtn = this.querySelector('.cf-submit');
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<i class="ti ti-loader"></i> Đang gửi...';

  var formData = new FormData();
  formData.append('event_id', eventId);
  formData.append('parent_id', parentId);
  formData.append('comment_name', name);
  formData.append('comment_email', email);
  formData.append('comment_content', content);

  fetch('<?php echo XC_URL; ?>/home/add_news_comment', {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    if (data.status === 'success') {
      alert('Bình luận của bạn đã được gửi thành công!');
      location.reload();
    } else {
      alert(data.message || 'Đã có lỗi xảy ra. Vui lòng thử lại.');
    }
  })
  .catch(function(err) {
    console.error(err);
    alert('Đã xảy ra lỗi kết nối. Vui lòng thử lại.');
  })
  .finally(function() {
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="ti ti-send"></i>Gửi bình luận';
  });
});

function replyComment(commentId, authorName) {
  document.getElementById('comment_parent_id').value = commentId;
  document.getElementById('replying-to-name').textContent = authorName;
  document.getElementById('replying-to-helper').style.display = 'flex';
  
  var commentForm = document.getElementById('commentForm');
  commentForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
  document.getElementById('comment_content').focus();
}

function cancelReply() {
  document.getElementById('comment_parent_id').value = '';
  document.getElementById('replying-to-helper').style.display = 'none';
}

function likeComment(commentId, btn) {
  var isLiked = btn.classList.contains('liked');
  var countSpan = btn.querySelector('.like-count');
  var currentCount = parseInt(countSpan.textContent, 10) || 0;
  
  if (isLiked) {
    btn.classList.remove('liked');
    countSpan.textContent = currentCount - 1;
  } else {
    btn.classList.add('liked');
    countSpan.textContent = currentCount + 1;
  }
}

// Comment access and public display adjustments
(function() {
  var canComment = <?php echo $canComment ? 'true' : 'false'; ?>;
  var commentForm = document.getElementById('commentForm');
  var commentName = document.getElementById('comment_name');
  var commentEmail = document.getElementById('comment_email');
  var commentGrid = document.querySelector('.cf-grid');
  var replyHelper = document.getElementById('replying-to-helper');
  var commentWrap = document.querySelector('.comment-form-wrap');
  var sessionName = <?php echo json_encode(isset($_SESSION['user']['full_name']) ? (string)$_SESSION['user']['full_name'] : ''); ?>;
  var sessionEmail = <?php echo json_encode(isset($_SESSION['user']['email']) ? (string)$_SESSION['user']['email'] : ''); ?>;

  if (replyHelper) {
    replyHelper.style.display = 'none';
  }
  if (commentGrid) {
    commentGrid.style.display = 'none';
  }
  if (commentName) {
    commentName.value = sessionName || 'Tai khoan';
    commentName.removeAttribute('required');
  }
  if (commentEmail) {
    commentEmail.value = sessionEmail || 'account@example.com';
    commentEmail.removeAttribute('required');
  }

  if (!canComment && commentWrap) {
    var notice = document.createElement('div');
    notice.className = 'alert alert-warning';
    notice.style.marginBottom = '12px';
    notice.innerHTML = 'Vui lòng đăng nhập để sử dụng chức năng bình luận.';
    commentWrap.insertBefore(notice, commentWrap.firstChild.nextSibling);
    var commentTextarea = document.getElementById('comment_content');
    var submitBtn = commentWrap.querySelector('.cf-submit');
    if (commentTextarea) {
      commentTextarea.setAttribute('disabled', 'disabled');
      commentTextarea.setAttribute('placeholder', 'Đăng nhập để gửi bình luận');
    }
    if (submitBtn) {
      submitBtn.setAttribute('disabled', 'disabled');
    }
  }

  if (commentForm) {
    commentForm.addEventListener('submit', function(e) {
      if (!canComment) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var loginBtn = document.querySelector('.js-login-open');
        if (loginBtn) {
          loginBtn.click();
        } else {
          window.location.href = '<?php echo $commentLoginUrl; ?>';
        }
      }
    }, true);
  }
})();

// Hot Slider
(function(){
  var track=document.getElementById('hsTrack');
  var dotsWrap=document.getElementById('hsDots');
  var slides=document.querySelectorAll('.hs-slide');
  if(!track || !slides.length) return;
  var current=0;
  var autoTimer;

  function perView(){
    var w=window.innerWidth;
    if(w<=480)return 1;
    if(w<=640)return 1;
    if(w<=900)return 2;
    if(w<=1060)return 3;
    return 4;
  }
  function groups(){return Math.ceil(slides.length/perView())}

  function buildDots(){
    if(!dotsWrap) return;
    dotsWrap.innerHTML='';
    for(var i=0;i<groups();i++){
      var d=document.createElement('div');
      d.className='hs-dot'+(i===0?' active':'');
      (function(idx){d.addEventListener('click',function(){go(idx)})})(i);
      dotsWrap.appendChild(d);
    }
  }

  function go(page){
    var g=groups();
    if(page<0)page=g-1;
    if(page>=g)page=0;
    current=page;
    var pct=(100/slides.length)*perView()*current;
    track.style.transform='translateX(-'+pct+'%)';
    document.querySelectorAll('.hs-dot').forEach(function(d,i){d.classList.toggle('active',i===current)});
    clearInterval(autoTimer);
    autoTimer=setInterval(function(){go(current+1)},4000);
  }

  var nextBtn = document.getElementById('hsNext');
  var prevBtn = document.getElementById('hsPrev');
  if(nextBtn) nextBtn.addEventListener('click',function(){go(current+1)});
  if(prevBtn) prevBtn.addEventListener('click',function(){go(current-1)});

  var tx=0;
  var hsWrap = document.getElementById('hsWrap');
  if(hsWrap) {
    hsWrap.addEventListener('touchstart',function(e){tx=e.changedTouches[0].clientX},{passive:true});
    hsWrap.addEventListener('touchend',function(e){
      var d=tx-e.changedTouches[0].clientX;
      if(Math.abs(d)>40)go(current+(d>0?1:-1));
    },{passive:true});
  }

  window.addEventListener('resize',function(){buildDots();go(0)});
  buildDots();
  autoTimer=setInterval(function(){go(current+1)},4000);
})();
</script>
<?php require 'footer.php'; ?>

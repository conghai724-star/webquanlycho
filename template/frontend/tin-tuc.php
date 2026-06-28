<?php require "header.php"; ?>
<?php
$newsSections = isset($news_sections) && is_array($news_sections) ? $news_sections : array();
$newsCounts = isset($news_counts) && is_array($news_counts) ? $news_counts : array('all' => 0, 'site' => 0, 'employer' => 0, 'seeker' => 0);
$featuredNews = isset($featured_news) && is_array($featured_news) ? $featured_news : array();
$newsKeyword = isset($news_keyword) ? trim((string)$news_keyword) : '';
$newsActiveSection = isset($news_active_section) && $news_active_section !== '' ? (string)$news_active_section : 'all';

function newsH($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function newsStrip($value){ return trim(strip_tags((string)$value)); }
function newsExcerpt($news, $length = 160){
  $text = newsStrip($news->event_description ?? '');
  if($text === ''){ $text = newsStrip($news->event_content ?? ''); }
  if($text === ''){ return 'Nội dung đang được cập nhật.'; }
  if(function_exists('mb_strlen') && function_exists('mb_substr')){
    return mb_strlen($text, 'UTF-8') > $length ? mb_substr($text, 0, $length, 'UTF-8').'...' : $text;
  }
  return strlen($text) > $length ? substr($text, 0, $length).'...' : $text;
}
function newsDateText($value){
  $time = $value ? strtotime((string)$value) : false;
  return $time ? date('d/m/Y', $time) : 'Đang cập nhật';
}
function newsImageUrl($value){
  $value = trim((string)$value);
  if($value === ''){ return 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=900&h=520&fit=crop'; }
  if(preg_match('#^(https?:)?//#i', $value) || strpos($value, 'data:') === 0){ return $value; }
  return XC_URL.'/uploads/events/'.ltrim($value, '/');
}
function newsDetailUrl($news){
  return general::getInstance()->permalink((int)($news->id ?? 0), 'event');
}
function newsSectionMeta($key){
  $map = array(
    'site' => array('label' => 'Tin từ ViecLam.vn', 'class' => 'site', 'icon' => 'ti ti-building-community', 'color' => '#0d4e96', 'bar' => 'blue'),
    'employer' => array('label' => 'Tin từ Nhà tuyển dụng', 'class' => 'employer', 'icon' => 'ti ti-speakerphone', 'color' => '#2e7d32', 'bar' => 'green'),
    'seeker' => array('label' => 'Dành cho người tìm việc', 'class' => 'seeker', 'icon' => 'ti ti-user-search', 'color' => '#e65100', 'bar' => 'orange'),
  );
  return isset($map[$key]) ? $map[$key] : $map['site'];
}
function newsTypeLabel($news, $fallbackKey = 'site'){
  $type = isset($news->event_type) ? (int)$news->event_type : null;
  if($type === 1){ return 'Nhà tuyển dụng'; }
  if($type === 2){ return 'Người tìm việc'; }
  if($type === 0 || $type === 3){ return 'ViecLam.vn'; }
  $meta = newsSectionMeta($fallbackKey);
  return $meta['label'];
}
function newsRenderCard($news, $sectionKey = 'site'){
  $meta = newsSectionMeta($sectionKey);
  $title = $news->event_name ?? 'Bài viết đang cập nhật';
  ?>
  <div class="post-card">
    <div class="card-thumb">
      <img src="<?php echo newsH(newsImageUrl($news->event_image ?? '')); ?>" alt="<?php echo newsH($title); ?>"/>
      <?php if((int)($news->event_hot ?? 0) === 1): ?><div class="card-badge"><span class="hot">HOT</span></div><?php endif; ?>
    </div>
    <div class="card-body">
      <span class="post-cat-badge <?php echo newsH($meta['class']); ?>" style="font-size:10px;margin-bottom:7px"><i class="<?php echo newsH($meta['icon']); ?>"></i> <?php echo newsH(newsTypeLabel($news, $sectionKey)); ?></span>
      <a href="<?php echo newsH(newsDetailUrl($news)); ?>" class="card-title"><?php echo newsH($title); ?></a>
      <p class="card-excerpt"><?php echo newsH(newsExcerpt($news, 140)); ?></p>
      <div class="card-meta">
        <span><i class="ti ti-calendar"></i> <?php echo newsH(newsDateText($news->event_created_date ?? '')); ?></span>
      </div>
    </div>
  </div>
  <?php
}
function newsRenderListItem($news, $sectionKey = 'employer'){
  $meta = newsSectionMeta($sectionKey);
  $title = $news->event_name ?? 'Bài viết đang cập nhật';
  ?>
  <div class="post-list-item">
    <div class="li-thumb">
      <img src="<?php echo newsH(newsImageUrl($news->event_image ?? '')); ?>" alt="<?php echo newsH($title); ?>"/>
    </div>
    <div class="li-body">
      <span class="post-cat-badge <?php echo newsH($meta['class']); ?>" style="font-size:10px;margin-bottom:6px"><i class="<?php echo newsH($meta['icon']); ?>"></i> <?php echo newsH(newsTypeLabel($news, $sectionKey)); ?></span>
      <a href="<?php echo newsH(newsDetailUrl($news)); ?>" class="li-title"><?php echo newsH($title); ?></a>
      <p class="li-excerpt"><?php echo newsH(newsExcerpt($news, 180)); ?></p>
      <div class="li-meta">
        <span><i class="ti ti-calendar"></i> <?php echo newsH(newsDateText($news->event_created_date ?? '')); ?></span>
      </div>
    </div>
  </div>
  <?php
}

$siteItems = isset($newsSections['site']['items']) && is_array($newsSections['site']['items']) ? $newsSections['site']['items'] : array();
$employerItems = isset($newsSections['employer']['items']) && is_array($newsSections['employer']['items']) ? $newsSections['employer']['items'] : array();
$seekerItems = isset($newsSections['seeker']['items']) && is_array($newsSections['seeker']['items']) ? $newsSections['seeker']['items'] : array();
$popularItems = !empty($featuredNews) ? $featuredNews : array_merge($siteItems, $employerItems, $seekerItems);
?>

<section class="news-hero">
  <div class="news-hero-inner">
    <div class="news-hero-label"><i class="ti ti-news"></i> Trung tâm tin tức</div>
    <h1>Tin tức <span>Thị trường lao động</span><br>& Nghề nghiệp Việt Nam</h1>
    <p>Cập nhật nhanh nhất về tuyển dụng, xu hướng việc làm và kỹ năng nghề nghiệp</p>
    <form class="news-hero-search" method="get" action="<?php echo XC_URL; ?>/tin-tuc-su-kien.html">
      <input type="text" name="keyword" value="<?php echo newsH($newsKeyword); ?>" placeholder="Tìm kiếm bài viết, chủ đề..."/>
      <?php if($newsActiveSection !== 'all'): ?><input type="hidden" name="section" value="<?php echo newsH($newsActiveSection); ?>"/><?php endif; ?>
      <button type="submit"><i class="ti ti-search" style="vertical-align:-2px;margin-right:6px"></i>Tìm kiếm</button>
    </form>
  </div>
</section>

<div class="news-tabs-bar">
  <div class="news-tabs-inner" id="newsTabs">
    <div class="news-tab <?php echo $newsActiveSection === 'all' ? 'active' : ''; ?>" data-section="all"><i class="ti ti-layout-grid tab-icon"></i>Tất cả <span class="tab-count"><?php echo (int)($newsCounts['all'] ?? 0); ?></span></div>
    <div class="news-tab <?php echo $newsActiveSection === 'site' ? 'active' : ''; ?>" data-section="site"><i class="ti ti-building-community tab-icon"></i>Tin từ ViecLam <span class="tab-count"><?php echo (int)($newsCounts['site'] ?? 0); ?></span></div>
    <div class="news-tab <?php echo $newsActiveSection === 'employer' ? 'active' : ''; ?>" data-section="employer"><i class="ti ti-speakerphone tab-icon"></i>Nhà tuyển dụng <span class="tab-count"><?php echo (int)($newsCounts['employer'] ?? 0); ?></span></div>
    <div class="news-tab <?php echo $newsActiveSection === 'seeker' ? 'active' : ''; ?>" data-section="seeker"><i class="ti ti-user-search tab-icon"></i>Người tìm việc <span class="tab-count"><?php echo (int)($newsCounts['seeker'] ?? 0); ?></span></div>
  </div>
</div>

<div class="news-main">
  <div class="news-content">
    <section class="news-section" id="sec-site">
      <div class="news-section-header">
        <div class="news-section-title">
          <span class="ns-bar blue"></span>
          <i class="ti ti-building-community" style="color:#0d4e96"></i>
          Tin từ ViecLam.vn
        </div>
      </div>
      <?php if(!empty($siteItems)): ?>
        <?php $siteFeatured = $siteItems[0]; $siteGrid = array_slice($siteItems, 1); ?>
        <div class="post-featured">
          <div class="post-thumb">
            <img src="<?php echo newsH(newsImageUrl($siteFeatured->event_image ?? '')); ?>" alt="<?php echo newsH($siteFeatured->event_name ?? ''); ?>"/>
          </div>
          <div class="post-body">
            <span class="post-cat-badge site"><i class="ti ti-building-community"></i> <?php echo newsH(newsTypeLabel($siteFeatured, 'site')); ?></span>
            <a href="<?php echo newsH(newsDetailUrl($siteFeatured)); ?>" class="post-title"><?php echo newsH($siteFeatured->event_name ?? ''); ?></a>
            <p class="post-excerpt"><?php echo newsH(newsExcerpt($siteFeatured, 220)); ?></p>
            <div class="post-meta">
              <span><i class="ti ti-calendar"></i> <?php echo newsH(newsDateText($siteFeatured->event_created_date ?? '')); ?></span>
            </div>
          </div>
        </div>
        <?php if(!empty($siteGrid)): ?>
          <div class="posts-grid">
            <?php foreach($siteGrid as $news){ newsRenderCard($news, 'site'); } ?>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="post-featured"><div class="post-body"><p class="post-excerpt">Chưa có bài viết trong mục này.</p></div></div>
      <?php endif; ?>
    </section>

    <section class="news-section" id="sec-employer">
      <div class="news-section-header">
        <div class="news-section-title">
          <span class="ns-bar green"></span>
          <i class="ti ti-speakerphone" style="color:#2e7d32"></i>
          Tin từ Nhà tuyển dụng
        </div>
      </div>
      <?php if(!empty($employerItems)): ?>
        <div class="posts-list">
          <?php foreach($employerItems as $news){ newsRenderListItem($news, 'employer'); } ?>
        </div>
      <?php else: ?>
        <div class="post-featured"><div class="post-body"><p class="post-excerpt">Chưa có bài viết trong mục này.</p></div></div>
      <?php endif; ?>
    </section>

    <section class="news-section" id="sec-seeker">
      <div class="news-section-header">
        <div class="news-section-title">
          <span class="ns-bar orange"></span>
          <i class="ti ti-user-search" style="color:#e65100"></i>
          Dành cho người tìm việc
        </div>
      </div>
      <?php if(!empty($seekerItems)): ?>
        <div class="posts-grid">
          <?php foreach($seekerItems as $news){ newsRenderCard($news, 'seeker'); } ?>
        </div>
      <?php else: ?>
        <div class="post-featured"><div class="post-body"><p class="post-excerpt">Chưa có bài viết trong mục này.</p></div></div>
      <?php endif; ?>
    </section>
  </div>

  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-card-header"><i class="ti ti-flame"></i> Bài viết nổi bật</div>
      <div class="sidebar-posts">
        <?php if(!empty($popularItems)): ?>
          <?php foreach(array_slice($popularItems, 0, 5) as $index => $news): ?>
            <div class="sidebar-post">
              <span class="sp-num <?php echo $index < 3 ? 'top' : ''; ?>"><?php echo $index + 1; ?></span>
              <div>
                <a href="<?php echo newsH(newsDetailUrl($news)); ?>" class="sp-title"><?php echo newsH($news->event_name ?? ''); ?></a>
                <div class="sp-date"><i class="ti ti-calendar" style="font-size:10px"></i> <?php echo newsH(newsDateText($news->event_created_date ?? '')); ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="sidebar-post"><div><div class="sp-title">Chưa có bài viết nổi bật.</div></div></div>
        <?php endif; ?>
      </div>
    </div>

    <div class="sidebar-card">
      <div class="sidebar-card-header"><i class="ti ti-share"></i> Theo dõi chúng tôi</div>
      <div class="social-follow">
        <div class="social-row fb"><span class="sr-left"><i class="ti ti-brand-facebook"></i> Facebook</span><span class="sr-count">250K likes</span></div>
        <div class="social-row zalo"><span class="sr-left"><i class="ti ti-message-circle"></i> Zalo OA</span><span class="sr-count">180K followers</span></div>
        <div class="social-row yt"><span class="sr-left"><i class="ti ti-brand-youtube"></i> YouTube</span><span class="sr-count">85K subscribers</span></div>
      </div>
    </div>
  </aside>
</div>

<script>
(function(){
  var tabs = document.querySelectorAll('.news-tab');
  var active = '<?php echo newsH($newsActiveSection); ?>' || 'all';

  function applySection(sec){
    tabs.forEach(function(t){ t.classList.toggle('active', t.getAttribute('data-section') === sec); });
    var sections = {
      all:['sec-site','sec-employer','sec-seeker'],
      site:['sec-site'],
      employer:['sec-employer'],
      seeker:['sec-seeker']
    };
    ['sec-site','sec-employer','sec-seeker'].forEach(function(id){
      var el = document.getElementById(id);
      if(el){ el.style.display = 'none'; }
    });
    (sections[sec] || sections.all).forEach(function(id){
      var el = document.getElementById(id);
      if(el){ el.style.display = 'block'; }
    });
  }

  tabs.forEach(function(tab){
    tab.addEventListener('click', function(){
      applySection(tab.getAttribute('data-section'));
    });
  });

  applySection(active);
})();
</script>

<?php require "footer.php"; ?>

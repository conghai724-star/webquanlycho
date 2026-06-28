<?php require "header.php"; ?>
<?php
$stats = isset($dashboard_stats) && is_array($dashboard_stats) ? $dashboard_stats : array();
function dashboardCount($stats, $key){ return number_format((int)($stats[$key] ?? 0), 0, ',', '.'); }
$cards = array(
   array('key' => 'job_posts', 'label' => 'Tin tuyển dụng', 'note' => 'Tổng số tin job', 'icon' => 'fa-briefcase', 'color' => '#0d6efd', 'url' => XC_URL.'/admin/employers/posts'),
   array('key' => 'candidates', 'label' => 'Ứng viên', 'note' => 'Tổng hồ sơ ứng viên', 'icon' => 'fa-users', 'color' => '#6f42c1', 'url' => XC_URL.'/admin/candidates'),
   array('key' => 'employers', 'label' => 'Nhà tuyển dụng', 'note' => 'Tổng số doanh nghiệp', 'icon' => 'fa-building', 'color' => '#198754', 'url' => XC_URL.'/admin/employers'),
   array('key' => 'linked_employers', 'label' => 'Nhà tuyển dụng liên kết', 'note' => 'Đã liên kết với site', 'icon' => 'fa-link', 'color' => '#0dcaf0', 'url' => XC_URL.'/admin/employers?linked_status=linked'),
   array('key' => 'unlinked_employers', 'label' => 'Nhà tuyển dụng chưa liên kết', 'note' => 'Chưa liên kết với site', 'icon' => 'fa-link-slash', 'color' => '#6c757d', 'url' => XC_URL.'/admin/employers?linked_status=unlinked'),
   array('key' => 'published_news', 'label' => 'Tin tức đã đăng', 'note' => 'Tin đang hiển thị', 'icon' => 'fa-newspaper', 'color' => '#fd7e14', 'url' => XC_URL.'/admin/events?status=1'),
   array('key' => 'pending_job_posts', 'label' => 'Tin job chưa duyệt', 'note' => 'Cần kiểm duyệt', 'icon' => 'fa-clock', 'color' => '#dc3545', 'url' => XC_URL.'/admin/employers/posts?status=pending'),
   array('key' => 'pending_candidates', 'label' => 'Ứng viên chưa duyệt', 'note' => 'Cần kiểm duyệt hồ sơ', 'icon' => 'fa-user-clock', 'color' => '#d63384', 'url' => XC_URL.'/admin/candidates?status=pending'),
   array('key' => 'students', 'label' => 'Sinh viên tại trường', 'note' => 'Danh sách sinh viên', 'icon' => 'fa-graduation-cap', 'color' => '#20c997', 'url' => XC_URL.'/admin/students')
);
?>
<style>
.admin-dashboard{padding:28px}.dashboard-heading{margin-bottom:22px}.dashboard-heading h1{margin:0;color:#172033;font-size:28px;font-weight:800}.dashboard-heading p{margin:7px 0 0;color:#718096;font-size:15px}.dashboard-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}.dashboard-stat{display:flex;min-height:150px;align-items:center;gap:16px;padding:22px;border:1px solid #e5ebf3;border-radius:10px;background:#fff;color:inherit;text-decoration:none;box-shadow:0 5px 18px rgba(25,50,85,.05);transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}.dashboard-stat:hover{transform:translateY(-3px);border-color:var(--stat-color);box-shadow:0 12px 28px rgba(25,50,85,.11);color:inherit}.dashboard-stat-icon{display:flex;width:58px;height:58px;align-items:center;justify-content:center;flex:0 0 58px;border-radius:10px;background:color-mix(in srgb,var(--stat-color) 13%,white);color:var(--stat-color);font-size:24px}.dashboard-stat-value{color:#172033;font-size:31px;font-weight:850;line-height:1}.dashboard-stat-label{margin-top:8px;color:#263247;font-size:16px;font-weight:750}.dashboard-stat-note{margin-top:4px;color:#8491a3;font-size:13px}.dashboard-stat-arrow{margin-left:auto;color:#a3afbf;font-size:16px;transition:transform .18s ease,color .18s ease}.dashboard-stat:hover .dashboard-stat-arrow{color:var(--stat-color);transform:translateX(3px)}@media(max-width:1100px){.dashboard-stats{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:680px){.admin-dashboard{padding:18px 14px}.dashboard-stats{grid-template-columns:1fr}.dashboard-stat{min-height:128px;padding:18px}}
</style>
<div class="sidebar-footer"></div></aside>
<main class="main-content">
   <div class="admin-dashboard">
      <!-- <div class="dashboard-heading"><h1>Tổng quan quản trị</h1><p>Theo dõi nhanh dữ liệu tuyển dụng và các nội dung cần xử lý.</p></div> -->
      <div class="dashboard-stats">
         <?php foreach($cards as $card): ?>
         <a class="dashboard-stat" href="<?php echo htmlspecialchars($card['url'], ENT_QUOTES, 'UTF-8'); ?>" style="--stat-color:<?php echo $card['color']; ?>">
            <span class="dashboard-stat-icon"><i class="fa-solid <?php echo $card['icon']; ?>"></i></span>
            <span><span class="dashboard-stat-value"><?php echo dashboardCount($stats, $card['key']); ?></span><span class="dashboard-stat-label"><?php echo htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8'); ?></span><span class="dashboard-stat-note"><?php echo htmlspecialchars($card['note'], ENT_QUOTES, 'UTF-8'); ?></span></span>
            <i class="fa-solid fa-arrow-right dashboard-stat-arrow"></i>
         </a>
         <?php endforeach; ?>
      </div>
   </div>
</main>
<?php require "footer.php"; ?>

<?php require "header.php"; ?>
<style>
.admin-notice-page{padding:24px}
.admin-notice-card{max-width:820px;background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:28px;box-shadow:0 8px 24px rgba(15,23,42,.06)}
.admin-notice-card h1{margin:0 0 12px;font-size:28px;font-weight:800;color:#172033}
.admin-notice-card p{margin:0 0 18px;color:#5b6472;font-size:15px;line-height:1.7}
.admin-notice-actions{display:flex;flex-wrap:wrap;gap:12px}
.admin-notice-actions a{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:10px;text-decoration:none;font-weight:600}
.admin-notice-primary{background:#0d6efd;color:#fff}
.admin-notice-secondary{background:#f3f4f6;color:#111827}
</style>
<main class="main-content">
   <div class="admin-notice-page">
      <div class="admin-notice-card">
         <h1><?php echo isset($notice_title) ? htmlspecialchars($notice_title, ENT_QUOTES, 'UTF-8') : 'Thông báo'; ?></h1>
         <p><?php echo isset($notice_description) ? htmlspecialchars($notice_description, ENT_QUOTES, 'UTF-8') : 'Chức năng này đang được cập nhật.'; ?></p>
         <div class="admin-notice-actions">
            <a class="admin-notice-primary" href="<?php echo XC_URL; ?>/admin">Về trang quản trị</a>
            <a class="admin-notice-secondary" href="<?php echo XC_URL; ?>/admin/users">Mở quản lý tài khoản</a>
         </div>
      </div>
   </div>
</main>
<?php require "footer.php"; ?>

<?php include_once "header.php";
$item = isset($customer_feedback_detail) ? $customer_feedback_detail : null;
?>

<div class="content container-fluid">
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h3 class="page-title">Chi tiết phản hồi khách hàng</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL; ?>/admin/customerfeedbacks">Phản hồi khách hàng</a></li>
               <li class="breadcrumb-item active">Chi tiết</li>
            </ul>
         </div>
         <div class="col-auto">
            <a class="btn btn-light border" href="<?php echo XC_URL; ?>/admin/customerfeedbacks">Quay lại danh sách</a>
         </div>
      </div>
   </div>

   <div class="card">
      <div class="card-body">
         <?php if($item): ?>
            <div class="row g-4">
               <div class="col-md-6">
                  <label class="form-label text-muted">Họ và tên</label>
                  <div class="fw-semibold"><?php echo htmlspecialchars($item->customer_name, ENT_QUOTES, 'UTF-8'); ?></div>
               </div>
               <div class="col-md-6">
                  <label class="form-label text-muted">Số điện thoại</label>
                  <div><?php echo htmlspecialchars($item->customer_phone, ENT_QUOTES, 'UTF-8'); ?></div>
               </div>
               <div class="col-md-6">
                  <label class="form-label text-muted">Email</label>
                  <div><?php echo htmlspecialchars($item->customer_email, ENT_QUOTES, 'UTF-8'); ?></div>
               </div>
               <div class="col-md-6">
                  <label class="form-label text-muted">Ngày gửi</label>
                  <div><?php echo !empty($item->create_date) ? htmlspecialchars(date('d/m/Y H:i', strtotime($item->create_date)), ENT_QUOTES, 'UTF-8') : ''; ?></div>
               </div>
               <div class="col-12">
                  <label class="form-label text-muted">Địa chỉ</label>
                  <div><?php echo htmlspecialchars($item->customer_address, ENT_QUOTES, 'UTF-8'); ?></div>
               </div>
               <div class="col-12">
                  <label class="form-label text-muted">Nội dung phản hồi</label>
                  <div class="border rounded-3 p-3 bg-light"><?php echo nl2br(htmlspecialchars($item->content, ENT_QUOTES, 'UTF-8')); ?></div>
               </div>
               <div class="col-12">
                  <form method="post" onsubmit="return confirm('Xóa phản hồi này?');">
                     <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>">
                     <button class="btn btn-danger" name="customer_feedback_action" value="delete">Xóa phản hồi</button>
                  </form>
               </div>
            </div>
         <?php else: ?>
            <div class="text-muted">Không tìm thấy dữ liệu phản hồi.</div>
         <?php endif; ?>
      </div>
   </div>
</div>

<?php include_once "footer.php"; ?>

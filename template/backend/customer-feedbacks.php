<?php include_once "header.php";
$customer_feedbacks = is_array($customer_feedbacks) ? $customer_feedbacks : array();
$page = isset($customer_feedback_page) ? (int)$customer_feedback_page : 1;
$per_page = isset($customer_feedback_per_page) ? (int)$customer_feedback_per_page : 10;
$total_feedback = isset($customer_feedback_total) ? (int)$customer_feedback_total : count($customer_feedbacks);
$total_pages = isset($customer_feedback_total_pages) ? (int)$customer_feedback_total_pages : 1;
$row_offset = max(0, ($page - 1) * $per_page);
$keyword = isset($customer_feedback_keyword) ? trim((string)$customer_feedback_keyword) : '';

if (!function_exists('backendCustomerFeedbackExcerpt')) {
	function backendCustomerFeedbackExcerpt($value, $limit = 90) {
		$value = trim((string)$value);
		if ($value === '') { return ''; }
		if (function_exists('mb_strlen') && function_exists('mb_substr')) {
			return mb_strlen($value, 'UTF-8') > $limit ? mb_substr($value, 0, $limit, 'UTF-8').'...' : $value;
		}
		return strlen($value) > $limit ? substr($value, 0, $limit).'...' : $value;
	}
}

if (!function_exists('backendCustomerFeedbackPageUrl')) {
	function backendCustomerFeedbackPageUrl($targetPage, $keyword = '') {
		$query = array('page' => (int)$targetPage);
		if ($keyword !== '') {
			$query['keyword'] = $keyword;
		}
		return XC_URL.'/admin/customerfeedbacks?'.http_build_query($query);
	}
}
?>

<div class="content container-fluid">
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h3 class="page-title">Phản hồi của khách hàng</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item active">Danh sách phản hồi liên hệ</li>
            </ul>
         </div>
      </div>
   </div>

   <?php if(!empty($customer_feedback_flash)): ?>
      <div class="alert alert-<?php echo $customer_feedback_flash['type'] == 'success' ? 'success' : 'info'; ?>"><?php echo htmlspecialchars($customer_feedback_flash['message']); ?></div>
   <?php endif; ?>

   <div class="card card-table">
      <div class="card-body">
         <form class="row g-3 mb-4" method="get">
            <div class="col-md-5">
               <input class="form-control" type="text" name="keyword" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Tìm theo họ tên, SĐT, email, địa chỉ hoặc nội dung">
            </div>
            <div class="col-auto">
               <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            </div>
            <div class="col-auto">
               <a class="btn btn-light border" href="<?php echo XC_URL; ?>/admin/customerfeedbacks">Làm mới</a>
            </div>
         </form>

         <div class="table-responsive">
            <table class="table table-hover table-center mb-0">
               <thead>
                  <tr>
                     <th>STT</th>
                     <th>Khách hàng</th>
                     <th>Liên hệ</th>
                     <th>Nội dung</th>
                     <th>Ngày gửi</th>
                     <th>Thao tác</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($customer_feedbacks)): ?>
                     <?php $stt = $row_offset + 1; foreach($customer_feedbacks as $item): ?>
                        <tr>
                           <td><?php echo $stt++; ?></td>
                           <td>
                              <div class="fw-semibold"><?php echo htmlspecialchars($item->customer_name, ENT_QUOTES, 'UTF-8'); ?></div>
                              <small class="text-muted"><?php echo htmlspecialchars($item->customer_address, ENT_QUOTES, 'UTF-8'); ?></small>
                           </td>
                           <td>
                              <small class="d-block"><?php echo htmlspecialchars($item->customer_phone, ENT_QUOTES, 'UTF-8'); ?></small>
                              <small class="d-block text-muted"><?php echo htmlspecialchars($item->customer_email, ENT_QUOTES, 'UTF-8'); ?></small>
                           </td>
                           <td><?php echo htmlspecialchars(backendCustomerFeedbackExcerpt($item->content), ENT_QUOTES, 'UTF-8'); ?></td>
                           <td><?php echo !empty($item->create_date) ? htmlspecialchars(date('d/m/Y H:i', strtotime($item->create_date)), ENT_QUOTES, 'UTF-8') : ''; ?></td>
                           <td>
                              <div class="d-flex align-items-center gap-2 flex-wrap">
                                 <a class="btn btn-sm btn-info text-white" href="<?php echo XC_URL; ?>/admin/customerfeedbacks/detail/<?php echo (int)$item->id; ?>">Xem chi tiết</a>
                                 <form method="post" class="d-inline" onsubmit="return confirm('Xóa phản hồi này?');">
                                    <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>">
                                    <button class="btn btn-sm btn-danger" name="customer_feedback_action" value="delete">Xóa</button>
                                 </form>
                              </div>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <tr><td colspan="6" class="text-center text-muted py-4">Chưa có phản hồi khách hàng.</td></tr>
                  <?php endif; ?>
               </tbody>
            </table>
         </div>

         <?php if ($total_pages > 1): ?>
            <nav class="mt-4">
               <ul class="pagination justify-content-center mb-0">
                  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                     <?php if ($i === $page): ?>
                        <li class="page-item active"><span class="page-link"><?php echo $i; ?></span></li>
                     <?php else: ?>
                        <li class="page-item"><a class="page-link" href="<?php echo htmlspecialchars(backendCustomerFeedbackPageUrl($i, $keyword), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $i; ?></a></li>
                     <?php endif; ?>
                  <?php endfor; ?>
               </ul>
            </nav>
         <?php endif; ?>

         <div class="mt-3 text-muted small">Tổng phản hồi: <?php echo number_format($total_feedback); ?></div>
      </div>
   </div>
</div>

<?php include_once "footer.php"; ?>

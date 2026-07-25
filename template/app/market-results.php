<?php include_once "header.php";
$market_results = is_array($market_results) ? $market_results : array();
$page = isset($market_results_page) ? (int)$market_results_page : 1;
$per_page = isset($market_results_per_page) ? (int)$market_results_per_page : 20;
$total_results = isset($market_results_total) ? (int)$market_results_total : count($market_results);
$total_pages = isset($market_results_total_pages) ? (int)$market_results_total_pages : 1;
$row_offset = max(0, ($page - 1) * $per_page);

if (!function_exists('backendMarketResultExcerpt')) {
	function backendMarketResultExcerpt($value, $limit = 70) {
		$value = trim((string)$value);
		if ($value === '') { return ''; }
		if (function_exists('mb_strlen') && function_exists('mb_substr')) {
			return mb_strlen($value, 'UTF-8') > $limit ? mb_substr($value, 0, $limit, 'UTF-8').'...' : $value;
		}
		return strlen($value) > $limit ? substr($value, 0, $limit).'...' : $value;
	}
}

if (!function_exists('backendMarketResultPageUrl')) {
	function backendMarketResultPageUrl($targetPage) {
		return XC_URL.'/admin/marketresults?page='.(int)$targetPage;
	}
}

if (!function_exists('backendMarketResultPaginationItems')) {
	function backendMarketResultPaginationItems($currentPage, $totalPages) {
		$currentPage = max(1, (int)$currentPage);
		$totalPages = max(1, (int)$totalPages);
		if ($totalPages <= 7) { return range(1, $totalPages); }
		if ($currentPage <= 4) { return array(1, 2, 3, 4, 5, 'ellipsis', $totalPages); }
		if ($currentPage >= $totalPages - 3) { return array(1, 'ellipsis', $totalPages - 4, $totalPages - 3, $totalPages - 2, $totalPages - 1, $totalPages); }
		return array(1, 'ellipsis', $currentPage - 1, $currentPage, $currentPage + 1, 'ellipsis', $totalPages);
	}
}
?>

<div class="content container-fluid">
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h3 class="page-title">Danh sách kết quả sàn việc làm</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item active">Kết quả sàn việc làm</li>
            </ul>
         </div>
         <div class="col-auto">
            <a href="<?php echo XC_URL; ?>/admin/marketresults/add" class="btn btn-primary">Thêm mới</a>
         </div>
      </div>
   </div>

   <?php if(!empty($market_result_flash)): ?>
      <div class="alert alert-<?php echo $market_result_flash['type'] == 'success' ? 'success' : 'info'; ?>"><?php echo htmlspecialchars($market_result_flash['message']); ?></div>
   <?php endif; ?>

   <div class="card card-table">
      <div class="card-body">
         <div class="table-responsive">
            <table class="table table-hover table-center mb-0">
               <thead>
                  <tr>
                     <th>STT</th>
                     <th>Tên đợt</th>
                     <th>Ngày</th>
                     <th>Thống kê</th>
                     <th>Trạng thái</th>
                     <th>Người tạo</th>
                     <th>Thao tác</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($market_results)): ?>
                     <?php $stt = $row_offset + 1; foreach($market_results as $item): ?>
                        <tr>
                           <td><?php echo $stt++; ?></td>
                           <td>
                              <div class="fw-semibold"><?php echo htmlspecialchars($item->result_title, ENT_QUOTES, 'UTF-8'); ?></div>
                              <small class="text-muted"><?php echo htmlspecialchars(backendMarketResultExcerpt($item->result_summary, 90), ENT_QUOTES, 'UTF-8'); ?></small>
                           </td>
                           <td><?php echo !empty($item->result_date) ? htmlspecialchars(date('d/m/Y', strtotime($item->result_date)), ENT_QUOTES, 'UTF-8') : ''; ?></td>
                           <td>
                              <small class="d-block">DN: <?php echo (int)$item->company_total; ?></small>
                              <small class="d-block">Vị trí: <?php echo (int)$item->position_total; ?></small>
                              <small class="d-block">Hồ sơ: <?php echo (int)$item->profile_total; ?></small>
                              <small class="d-block">PV: <?php echo (int)$item->interview_total; ?></small>
                           </td>
                           <td><span class="badge bg-<?php echo (int)$item->result_status === 1 ? 'success' : 'secondary'; ?>"><?php echo (int)$item->result_status === 1 ? 'Hiển thị' : 'Ẩn'; ?></span></td>
                           <td><?php echo htmlspecialchars($item->created_by_name ? $item->created_by_name : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                           <td>
                              <div class="d-flex align-items-center gap-2 flex-wrap">
                                 <a class="btn btn-sm btn-info text-white" href="<?php echo XC_URL; ?>/admin/marketresults/edit/<?php echo (int)$item->id; ?>">Sửa</a>
                                 <form method="post" class="d-inline">
                                    <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>">
                                    <button class="btn btn-sm btn-danger" name="market_result_action" value="delete" onclick="return confirm('Xóa kết quả sàn này?')">Xóa</button>
                                 </form>
                              </div>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <tr><td colspan="7" class="text-center text-muted py-4">Chưa có dữ liệu kết quả sàn.</td></tr>
                  <?php endif; ?>
               </tbody>
            </table>
         </div>

         <?php if ($total_pages > 1): ?>
            <div class="d-flex justify-content-center mt-4">
               <nav aria-label="Phân trang kết quả sàn">
                  <ul class="pagination mb-0">
                     <?php foreach (backendMarketResultPaginationItems($page, $total_pages) as $paginationItem): ?>
                        <?php if ($paginationItem === 'ellipsis'): ?>
                           <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php elseif ((int)$paginationItem === $page): ?>
                           <li class="page-item active"><span class="page-link"><?php echo (int)$paginationItem; ?></span></li>
                        <?php else: ?>
                           <li class="page-item"><a class="page-link" href="<?php echo htmlspecialchars(backendMarketResultPageUrl((int)$paginationItem), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int)$paginationItem; ?></a></li>
                        <?php endif; ?>
                     <?php endforeach; ?>
                  </ul>
               </nav>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>

<?php include_once "footer.php"; ?>

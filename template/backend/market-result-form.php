<?php include_once "header.php";
$market_result_edit = $market_result_edit ? $market_result_edit : (object) array(
	'id' => 0,
	'result_title' => '',
	'result_summary' => '',
	'result_content' => '',
	'result_image' => '',
	'result_date' => '',
	'company_total' => 0,
	'position_total' => 0,
	'profile_total' => 0,
	'interview_total' => 0,
	'implementation_content' => '',
	'highlight_content' => '',
	'note_content' => '',
	'result_status' => 1
);
$isEdit = (int)$market_result_edit->id > 0;
?>

<div class="content container-fluid">
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h3 class="page-title"><?php echo $isEdit ? 'Cập nhật kết quả sàn' : 'Thêm kết quả sàn'; ?></h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL; ?>/admin/marketresults">Kết quả sàn việc làm</a></li>
               <li class="breadcrumb-item active"><?php echo $isEdit ? 'Cập nhật' : 'Thêm mới'; ?></li>
            </ul>
         </div>
      </div>
   </div>

   <?php if(!empty($market_result_flash)): ?>
      <div class="alert alert-<?php echo $market_result_flash['type'] == 'success' ? 'success' : 'info'; ?>"><?php echo htmlspecialchars($market_result_flash['message']); ?></div>
   <?php endif; ?>

   <form method="post">
      <input type="hidden" name="id" value="<?php echo (int)$market_result_edit->id; ?>">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-md-8">
                  <div class="form-group">
                     <label>Tên đợt kết quả sàn</label>
                     <input type="text" class="form-control" name="result_title" value="<?php echo htmlspecialchars((string)$market_result_edit->result_title, ENT_QUOTES, 'UTF-8'); ?>" required>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Ngày tổ chức</label>
                     <input type="date" class="form-control" name="result_date" value="<?php echo !empty($market_result_edit->result_date) ? htmlspecialchars(date('Y-m-d', strtotime($market_result_edit->result_date)), ENT_QUOTES, 'UTF-8') : ''; ?>">
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Mô tả ngắn</label>
                     <textarea class="form-control" name="result_summary" rows="3"><?php echo htmlspecialchars((string)$market_result_edit->result_summary, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Hình đại diện</label>
                     <input type="text" class="form-control" name="result_image" value="<?php echo htmlspecialchars((string)$market_result_edit->result_image, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nhập URL ảnh hoặc tên file trong uploads/images">
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Số doanh nghiệp</label>
                     <input type="number" min="0" class="form-control" name="company_total" value="<?php echo (int)$market_result_edit->company_total; ?>">
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Số vị trí tuyển dụng</label>
                     <input type="number" min="0" class="form-control" name="position_total" value="<?php echo (int)$market_result_edit->position_total; ?>">
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Số hồ sơ ứng tuyển</label>
                     <input type="number" min="0" class="form-control" name="profile_total" value="<?php echo (int)$market_result_edit->profile_total; ?>">
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Số lượt phỏng vấn</label>
                     <input type="number" min="0" class="form-control" name="interview_total" value="<?php echo (int)$market_result_edit->interview_total; ?>">
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Nội dung chi tiết</label>
                     <textarea class="form-control" name="result_content" rows="10" placeholder="Có thể nhập HTML hoặc nội dung văn bản chi tiết"><?php echo htmlspecialchars((string)$market_result_edit->result_content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Nội dung triển khai</label>
                     <textarea class="form-control" name="implementation_content" rows="8" placeholder="Mỗi dòng là một ý"><?php echo htmlspecialchars((string)$market_result_edit->implementation_content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Kết quả nổi bật</label>
                     <textarea class="form-control" name="highlight_content" rows="8" placeholder="Mỗi dòng là một ý"><?php echo htmlspecialchars((string)$market_result_edit->highlight_content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
               </div>
               <div class="col-md-8">
                  <div class="form-group">
                     <label>Ghi chú</label>
                     <textarea class="form-control" name="note_content" rows="4"><?php echo htmlspecialchars((string)$market_result_edit->note_content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Trạng thái</label>
                     <select class="form-control" name="result_status">
                        <option value="1" <?php echo (int)$market_result_edit->result_status === 1 ? 'selected' : ''; ?>>Hiển thị</option>
                        <option value="0" <?php echo (int)$market_result_edit->result_status === 0 ? 'selected' : ''; ?>>Ẩn</option>
                     </select>
                  </div>
               </div>
            </div>
         </div>
         <div class="card-footer d-flex justify-content-between">
            <a href="<?php echo XC_URL; ?>/admin/marketresults" class="btn btn-light">Quay lại</a>
            <button type="submit" class="btn btn-primary" name="market_result_action" value="save"><?php echo $isEdit ? 'Lưu cập nhật' : 'Thêm mới'; ?></button>
         </div>
      </div>
   </form>
</div>

<?php include_once "footer.php"; ?>

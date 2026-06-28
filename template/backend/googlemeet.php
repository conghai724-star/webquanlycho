<?php require "header.php"; ?>
<?php
$googlemeet_items = is_array($googlemeet_items) ? $googlemeet_items : array();
$googlemeet_edit = $googlemeet_edit ? $googlemeet_edit : (object) array('id' => 0, 'title' => '', 'meet_url' => '', 'host_name' => '', 'meeting_date' => '', 'start_time' => '', 'end_time' => '', 'description' => '', 'meet_status' => 1, 'sort_order' => 0);
?>
<script>
$(document).ready(function () {
   var $table = $('#googlemeet-list-table');
   if ($.fn.DataTable && $table.length) {
      $table.DataTable({
         dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive border-bottom"rt><"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>><"clear">',
         pageLength: 10,
         order: [[1, 'desc']],
         columnDefs: [{ targets: [4], orderable: false, searchable: false }],
         language: {
            search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ phiên',
            info: 'Hiển thị _START_ - _END_ trong _TOTAL_ phiên',
            infoEmpty: 'Không có phiên Google Meet',
            zeroRecords: 'Không tìm thấy phiên phù hợp',
            paginate: { first: 'Đầu', last: 'Cuối', next: 'Sau', previous: 'Trước' }
         }
      });
   }
});
</script>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <?php if(!empty($googlemeet_flash)): ?>
      <div class="alert alert-<?php echo $googlemeet_flash['type'] == 'success' ? 'success' : 'info'; ?> rounded-3"><?php echo htmlspecialchars($googlemeet_flash['message']); ?></div>
   <?php endif; ?>

   <div class="row">
      <div class="col-xl-4 col-lg-5">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title"><?php echo (int)$googlemeet_edit->id > 0 ? 'Sửa phiên Google Meet' : 'Thêm phiên Google Meet'; ?></h4>
               </div>
            </div>
            <div class="card-body">
               <form method="post">
                  <input type="hidden" name="id" value="<?php echo (int)$googlemeet_edit->id; ?>">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <label class="form-label">Tiêu đề</label>
                        <input class="form-control" name="title" value="<?php echo htmlspecialchars($googlemeet_edit->title); ?>" required>
                     </div>
                     <div class="form-group col-md-12">
                        <label class="form-label">Link Google Meet</label>
                        <input class="form-control" name="meet_url" value="<?php echo htmlspecialchars($googlemeet_edit->meet_url); ?>" required>
                     </div>
                     <div class="form-group col-md-12">
                        <label class="form-label">Người chủ trì</label>
                        <input class="form-control" name="host_name" value="<?php echo htmlspecialchars($googlemeet_edit->host_name); ?>">
                     </div>
                     <div class="form-group col-md-6">
                        <label class="form-label">Ngày họp</label>
                        <input type="date" class="form-control" name="meeting_date" value="<?php echo htmlspecialchars($googlemeet_edit->meeting_date); ?>">
                     </div>
                     <div class="form-group col-md-3">
                        <label class="form-label">Bắt đầu</label>
                        <input class="form-control" name="start_time" value="<?php echo htmlspecialchars($googlemeet_edit->start_time); ?>">
                     </div>
                     <div class="form-group col-md-3">
                        <label class="form-label">Kết thúc</label>
                        <input class="form-control" name="end_time" value="<?php echo htmlspecialchars($googlemeet_edit->end_time); ?>">
                     </div>
                     <div class="form-group col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-control" name="meet_status">
                           <option value="1" <?php echo (int)$googlemeet_edit->meet_status === 1 ? 'selected' : ''; ?>>Hiển thị</option>
                           <option value="0" <?php echo (int)$googlemeet_edit->meet_status === 0 ? 'selected' : ''; ?>>Ẩn</option>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label class="form-label">Thứ tự</label>
                        <input type="number" class="form-control" name="sort_order" value="<?php echo (int)$googlemeet_edit->sort_order; ?>">
                     </div>
                     <div class="form-group col-md-12">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($googlemeet_edit->description); ?></textarea>
                     </div>
                  </div>
                  <div class="text-end">
                     <a href="<?php echo XC_URL; ?>/admin/googlemeet" class="btn btn-warning btn-sm">Làm mới</a>
                     <button type="submit" class="btn btn-primary btn-sm" name="googlemeet_action" value="save"><?php echo (int)$googlemeet_edit->id > 0 ? 'Lưu cập nhật' : 'Thêm mới'; ?></button>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <div class="col-xl-8 col-lg-7">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <div class="header-title">
                  <h4 class="card-title mb-0">Danh sách phiên Google Meet</h4>
                  <small class="text-muted">Quản lý các phiên sàn việc làm online, cập nhật nhanh thời gian và đường dẫn tham gia.</small>
               </div>
            </div>
            <div class="card-body px-0">
               <div class="table-responsive">
                  <table id="googlemeet-list-table" class="table table-bordered table-hover" role="grid">
                     <thead>
                        <tr class="ligth">
                           <th>Tiêu đề</th>
                           <th>Ngày họp</th>
                           <th>Chủ trì</th>
                           <th>Trạng thái</th>
                           <th style="min-width: 220px">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($googlemeet_items as $item): ?>
                           <tr>
                              <td>
                                 <div class="fw-semibold"><?php echo htmlspecialchars($item->title); ?></div>
                                 <small><a href="<?php echo htmlspecialchars($item->meet_url); ?>" target="_blank"><?php echo htmlspecialchars($item->meet_url); ?></a></small>
                              </td>
                              <td><?php echo htmlspecialchars($item->meeting_date); ?><br><small class="text-muted"><?php echo htmlspecialchars(trim($item->start_time.' - '.$item->end_time, ' -')); ?></small></td>
                              <td><?php echo htmlspecialchars($item->host_name); ?></td>
                              <td><span class="badge bg-<?php echo (int)$item->meet_status === 1 ? 'success' : 'secondary'; ?>"><?php echo (int)$item->meet_status === 1 ? 'Hiển thị' : 'Ẩn'; ?></span></td>
                              <td>
                                 <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-info text-white" href="<?php echo XC_URL; ?>/admin/googlemeet?edit=<?php echo (int)$item->id; ?>">Sửa</a>
                                    <form method="post" class="d-inline">
                                       <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>">
                                       <button class="btn btn-sm btn-danger" name="googlemeet_action" value="delete" onclick="return confirm('Xóa phiên Google Meet này?')">Xóa</button>
                                    </form>
                                 </div>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php require "footer.php"; ?>

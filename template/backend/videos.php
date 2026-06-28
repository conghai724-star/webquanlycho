<?php require "header.php"; ?>
<?php
$videos = is_array($videos) ? $videos : array();
$video_edit = $video_edit ? $video_edit : (object) array('id' => 0, 'title' => '', 'video_url' => '', 'thumbnail_url' => '', 'description' => '', 'video_status' => 1, 'sort_order' => 0);
?>
<script>
$(document).ready(function () {
   var $table = $('#video-list-table');
   if ($.fn.DataTable && $table.length) {
      $table.DataTable({
         dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive border-bottom"rt><"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>><"clear">',
         pageLength: 10,
         order: [[0, 'desc']],
         columnDefs: [{ targets: [4], orderable: false, searchable: false }],
         language: {
            search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ video',
            info: 'Hiển thị _START_ - _END_ trong _TOTAL_ video',
            infoEmpty: 'Không có video',
            zeroRecords: 'Không tìm thấy video phù hợp',
            paginate: { first: 'Đầu', last: 'Cuối', next: 'Sau', previous: 'Trước' }
         }
      });
   }
});
</script>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <?php if(!empty($video_flash)): ?>
      <div class="alert alert-<?php echo $video_flash['type'] == 'success' ? 'success' : 'info'; ?> rounded-3"><?php echo htmlspecialchars($video_flash['message']); ?></div>
   <?php endif; ?>

   <div class="row">
      <div class="col-xl-4 col-lg-5">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title"><?php echo (int)$video_edit->id > 0 ? 'Sửa video' : 'Thêm video'; ?></h4>
               </div>
            </div>
            <div class="card-body">
               <form method="post">
                  <input type="hidden" name="id" value="<?php echo (int)$video_edit->id; ?>">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <label class="form-label">Tiêu đề</label>
                        <input class="form-control" name="title" value="<?php echo htmlspecialchars($video_edit->title); ?>" required>
                     </div>
                     <div class="form-group col-md-12">
                        <label class="form-label">Link video</label>
                        <input class="form-control" name="video_url" value="<?php echo htmlspecialchars($video_edit->video_url); ?>" required>
                     </div>
                     <div class="form-group col-md-12">
                        <label class="form-label">Ảnh thumbnail</label>
                        <input class="form-control" name="thumbnail_url" value="<?php echo htmlspecialchars($video_edit->thumbnail_url); ?>">
                     </div>
                     <div class="form-group col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-control" name="video_status">
                           <option value="1" <?php echo (int)$video_edit->video_status === 1 ? 'selected' : ''; ?>>Hiển thị</option>
                           <option value="0" <?php echo (int)$video_edit->video_status === 0 ? 'selected' : ''; ?>>Ẩn</option>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label class="form-label">Thứ tự</label>
                        <input type="number" class="form-control" name="sort_order" value="<?php echo (int)$video_edit->sort_order; ?>">
                     </div>
                     <div class="form-group col-md-12">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($video_edit->description); ?></textarea>
                     </div>
                  </div>
                  <div class="text-end">
                     <a href="<?php echo XC_URL; ?>/admin/videos" class="btn btn-warning btn-sm">Làm mới</a>
                     <button type="submit" class="btn btn-primary btn-sm" name="video_action" value="save"><?php echo (int)$video_edit->id > 0 ? 'Lưu cập nhật' : 'Thêm mới'; ?></button>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <div class="col-xl-8 col-lg-7">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <div class="header-title">
                  <h4 class="card-title mb-0">Danh sách video</h4>
                  <small class="text-muted">Quản lý thư viện video với giao diện đồng bộ cùng danh sách tài khoản Admin.</small>
               </div>
            </div>
            <div class="card-body px-0">
               <div class="table-responsive">
                  <table id="video-list-table" class="table table-bordered table-hover" role="grid">
                     <thead>
                        <tr class="ligth">
                           <th>ID</th>
                           <th>Video</th>
                           <th>Thumbnail</th>
                           <th>Trạng thái</th>
                           <th style="min-width: 220px">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($videos as $item): ?>
                           <tr>
                              <td><?php echo (int)$item->id; ?></td>
                              <td>
                                 <div class="fw-semibold"><?php echo htmlspecialchars($item->title); ?></div>
                                 <small><a href="<?php echo htmlspecialchars($item->video_url); ?>" target="_blank"><?php echo htmlspecialchars($item->video_url); ?></a></small>
                                 <div class="text-muted mt-1"><?php echo nl2br(htmlspecialchars($item->description)); ?></div>
                              </td>
                              <td>
                                 <?php if(isset($item->thumbnail_url) && trim($item->thumbnail_url) !== ''): ?>
                                    <img src="<?php echo htmlspecialchars($item->thumbnail_url); ?>" alt="<?php echo htmlspecialchars($item->title); ?>" style="width:120px;height:70px;object-fit:cover;border-radius:8px;">
                                 <?php endif; ?>
                              </td>
                              <td><span class="badge bg-<?php echo (int)$item->video_status === 1 ? 'success' : 'secondary'; ?>"><?php echo (int)$item->video_status === 1 ? 'Hiển thị' : 'Ẩn'; ?></span></td>
                              <td>
                                 <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-info text-white" href="<?php echo XC_URL; ?>/admin/videos?edit=<?php echo (int)$item->id; ?>">Sửa</a>
                                    <form method="post" class="d-inline">
                                       <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>">
                                       <button class="btn btn-sm btn-danger" name="video_action" value="delete" onclick="return confirm('Xóa video này?')">Xóa</button>
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

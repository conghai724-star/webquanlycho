<?php require "header.php"; ?>
<?php $news_comments = is_array($news_comments) ? $news_comments : array(); ?>
<script>
$(document).ready(function () {
   var $table = $('#newscomment-list-table');
   if ($.fn.DataTable && $table.length) {
      $table.DataTable({
         dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive border-bottom"rt><"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>><"clear">',
         pageLength: 10,
         order: [[0, 'desc']],
         columnDefs: [{ targets: [5], orderable: false, searchable: false }],
         language: {
            search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ bình luận',
            info: 'Hiển thị _START_ - _END_ trong _TOTAL_ bình luận',
            infoEmpty: 'Không có bình luận',
            zeroRecords: 'Không tìm thấy bình luận phù hợp',
            paginate: { first: 'Đầu', last: 'Cuối', next: 'Sau', previous: 'Trước' }
         }
      });
   }
});
</script>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <?php if(!empty($newscomment_flash)): ?>
      <div class="alert alert-<?php echo $newscomment_flash['type'] == 'success' ? 'success' : 'info'; ?> rounded-3"><?php echo htmlspecialchars($newscomment_flash['message']); ?></div>
   <?php endif; ?>

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <div class="header-title">
                  <h4 class="card-title mb-0">Danh sách bình luận tin tức</h4>
                  <small class="text-muted">Theo dõi bình luận từ người dùng, phản hồi trực tiếp và dọn dẹp nội dung không phù hợp.</small>
               </div>
            </div>
            <div class="card-body px-0">
               <div class="table-responsive">
                  <table id="newscomment-list-table" class="table table-bordered table-hover" role="grid">
                     <thead>
                        <tr class="ligth">
                           <th>ID</th>
                           <th>Người bình luận</th>
                           <th>Bài viết</th>
                           <th>Nội dung</th>
                           <th>Phản hồi admin</th>
                           <th style="min-width: 260px">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($news_comments as $item): ?>
                           <tr>
                              <td><?php echo (int)$item->id; ?></td>
                              <td>
                                 <div class="fw-semibold"><?php echo htmlspecialchars($item->author_name !== '' ? $item->author_name : (isset($item->user_name) ? $item->user_name : 'Ẩn danh')); ?></div>
                                 <small class="text-muted"><?php echo htmlspecialchars(isset($item->author_email) ? $item->author_email : ''); ?></small>
                              </td>
                              <td><?php echo htmlspecialchars(isset($item->new_title) ? $item->new_title : 'Chưa gắn bài viết'); ?></td>
                              <td style="min-width: 280px"><?php echo nl2br(htmlspecialchars($item->comment_content)); ?></td>
                              <td style="min-width: 240px">
                                 <span class="text-muted"><?php echo nl2br(htmlspecialchars(isset($item->admin_reply) ? $item->admin_reply : '')); ?></span>
                              </td>
                              <td>
                                 <form method="post">
                                    <input type="hidden" name="comment_id" value="<?php echo (int)$item->id; ?>">
                                    <textarea class="form-control form-control-sm mb-2" name="admin_reply" rows="2" placeholder="Nhập phản hồi..."><?php echo htmlspecialchars(isset($item->admin_reply) ? $item->admin_reply : ''); ?></textarea>
                                    <div class="d-flex gap-2 flex-wrap">
                                       <button class="btn btn-sm btn-primary" name="newscomment_action" value="reply">Phản hồi</button>
                                       <button class="btn btn-sm btn-danger" name="newscomment_action" value="delete" onclick="return confirm('Xóa bình luận này?')">Xóa</button>
                                    </div>
                                 </form>
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

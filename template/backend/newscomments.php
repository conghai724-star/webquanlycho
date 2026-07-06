<?php require "header.php"; ?>
<?php
$news_comments = is_array($news_comments) ? $news_comments : array();
if(!function_exists('adminNewsCommentExcerpt')){
   function adminNewsCommentExcerpt($text, $limit = 80){
      $text = trim((string)$text);
      if($text === ''){ return ''; }
      if(function_exists('mb_strimwidth')){
         return mb_strimwidth($text, 0, $limit, '...');
      }
      return strlen($text) > $limit ? substr($text, 0, $limit - 3).'...' : $text;
   }
}
?>
<script>
$(document).ready(function () {
   var $table = $('#newscomment-list-table');
   if ($.fn.DataTable && $table.length) {
      $table.DataTable({
         dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive border-bottom"rt><"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>><"clear">',
         pageLength: 10,
         order: [[0, 'asc']],
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
                  <small class="text-muted">Quản lý bình luận người dùng, phản hồi qua modal và ẩn nội dung không phù hợp.</small>
               </div>
            </div>
            <div class="card-body px-0">
               <div class="table-responsive">
                  <table id="newscomment-list-table" class="table table-bordered table-hover align-middle" role="grid">
                     <thead>
                        <tr class="ligth">
                           <th style="width:70px;">STT</th>
                           <th style="min-width:180px;">Người bình luận</th>
                           <th style="min-width:220px;">Tên bài viết</th>
                           <th style="min-width:240px;">Nội dung</th>
                           <th style="min-width:220px;">Phản hồi</th>
                           <th style="min-width:220px;">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($news_comments as $index => $item): ?>
                           <?php
                           $detailModalId = 'commentDetailModal'.$index;
                           $replyModalId = 'commentReplyModal'.$index;
                           $commenterName = isset($item->commenter_name) ? $item->commenter_name : 'Ẩn danh';
                           $commenterEmail = isset($item->commenter_email) ? $item->commenter_email : '';
                           $commenterPhone = isset($item->commenter_phone) ? $item->commenter_phone : '';
                           $eventTitle = isset($item->event_title) ? $item->event_title : '';
                           $commentDate = !empty($item->created_at) ? date('d/m/Y H:i', strtotime($item->created_at)) : '';
                           $adminReply = isset($item->admin_reply) ? $item->admin_reply : '';
                           ?>
                           <tr>
                              <td><?php echo $index + 1; ?></td>
                              <td><?php echo htmlspecialchars($commenterName); ?></td>
                              <td><?php echo htmlspecialchars(adminNewsCommentExcerpt($eventTitle, 60)); ?></td>
                              <td><?php echo htmlspecialchars(adminNewsCommentExcerpt($item->comment_content, 90)); ?></td>
                              <td><?php echo htmlspecialchars(adminNewsCommentExcerpt($adminReply, 90)); ?></td>
                              <td>
                                 <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="modal" data-bs-target="#<?php echo $detailModalId; ?>">Xem chi tiết</button>
                                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#<?php echo $replyModalId; ?>">Phản hồi</button>
                                    <form method="post" onsubmit="return confirm('Ẩn bình luận này?');">
                                       <input type="hidden" name="comment_id" value="<?php echo (int)$item->id; ?>">
                                       <button class="btn btn-sm btn-danger" name="newscomment_action" value="delete" type="submit">Xóa</button>
                                    </form>
                                 </div>
                              </td>
                           </tr>

                           <div class="modal fade" id="<?php echo $detailModalId; ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-centered">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title">Chi tiết bình luận</h5>
                                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                       <div class="row g-3">
                                          <div class="col-md-6">
                                             <label class="form-label text-muted">Họ tên người bình luận</label>
                                             <div class="fw-semibold"><?php echo htmlspecialchars($commenterName); ?></div>
                                          </div>
                                          <div class="col-md-6">
                                             <label class="form-label text-muted">Email</label>
                                             <div><?php echo htmlspecialchars($commenterEmail); ?></div>
                                          </div>
                                          <div class="col-md-6">
                                             <label class="form-label text-muted">SĐT</label>
                                             <div><?php echo htmlspecialchars($commenterPhone); ?></div>
                                          </div>
                                          <div class="col-md-6">
                                             <label class="form-label text-muted">Ngày bình luận</label>
                                             <div><?php echo htmlspecialchars($commentDate); ?></div>
                                          </div>
                                          <div class="col-md-12">
                                             <label class="form-label text-muted">Tên bài viết</label>
                                             <div class="fw-semibold"><?php echo htmlspecialchars($eventTitle !== '' ? $eventTitle : 'Chưa gắn bài viết'); ?></div>
                                          </div>
                                          <div class="col-md-12">
                                             <label class="form-label text-muted">Nội dung bình luận</label>
                                             <div class="border rounded-3 p-3 bg-light"><?php echo nl2br(htmlspecialchars($item->comment_content)); ?></div>
                                          </div>
                                          <div class="col-md-12">
                                             <label class="form-label text-muted">Phản hồi admin</label>
                                             <div class="border rounded-3 p-3 bg-light"><?php echo $adminReply !== '' ? nl2br(htmlspecialchars($adminReply)) : '<span class="text-muted">Chưa có phản hồi</span>'; ?></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="modal fade" id="<?php echo $replyModalId; ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                 <div class="modal-content">
                                    <form method="post">
                                       <div class="modal-header">
                                          <h5 class="modal-title">Phản hồi bình luận</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                       </div>
                                       <div class="modal-body">
                                          <input type="hidden" name="comment_id" value="<?php echo (int)$item->id; ?>">
                                          <div class="mb-3">
                                             <label class="form-label text-muted">Người bình luận</label>
                                             <div class="fw-semibold"><?php echo htmlspecialchars($commenterName); ?></div>
                                          </div>
                                          <div class="mb-3">
                                             <label class="form-label text-muted">Nội dung</label>
                                             <div class="border rounded-3 p-3 bg-light"><?php echo nl2br(htmlspecialchars($item->comment_content)); ?></div>
                                          </div>
                                          <div>
                                             <label class="form-label">Nội dung phản hồi</label>
                                             <textarea class="form-control" name="admin_reply" rows="5" placeholder="Nhập phản hồi..."><?php echo htmlspecialchars($adminReply); ?></textarea>
                                          </div>
                                       </div>
                                       <div class="modal-footer">
                                          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                                          <button class="btn btn-primary" name="newscomment_action" value="reply" type="submit">Lưu phản hồi</button>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
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

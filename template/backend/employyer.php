<?php require "header.php"; ?>
<style>
   .employer-logo {
      align-items: center;
      background: #eef3f8;
      border-radius: 8px;
      display: flex;
      height: 48px;
      justify-content: center;
      overflow: hidden;
      width: 48px;
   }
   .employer-logo img {
      height: 100%;
      object-fit: cover;
      width: 100%;
   }
   .employer-logo span {
      color: #3a57e8;
      font-weight: 700;
      text-transform: uppercase;
   }
   .employer-company {
      min-width: 220px;
   }
   .employer-actions {
      min-width: 148px;
   }
</style>
<script>
jQuery(function($) {
   $('.btn-link-employer').on('click', function(e) {
      e.preventDefault();
      var $btn = $(this);
      var id = $btn.data('id');

      Swal.fire({
         icon: 'question',
         title: 'Xác nhận liên kết',
         text: 'Bạn muốn đánh dấu doanh nghiệp này là đã liên kết?',
         showCancelButton: true,
         confirmButtonText: 'Liên kết',
         cancelButtonText: 'Hủy'
      }).then(function(result) {
         if (!result.isConfirmed) return;

         $.ajax({
            type: 'POST',
            url: '<?php echo XC_URL; ?>/api/linkemployer',
            data: { id: id },
            dataType: 'json',
            success: function(data) {
               if (data.status == 200) {
                  Swal.fire({ icon: 'success', title: 'Liên kết thành công', timer: 1400, showConfirmButton: false });
                  setTimeout(function() { location.reload(); }, 1500);
               } else {
                  Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message || 'Không thể liên kết doanh nghiệp' });
               }
            },
            error: function() {
               Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra khi gọi API liên kết' });
            }
         });
      });
   });

   $('.btn-delete-employer').on('click', function(e) {
      e.preventDefault();
      var id = $(this).data('id');

      Swal.fire({
         icon: 'warning',
         title: 'Xác nhận xóa',
         text: 'Bạn chắc chắn muốn xóa doanh nghiệp này?',
         showCancelButton: true,
         confirmButtonText: 'Xóa',
         cancelButtonText: 'Hủy',
         confirmButtonColor: '#dc3545'
      }).then(function(result) {
         if (!result.isConfirmed) return;

         $.ajax({
            type: 'POST',
            url: '<?php echo XC_URL; ?>/api/deleteemployer',
            data: { id: id },
            dataType: 'json',
            success: function(data) {
               if (data.status == 200) {
                  Swal.fire({ icon: 'success', title: 'Xóa thành công', timer: 1400, showConfirmButton: false });
                  setTimeout(function() { location.reload(); }, 1500);
               } else {
                  Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message || 'Không thể xóa doanh nghiệp' });
               }
            },
            error: function() {
               Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra khi gọi API xóa' });
            }
         });
      });
   });
});
</script>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <div class="header-title">
                  <h4 class="card-title mb-0">Quản lý nhà tuyển dụng</h4>
               </div>
            </div>
            <div class="card-body">
               <form class="row g-3 align-items-end mb-4" method="get" action="<?php echo XC_URL; ?>/admin/employers">
                  <div class="col-lg-5 col-md-6">
                     <label class="form-label">Tìm kiếm</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control" name="keyword" value="<?php echo htmlspecialchars($keyword ?? ''); ?>" placeholder="Tên công ty, mã số thuế, người đại diện">
                     </div>
                  </div>
                  <div class="col-lg-3 col-md-4">
                     <label class="form-label">Trạng thái liên kết</label>
                     <select class="form-select" name="linked_status">
                        <option value="" <?php echo empty($linked_status) ? 'selected' : ''; ?>>Tất cả</option>
                        <option value="linked" <?php echo (isset($linked_status) && $linked_status === 'linked') ? 'selected' : ''; ?>>Đã liên kết</option>
                        <option value="unlinked" <?php echo (isset($linked_status) && $linked_status === 'unlinked') ? 'selected' : ''; ?>>Chưa liên kết</option>
                     </select>
                  </div>
                  <div class="col-lg-4 col-md-2 d-flex gap-2">
                     <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter me-1"></i> Lọc
                     </button>
                     <a href="<?php echo XC_URL; ?>/admin/employers" class="btn btn-light">
                        <i class="fa-solid fa-rotate-left me-1"></i> Xóa lọc
                     </a>
                  </div>
               </form>

               <div class="table-responsive">
                  <table class="table table-bordered table-hover align-middle" role="grid">
                     <thead>
                        <tr>
                           <th>Logo</th>
                           <th>Tên công ty</th>
                           <th>Mã số thuế</th>
                           <th>Người đại diện</th>
                           <th>Đã liên kết</th>
                           <th class="employer-actions">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if(isset($employers) && is_array($employers) && count($employers) > 0): ?>
                           <?php foreach($employers as $employer): ?>
                              <?php
                                 $company_name = $employer->company_name ?? '';
                                 $logo_url = $employer->logo_url ?? '';
                                 $logo_src = '';
                                 if($logo_url != '') {
                                    $logo_src = (strpos($logo_url, 'http') === 0) ? $logo_url : XC_URL . '/' . ltrim($logo_url, '/');
                                 }
                              ?>
                              <tr>
                                 <td>
                                    <div class="employer-logo">
                                       <?php if($logo_src != ''): ?>
                                          <img src="<?php echo htmlspecialchars($logo_src); ?>" alt="<?php echo htmlspecialchars($company_name); ?>">
                                       <?php else: ?>
                                          <span><?php echo htmlspecialchars(substr($company_name, 0, 1)); ?></span>
                                       <?php endif; ?>
                                    </div>
                                 </td>
                                 <td class="employer-company">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($company_name); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars($employer->website_url ?? ''); ?></small>
                                 </td>
                                 <td><?php echo htmlspecialchars($employer->tax_code ?? '-'); ?></td>
                                 <td>
                                    <div><?php echo htmlspecialchars($employer->representative_name ?: '-'); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($employer->representative_phone ?: ''); ?></small>
                                 </td>
                                 <td>
                                    <?php if((int)$employer->is_linked_school === 1): ?>
                                       <span class="badge bg-success">Đã liên kết</span>
                                    <?php else: ?>
                                       <span class="badge bg-secondary">Chưa liên kết</span>
                                    <?php endif; ?>
                                 </td>
                                 <td>
                                    <div class="d-flex align-items-center gap-1 list-user-action">
                                       <?php if((int)$employer->is_linked_school !== 1): ?>
                                          <a class="btn btn-sm btn-icon btn-success btn-link-employer" href="#" data-id="<?php echo $employer->id; ?>" title="Liên kết" data-bs-toggle="tooltip">
                                             <span class="btn-inner"><i class="fa-solid fa-link"></i></span>
                                          </a>
                                       <?php endif; ?>
                                       <button type="button"
                                               class="btn btn-sm btn-icon btn-info"
                                               title="Xem chi tiết"
                                               data-bs-toggle="modal"
                                               data-bs-target="#employerDetailModal<?php echo $employer->id; ?>">
                                          <span class="btn-inner"><i class="fa-solid fa-eye"></i></span>
                                       </button>
                                       <a class="btn btn-sm btn-icon btn-danger btn-delete-employer" href="#" data-id="<?php echo $employer->id; ?>" title="Xóa" data-bs-toggle="tooltip">
                                          <span class="btn-inner"><i class="fa-solid fa-trash"></i></span>
                                       </a>
                                    </div>

                                    <div class="modal fade" id="employerDetailModal<?php echo $employer->id; ?>" tabindex="-1" aria-hidden="true">
                                       <div class="modal-dialog modal-dialog-centered modal-lg">
                                          <div class="modal-content">
                                             <div class="modal-header">
                                                <h5 class="modal-title"><?php echo htmlspecialchars($company_name); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body">
                                                <div class="row g-3">
                                                   <div class="col-md-6">
                                                      <label class="form-label text-muted">Mã số thuế</label>
                                                      <div><?php echo htmlspecialchars($employer->tax_code ?? '-'); ?></div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <label class="form-label text-muted">Người đại diện</label>
                                                      <div><?php echo htmlspecialchars($employer->representative_name ?: '-'); ?></div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <label class="form-label text-muted">Email đại diện</label>
                                                      <div><?php echo htmlspecialchars($employer->representative_email ?: '-'); ?></div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <label class="form-label text-muted">Điện thoại đại diện</label>
                                                      <div><?php echo htmlspecialchars($employer->representative_phone ?: '-'); ?></div>
                                                   </div>
                                                   <div class="col-md-12">
                                                      <label class="form-label text-muted">Địa chỉ</label>
                                                      <div><?php echo htmlspecialchars($employer->address_detail ?: '-'); ?></div>
                                                   </div>
                                                   <div class="col-md-12">
                                                      <label class="form-label text-muted">Mô tả</label>
                                                      <div><?php echo nl2br(htmlspecialchars($employer->description ?: '-')); ?></div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        <?php else: ?>
                           <tr>
                              <td colspan="6" class="text-center py-4">Không có nhà tuyển dụng phù hợp.</td>
                           </tr>
                        <?php endif; ?>
                     </tbody>
                  </table>
               </div>
               <?php
                  $current_page = isset($page) ? (int)$page : 1;
                  $page_count = isset($total_pages) ? (int)$total_pages : 1;
                  $query_params = array();
                  if(isset($keyword) && $keyword !== '') {
                     $query_params['keyword'] = $keyword;
                  }
                  if(isset($linked_status) && $linked_status !== '') {
                     $query_params['linked_status'] = $linked_status;
                  }
                  $from_record = isset($total_employers) && $total_employers > 0 ? (($current_page - 1) * 20) + 1 : 0;
                  $to_record = isset($total_employers) ? min($current_page * 20, (int)$total_employers) : 0;
               ?>
               <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                  <div class="text-muted">
                     Hiển thị <?php echo $from_record; ?>-<?php echo $to_record; ?> / <?php echo (int)($total_employers ?? 0); ?> nhà tuyển dụng
                  </div>
                  <?php if($page_count > 1): ?>
                     <nav aria-label="Phân trang nhà tuyển dụng">
                        <ul class="pagination mb-0">
                           <?php
                              $prev_params = array_merge($query_params, array('page' => max(1, $current_page - 1)));
                              $next_params = array_merge($query_params, array('page' => min($page_count, $current_page + 1)));
                           ?>
                           <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                              <a class="page-link" href="<?php echo XC_URL; ?>/admin/employers?<?php echo http_build_query($prev_params); ?>">Trước</a>
                           </li>
                           <?php for($i = max(1, $current_page - 2); $i <= min($page_count, $current_page + 2); $i++): ?>
                              <?php $page_params = array_merge($query_params, array('page' => $i)); ?>
                              <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                 <a class="page-link" href="<?php echo XC_URL; ?>/admin/employers?<?php echo http_build_query($page_params); ?>"><?php echo $i; ?></a>
                              </li>
                           <?php endfor; ?>
                           <li class="page-item <?php echo ($current_page >= $page_count) ? 'disabled' : ''; ?>">
                              <a class="page-link" href="<?php echo XC_URL; ?>/admin/employers?<?php echo http_build_query($next_params); ?>">Sau</a>
                           </li>
                        </ul>
                     </nav>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php require "footer.php"; ?>

<?php require "header.php"; ?>
<?php
$candidates = is_array($candidates) ? $candidates : array();
$candidate_experiences = is_array($candidate_experiences) ? $candidate_experiences : array();
$candidate_certificates = is_array($candidate_certificates) ? $candidate_certificates : array();
$status_map = array(
    1 => array('label' => 'Chờ duyệt', 'class' => 'warning'),
    2 => array('label' => 'Từ chối', 'class' => 'danger'),
    3 => array('label' => 'Đã duyệt', 'class' => 'success'),
    99 => array('label' => 'Đã xóa', 'class' => 'secondary')
);
?>
<script>
$(document).ready(function () {
   var $table = $('#candidate-list-table');

   if ($.fn.DataTable && $table.length) {
      var candidateTable = $table.DataTable({
         dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive border-bottom"rt><"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>><"clear">',
         pageLength: 10,
         order: [[1, 'asc']],
         columnDefs: [
            { targets: [0, 6], orderable: false },
            { targets: 6, searchable: false }
         ],
         language: {
            search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ ứng viên',
            info: 'Hiển thị _START_ - _END_ trong _TOTAL_ ứng viên',
            infoEmpty: 'Không có ứng viên',
            infoFiltered: '(lọc từ _MAX_ ứng viên)',
            zeroRecords: 'Không tìm thấy ứng viên phù hợp',
            paginate: {
               first: 'Đầu',
               last: 'Cuối',
               next: 'Sau',
               previous: 'Trước'
            }
         },
         initComplete: function () {
            var api = this.api();

            api.columns([4, 5]).every(function () {
               var column = this;
               var $select = $('#candidate-list-table thead .candidate-filter-row th')
                  .eq(column.index())
                  .find('select');

               column.data().unique().sort().each(function (value) {
                  var text = $('<div>').html(value).text().trim();
                  if (text) {
                     $select.append($('<option>').val(text).text(text));
                  }
               });

               $select.on('change', function () {
                  var value = $.fn.dataTable.util.escapeRegex(this.value);
                  column.search(value ? '^' + value + '$' : '', true, false).draw();
               });
            });
         }
      });

      $('#candidate-list-table thead').on('keyup change', '.candidate-column-search', function () {
         candidateTable.column($(this).data('column')).search(this.value).draw();
      });

      candidateTable.on('order.dt search.dt draw.dt', function () {
         candidateTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, index) {
            cell.innerHTML = index + 1;
         });
      }).draw();
   }
});
</script>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <?php if(!empty($candidate_flash)): ?>
      <div class="alert alert-<?php echo $candidate_flash['type'] == 'success' ? 'success' : ($candidate_flash['type'] == 'warning' ? 'warning' : 'info'); ?> rounded-3"><?php echo htmlspecialchars($candidate_flash['message']); ?></div>
   <?php endif; ?>

   <?php if($candidate_detail): ?>
      <?php $detail_status = isset($status_map[(int)$candidate_detail->status]) ? $status_map[(int)$candidate_detail->status] : array('label' => 'Không xác định', 'class' => 'secondary'); ?>
      <div class="row mb-4">
         <div class="col-sm-12">
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="header-title">
                     <h4 class="card-title mb-1">Chi tiết ứng viên: <?php echo htmlspecialchars($candidate_detail->full_name); ?></h4>
                     <small class="text-muted">Theo dõi nhanh thông tin hồ sơ, kinh nghiệm và chứng chỉ của ứng viên.</small>
                  </div>
                  <div class="d-flex align-items-center gap-2">
                     <span class="badge bg-<?php echo $detail_status['class']; ?>"><?php echo $detail_status['label']; ?></span>
                     <a class="btn btn-light btn-sm" href="<?php echo XC_URL; ?>/admin/candidates">Đóng chi tiết</a>
                  </div>
               </div>
               <div class="card-body">
                  <div class="row g-4">
                     <div class="col-lg-6">
                        <div class="border rounded p-3 h-100">
                           <h6 class="mb-3">Thông tin liên hệ</h6>
                           <p class="mb-2"><strong>Email:</strong> <?php echo htmlspecialchars(isset($candidate_detail->user_email) ? $candidate_detail->user_email : ''); ?></p>
                           <p class="mb-2"><strong>Điện thoại:</strong> <?php echo htmlspecialchars(isset($candidate_detail->phone) && $candidate_detail->phone !== '' ? $candidate_detail->phone : (isset($candidate_detail->user_phone) ? $candidate_detail->user_phone : '')); ?></p>
                           <p class="mb-2"><strong>Chuyên ngành:</strong> <?php echo htmlspecialchars(isset($candidate_detail->job_category_name) ? $candidate_detail->job_category_name : ''); ?></p>
                           <p class="mb-2"><strong>Vị trí mong muốn:</strong> <?php echo htmlspecialchars(isset($candidate_detail->desired_position) ? $candidate_detail->desired_position : ''); ?></p>
                           <p class="mb-2"><strong>Khu vực mong muốn:</strong> <?php echo htmlspecialchars(isset($candidate_detail->province_name) ? $candidate_detail->province_name : ''); ?></p>
                           <p class="mb-0"><strong>Mức lương:</strong> <?php echo htmlspecialchars(isset($candidate_detail->salary_name) ? $candidate_detail->salary_name : ''); ?></p>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="border rounded p-3 h-100">
                           <h6 class="mb-3">Tóm tắt hồ sơ</h6>
                           <p class="mb-3"><strong>Mục tiêu nghề nghiệp</strong><br><span class="text-muted"><?php echo nl2br(htmlspecialchars(isset($candidate_detail->career_goal) ? $candidate_detail->career_goal : '')); ?></span></p>
                           <p class="mb-0"><strong>Kỹ năng</strong><br><span class="text-muted"><?php echo nl2br(htmlspecialchars(isset($candidate_detail->soft_skills) ? $candidate_detail->soft_skills : '')); ?></span></p>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="border rounded p-3 h-100">
                           <h6 class="mb-3">Kinh nghiệm</h6>
                           <?php if(count($candidate_experiences)): ?>
                              <ul class="mb-0 ps-3">
                                 <?php foreach($candidate_experiences as $item): ?>
                                    <li class="mb-2"><?php echo htmlspecialchars(isset($item->company_name) ? $item->company_name : ''); ?> - <?php echo htmlspecialchars(isset($item->job_title) ? $item->job_title : (isset($item->position) ? $item->position : '')); ?></li>
                                 <?php endforeach; ?>
                              </ul>
                           <?php else: ?>
                              <span class="text-muted">Chưa có dữ liệu kinh nghiệm.</span>
                           <?php endif; ?>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="border rounded p-3 h-100">
                           <h6 class="mb-3">Chứng chỉ</h6>
                           <?php if(count($candidate_certificates)): ?>
                              <ul class="mb-0 ps-3">
                                 <?php foreach($candidate_certificates as $item): ?>
                                    <li class="mb-2"><?php echo htmlspecialchars(isset($item->certificate_name) ? $item->certificate_name : (isset($item->cert_name) ? $item->cert_name : '')); ?></li>
                                 <?php endforeach; ?>
                              </ul>
                           <?php else: ?>
                              <span class="text-muted">Chưa có dữ liệu chứng chỉ.</span>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php endif; ?>

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <div class="header-title">
                  <h4 class="card-title mb-0">Danh sách ứng viên</h4>
                  <small class="text-muted">Quản lý hồ sơ ứng viên với các thao tác phê duyệt, từ chối, xem chi tiết và xóa.</small>
               </div>
            </div>
            <div class="card-body px-0">
               <div class="table-responsive">
                  <table id="candidate-list-table" class="table table-bordered table-hover" role="grid">
                     <thead>
                        <tr class="ligth">
                           <th>STT</th>
                           <th>Họ và tên</th>
                           <th>Liên hệ</th>
                           <th>Vị trí mong muốn</th>
                           <th>Chuyên ngành</th>
                           <th>Trạng thái</th>
                           <th style="min-width: 270px">Thao tác</th>
                        </tr>
                        <tr class="candidate-filter-row">
                           <th></th>
                           <th><input type="search" class="form-control form-control-sm candidate-column-search" data-column="1" placeholder="Lọc họ tên"></th>
                           <th><input type="search" class="form-control form-control-sm candidate-column-search" data-column="2" placeholder="Lọc liên hệ"></th>
                           <th><input type="search" class="form-control form-control-sm candidate-column-search" data-column="3" placeholder="Lọc vị trí"></th>
                           <th><select class="form-select form-select-sm"><option value="">Tất cả chuyên ngành</option></select></th>
                           <th><select class="form-select form-select-sm"><option value="">Tất cả trạng thái</option></select></th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $i = 1; foreach($candidates as $item): ?>
                           <?php $status = isset($status_map[(int)$item->status]) ? $status_map[(int)$item->status] : array('label' => 'Không xác định', 'class' => 'secondary'); ?>
                           <tr>
                              <td><?php echo $i; ?></td>
                              <td>
                                 <div class="fw-semibold"><?php echo htmlspecialchars($item->full_name); ?></div>
                                 <small class="text-muted"><?php echo htmlspecialchars(isset($item->province_name) ? $item->province_name : ''); ?></small>
                              </td>
                              <td>
                                 <?php echo htmlspecialchars(isset($item->user_email) ? $item->user_email : ''); ?><br>
                                 <small class="text-muted"><?php echo htmlspecialchars(isset($item->phone) && $item->phone !== '' ? $item->phone : (isset($item->user_phone) ? $item->user_phone : '')); ?></small>
                              </td>
                              <td><?php echo htmlspecialchars(isset($item->desired_position) ? $item->desired_position : ''); ?></td>
                              <td><?php echo htmlspecialchars(isset($item->job_category_name) ? $item->job_category_name : ''); ?></td>
                              <td><span class="badge bg-<?php echo $status['class']; ?>"><?php echo $status['label']; ?></span></td>
                              <td>
                                 <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-primary" href="<?php echo XC_URL; ?>/admin/candidates?detail=<?php echo (int)$item->id; ?>">Chi tiết</a>
                                    <form method="post" class="d-inline">
                                       <input type="hidden" name="candidate_id" value="<?php echo (int)$item->id; ?>">
                                       <button class="btn btn-sm btn-success" name="candidate_action" value="approve" onclick="return confirm('Phê duyệt ứng viên này?')">Phê duyệt</button>
                                    </form>
                                    <form method="post" class="d-inline">
                                       <input type="hidden" name="candidate_id" value="<?php echo (int)$item->id; ?>">
                                       <button class="btn btn-sm btn-warning text-dark" name="candidate_action" value="reject" onclick="return confirm('Từ chối ứng viên này?')">Từ chối</button>
                                    </form>
                                    <form method="post" class="d-inline">
                                       <input type="hidden" name="candidate_id" value="<?php echo (int)$item->id; ?>">
                                       <button class="btn btn-sm btn-danger" name="candidate_action" value="delete" onclick="return confirm('Xóa hồ sơ ứng viên này?')">Xóa</button>
                                    </form>
                                 </div>
                              </td>
                           </tr>
                        <?php $i++; endforeach; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php require "footer.php"; ?>

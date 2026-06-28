<?php require "header.php"; ?>
<script>
$(document).ready(function () {
   var $userTable = $('#user-list-table');

   if ($.fn.DataTable && $userTable.length) {
      var userTable = $userTable.DataTable({
         dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive border-bottom"rt><"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>><"clear">',
         pageLength: 10,
         order: [[1, 'asc']],
         orderCellsTop: true,
         columnDefs: [
            { targets: [0, 5], orderable: false },
            { targets: 5, searchable: false }
         ],
         language: {
            search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ tài khoản admin',
            info: 'Hiển thị _START_ - _END_ trong _TOTAL_ tài khoản admin',
            infoEmpty: 'Không có tài khoản admin',
            infoFiltered: '(lọc từ _MAX_ tài khoản)',
            zeroRecords: 'Không tìm thấy tài khoản phù hợp',
            paginate: {
               first: 'Đầu',
               last: 'Cuối',
               next: 'Sau',
               previous: 'Trước'
            }
         },
         initComplete: function () {
            var api = this.api();

            api.columns([3, 4]).every(function () {
               var column = this;
               var $select = $('#user-list-table thead .user-filter-row th')
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

      $('#user-list-table thead').on('keyup change', '.user-column-search', function () {
         userTable.column($(this).data('column')).search(this.value).draw();
      });

      userTable.on('order.dt search.dt draw.dt', function () {
         userTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, index) {
            cell.innerHTML = index + 1;
         });
      }).draw();
   }

   $(document).on('click', '.reset-password-btn', function () {
      var id = $(this).data('id');

      $.ajax({
         type: "POST",
         url: "<?php echo XC_URL; ?>/api/resetpassword",
         data: { id: id },
         dataType: "json",
         success: function (data) {
            if (Number(data.status) === 200) {
               Swal.fire({
                  icon: 'success',
                  title: 'Reset mật khẩu thành công',
                  html: 'Mật khẩu mới: <strong style="font-size:18px;color:#007bff;">' + data.new_password + '</strong>',
                  allowOutsideClick: false,
                  showConfirmButton: true
               });
            } else {
               Swal.fire({
                  icon: 'error',
                  title: 'Lỗi',
                  text: data.message || 'Không thể reset mật khẩu'
               });
            }
         },
         error: function () {
            Swal.fire({
               icon: 'error',
               title: 'Lỗi',
               text: 'Có lỗi xảy ra khi gọi API reset mật khẩu'
            });
         }
      });
   });

   $(document).on('click', '.btn-open-delete-user', function (e) {
      e.preventDefault();
      $('#delete_user_id').val($(this).data('user-id'));
      $('#delete_user_status').val($(this).data('user-status'));
   });

   $('#confirmDeleteUserBtn').on('click', function () {
      var id = $('#delete_user_id').val();
      var user_status = $('#delete_user_status').val();
      if (!id) return;

      var $btn = $(this);
      $btn.prop('disabled', true);

      $.ajax({
         type: "POST",
         url: "<?php echo XC_URL; ?>/api/deleteuser",
         data: {
            id: id,
            user_status: user_status
         },
         dataType: "json",
         success: function (data) {
            if (Number(data.status) === 200) {
               $('#deleteUserModal').modal('hide');
               Swal.fire({
                  icon: 'success',
                  title: 'Xóa thành công',
                  timer: 1700,
                  showConfirmButton: false
               });
               setTimeout(function () { location.reload(); }, 1800);
            } else {
               Swal.fire({
                  icon: 'error',
                  title: 'Lỗi',
                  text: data.message || 'Không thể xóa tài khoản'
               });
            }
         },
         error: function () {
            Swal.fire({
               icon: 'error',
               title: 'Lỗi',
               text: 'Có lỗi xảy ra khi gọi API xóa'
            });
         },
         complete: function () {
            $btn.prop('disabled', false);
         }
      });
   });
});
</script>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <div>
      <div class="row">
         <div class="col-sm-12">
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="header-title">
                     <h4 class="card-title mb-0">Danh sách tài khoản Admin</h4>
                     <small class="text-muted">Chỉ hiển thị các tài khoản có thể đăng nhập vào trang quản trị và được gắn nhóm quyền admin.</small>
                  </div>

                  <div>
                     <a href="<?php echo XC_URL;?>/admin/users/add" class="btn btn-success rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                           <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Thêm tài khoản Admin</span>
                     </a>
                  </div>
               </div>

               <div class="card-body px-0">
                  <div class="table-responsive">
                     <table id="user-list-table" class="table table-bordered table-hover" role="grid">
                        <thead>
                           <tr class="ligth">
                              <th>STT</th>
                              <th>Họ và tên</th>
                              <th>Email</th>
                              <th>Nhóm quyền</th>
                              <th>Trạng thái</th>
                              <th style="min-width: 220px">Thao tác</th>
                           </tr>
                           <tr class="user-filter-row">
                              <th></th>
                              <th>
                                 <input type="search" class="form-control form-control-sm user-column-search" data-column="1" placeholder="Lọc họ và tên">
                              </th>
                              <th>
                                 <input type="search" class="form-control form-control-sm user-column-search" data-column="2" placeholder="Lọc email">
                              </th>
                              <th>
                                 <select class="form-select form-select-sm">
                                    <option value="">Tất cả nhóm quyền</option>
                                 </select>
                              </th>
                              <th>
                                 <select class="form-select form-select-sm">
                                    <option value="">Tất cả trạng thái</option>
                                 </select>
                              </th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $i = 1; ?>
                           <?php foreach($users as $user): ?>
                           <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'); ?></td>
                              <td><?php echo htmlspecialchars($user->user_email, ENT_QUOTES, 'UTF-8'); ?></td>
                              <td><?php echo htmlspecialchars($user->group_name, ENT_QUOTES, 'UTF-8'); ?></td>
                              <td>
                                 <?php if((int)$user->user_status === 1){ ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                 <?php } elseif((int)$user->user_status === 2) { ?>
                                    <span class="badge bg-warning text-dark">Tạm khóa</span>
                                 <?php } else { ?>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($user->status_label, ENT_QUOTES, 'UTF-8'); ?></span>
                                 <?php } ?>
                              </td>
                              <td>
                                 <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-primary" href="<?php echo XC_URL; ?>/admin/users/role/<?php echo $user->uid; ?>">
                                       Phân quyền
                                    </a>

                                    <a class="btn btn-sm btn-info text-white" href="<?php echo XC_URL; ?>/admin/users/edit/<?php echo $user->uid; ?>">
                                       Sửa
                                    </a>

                                    <a class="btn btn-sm btn-secondary reset-password-btn"
                                       href="#"
                                       data-id="<?php echo $user->uid; ?>">
                                       Reset mật khẩu
                                    </a>

                                    <a class="btn btn-sm btn-danger btn-open-delete-user"
                                       data-bs-toggle="modal"
                                       data-bs-target="#deleteUserModal"
                                       data-user-id="<?php echo $user->uid; ?>"
                                       data-user-status="<?php echo isset($user->user_status) ? $user->user_status : ''; ?>"
                                       href="#">
                                       Xóa
                                    </a>
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
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            Bạn chắc chắn muốn xóa tài khoản admin này?
            <input type="hidden" id="delete_user_id" value="">
            <input type="hidden" id="delete_user_status" value="">
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteUserBtn">Xác nhận</button>
         </div>
      </div>
   </div>
</div>

<?php require "footer.php"; ?>

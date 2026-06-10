

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
            search: 'T\u00ecm ki\u1ebfm:',
            lengthMenu: 'Hi\u1ec3n th\u1ecb _MENU_ t\u00e0i kho\u1ea3n',
            info: 'Hi\u1ec3n th\u1ecb _START_ - _END_ trong _TOTAL_ t\u00e0i kho\u1ea3n',
            infoEmpty: 'Kh\u00f4ng c\u00f3 t\u00e0i kho\u1ea3n',
            infoFiltered: '(l\u1ecdc t\u1eeb _MAX_ t\u00e0i kho\u1ea3n)',
            zeroRecords: 'Kh\u00f4ng t\u00ecm th\u1ea5y t\u00e0i kho\u1ea3n ph\u00f9 h\u1ee3p',
            paginate: {
               first: '\u0110\u1ea7u',
               last: 'Cu\u1ed1i',
               next: 'Sau',
               previous: 'Tr\u01b0\u1edbc'
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

///-----------Users action-----------
   // Add user

   //END add user
   //Edit User

   //End Edit User
    // Reset mật khẩu
    $(document).on('click', '.reset-password-btn', function (e) {
      // e.preventDefault();
      var $btn = $(this);
      var id = $btn.data('id');
      
      $.ajax({
         type: "POST",
         url: "<?php echo XC_URL; ?>/api/resetpassword",
         data: {
            id: id
         },
         dataType: "json",
         success: function (data) {
            if (data.status == 200) {
               Swal.fire({
                  icon: 'success',
                  title: 'Reset mật khẩu thành công',
                  html: 'Mật khẩu của bạn là: <strong style="font-size: 18px; color: #007bff;">' + data.new_password + '</strong>',
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

    // xóa tài khoản
    $(document).on('click', '.btn-open-delete-user', function (e) {
      e.preventDefault();
      var userId = $(this).data('user-id');
      var userStatus = $(this).data('user-status');

      $('#delete_user_id').val(userId);
      $('#delete_user_status').val(userStatus);
   });

   $('#confirmDeleteUserBtn').on('click', function () {
      var id = $('#delete_user_id').val();
      var user_status = $('#delete_user_status').val();

      if (!id) return;

      var $btn = $(this);
      $btn.prop('disabled', true);

      $.ajax({
         type: "POST",
         url: XC_URL + "/api/deleteuser",
         data: {
            id: id,
            user_status: user_status
         },
         dataType: "json",
         success: function (data) {
            if (data.status == 200) {
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
///-----------END Users action-----------




}); 
  
</script>
      <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
          <div class="row">
              <div class="col-sm-12">
                <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">

                <div class="header-title">
                  <h4 class="card-title mb-0">
                      Quản lý tài khoản
                  </h4>
                </div>

              <div>
                <a href="<?php echo XC_URL;?>/admin/users/add" class="btn btn-success rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
                    
                    <svg width="20" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg">

                      <path d="M12 5V19" stroke="currentColor"
                          stroke-width="2" stroke-linecap="round"/>

                      <path d="M5 12H19" stroke="currentColor"
                          stroke-width="2" stroke-linecap="round"/>

                    </svg>

                    <span>Thêm tài khoản</span>

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
                           <th>Loại tài khoản</th>
                           <th>Trạng thái</th>
                           <th style="min-width: 100px">Thao tác</th>
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
                                 <option value="">Tất cả loại</option>
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
                          <td><?php echo $user->full_name; ?></td>
                          <td><?php echo $user->user_email; ?></td>
                          <td><?php echo $user->group_name; ?></td>
                          <td><?php echo $user->status_label; ?></td>
                           <td>
                              <div class="flex align-items-center list-user-action">
                              <a class="btn btn-sm btn-icon btn-secondary reset-password-btn" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="Reset mật khẩu" 
                                    href="#" data-id="<?php echo $user->uid; ?>">
                                      <span class="btn-inner">
                                        <i class="fa-solid fa-key"></i>
                                      </span>
                                  </a>
                              <a class="btn btn-sm btn-icon btn-info" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" disable 
                                    title="Detail" 
                                    href="<?php echo XC_URL;?>/admin/users/detail/<?php echo $user->uid; ?>">

                                      <span class="btn-inner">

                                          <svg class="icon-20" width="20" viewBox="0 0 24 24" 
                                              fill="none" xmlns="http://www.w3.org/2000/svg">

                                              <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" 
                                                  stroke="currentColor" 
                                                  stroke-width="1.5"/>

                                              <path d="M2 12C3.6 7.8 7.4 5 12 5C16.6 5 20.4 7.8 22 12C20.4 16.2 16.6 19 12 19C7.4 19 3.6 16.2 2 12Z" 
                                                  stroke="currentColor" 
                                                  stroke-width="1.5" 
                                                  stroke-linecap="round" 
                                                  stroke-linejoin="round"/>

                                          </svg>

                                      </span>

                                  </a>
                                 <a class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-original-title="Edit" href="<?php echo XC_URL;?>/admin/users/edit/<?php echo $user->uid; ?>">
                                    <span class="btn-inner">
                                       <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       </svg>
                                    </span>
                                 </a>
                                 <a class="btn btn-sm btn-icon btn-info"  data-bs-toggle="tooltip" data-bs-placement="top" title="Phân quyền" href="<?php echo XC_URL;?>/admin/users/role/<?php echo $user->uid; ?>">
                                    <span class="btn-inner">
                                       <i class="fa-solid fa-shield-halved" ></i>
                                    </span>
                                 </a>
                                 <a class="btn btn-sm btn-icon btn-danger btn-open-delete-user"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal"
                                    data-user-id="<?php echo $user->uid; ?>"
                                    data-user-status="<?php echo isset($user->user_status) ? $user->user_status : ''; ?>"
                                    data-bs-placement="top"
                                    title="Delete"
                                    href="#">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                          <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                  </a>
                              </div>
                           </td>
                        </tr>
                          <?php 
                          $i++;
                          endforeach;
                          ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
      </div>
      <div class="btn-download">
          <a class="btn btn-success px-3 py-2" href="https://iqonic.design/product/admin-templates/hope-ui-admin-free-open-source-bootstrap-admin-template/" target="_blank" >
              <svg class="icon-24"  width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path opacity="0.4" d="M17.554 7.29614C20.005 7.29614 22 9.35594 22 11.8876V16.9199C22 19.4453 20.01 21.5 17.564 21.5L6.448 21.5C3.996 21.5 2 19.4412 2 16.9096V11.8773C2 9.35181 3.991 7.29614 6.438 7.29614H7.378L17.554 7.29614Z" fill="currentColor"></path>
                  <path d="M12.5464 16.0374L15.4554 13.0695C15.7554 12.7627 15.7554 12.2691 15.4534 11.9634C15.1514 11.6587 14.6644 11.6597 14.3644 11.9654L12.7714 13.5905L12.7714 3.2821C12.7714 2.85042 12.4264 2.5 12.0004 2.5C11.5754 2.5 11.2314 2.85042 11.2314 3.2821L11.2314 13.5905L9.63742 11.9654C9.33742 11.6597 8.85043 11.6587 8.54843 11.9634C8.39743 12.1168 8.32142 12.3168 8.32142 12.518C8.32142 12.717 8.39743 12.9171 8.54643 13.0695L11.4554 16.0374C11.6004 16.1847 11.7964 16.268 12.0004 16.268C12.2054 16.268 12.4014 16.1847 12.5464 16.0374Z" fill="currentColor"></path>
              </svg>
          </a>
      </div>

      <!--Modal xóa tài khoản -->
      <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  Bạn chắc chắn muốn xóa tài khoản này?
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
      <!-- END Modal xóa tài khoản -->
     <?php require "footer.php"; ?>

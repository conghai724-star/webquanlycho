<?php require "header.php"; ?>
<script>
	jQuery(document).ready(function(){
		jQuery("#table-group").on('click', '.btn-delete-group', function(e) {
			var id = jQuery(this).attr("data-id");
			var group_status =  jQuery(this).attr("data-status");
			jQuery.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deletegroup",
				"data": {
					'id': id,
					'group_status': group_status
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Xoá thành công",
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ location.reload();     }, 2000);
					}else{
						Swal.fire({
						  icon: 'error',
						  title: "Lỗi",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			});
			return false;
		});
	});
</script>

      <div class="conatiner-fluid content-inner mt-n5 py-0">
      <div>
         <div class="row">

            <div class="col-xl-12 col-lg-12">
               <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title"><?php echo $pagetitle ?? 'Danh sách Nhóm Quyền';?></h4>
                     </div>
                     <div class="header-action">
                        <a href="<?php echo XC_URL; ?>/admin/groups/add" class="btn btn-primary btn-sm">Thêm nhóm quyền mới</a>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table id="table-group" class="table table-bordered table-hover" role="grid">
                           <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Tên nhóm quyền</th>
                                 <th>Class CSS</th>
                                 <th>Icon</th>
                                 <th>Quyền liên kết</th>
                                 <th>Thao tác</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if(isset($groups) && is_array($groups)): ?>
                              <?php foreach($groups as $group): ?>
                              <tr>
                                 <td><?php echo $group->id; ?></td>
                                 <td><?php echo $group->group_name; ?></td>
                                 <td><?php echo $group->group_class ?: '-'; ?></td>
                                 <td><?php echo $group->group_icon ?: '-'; ?></td>
                                 <td><?php echo $group->user_role_id ?: '-'; ?></td>
                                 <td>
                                    <button class="btn btn-sm btn-danger btn-delete-group"
                                            data-id="<?php echo $group->id; ?>"
                                            data-status="<?php echo $group->group_status; ?>">
                                       <i class="fa fa-trash"></i> Xóa
                                    </button>
                                 </td>
                              </tr>
                              <?php endforeach; ?>
                              <?php else: ?>
                              <tr>
                                 <td colspan="6" class="text-center">Không có nhóm quyền nào</td>
                              </tr>
                              <?php endif; ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
			   </div>
			</div>
		 </div>
      </div>
      <?php require "footer.php";?>
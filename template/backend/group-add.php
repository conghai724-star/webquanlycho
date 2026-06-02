<?php require "header.php"; ?>
<script>
	jQuery(document).ready(function(){
		jQuery('#frm-add-group').validate({
			rules: {
				group_name: {
					required: true,
					minlength: 3
				}
			},
			messages: {
				group_name: {
					required: 'Vui lòng nhập tên nhóm quyền',
					minlength: 'Tên nhóm quyền phải có ít nhất 3 ký tự'
				}
			},
			errorClass: 'is-invalid',
			success: function(label) {
				label.remove();
			}
		});

		jQuery('#btn-add-group').click(function(e){
			e.preventDefault();
			if(jQuery('#frm-add-group').valid()){
				var group_name = jQuery('#group_name').val();
				var group_class = jQuery('#group_class').val();
				var group_icon = jQuery('#group_icon').val();
				var user_role_id = jQuery('#user_role_id').val();

				jQuery.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/addGroup",
					data:{
						'group_name': group_name,
						'group_class': group_class,
						'group_icon': group_icon,
						'user_role_id': user_role_id
					},
					dataType: 'json',
					success: function(data){
						if(data.status == 200){
							Swal.fire({
							  icon: 'success',
							  title: data.message,
							  footer: '<a href=""></a>',
							  timer: 1700
							})
							setTimeout(function(){ location.href=data.url;     }, 2000);
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
			}
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
                        <h4 class="card-title"><?php echo $pagetitle ?? 'Thêm Nhóm Quyền Mới';?></h4>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="#" method="POST" id="frm-add-group">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="form-label" for="group_name">Tên nhóm quyền:</label>
                                    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Nhập tên nhóm quyền" required>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="form-label" for="group_class">Class CSS:</label>
                                    <input type="text" class="form-control" id="group_class" name="group_class" placeholder="Nhập class CSS (tùy chọn)">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="form-label" for="group_icon">Icon:</label>
                                    <input type="text" class="form-control" id="group_icon" name="group_icon" placeholder="Nhập icon (tùy chọn)">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="form-label" for="user_role_id">Quyền liên kết:</label>
                                    <select class="form-control" id="user_role_id" name="user_role_id">
                                       <option value="">Chọn quyền liên kết (tùy chọn)</option>
                                       <?php if(isset($user_roles) && is_array($user_roles)): ?>
                                       <?php foreach($user_roles as $role): ?>
                                       <option value="<?php echo $role->id; ?>"><?php echo $role->role_name; ?></option>
                                       <?php endforeach; ?>
                                       <?php endif; ?>
                                    </select>
                                 </div>
                              </div>
                           </div>

                           <div class="text-end mt-3">
                              <button type="button" id="btn-add-group" class="btn btn-primary btn-sm">Thêm nhóm quyền</button>
                              <a href="<?php echo XC_URL; ?>/admin/groups" class="btn btn-secondary btn-sm">Quay lại</a>
                           </div>
                        </form>
                     </div>
                  </div>
			   </div>
			</div>
		 </div>
      </div>
      <?php require "footer.php";?>
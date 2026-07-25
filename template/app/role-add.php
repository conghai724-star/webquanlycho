<?php require "header.php"; ?>
<script>
	jQuery(document).ready(function(){
		jQuery('#frm-add-role').validate({
			rules: {
				role_name: {
					required: true,
					minlength: 3
				},
				role_description: {
					required: true
				}
			},
			messages: {
				role_name: {
					required: 'Vui lòng nhập tên quyền',
					minlength: 'Tên quyền phải có ít nhất 3 ký tự'
				},
				role_description: {
					required: 'Vui lòng nhập mô tả quyền'
				}
			},
			errorClass: 'is-invalid',
			success: function(label) {
				label.remove();
			}
		});

		jQuery('#btn-add-role').click(function(e){
			e.preventDefault();
			if(jQuery('#frm-add-role').valid()){
				var role_name = jQuery('#role_name').val();
				var role_description = jQuery('#role_description').val();

				jQuery.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/addRole",
					data:{
						'role_name': role_name,
						'role_description': role_description
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
                        <h4 class="card-title"><?php echo $pagetitle ?? 'Thêm Quyền Mới';?></h4>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="#" method="POST" id="frm-add-role">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="form-label" for="role_name">Tên quyền:</label>
                                    <input type="text" class="form-control" id="role_name" name="role_name" placeholder="Nhập tên quyền" required>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="form-label" for="role_description">Mô tả quyền:</label>
                                    <textarea class="form-control" id="role_description" name="role_description" rows="3" placeholder="Nhập mô tả quyền" required></textarea>
                                 </div>
                              </div>
                           </div>

                           <div class="text-end mt-3">
                              <button type="button" id="btn-add-role" class="btn btn-primary btn-sm">Thêm quyền</button>
                              <a href="<?php echo XC_URL; ?>/admin/roles" class="btn btn-secondary btn-sm">Quay lại</a>
                           </div>
                        </form>
                     </div>
                  </div>
			   </div>
			</div>
		 </div>
      </div>
      <?php require "footer.php";?>
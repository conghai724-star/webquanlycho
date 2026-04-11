	<?php include "header.php";?>
	<link rel="stylesheet" href="<?php echo $template_path;?>/assets/js/plugins/datatables/dataTables.bootstrap4.css">
	<link rel="stylesheet" href="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css">
	<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/dataTables.buttons.min.js"></script>
	<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.print.min.js"></script>
	<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.html5.min.js"></script>
	<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.flash.min.js"></script>
	<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.colVis.min.js"></script>
<script>
    $(document).ready(function(){
        jQuery(".js-dataTable-full-pagination").dataTable({
			pagingType: "full_numbers",
			pageLength: 20,
			lengthMenu: [
				[5, 10, 20],
				[5, 10, 20]
			],
			autoWidth: !1
		});


	// 	$("#frm-action").validate({
	// 	onfocusout: false,
	// 	onkeyup: false,
	// 	onclick: false,
	// 	rules: {
	// 		"user_username": {
	// 			required: true,
	// 			alpha: true,
	// 			maxlength: 15
	// 		},
	// 		"user_phone": {
	// 			required: true,
	// 			alpha: true,
	// 			maxlength: 15,
	// 			minlength: 10
	// 		},
	// 		"user_fullname":{
	// 			required: true
	// 		},
	// 		"user_address":{
	// 			required: true
	// 		},
	// 		"user_phone":{
	// 			required: true
	// 		}	
	// 	},
	// 	messages:{
	// 			// user_national_id: {
	// 			// 	required: "Vui lòng số CMND",
	// 			// 	minlength: "số CMND phải vượt quá 5 ký tự",
	// 			// 	maxlength: "số CMND phải ngắn hơn 15 ký tự"
	// 			// },
	// 			user_phone: {
	// 				required: "Vui lòng nhập số điện thoại",
	// 				minlength: "Số điện thoại phải vượt quá 8 ký tự",
	// 				maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
	// 			},
	// 			user_username: "Vui lòng nhập tên tài khoản",
	// 			user_address: "Vui lòng nhập địa chỉ",
	// 			user_email: "Vui lòng nhập email"
				
	// 		}
	// });
		$(".btn-delete").on("click", function()
		{
			var button = $(this);
			Swal.fire({
			  title: 'Bạn có chắc chắn?',
			  text: "Thao tác này không thể khôi phục được!",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Đồng ý',
			  cancelButtonText: 'Hủy',
			}).then((result) => {
			  if (result.isConfirmed) {
				  var id = button.attr("data-id");
					$.ajax({
						type: "POST",
						url: "<?php echo XC_URL;?>/api/deleteuser",
						data: {id:id},
						dataType: "json",
						cache: false,
						success: function(data)
						{
							if(data.status != "200")
							{
								Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: data.message,
								  footer: '<a href>Xem thêm về lỗi này?</a>'
								})
							}
							else
							{
								Swal.fire({
								  icon: 'success',
								  title: 'Thành công',
								  text: 'Đã xóa thành công!',
								  timer: 1700
								})
								setTimeout(function(){ location.reload();     }, 2000);
											
							}
						}
					});
					
			  }
			})
			
		});
		// 
		$("#btn-save").on("click",function()
		{// var id = $("#uid").val();
     		var user_username = $("#user_username").val();
			var user_fullname = $("#user_fullname").val();
      		var user_phone = $("#user_phone").val();
			var user_email = $("#user_email").val();
			var user_password = 'Admin@2025'
			var user_address = $("#user_address").val();
			var user_group = $("#user_group").val();
			var user_dept = $("#user_dept").val();
      		console.log(user_username);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/adduser",
			  data:{
					'user_username': user_username,
					'user_fullname': user_fullname,
					'user_email': user_email,
					'user_password': user_password,
					'user_address': user_address,
					'user_group': user_group,
					'user_dept': user_dept
				},
				dataType: "json",
				cache: false,
				// processData: false,  
				// contentType: false, 
				enctype: 'multipart/form-data',
				success: function(data)
				{
          		console.log(data);
				if(data.status != "200")
						{
							Swal.fire({
								icon: 'error',
								title: 'Lỗi. Vui lòng liên hệ bộ phận CNTT.',
								text: data.message,
								footer: '<a href>Xem thêm về lỗi này?</a>'
							})
						}
						else
						{
							Swal.fire({
								icon: 'success',
								title: 'Thành công',
								text: 'Thêm thành công!',
								timer: 1700
							})
							setTimeout(function(){ location.reload();     }, 2000);
										
						}
				}
      
			});
			
		});
    
  
    })
    </script>
<main id="main-container">
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách tài khoản</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-add-user" data-toggle="modal" data-target=".bd-example-modal-lg">
                <i class="fa fa-fw fa-plus mr-1"></i> Thêm nhân viên
            </button>
        </div>
   </div>
</div>
<div class="content">
    <div class="block block-rounded">
        
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 8%;">Mã NV</th>
                        <th>Họ và tên</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Email</th>
                        <th>Tài khoản</th>
                        <th class="d-none d-sm-table-cell" style="width: 10%;">Loại</th>
                        <th>Phòng ban</th>
						<th>Số điện thoại</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$i = 1;
					foreach($users as $user)
					{
					?>
					<tr>
                        <td class="text-center"><?php echo $user->uid;?></td>
                        <td class="font-w600"><?php echo $user->user_fullname;?></td>
                        <td class="d-none d-sm-table-cell">
                            <?php echo $user->user_email;?>
                        </td>
                         <td class="d-none d-sm-table-cell">
                            <?php echo $user->user_username;?>
                        </td>
                        <td class="d-none d-sm-table-cell">
							<?php if($user->user_group == 1)
							{
							?>
								<span class="badge badge-<?php echo $user->group_class ?>"><?php echo $user->group_name ?></span>
							<?php
							}
							
							?>
                        </td>
						<td><?php echo $user->depart_name;?></td>
						<td><?php echo $user->user_phone;?></td>
                        <td>
                            <em class="text-muted"><?php echo $this->helper->time_ago($user->user_register_time);?></em>
                        </td>
						<td class="text-center">
                            <div class="btn-group">
                                <a href='<?php echo XC_URL;?>/admin/editusers/<?php echo $user->uid;?>'><button type="button" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit-user" data-id="<?php echo $user->uid;?>" data-toggle="tooltip" title="Chỉnh sửa" data-original-title="Edit">
                                    <i class="fa fa-pencil-alt" ></i>
                                </button></a>
                                <button type="button" class="btn btn-sm btn-primary js-tooltip-enabled btn-delete" data-toggle="tooltip" title="Delete" data-original-title="Delete" data-id="<?php echo $user->uid;?>" >
                                    <i class="fa fa-times"></i>
                                </button>
								<a href='<?php echo XC_URL;?>/admin/editusers/<?php echo $user->uid;?>'><button type="button" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit-user" data-id="<?php echo $user->uid;?>" data-toggle="tooltip" title="Chỉnh sửa" data-original-title="Edit">
                                  <i class="fa fa-key"></i>
                                </button></a>
                            </div>
                        </td>
                    </tr>
				<?php
												$i++;
												}
												?>
                                        
                                    </tbody>
            </table>
        </div>
    </div>

    <!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button> -->

<!-- Modal -->
<!-- Large modal -->


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="myLargeModalLabel">Thêm người dùng</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="formAddUser">
          <!-- Hàng 1 -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="input1">Họ và tên</label>
              <input type="text" class="form-control" id="user_fullname" placeholder="Họ và tên">
            </div>
            <div class="form-group col-md-6">
              <label for="input2">Email</label>
              <input type="email" class="form-control" id="user_email" placeholder="Email">
            </div>
          </div>

          <!-- Hàng 2 -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="input3">Số điện thoại</label>
              <input type="text" class="form-control" id="user_phone" placeholder="SĐT">
            </div>
            <div class="form-group col-md-6">
              <label for="input4">Địa chỉ</label>
              <input type="text" class="form-control" id="user_address" placeholder="Địa chỉ">
            </div>
          </div>

          <!-- Hàng 3 -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="input5">Tên đăng nhập</label>
              <input type="text" class="form-control" id="user_username" placeholder="Username">
            </div>
            

          <!-- Hàng 4 -->
            <div class="form-group col-md-3">
              <label for="input7">Khoa/phòng</label>
              <select class="form-control">
               <?php foreach ($departments as $departments){?>
               <option value = '<?php echo $departments->id;?>'><?php echo $departments->depart_name; ?></option>
               <?php } ?>
              </select>
              
            </div>
            <div class="form-group col-md-3">
              <label for="input7">Quyền</label>
              <select class="form-control">
               <?php foreach ($user_group as $user_group){?>
               <option value = '<?php echo $user_group->id;?>'><?php echo $user_group->group_name; ?></option>
               <?php } ?>
              </select>
            </div>
           
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <a href='' class="btn btn-primary" form="formAddUser" id='btn-save'>Thêm</a>
      </div>
      
    </div>
  </div>
</div>
<!--end adduser-->
    
</div>
    </main>
<?php include_once "footer.php";?>
<?php include_once "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"user_phone": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 8
			},
			"user_fullname":{
				required: true
			},
			"user_address":{
				required: true
			},
			"user_email":{
				required: true
			},
			"user_dept":{
				required: true
			},
            "user_username":{
				required: true
			},
			"user_group":{
				required: true
			}
		},
		messages:{
				user_phone: {
					required: "Vui lòng nhập số điện thoại",
					minlength: "Số điện thoại phải vượt quá 8 ký tự",
					maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
				},
				user_fulname: "Vui lòng nhập họ tên",
                user_username: "Vui lòng nhập tên tài khoản",
				user_address: "Vui lòng nhập địa chỉ",
				user_email: "Vui lòng nhập email",
				user_dept: "Vui lòng chọn phòng ban",
                user_group:"Vui lòng chọn quyền"
				
			}
	});
		$("#table-user").on('click', '.btn-delete-user', function(e) {
			var id = $(this).attr("data-id");
			var user_status =  $(this).attr("data-status");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deleteuser",
				"data": {
					'id': id,
					'user_status': user_status
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
						  title: "Lỗi",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			
			});
			return false;
		});
		
		
		$("#table-user").on('click', '.btn-duplicate-user', function(e) {
			var eid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/duplicateuser",
				"data": {
					'eid': eid
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Nhân bản thành công",
						  text: "Mã Khách hàng/NCC mới: " + data.new_user_code,
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
	
		$('#btn-save').click(function(e){
			if($("#frm-action").valid())
		{
				var user_username = $("#user_username").val();
                var user_fullname = $("#user_fullname").val();
                var user_phone = $("#user_phone").val();
                var user_email = $("#user_email").val();
                var user_password = 'Admin@2025'
                var user_address = $("#user_address").val();
                var user_group = $("#user_group").val();
                var user_dept = $("#user_dept").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/adduser",
				data:{
					'user_username': user_username,
					'user_fullname': user_fullname,
					'user_email': user_email,
					'user_phone': user_phone,
					'user_password': user_password,
					'user_address': user_address,
					'user_group': user_group,
					'user_dept': user_dept
					
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Thêm thành công",
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
			}
		});
	
		
	});
</script>
<style>
::placeholder{
	font-size:12px;
	font-style: italic;
}
.btn-search{
	background-color:white;
	border:none;
}
label.error{
	color:red;
}
</style>
<div class="content container-fluid">
   
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h3 class="page-title">Danh sách người dùng</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL?>">Trang chủ</a></li>
               <li class="breadcrumb-item active">Người dùng</li>
            </ul>
         </div>
         <div class="col-auto">
             <a href="#" data-bs-toggle="modal" id="btn-add-user" data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary me-1" >
            <i class="fas fa-plus"></i>Thêm người dùng
            </a>
            <!-- <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
            <i class="fas fa-filter"></i> -->
            </a>
         </div>
      </div>
   </div>
   
   </div>
   <div class="row">
      <div class="col-sm-12">
         <div class="card card-table">
            <div class="card-body">
               <div class="table-responsive">
                  <table id="table-user" class="table table-center table-hover datatable">
                     <thead class="thead-light">
                        <tr>
                           <th>STT</th>
						   <th>Tên tài khoản</th>
                           <th>Tên người dùng</th>
						   <th>Email</th>
						   <th>Số điện thoại</th>
						   <th>Khoa/phòng</th>
                           <th>Quyền</th>
                           <th class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							$i = 1;
							foreach($users as $users)
                           {
                           ?>
                        <tr>
                           <td>
                              <?php echo $i;?>
                           </td>
						   <td>
                              <?php echo $users->user_username;?>
                           </td>
						   <td>
                              <?php echo $users->user_fullname;?>
                           </td>
                           <td>
                              <?php echo $users->user_email;?>
                           </td>
                           <td><?php echo $users->user_phone;?></td>
						   <td><?php echo $users->depart_name;?></td>
                           <td><?php echo $users->group_name;?></td>
                           <td class="text-right">
                              <div class="btn-group">
								    <a href="editusers/<?php echo $users->uid;?>" class="btn btn-sm btn-white text-success btn-edit" >Sửa</a>
								   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									  <a class="dropdown-item" href="users/detail/<?php echo $users->uid;?>">Xem</a>
									  <!-- <a class="dropdown-item btn-duplicate-user" data-id="<?php echo $user->uid;?>" href="#">Nhân bản</a> -->
									  <a class="dropdown-item btn-delete-user" data-id="<?php echo $users->uid;?>" href="#" data-status="<?php echo $users->user_status;?>">Xoá</a>
									  
									
									  <!--<div class="dropdown-divider"></div>
									  <a class="dropdown-item" href="#">Tạo phiếu thu</a>
									  <a class="dropdown-item" href="#">Tạo phiếu chi</a>-->
								   </div>
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
         </div>
      </div>
   </div>
   <!-- ADD--->
                                        
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
                    <form id="frm-action" action="#">
                    <!-- Hàng 1 -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="input1">Họ và tên</label>
                        <input type="text" class="form-control" id="user_fullname" placeholder="Họ và tên" name='user_fullname'>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="input2">Email</label>
                        <input type="email" class="form-control" id="user_email" placeholder="Email" name='user_email'>
                        </div>
                    </div>

                    <!-- Hàng 2 -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="input3">Số điện thoại</label>
                        <input type="text" class="form-control" id="user_phone" placeholder="SĐT" name='user_phone'>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="input4">Địa chỉ</label>
                        <input type="text" class="form-control" id="user_address" placeholder="Địa chỉ" name='user_address'>
                        </div>
                    </div>

                    <!-- Hàng 3 -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="input5">Tên đăng nhập</label>
                        <input type="text" class="form-control" id="user_username" placeholder="Username" name='user_username'>
                        </div>
                        

                    <!-- Hàng 4 -->
                        <div class="form-group col-md-3">
                        <label for="input7">Khoa/phòng</label>
                        <select class="form-control" id='user_dept' name='user_dept'>
                        <?php foreach ($departments as $departments){?>
                        <option value = '<?php echo $departments->id;?>'><?php echo $departments->depart_name; ?></option>
                        <?php } ?>
                        </select>
                        
                        </div>
                        <div class="form-group col-md-3">
                        <label for="input7">Quyền</label>
                        <select class="form-control" id='user_group' name='user_group'>
                        <?php foreach ($user_group as $user_group){?>
                        <option value = '<?php echo $user_group->id;?>'><?php echo $user_group->group_name; ?></option>
                        <?php } ?>
                        </select>
                        </div>
                    
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <!-- <a href='' class="btn btn-primary" form="formAddUser" id='btn-save'>Thêm</a> -->
                     <button type="button" class="btn btn-primary" id = 'btn-save'>Thêm</button>
                </div>
                
                </div>
            </div>
            </div>
		<!-- end--->
         </div>
    </div>
</div>

</div>


<?php include_once "footer.php";?>
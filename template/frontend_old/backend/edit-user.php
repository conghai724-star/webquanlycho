<?php include_once "header.php"; ?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>

<script>
	$(document).ready(function(e){
		 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			
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
			"user_group":{
				required: true
			},
			"user_username":{
				required: true
			},
			"user_phone":{
				required: true
			}
					
		},
		messages:{
				// user_national_id: {
				// 	required: "Vui lòng số CMND",
				// 	minlength: "số CMND phải vượt quá 5 ký tự",
				// 	maxlength: "số CMND phải ngắn hơn 15 ký tự"
				// },
				user_phone: {
					required: "Vui lòng nhập số điện thoại",
					minlength: "Số điện thoại phải vượt quá 8 ký tự",
					maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
				},
				user_fullname: "Vui lòng nhập họ và tên",
				user_address: "Vui lòng nhập địa chỉ",
				user_email: "Vui lòng nhập email",
				user_dept: "Vui lòng chọn khoa phòng",
				user_group: "Vui lòng chọn quyền",
				user_username: "Vui lòng nhập tên tài khoản"

				
			}
	});
		$('#updateuser').click(function(e){
			if($("#frm-action").valid())
			{
				var userid = $('#userid').val();
				var user_fullname = $('#user_fullname').val();
				var user_address = $('#user_address').val();
				var user_phone = $('#user_phone').val();
				var user_email = $('#user_email').val();
				var user_dept = $('#user_dept').val();
				var user_group = $('#user_group').val();
				var user_username = $('#user_username').val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/updateUser",
				data:{
					'userid': userid,
					'user_fullname': user_fullname,
					'user_address': user_address,
					'user_email': user_email,
					'user_phone': user_phone,
					'user_dept': user_dept,
					'user_group': user_group,
					'user_username': user_username
					
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Cập nhật thành công",
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ window.location.href="<?php echo XC_URL;?>/admin/users";     }, 2000);	
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
	label.error{
		color:red;
	}
</style>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col-sm-12">
            <h3 class="page-title">Người dùng</h3>
            <!-- <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
               <li class="breadcrumb-item"><a href="customers.html">users</a></li>
               <li class="breadcrumb-item active">Add users</li>
            </ul> -->
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Thông tin người dùng</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" id="frm-action">
                  <div class="row">
                     <div class="col-md-12" data-select2-id="12">
                        <div class="row">
							<div class="col-md-6">
                              <div class="form-group">
                                 <label>Họ và tên:</label>
                                 <input type="text" value="<?php echo $user->user_fullname;?>" class="form-control" name='user_fullname' id='user_fullname'>
                              </div>
                           </div>
                           
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Email:</label>
                                 <input type="text" class="form-control" id='user_email' value="<?php echo $user->user_email;?>" name="user_email">
                              </div>
                           </div>
						  
                        </div>
                     </div>

                     <div class="col-md-12" data-select2-id="12">
                        <div class="row">
							<div class="col-md-6">
                              <div class="form-group">
                                 <label>SĐT:</label>
                                 <input type="text"  value="<?php echo $user->user_phone;?>" class="form-control" name='user_phone' id='user_phone'>
                              </div>
                           </div>
                           
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Địa chỉ:</label>
                                 <input type="text" class="form-control" id='user_address' value="<?php echo $user->user_address;?>" name="user_address">
                              </div>
                           </div>
						  
                        </div>
                     </div>

                     <div class="col-md-12" data-select2-id="12">
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                 <label>Tên tài khoản:</label>
                                 <input type="text" class="form-control" id='user_username' value="<?php echo $user->user_username;?>" name="user_username">
                              </div>
                           </div>

							<div class="col-md-3">
                              <div class="form-group">
                                <label>Khoa/phòng:</label>
                                <select class="select select2" id='user_dept' name="user_dept">
                                    <option></option>
                                    <?php foreach($departments as $departments){?>
                                        <option  value = '<?php echo $departments->id;?>' <?php echo ($user->user_dept == $departments->id) ? 'selected=selected' : '';?>><?php echo $departments->depart_name;?></option>
                                    <?php }?>
                                    
                                </select>
                                </div>
                           </div>  
                           
                           <div class="col-md-3">
                              <div class="form-group">
                                <label>Quyền:</label>
                                <select class="select select2" id='user_group' name="user_group">
                                    <option></option>
                                    <?php foreach($user_group as $user_group){?>
                                        <option  value = '<?php echo $user_group->id;?>' <?php echo ($user->user_group == $user_group->id) ? 'selected=selected' : '';?>><?php echo $user_group->group_name;?></option>
                                    <?php }?>
                                    
                                </select>
                                </div>
                           </div>   
                                    
                        </div>
                     </div>

                  </div>
                  <div class="text-end">
				  <!-- <input type = "hidden" value = "<?php echo $user->user_created_date;?>" id = 'user_created_date' /> -->
				  <!-- <input type = "hidden" value = "<?php echo $user->user_branch;?>" id = 'user_branch' /> -->
					<input type = "hidden" value = "<?php echo $user->uid;?>" id = 'userid' />
                     <button type="button" class="btn btn-primary" id = 'updateuser'>Cập Nhật</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   
   
   
   
</div>
      
<?php include_once "footer.php";?>
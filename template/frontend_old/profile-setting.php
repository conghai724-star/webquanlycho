

<?php include_once "header.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
			$(document).ready(function(e){
				$("#btn-save").click(function(e){
					var user_firstname = $("#user_firstname").val();
					var user_lastname = $("#user_lastname").val();
					var user_birthday = $("#user_birthday").val();
					var user_email = $("#user_email").val();
					var user_address_province = $("#user_address_province").val();
					var user_address = $("#user_address").val();
					var user_phone = $("#user_phone").val();
					var user_avatar_exits = $("#user_avatar_exits").val();
					var user_avatar = $("#user_avatar").val();
					if(!user_avatar){
						var user_avatar = user_avatar_exits;
					}else{
						var user_avatar = $("#user_avatar")[0].files[0].name;
					}
					$.ajax({ 
						type: "POST",
						url: "<?php echo XC_URL?>/apicuong/updateProfile",
						data:{
							"user_firstname" : user_firstname,
							"user_lastname" : user_lastname,
							"user_birthday" : user_birthday,
							"user_email" : user_email,
							"user_address" : user_address,
							"user_address_province": user_address_province,
							"user_avatar": user_avatar,
							"user_phone" : user_phone
						},
						dataType: 'json',
						success:function(data){
							if(data.status == 200){
								Swal.fire({
								  icon: 'success',
								  title: data.message,
								  footer: '<a href=""></a>',
								  timer: 1700
								})
								setTimeout(function(){ window.location.href='https://cestalk.kogi.dev/page/profilesetting';     }, 2000);
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
<div class="content">
   <div class="container">
      <div class="row">
         <div class="col-md-4 col-lg-4 col-xl-3 theiaStickySidebar">
            <?php include_once "user-sidebar.php";?>
         </div>
         <div class="col-md-8 col-lg-8 col-xl-9">
            <div class="card">
               <div class="card-body">
                  <form>
                     <div class="row form-row">
                        <div class="col-12 col-md-12">
                           <div class="form-group">
                              <div class="change-avatar">
							  <form method="POST" enctype="multipart/form-data">
                                 <div class="profile-img">
								 <?php if($user->user_avatar != ''){
									  echo "<img src=".$template_path."/assets/img/customers/".$user->user_avatar.">";
										
									}else{
										  echo "<img src=".$template_path."/assets/img/customers/default.png />";
									}  ?>
                                   
                                 </div>
								</form>
                                 <div class="upload-img">
                                    <div class="change-photo-btn">
                                       <span><i class="fa fa-upload"></i> Upload Photo</span>
                                       <input type="file" class="upload" id="user_avatar" name="user_avatar">
									   <input type="hidden" id="user_avatar_exits" value="<?php echo $user->user_avatar;?>"/>
									   
									   
                                    </div>
                                    <small class="form-text text-muted">Allowed JPG, GIF or PNG. Max size of 2MB</small>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-md-6">
                           <div class="form-group">
                              <label>First Name</label>
                              <input type="text" id="user_firstname" class="form-control" value="<?php echo $user->user_firstname;?>">
                           </div>
                        </div>
                        <div class="col-12 col-md-6">
                           <div class="form-group">
                              <label>Last Name</label>
                              <input type="text" id="user_lastname" class="form-control" value="<?php echo $user->user_lastname;?>">
                           </div>
                        </div>
                        <div class="col-12 col-md-6">
                           <div class="form-group">
                              <label>Date of Birth</label>
                              <div class="cal-icon">
                                 <input type="text" name="user_birthday" id="user_birthday" class="form-control datetimepicker" value="<?php if($user->user_birthday == "" ){
							echo "";
						}else{
							echo date("d/m/Y", strtotime($user->user_birthday));
						}?>">
                              </div>
                           </div>
                        </div>
                       
                        <div class="col-12 col-md-6">
                           <div class="form-group">
                              <label>Email ID</label>
                              <input type="email" class="form-control" value="<?php echo $user->user_email;?>" id="user_email">
                           </div>
                        </div>
                        
                        <div class="col-12">
                           <div class="form-group">
                              <label>Address</label>
                              <input type="text" class="form-control" value="<?php echo $user->user_address;?>" id="user_address">
                           </div>
                        </div>
                        <div class="col-12 col-md-6">
                           <div class="form-group">
                              <label>City</label>
								<select class="form-control" id="user_address_province">
								<?php foreach($provinces as $province){?>
									<option  value="<?php echo $province->id;?>" <?php echo ($province->id == $user->user_address_province) ? "selected" : "";?>> <?php echo $province->province_name;?>
									</option>
								<?php }?>
								</select>
                           </div>
                        </div>
                       <div class="col-12 col-md-6">
                           <div class="form-group">
                              <label>Mobile</label>
                              <input type="text" value="<?php echo $user->user_phone;?>" class="form-control" id="user_phone">
                           </div>
                        </div>
                       
                       
                     </div>
                     <div class="submit-section">
                        <button type="button" class="btn btn-primary submit-btn" id="btn-save">Save Changes</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include_once "footer.php"; ?>
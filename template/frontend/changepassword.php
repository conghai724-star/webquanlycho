<?php include "header.php";?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
			$(document).ready(function(e){
				$("#btn-submit").click(function(e){
					var oldpassword = $("#oldpassword").val();
					var newpassword = $("#newpassword").val();
					var comfirmpassword = $("#comfirmpassword").val();
					$.ajax({ 
						type: "POST",
						url: "<?php echo XC_URL?>/apicuong/updatePassword",
						data:{
							"oldpassword" : oldpassword,
							"newpassword" : newpassword,
							"comfirmpassword" : comfirmpassword,
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
								setTimeout(function(){ window.location.href='https://cestalk.kogi.dev/page/login';     }, 2000);
							}else{
								Swal.fire({
								  icon: 'error',
								  title: "Error",
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
            <div class="profile-sidebar">
               <div class="widget-profile pro-widget-content">
                  <div class="profile-info-widget">
                     <a href="#" class="booking-doc-img">
					 <?php if($user->user_avatar !== ''){
						 echo '<img src="'.$template_path.'/assets/img/customers/"'.$user->user_avatar.'" alt="User Image">';
					 }else{
						  echo '<img src="'.$template_path.'/assets/img/customers/default.png" alt="User Image">';
					 }?>
                    
                     </a>
                     <div class="profile-det-info">
                        <h3><?php echo $user->user_firstname." ".$user->user_lastname;?></h3>
                        <div class="customer-details">
                           <h5><i class="fas fa-birthday-cake"></i> <?php echo date("d-m-Y",strtotime($user->user_birthday));?></h5>
                           <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> <?php echo $user->user_address;?></h5>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="dashboard-widget">
                  <nav class="dashboard-menu">
                     <ul>
                        <li class="active">
                           <a href="#">
                           <i class="fas fa-columns"></i>
                           <span>Tổng quan</span>
                           </a>
                        </li>
                        <li>
                           <a href="favourites.html">
                           <i class="fas fa-bookmark"></i>
                           <span>Chương trình đã lưu</span>
                           </a>
                        </li>
                        <li>
                           <a href="chat.html">
                           <i class="fas fa-comments"></i>
                           <span>Tin nhắn</span>
                           <small class="unread-msg">23</small>
                           </a>
                        </li>
                        <li>
                           <a href="profile-settings.html">
                           <i class="fas fa-user-cog"></i>
                           <span>Cài đặt</span>
                           </a>
                        </li>
                        <li>
                           <a href="change-password.html">
                           <i class="fas fa-lock"></i>
                           <span>Đổi mật khẩu</span>
                           </a>
                        </li>
                        <li>
                           <a href="index.html">
                           <i class="fas fa-sign-out-alt"></i>
                           <span>Đăng xuất</span>
                           </a>
                        </li>
                     </ul>
                  </nav>
               </div>
            </div>
         </div>
            <div class="col-md-8 col-lg-8 col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">

                                <form id="formchangepassword">
                                    <div class="form-group">
                                        <label>Old Password</label>
                                        <input type="password" class="form-control" id="oldpassword" name="oldpassword">
                                    </div>
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password" class="form-control" id="newpassword" name="newpassword">
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" id="comfirmpassword" name="comfirmpassword">
                                    </div>
                                    <div class="submit-section">
                                        <button type="button" id="btn-submit"class="btn btn-primary submit-btn">Save Changes</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php";?>
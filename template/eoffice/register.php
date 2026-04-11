<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
      <title>Register</title>
	  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">


      <link rel="shortcut icon" href="<?php echo $template_path; ?>/assets/img/favicon.png">
      <link rel="stylesheet" href="<?php echo $template_path; ?>/assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="<?php echo $template_path; ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
      <link rel="stylesheet" href="<?php echo $template_path; ?>/assets/plugins/fontawesome/css/all.min.css">
      <link rel="stylesheet" href="<?php echo $template_path; ?>/assets/css/style.css">
      <!--[if lt IE 9]>
      <script src="<?php echo $template_path; ?>/assets/js/html5shiv.min.js"></script>
      <script src="<?php echo $template_path; ?>/assets/js/respond.min.js"></script>
      <![endif]-->
	   
	  <script src="<?php echo $template_path; ?>/assets/js/jquery-3.6.0.min.js"></script>
	  
	  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	 <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
	  <script>
			$(document).ready(function(){
				
				$("#frm-action").validate({
					onfocusout: false,
					onkeyup: false,
					onclick: false,
					rules: {
						"username": {
							required: true,
							maxlength: 50,
							minlength: 3
						},
						"fullname":{
							required: true,
							maxlength: 40,
							minlength: 5
						},
						"phone":{
							required: true,
							maxlength: 15,
							minlength: 8
						},
						"email":{
							required:true
						},
						"password":{
							required:true
						},
						"repassword":{
							required:true
						}
						
						
					},
					messages:{
							username: {
								required: "Vui lòng nhập tên đăng nhập",
								minlength: "Tên đăng nhập phải vượt quá 3 ký tự",
								maxlength: "Tên đăng nhập phải ngắn hơn 50 ký tự"
							},
							fullname: {
								required: "Vui lòng nhập họ và tên",
								minlength: "Họ và tên phải vượt quá 5 ký tự",
								maxlength: "Họ và tên phải ngắn hơn 40 ký tự"
							},
							phone: {
								required: "Vui lòng nhập số điện thoại",
								minlength: "Số điện thoại phải vượt quá 8 ký tự",
								maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
							},
							email: "Vui lòng nhập email",
							password: "Vui lòng nhập mật khẩu",
							repassword: "Vui lòng nhập xác nhận mật khẩu"
						}
				});
				 $("#repassword").keyup(function(){
						var password = $("#password").val();
						var confirmPassword = $("#repassword").val();
				   
					if(password.length < 8 ){
						$("#password").css('border','1px solid red');
						$("#result_validate").html('Mật khẩu phải lớn hơn 8 ký tự');
						
					}else {
						$("#password").css('border','1px solid #dee2e6');
						$("#result_validate").html('');
					}
					if (password != confirmPassword)
						$("#repassword").css('border','1px solid red');
					else
						$("#repassword").css('border','1px solid #dee2e6');
				   
				   });
				  
				$("#btregister").click(function(){
					
				if($("#frm-action").valid())
				{
					var username = $("#username").val();
					var fullname = $('#fullname').val();
					var phone = $("#phone").val();
					var email = $('#email').val();
					var password = $('#password').val();
					var repassword = $('#repassword').val();
					var checkverify = $('#checkverify:checked').val();
					
					console.log(checkverify);
					$.ajax({
						"type": "POST",
						"url": "<?php echo XC_URL; ?>/api/registers",
						"data": {
							'username': username,
							'fullname': fullname,
							'phone': phone,
							'email': email,
							'password': password,
							'repassword': repassword
						},
						"dataType":'json',
						success:function(data){
							if(data.status == 200){
								Swal.fire({
								  icon: 'success',
								  title: data.message,
								  footer: '<a href=""></a>',
								  timer: 1700
								})
								setTimeout(function(){ window.location.href="<?php echo XC_URL;?>/login";     }, 2000);
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
				}
				});
				
			});
	  </script>
	  <style>
		label.error{
			color:red;
		}
	  </style>
   </head>
   <body>
      <div class="main-wrapper login-body">
         <div class="login-wrapper">
            <div class="container">
               <img class="img-fluid logo-dark mb-2" src="<?php echo $template_path; ?>/assets/img/logo.png" alt="Logo">
               <div class="loginbox">
                  <div class="login-right">
                     <div class="login-right-wrap">
                        <h1 style="font-weight: bold">ĐĂNG KÝ</h1>
                        <p class="account-subtitle">Nền tảng quản trị doanh nghiệp SME</p>
                        <form action="#" method='POST' id="frm-action">
						 <div class="form-group" id="">
                              <label class="form-control-label">Tên đăng nhập</label>
                              <input type="text" class="form-control" id='username' name="username">
                           </div>
                           <div class="form-group">
                              <label class="form-control-label">Họ và tên</label>
                              <input type="text" class="form-control" id='fullname' name="fullname">
                           </div>
						   <div class="form-group">
                              <label class="form-control-label">Số điện thoại</label>
                              <input type="text" class="form-control" id='phone' name="phone">
                           </div>
						   <div class="form-group">
                              <label class="form-control-label">Email</label>
                              <input type="email" class="form-control" id='email' name="email">
                           </div>
                           <div class="form-group">
                              <label class="form-control-label">Mật khẩu</label>
                              <div class="pass-group">
                                 <input type="password" class="form-control pass-input" id="password" name="password">
                                 <span class="fas fa-eye toggle-password"></span>
								  <p id = "result_validate"></p>
                              </div>
                           </div>
						   <div class="form-group">
                              <label class="form-control-label">Nhập lại mật khẩu</label>
                              <div class="pass-group">
                                 <input type="password" class="form-control pass-input" id="repassword" name="repassword">
                                 <span class="fas fa-eye toggle-password"></span>
								  <p id = "result_validate"></p>
                              </div>
                           </div>
                           <div class="agree-check-box"><label class="apui-checkbox"><input type="checkbox" name="checkverify" id="checkverify" value="1"><span class="checkmark"></span><span class="caption"> Tôi đã hiểu và đồng ý với <a href="#" target="_blank">Thỏa thuận dịch vụ</a> do VCF Media cung cấp.</span></label></div>
                           <button class="btn btn-lg btn-block btn-primary w-100" type="button" id='btregister'>Đăng ký</button>
                           <div class="login-or" style="display: none;">
                              <span class="or-line"></span>
                              <span class="span-or">or</span>
                           </div>
                           <div class="social-login mb-3"  style="display: none;">
                              <span>Login with</span>
                              <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a><a href="#" class="google"><i class="fab fa-google"></i></a>
                           </div>
                           <div class="text-center dont-have"  style="display: none;">Don't have an account yet? <a href="register.html">Register</a></div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <script src="<?php echo $template_path; ?>/assets/js/popper.min.js"></script>
      <script src="<?php echo $template_path; ?>/assets/js/bootstrap.min.js"></script>
      <script src="<?php echo $template_path; ?>/assets/js/feather.min.js"></script>
      <script src="<?php echo $template_path; ?>/assets/js/script.js"></script>

   </body>
   <!-- Mirrored from kanakku.dreamguystech.com/html/template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 17 Aug 2021 09:22:14 GMT -->
</html>
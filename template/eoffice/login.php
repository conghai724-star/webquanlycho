<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
      <title>Login</title>
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
	  <script>
			$(document).ready(function(){
				$("#btlogin").click(function(){
					console.log('a');
					var email = $('#email').val();
					var password = $('#password').val();
					$.ajax({
						"type": "POST",
						"url": "<?php echo XC_URL; ?>/api/login",
						"data": {
							'email': email,
							'password': password
						},
						"dataType":'json',
						success:function(data){
							if(data.status == 200){
								window.location.href = data.return_url;
							}else{
								Swal.fire({
								  icon: 'error',
								  title: "Đăng nhập thất bại!",
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
	  
   </head>
   <body>
      <div class="main-wrapper login-body">
         <div class="login-wrapper">
            <div class="container">
               <img class="img-fluid logo-dark mb-2" src="<?php echo $template_path; ?>/assets/img/logo.png" alt="Logo">
               <div class="loginbox">
                  <div class="login-right">
                     <div class="login-right-wrap">
                        <h1 style="font-weight: bold">ĐĂNG NHẬP</h1>
                        <p class="account-subtitle">Nền tảng quản trị doanh nghiệp SME</p>
                        <form action="#" method='POST'>
                           <div class="form-group">
                              <label class="form-control-label">Email</label>
                              <input type="email" class="form-control" id='email'>
                           </div>
                           <div class="form-group">
                              <label class="form-control-label">Mật khẩu</label>
                              <div class="pass-group">
                                 <input type="password" class="form-control pass-input" id="password">
                                 <span class="fas fa-eye toggle-password"></span>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="row">
                                 <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                       <input type="checkbox" class="custom-control-input" id="cb1">
                                       <label class="custom-control-label" for="cb1">Lưu đăng nhập</label>
                                    </div>
                                 </div>
                                 <div class="col-6 text-end">
                                    <a class="forgot-link" href="forgot-password.html">Quên mật khẩu?</a>
                                 </div>
                              </div>
                           </div>
                           <button class="btn btn-lg btn-block btn-primary w-100" type="button" id = 'btlogin'>Đăng nhập</button>
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
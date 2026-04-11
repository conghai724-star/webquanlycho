<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
  
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
      <title><?php echo ($pagetitle)? $pagetitle." - " : "";?><?php echo $this->helper->get_config("website_name");?> - CloudERP</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
      <link rel="shortcut icon" href="<?php echo $template_path; ?>/backend/assets/img/favicon.png">

<link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/bootstrap.min.css">

<link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/plugins/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/plugins/fullcalendar/fullcalendar.min.css">
<link href="<?php echo $template_path;?>/backend/assets/plugins/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/plugins/datatables/datatables.min.css">

<link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/style.css">
      <!--[if lt IE 9]>
      <script src="<?php echo $template_path; ?>/backend/assets/js/html5shiv.min.js"></script>
      <script src="<?php echo $template_path; ?>/backend/assets/js/respond.min.js"></script>
      <![endif]-->
<!-- CSS -->

<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<script src="<?php echo $template_path; ?>/backend/assets/js/jquery-3.6.0.min.js"></script>

<script src="<?php echo $template_path; ?>/backend/assets/js/popper.min.js"></script>
<script src="<?php echo $template_path; ?>/backend/assets/js/bootstrap.min.js"></script>

<script src="<?php echo $template_path; ?>/backend/assets/js/feather.min.js"></script>

<script src="<?php echo $template_path; ?>/backend/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<script src="<?php echo $template_path; ?>/backend/assets/plugins/select2/js/select2.min.js"></script>

<script src="<?php echo $template_path; ?>/backend/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $template_path; ?>/backend/assets/plugins/datatables/datatables.min.js"></script>
<script src="<?php echo $template_path; ?>/backend/assets/js/jquery-ui.min.js"></script>
<script src="<?php echo $template_path; ?>/backend/assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo $template_path; ?>/backend/assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $template_path; ?>/backend/assets/js/number.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
 

   </head>
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
               <img class="img-fluid logo-dark mb-2" src="<?php echo $template_path; ?>/assets/images/logo.png" alt="Logo" style="
    width: 120px;
    height: 120px;">
               <div class="loginbox">
                  <div class="login-right">
                     <div class="login-right-wrap">
                        <h1 style="font-weight: bold">ĐĂNG NHẬP</h1>
                        <p class="account-subtitle">Quản trị phòng khám và nhà thuốc cao đẳng Kon Tum</p>
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
      
      <!-- <script src="<?php echo $template_path; ?>/assets/js/popper.min.js"></script>
      <script src="<?php echo $template_path; ?>/assets/js/bootstrap.min.js"></script>
      <script src="<?php echo $template_path; ?>/assets/js/feather.min.js"></script>
      <script src="<?php echo $template_path; ?>/assets/js/script.js"></script> -->
   </body>
   <?php include "footer.php";?>
<?php include_once "header.php"; ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-8 offset-md-2">
            <div class="account-content">
               <div class="row align-items-center justify-content-center">
                  <div class="col-md-7 col-lg-6 login-left">
                     <img src="<?php echo $template_path;?>/assets/img/global-1024x848.png" style="width: 75%" class="img-fluid" alt="CESTalk">
                  </div>
                  <div class="col-md-12 col-lg-6 login-right">
                     <div class="login-header">
                        <h3>Đăng ký tài khoản</h3>
                     </div>
                     <form action="#" id="register-form">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="form-group card-label">
									<label>Họ và tên đệm</label>
									<input class="form-control fw-bold" name="firstname" id="firstname" type="text">
								</div>
							</div>
							<div class="col-md-6 col-sm-12">
								<div class="form-group card-label">
									<label>Tên</label>
									<input class="form-control fw-bold" name="lastname" id="lastname" type="text">
								</div>
							</div>
						</div>
                        <div class="form-group card-label">
                           <label class="">Số điện thoại</label>
						   <input type="text" name="phone" id="phone" class="form-control floating fw-bold">
                        </div>
                        <div class="form-group card-label">
							<label class="">Mật khẩu</label>
                           <input type="password" name="password" id="password" class="form-control floating">
                        </div>
                        <div class="terms-and-policy pt-2 pb-2">
                           <span class="agree">Bằng việc đăng ký, CESTalk hiểu rằng bạn đã đọc và đồng ý với <span class="terms"><a href="#" target="_blank">Chính sách</a> và <a href="#" target="_blank">Điều khoản bảo mật</a></span> của CESTalk.</span>
                        </div>
                        <div class="text-end">
                           <a class="forgot-link" href="<?php echo XC_URL;?>/dang-nhap.html">Đã có tài khoản. Đăng nhập ngay</a>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg login-btn w-100" id="btn-register-post" type="submit">Đăng ký</button>
                        <div class="login-or">
                           <span class="or-line"></span>
                           <span class="span-or">hoặc</span>
                        </div>
                        <div class="row form-row social-login">
                           <div class="col-6">
                              <a href="#" class="btn btn-facebook btn-block w-100"><i class="fab fa-facebook-f me-1"></i> Đăng ký với Facebook</a>
                           </div>
                           <div class="col-6">
                              <a href="#" class="btn btn-google btn-block w-100"><i class="fab fa-google me-1"></i> Đăng ký với Google</a>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include_once "footer.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            jQuery.validator.addMethod("phoneVN", function(phone_number, element) {
                phone_number = phone_number.replace(/\s+/g, "");
                return this.optional(element) || (phone_number.length > 9 && phone_number.length < 11) &&
                    phone_number.match(/((09|03|07|08|05)+([0-9]{8})\b)/);
            }, "Please specify a valid phone number");
            jQuery.validator.addMethod(
                "regex",
                function(value, element, regexp) {
                    var check = false;
                    return this.optional(element) || regexp.test(value);
                },
                "Tên tài khoản không đúng định dạng."
            );
            jQuery("#register-form").validate({
				errorClass: "authError",
				errorElement: "span",
                rules: {
                    "firstname": {
                        required: true,
                        minlength: 8
                    },
					"lastname": {
                        required: true,
                        minlength: 2
                    },
                    "phone": {
                        required: !0,
                        phoneVN: true,
                        maxlength: 12
                    },
                    "password": {
                        required: !0,
                        minlength: 6
                    }
                },
                messages: {
                    "phone": {
                        required: "Vui lòng nhập số điện thoại",
                        phoneVN: "Số điện thoại không hợp lệ!",
                        maxlength: "Số điện thoại không hợp lệ!"
                    },
                    "firstname": {
                        required: "Vui lòng nhập họ và tên đệm",
                        minlength: "Họ và tên đệm không hợp lệ"
                    },
                    "lastname": {
                        required: "Vui lòng nhập tên",
                        minlength: "Vui lòng nhập tên thật."
                    },
                    "password": {
                        required: "Vui lòng nhập mật khẩu",
                        minlength: "Mật khẩu tối thiểu 6 ký tự!"
                    },
                }
            })
            $("#btn-register-post").click(function() {
                if ($("#register-form").valid()) {
                    var firstname = $('#firstname').val();
                    var lastname = $('#lastname').val();
                    var phone = $('#phone').val();
                    var password = $('#password').val();
                    $.ajax({
                        "type": "POST",
                        "url": "<?php echo XC_URL;?>/api/register",
                        "data": {
                            'firstname': firstname,
                            'lastname': lastname,
                            'phone': phone,
                            'password': password
                        },
                        "dataType": 'json',
                        success: function(data) {
                            if (data.status == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: "Chúc mừng!",
                                    text: data.message,
                                    footer: '<a href=""></a>',
                                    timer: 1700
                                })
                                setTimeout(function() {
                                    window.location.href = data.return_url;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: "Oh không!",
                                    text: data.message,
                                    footer: '<a href=""></a>'
                                })
                            }
                        }

                    });
                }
                return false;
            });
        });
</script>
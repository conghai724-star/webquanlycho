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
                        <h3>Đăng nhập</h3>
                     </div>
                     <form action="#" id="login-form">
                        <div class="form-group form-focus">
                           <input type="text" name="phone" id="phone" class="form-control floating fw-bold">
                           <label class="focus-label">Số điện thoại</label>
                        </div>
                        <div class="form-group form-focus">
                           <input type="password" name="password" id="password" class="form-control floating fw-bold">
                           <label class="focus-label">Mật khẩu</label>
                        </div>
                        <div class="text-end">
                           <a class="forgot-link" href="<?php echo XC_URL;?>/quen-mat-khau.html">Quên mật khẩu?</a>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg login-btn w-100" id="btn-login-post" type="submit">Đăng nhập</button>
                        <div class="login-or">
                           <span class="or-line"></span>
                           <span class="span-or">hoặc</span>
                        </div>
                        <div class="row form-row social-login">
                           <div class="col-6">
                              <a href="#" class="btn btn-facebook btn-block w-100"><i class="fab fa-facebook-f me-1"></i> Đăng nhập bằng Facebook</a>
                           </div>
                           <div class="col-6">
                              <a href="#" class="btn btn-google btn-block w-100"><i class="fab fa-google me-1"></i> Đăng nhập bằng Google</a>
                           </div>
                        </div>
                        <div class="text-center dont-have">Chưa có tài khoản? <a href="<?php echo XC_URL;?>/dang-ky.html">Đăng ký</a></div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div> 
</div>

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
            jQuery("#login-form").validate({
				errorClass: "authError",
				errorElement: "span",
                rules: {
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
                    "password": {
                        required: "Vui lòng nhập mật khẩu",
                        minlength: "Mật khẩu tối thiểu 6 ký tự!"
                    },
                }
            })
            $("#btn-login-post").click(function() {
                if ($("#login-form").valid()) {
                    var phone = $('#phone').val();
                    var password = $('#password').val();
                    $.ajax({
                        "type": "POST",
                        "url": "<?php echo XC_URL;?>/api/login",
                        "data": {
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
                                    title: "Ồ không!",
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

<?php include "footer.php"; ?>
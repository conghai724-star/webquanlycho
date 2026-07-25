<?php require "config.php";?>
<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Hope UI | Responsive Bootstrap 5 Admin Dashboard Template</title>
      
      <link rel="shortcut icon" href="<?php echo $admintemplate_path;?>/assets/images/favicon.ico">
      <link rel="stylesheet" href="<?php echo $admintemplate_path;?>/assets/css/core/libs.min.css">
      <link rel="stylesheet" href="<?php echo $admintemplate_path;?>/assets/css/hope-ui.min.css?v=4.0.0">
      <link rel="stylesheet" href="<?php echo $admintemplate_path;?>/assets/css/custom.min.css?v=4.0.0">
      <link rel="stylesheet" href="<?php echo $admintemplate_path;?>/assets/css/dark.min.css">
      <link rel="stylesheet" href="<?php echo $admintemplate_path;?>/assets/css/customizer.min.css">
      <link rel="stylesheet" href="<?php echo $admintemplate_path;?>/assets/css/rtl.min.css">
      <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
            $(document).ready(function(){
                var twoFactorTimer = null;
                var twoFactorExpiresAt = 0;

                function showProcessing(message){
                    Swal.fire({
                        title: 'Đang xử lý',
                        text: message || 'Vui lòng chờ trong giây lát...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: function(){
                            Swal.showLoading();
                        }
                    });
                }

                function showLoginError(message){
                    Swal.fire({
                        icon: 'error',
                        title: 'Đăng nhập thất bại!',
                        text: message || 'Không thể đăng nhập vào hệ thống.'
                    });
                }

                function clearTwoFactorTimer(){
                    if(twoFactorTimer){
                        window.clearInterval(twoFactorTimer);
                        twoFactorTimer = null;
                    }
                }

                function formatRemainingTime(seconds){
                    var safeSeconds = Math.max(0, parseInt(seconds || 0, 10));
                    var minutes = Math.floor(safeSeconds / 60);
                    var remainSeconds = safeSeconds % 60;
                    return minutes + ':' + String(remainSeconds).padStart(2, '0');
                }

                function updateTwoFactorCountdown(){
                    var timerEl = document.getElementById('twoFactorCountdown');
                    var noteEl = document.getElementById('twoFactorCountdownNote');
                    var resendWrap = document.getElementById('twoFactorResendWrap');
                    if(!timerEl || !noteEl || !resendWrap){
                        return;
                    }

                    var remaining = Math.max(0, Math.floor((twoFactorExpiresAt - Date.now()) / 1000));
                    if(remaining > 0){
                        var text = formatRemainingTime(remaining);
                        timerEl.textContent = text;
                        noteEl.innerHTML = 'Lấy lại mã xác thực sau <strong>' + text + '</strong>';
                        resendWrap.style.display = 'none';
                    }else{
                        timerEl.textContent = '0:00';
                        noteEl.textContent = 'Mã xác thực đã hết hạn.';
                        resendWrap.style.display = 'block';
                        clearTwoFactorTimer();
                    }
                }

                function startTwoFactorCountdown(expiresIn){
                    clearTwoFactorTimer();
                    twoFactorExpiresAt = Date.now() + (Math.max(0, parseInt(expiresIn || 0, 10)) * 1000);
                    updateTwoFactorCountdown();
                    twoFactorTimer = window.setInterval(updateTwoFactorCountdown, 1000);
                }

                function requestTwoFactorResend(){
                    return $.ajax({
                        type: 'POST',
                        url: "<?php echo XC_URL; ?>/api/admin_resend_2fa",
                        dataType: 'json'
                    });
                }

                function bindTwoFactorResendAction(){
                    var resendBtn = document.getElementById('twoFactorResendBtn');
                    if(!resendBtn){
                        return;
                    }

                    resendBtn.onclick = function(){
                        showProcessing('Đang gửi lại mã xác thực...');
                        requestTwoFactorResend().then(function(data){
                            Swal.close();
                            if(data.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Đã gửi lại mã',
                                    text: data.message,
                                    timer: 1200,
                                    showConfirmButton: false
                                }).then(function(){
                                    showTwoFactorPrompt(data.expires_in || 120);
                                });
                            }else{
                                showTwoFactorPrompt(0, data.message || 'Không thể gửi lại mã xác thực.');
                            }
                        }).catch(function(){
                            Swal.close();
                            showTwoFactorPrompt(0, 'Không thể gửi lại mã xác thực. Vui lòng thử lại.');
                        });
                    };
                }

                function showTwoFactorPrompt(expiresIn, validationMessage){
                    Swal.fire({
                        title: 'Xác thực 2 lớp',
                        text: 'Vui lòng nhập mã xác thực 5 chữ số đã được gửi về email của bạn.',
                        html: '<div style="margin-top:8px;text-align:center;color:#5b6777;font-size:14px;">'
                            + '<div style="margin-bottom:8px;">Mã xác thực còn hiệu lực trong <strong id="twoFactorCountdown">2:00</strong></div>'
                            + '<div id="twoFactorCountdownNote">Lấy lại mã xác thực sau <strong>2:00</strong></div>'
                            + '<div id="twoFactorResendWrap" style="display:none;margin-top:12px;">'
                            + '<button type="button" id="twoFactorResendBtn" class="swal2-confirm swal2-styled" style="margin:0;background:#0d4e96;">Lấy lại mã xác thực</button>'
                            + '</div>'
                            + '</div>',
                        input: 'text',
                        inputLabel: 'Mã xác thực',
                        inputPlaceholder: 'Nhập 5 chữ số',
                        inputAttributes: {
                            maxlength: '5',
                            autocomplete: 'one-time-code',
                            inputmode: 'numeric'
                        },
                        confirmButtonText: 'Xác nhận',
                        cancelButtonText: 'Hủy',
                        showCancelButton: true,
                        allowOutsideClick: false,
                        showLoaderOnConfirm: true,
                        didOpen: function(){
                            startTwoFactorCountdown(expiresIn || 120);
                            bindTwoFactorResendAction();
                            if(validationMessage){
                                Swal.showValidationMessage(validationMessage);
                            }
                        },
                        willClose: function(){
                            clearTwoFactorTimer();
                        },
                        preConfirm: function(value){
                            var code = String(value || '').trim();
                            if(!/^\d{5}$/.test(code)){
                                Swal.showValidationMessage('Vui lòng nhập đúng mã xác thực gồm 5 chữ số.');
                                return false;
                            }

                            return $.ajax({
                                type: 'POST',
                                url: "<?php echo XC_URL; ?>/api/admin_verify_2fa",
                                data: { code: code },
                                dataType: 'json'
                            }).then(function(data){
                                if(data.status == 200){
                                    return data;
                                }
                                if(data.code_expired){
                                    twoFactorExpiresAt = Date.now();
                                    updateTwoFactorCountdown();
                                }
                                Swal.showValidationMessage(data.message || 'Mã xác thực không hợp lệ.');
                                return false;
                            }).catch(function(){
                                Swal.showValidationMessage('Không thể gửi yêu cầu xác thực. Vui lòng thử lại.');
                                return false;
                            });
                        }
                    }).then(function(result){
                        if(result.isConfirmed && result.value && result.value.status == 200){
                            Swal.fire({
                                toast: true,
                                icon: 'success',
                                title: result.value.message,
                                showConfirmButton: false,
                                timer: 1200,
                                timerProgressBar: true
                            }).then(function(){
                                window.location.href = result.value.return_url;
                            });
                        }
                    });
                }

                $("#btlogin").click(function(){
                    var email = $('#email').val();
                    var password = $('#password').val();
                    showProcessing('Đang kiểm tra thông tin đăng nhập...');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo XC_URL; ?>/api/login",
                        data: {
                            email: email,
                            password: password
                        },
                        dataType: 'json',
                        success:function(data){
                            Swal.close();
                            if(data.status == 200){
                                Swal.fire({
                                    toast: true,
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1200,
                                    timerProgressBar: true,
                                    didOpen: function(toast){
                                        toast.addEventListener('mouseenter', Swal.stopTimer);
                                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                                    }
                                }).then(function(){
                                    window.location.href = data.return_url;
                                });
                            }else if(data.require_2fa){
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Xác thực 2 lớp',
                                    text: data.message,
                                    confirmButtonText: 'Nhập mã xác thực',
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if(result.isConfirmed){
                                        showTwoFactorPrompt(data.expires_in || 120);
                                    }
                                });
                            }else{
                                showLoginError(data.message);
                            }
                        },
                        error:function(){
                            Swal.close();
                            showLoginError('Không thể kết nối tới máy chủ. Vui lòng thử lại.');
                        }
                    });
                    return false;
                });
            });
      </script>
  </head>
  <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <div id="loading">
      <div class="loader simple-loader">
          <div class="loader-body"></div>
      </div>
    </div>
    
    <div class="wrapper">
      <section class="login-content">
         <div class="row m-0 align-items-center bg-white vh-100">            
            <div class="col-md-6">
               <div class="row justify-content-center">
                  <div class="col-md-10">
                     <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                        <div class="card-body">
                           <a href="../../dashboard/index.html" class="navbar-brand d-flex align-items-center mb-3">
                              <div class="logo-main">
                                  <div class="logo-normal">
                                      <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                                          <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                                          <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                                          <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                                      </svg>
                                  </div>
                                  <div class="logo-mini">
                                      <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                                          <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                                          <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                                          <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                                      </svg>
                                  </div>
                              </div>
                              <h4 class="logo-title ms-3">Chào mừng đến với trang quản trị</h4>
                           </a>
                           <h2 class="mb-2 text-center">Đăng nhập</h2>
                           <p class="text-center">Nhập email và mật khẩu để đăng nhập!</p>
                           <form>
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Email</label>
                                       <input type="email" class="form-control" id="email" aria-describedby="email" placeholder=" ">
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Password</label>
                                       <input type="password" class="form-control" id="password" aria-describedby="password" placeholder=" ">
                                    </div>
                                 </div>
                              </div>
                              <div class="d-flex justify-content-center">
                                 <button type="button" class="btn btn-primary" id='btlogin'>Đăng nhập</button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sign-bg">
                  <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <g opacity="0.05">
                     <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF"/>
                     <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF"/>
                     <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#3B8AFF"/>
                     <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF"/>
                     </g>
                  </svg>
               </div>
            </div>
            <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
               <img src="<?php echo $admintemplate_path;?>/assets/images/auth/01.png" class="img-fluid gradient-main animated-scaleX" alt="images">
            </div>
         </div>
      </section>
    </div>
    
    <script src="<?php echo $admintemplate_path;?>/assets/js/core/libs.min.js"></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/core/external.min.js"></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/charts/widgetcharts.js"></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/charts/vectore-chart.js"></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/charts/dashboard.js" ></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/plugins/fslightbox.js"></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/plugins/setting.js"></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/plugins/slider-tabs.js"></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/plugins/form-wizard.js"></script>
    <script src="<?php echo $admintemplate_path;?>/assets/js/hope-ui.js" defer></script>
  </body>
</html>

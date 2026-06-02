<?php require "header.php"; ?>
 <script>
$(document).ready(function() {
    // Giải mã HTML entities (ví dụ: &#272; → Đ)
        function decodeHtml(value) {
            return $('<textarea/>').html(value).text();
        }

        // Tạo một Promise delay (dùng để hiển thị loading tối thiểu X ms)
        function delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        // Gọi API thêm sinh viên với action: 'search' hoặc 'create'
        function callStudentApi(action, fullname, studentCode) {
            return $.ajax({
                type: 'POST',
                url: '<?php echo XC_URL;?>/api/addstudent',
                data: {
                    student_name: fullname,
                    student_code: studentCode,
                    action: action
                },
                dataType: 'json'
            });
        }

        // Hiển thị loading Swal (không có nút đóng, không đóng được)
        function showLoading(title, text) {
            Swal.fire({
                title,
                text,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
        }

        // Chạy API song song với delay tối thiểu, trả về kết quả API
        async function apiWithMinDelay(action, fullname, studentCode, minDelayMs = 1500) {
            const [result] = await Promise.all([
                callStudentApi(action, fullname, studentCode),
                delay(minDelayMs)
            ]);
            return result;
        }

        $('#studentRegisterBtn').on('click', async function () {
            const button = $(this);
            const fullname = $.trim($('#studentFullname').val());
            const studentCode = $.trim($('#studentCode').val());

            // Validate đầu vào
            if (!fullname || !studentCode) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thông tin không hợp lệ',
                    text: 'Vui lòng nhập đầy đủ họ tên và mã sinh viên hợp lệ để tiếp tục.'
                });
                return;
            }

            button.prop('disabled', true);

            try {
                // Bước 1: Tìm kiếm sinh viên
                showLoading('Đang tìm kiếm sinh viên...', 'Hệ thống đang kiểm tra thông tin trong cơ sở dữ liệu.');
                const searchResult = await apiWithMinDelay('search', fullname, studentCode);

                if (!searchResult?.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Không tìm thấy sinh viên',
                        text: searchResult?.message ?? 'Mã sinh viên hoặc tên sinh viên không đúng.'
                    });
                    return;
                }

                // Thông báo tìm thấy, chờ 1.5s rồi tiếp tục
                Swal.fire({
                    icon: 'success',
                    title: 'Đã tìm thấy sinh viên',
                    text: 'Hệ thống đã xác nhận thông tin sinh viên.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    timer: 1500
                });
                await delay(1500);

                // Bước 2: Tạo tài khoản
                showLoading('Đang khởi tạo tài khoản...', 'Vui lòng chờ trong giây lát.');
                const createResult = await apiWithMinDelay('create', fullname, studentCode);

                if (!createResult?.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể tạo tài khoản',
                        text: createResult?.message ?? 'Vui lòng thử lại sau.'
                    });
                    return;
                }
                // Thành công: hiển thị toast góc phải dưới, sau đó redirect
                Swal.fire({
                  toast: true,
                  position: 'bottom-end',
                  icon: 'success',
                  title: createResult.description ?? 'Tài khoản đã được tạo thành công.',
                  showConfirmButton: false,
                  timer: 2500,
                  timerProgressBar: true,
                  didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                  }
                });

                // Đồng thời có thể hiển thị modal chi tiết nếu cần (ở đây dùng redirect nhẹ sau toast)
                setTimeout(function(){ window.location.href = '<?php echo XC_URL;?>'; }, 2600);

            } catch (error) {
                const message = error?.responseJSON?.message ?? 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.';
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối',
                    text: message
                });
            } finally {
                button.prop('disabled', false);
            }
        });

        //create company
         $('#saveCompanyBtn').on('click', async function () {
          
            const button = $(this);
            const companyName = $.trim($('#newCompanyName').val());
            const companyTax = $.trim($('#newCompanyTax').val());

            // Validate đầu vào
            if (!companyName) {
                $('#newCompanyNameError').text('Vui lòng nhập tên công ty.').show();
                return;
            } else {
                $('#newCompanyNameError').hide();
            }
            if (!/^\d{10}$/.test(companyTax) && !/^\d{13}$/.test(companyTax)) {
                $('#newCompanyTaxError').show();
                return;
            } else {
                $('#newCompanyTaxError').hide();
            }
            $.ajax({
                type: 'POST',
                url: '<?php echo XC_URL;?>/api/insertcompany',
                data: {
                    company_name: companyName,
                    tax_code: companyTax
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        // Thêm công ty mới vào dropdown
                        // const newOption = $('<option>').val(response.data.id).text(response.data.company_name).attr('data-name', response.data.company_name);
                        // $('#companyName').append(newOption).val(response.data.id).trigger('change');
                        $('#closeCompanyModal').trigger('click');
                        Swal.fire({
                            icon: 'success',
                            title: 'Thêm công ty thành công',
                            text: 'Công ty đã được thêm và chọn tự động.'
                        }).then(() => { 
                            window.location.reload(); // Reload trang để cập nhật danh sách công ty mới nhất (cách đơn giản nhất để đồng bộ)
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Không thể thêm công ty',
                            text: response?.message ?? 'Vui lòng thử lại sau.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi kết nối',
                        text: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.'
                    });
                }
            });
            button.prop('disabled', true);

        });
        $('#registerEmployer').on('click', async function() {
              const button = $(this);
              const contactName = $.trim($('#employerContact').val());
              const email = $.trim($('#employerEmail').val());
              const companyId = $('#companyName').val();
              const phone = $.trim($('#employerPhone').val());
              const password = $('#employerPassword').val();
              const confirmPassword = $('#employerConfirm').val();

              button.prop('disabled', true);

              try {
                  // Bước 1: Hiển thị loading
                  showLoading('Đang xử lý đăng ký...', 'Vui lòng chờ trong giây lát.');

                  // Bước 2: Gửi request với minimum delay 1500ms
                  const [response] = await Promise.all([
                      $.ajax({
                          type: 'POST',
                          url: '<?php echo XC_URL;?>/api/registeremployer',
                          data: {
                              contact_name: contactName,
                              email: email,
                              company_id: companyId,
                              phone: phone,
                              password: password,
                              confirm_password: confirmPassword
                          },
                          dataType: 'json'
                      }),
                      delay(1500)
                  ]);

                  // Bước 3: Kiểm tra kết quả
                  if (response.status == 200) {
                      Swal.fire({
                          toast: true,
                          // position: 'bottom-end',
                          icon: 'success',
                          title: response.message,
                          showConfirmButton: false,
                          timer: 4000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                          }
                      });

                      setTimeout(function(){ window.location.href = '<?php echo XC_URL;?>'; }, 4000);
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Không thể đăng ký',
                          text: response?.message ?? 'Vui lòng thử lại sau.'
                      });
                  }
              } catch (error) {
                  const message = error?.responseJSON?.message ?? 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.';
                  Swal.fire({
                      icon: 'error',
                      title: 'Lỗi kết nối',
                      text: message
                  });
              } finally {
                  button.prop('disabled', false);
              }
      });

  /// register cannydate
   $('#candidateRegisterBtn').on('click', async function() {
              const button = $(this);
              const fullname = $.trim($('#candidateFullname').val());
              const email = $.trim($('#candidateEmail').val());
              const phone = $.trim($('#candidatePhone').val());
              const password = $('#candidatePassword').val();
              button.prop('disabled', true);

              try {
                  // Bước 1: Hiển thị loading
                  showLoading('Đang xử lý đăng ký...', 'Vui lòng chờ trong giây lát.');

                  // Bước 2: Gửi request với minimum delay 1500ms
                  const [response] = await Promise.all([
                      $.ajax({
                          type: 'POST',
                          url: '<?php echo XC_URL;?>/api/registercandidate',
                          data: {
                              fullname: fullname,
                              email: email,
                              phone: phone,
                              password: password
                          },
                          dataType: 'json'
                      }),
                      delay(1500)
                  ]);

                  // Bước 3: Kiểm tra kết quả
                  if (response.status == 200) {
                      Swal.fire({
                          toast: true,
                          icon: 'success',
                          title: response.message,
                          showConfirmButton: false,
                          timer: 4000,
                          timerProgressBar: true, 
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                          }
                      });

                      setTimeout(function(){ window.location.href = '<?php echo XC_URL;?>'; }, 4000);
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Không thể đăng ký',
                          text: response?.message ?? 'Vui lòng thử lại sau.'
                      });
                  }
              } catch (error) {
                  const message = error?.responseJSON?.message ?? 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.';
                  Swal.fire({
                      icon: 'error',
                      title: 'Lỗi kết nối',
                      text: message
                  });
              } finally {
                  button.prop('disabled', false);
              }

});
});
</script>

<main class="register-page">
  

  <section class="register-main">
    <div class="register-shell">
      <aside class="register-side" aria-label="Chọn đối tượng đăng ký">
        <h2 class="register-side-title">Chọn đối tượng</h2>
        <p class="register-side-desc">Hệ thống sẽ validate riêng từng nhóm và chỉ bật nút khi thông tin bắt buộc đã hợp lệ.</p>
        <div class="register-tabs" role="tablist">
          <button type="button" class="register-tab active" data-target="candidate" role="tab" aria-selected="true"><i class="ti ti-user"></i><span><b>Ứng viên</b><span>Dành cho người tìm việc và ứng tuyển.</span></span></button>
          <button type="button" class="register-tab" data-target="student" role="tab" aria-selected="false"><i class="ti ti-school"></i><span><b>Sinh viên</b><span>Tìm bằng mã sinh viên và tạo tài khoản.</span></span></button>
          <button type="button" class="register-tab" data-target="employer" role="tab" aria-selected="false"><i class="ti ti-building-skyscraper"></i><span><b>Nhà tuyển dụng</b><span>Dành cho doanh nghiệp đăng tin.</span></span></button>
        </div>
        <div class="register-note"><strong>Lưu ý:</strong> Mật khẩu tối thiểu 8 ký tự. Email, số điện thoại, mã số thuế và xác nhận mật khẩu được kiểm tra trực tiếp khi nhập.</div>
      </aside>

      <div class="register-card">
        <div class="register-card-head">
          <h2 class="register-form-title" id="registerTitle"><i class="ti ti-user-plus"></i><span>Đăng ký tài khoản ứng viên</span></h2>
          <p class="register-form-sub" id="registerSubtitle">Nhập đầy đủ thông tin để mở nút đăng ký tài khoản.</p>
        </div>

        <form class="register-form active" data-form="candidate" action="#" method="post" novalidate>
          <div class="register-grid">
            <div class="register-field full" data-field="fullname"><label for="candidateFullname">Họ và tên</label><div class="register-input"><i class="ti ti-user"></i><input id="candidateFullname" name="fullname" type="text" autocomplete="name" placeholder="Nhập họ và tên" data-required data-label="Họ và tên"></div><div class="register-error"></div></div>
            <div class="register-field"><label for="candidateEmail">Email</label><div class="register-input"><i class="ti ti-mail"></i><input id="candidateEmail" name="email" type="email" autocomplete="email" placeholder="vidu@email.com" data-required data-email data-label="Email"></div><div class="register-error"></div></div>
            <div class="register-field"><label for="candidatePhone">Số điện thoại</label><div class="register-input"><i class="ti ti-phone"></i><input id="candidatePhone" name="phone" type="tel" autocomplete="tel" placeholder="09xxxxxxxx" data-required data-phone data-label="Số điện thoại"></div><div class="register-error"></div></div>
            <div class="register-field"><label for="candidatePassword">Mật khẩu</label><div class="register-input" style="position:relative;"><i class="ti ti-lock"></i><input id="candidatePassword" name="password" type="password" autocomplete="new-password" placeholder="Tối thiểu 8 ký tự" style="padding-right:44px;" data-required data-password data-label="Mật khẩu"><i class="ti ti-eye toggle-password" data-target="candidatePassword" title="Xem mật khẩu" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; z-index:2; width:24px; height:24px; display:flex; align-items:center; justify-content:center;"></i></div><div class="register-error"></div></div>
            <div class="register-field"><label for="candidateConfirm">Xác nhận mật khẩu</label><div class="register-input" style="position:relative;"><i class="ti ti-lock-check"></i><input id="candidateConfirm" name="confirm_password" type="password" autocomplete="new-password" placeholder="Nhập lại mật khẩu" style="padding-right:44px;" data-required data-match="candidatePassword" data-label= "Xác nhận mật khẩu"><i class="ti ti-eye toggle-password" data-target="candidateConfirm" title="Xem mật khẩu" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; z-index:2; width:24px; height:24px; display:flex; align-items:center; justify-content:center;"></i></div><div class="register-error"></div></div>
          </div>
          <div class="terms-row"><input id="candidateTerms" type="checkbox" data-required data-label="Điều khoản dịch vụ"><label for="candidateTerms">Tôi đồng ý với <a href="#">điều khoản dịch vụ</a> và chính sách bảo mật của cổng thông tin việc làm.</label></div>
          <button type="button" class="register-submit" id = "candidateRegisterBtn" disabled><i class="ti ti-user-plus"></i> Đăng ký tài khoản</button>
          <div class="form-status">Vui lòng nhập đầy đủ thông tin bắt buộc để kích hoạt nút đăng ký.</div>
        </form>

        <form class="register-form" data-form="student" action="#" method="post" novalidate>
          <div class="register-grid">
            <div class="register-field"  data-field="fullname"><label for="studentFullname">Họ và tên</label>
             <div class="register-input"><i class="ti ti-user"></i>
                <input id="studentFullname" name="fullname" type="text" autocomplete="name" placeholder="Nhập họ và tên sinh viên" data-required data-label="Họ và tên"></div><div class="register-error"></div></div>
            <div class="register-field" data-field="studentid" >
              <label for="studentCode">Mã sinh viên</label>
              <div class="register-input"><i class="ti ti-id-badge-2"></i>
              <input id="studentCode" name="student_code" type="text" placeholder="Nhập mã sinh viên" data-required data-student-code data-label="Mã sinh viên"></div><div class="register-error"></div></div>
              <input type="hidden" id='method' value="student">
            </div>
          <button type="button" class="register-submit" disabled id="studentRegisterBtn"><i class="ti ti-search"></i> Tìm kiếm và tạo tài khoản</button>
          <div class="form-status">Nhập họ tên và mã sinh viên hợp lệ để tìm kiếm và tạo tài khoản.</div>
        </form>

        <form class="register-form" data-form="employer" action="#" method="post" novalidate>
          <div class="register-grid">
            <div class="register-field"><label for="employerContact">Tên người liên hệ <strong style="color:#e74c3c;">*</strong></label><div class="register-input"><i class="ti ti-user"></i><input id="employerContact" name="contact_name" type="text" autocomplete="name" placeholder="Nhập tên người liên hệ" data-required data-label="Tên người liên hệ"></div><div class="register-error"></div></div>
            <div class="register-field"><label for="employerEmail">Email <strong style="color:#e74c3c;">*</strong></label>
                <div class="register-input">
                    <i class="ti ti-mail"></i>
                    <input id="employerEmail" name="email" type="email" autocomplete="email" placeholder="doanhnghiep@email.com" data-required data-email data-label="Email"></div><div class="register-error"></div></div>
           
            <div class="register-field" id="companyField">
              <label for="companyName">Tên công ty <strong style="color:#e74c3c;">*</strong></label>
              <div style="display:flex;gap:8px;align-items:stretch;">
                <div class="register-input company-select-wrap" id="companySelectWrap">
                  <i class="ti ti-building"></i>
                    <select id="companyName" name="company_name" data-required data-label="Tên công ty">
                      <option value="">Chọn công ty</option>
                    <?php 
                      if (!empty($company) && is_array($company)) {
                        foreach ($company as $c) {
                          $companyName = isset($c->company_name) ? htmlspecialchars($c->company_name, ENT_QUOTES, 'UTF-8') : '';
                          $companyId = isset($c->id) ? intval($c->id) : 0;
                          echo '<option value="' . $companyId . '" data-name="' . $companyName . '">' . $companyName . '</option>';
                        }
                      }
                    ?>
                  </select>
                  <button type="button" id="companyComboboxTrigger" class="company-combobox-trigger" aria-haspopup="listbox" aria-expanded="false">Chọn hoặc tìm công ty…</button>
                  <span class="company-caret">&#9662;</span>
                  <div class="company-combobox-panel" id="companyComboboxPanel" role="listbox">
                    <input type="text" class="company-combobox-search" id="companySearchInput" placeholder="Tìm kiếm công ty…" autocomplete="off">
                    <div class="company-combobox-list" id="companyComboboxList"></div>
                   </div>
                </div>
                <button type="button" id="addCompanyBtn" title="Thêm công ty mới" style="flex-shrink:0;padding:0 14px;background:#2f73d7;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:20px;display:flex;align-items:center;justify-content:center;transition:background .2s;" onmouseover="this.style.background='#1a5bbf'" onmouseout="this.style.background='#2f73d7'">
                  <i class="ti ti-plus"></i>
                </button>
              </div>
              <div class="register-error" id="companyError"></div>
            </div>
          <div class="register-field"><label for="employerPhone">Số điện thoại <strong style="color:#e74c3c;">*</strong></label>
          <div class="register-input">
            <i class="ti ti-phone">

          </i><input id="employerPhone" name="phone" type="tel" inputmode="tel" placeholder="Nhập số điện thoại" data-required data-phone data-label="Số điện thoại"></div><div class="register-error"></div></div>

          <div class="register-field"><label for="employerPassword">Mật khẩu <strong style="color:#e74c3c;">*</strong></label><div class="register-input" style="position:relative;"><i class="ti ti-lock"></i><input id="employerPassword" name="password" type="password" autocomplete="new-password" placeholder="Tối thiểu 8 ký tự" style="padding-right:44px;" data-required data-password data-label="Mật khẩu"><i class="ti ti-eye toggle-password" data-target="employerPassword" title="Xem mật khẩu" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; z-index:2; width:24px; height:24px; display:flex; align-items:center; justify-content:center;"></i></div><div class="register-error"></div></div>
            <div class="register-field"><label for="employerConfirm">Xác nhận mật khẩu <strong style="color:#e74c3c;">*</strong></label><div class="register-input" style="position:relative;"><i class="ti ti-lock-check"></i><input id="employerConfirm" name="confirm_password" type="password" autocomplete="new-password" placeholder="Nhập lại mật khẩu" style="padding-right:44px;" data-required data-match="employerPassword" data-label="Xác nhận mật khẩu"><i class="ti ti-eye toggle-password" data-target="employerConfirm" title="Xem mật khẩu" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; z-index:2; width:24px; height:24px; display:flex; align-items:center; justify-content:center;"></i></div><div class="register-error"></div></div>
           
          </div>
          <div class="terms-row"><input id="employerTerms" type="checkbox" data-required data-label="Điều khoản dịch vụ"><label for="employerTerms">Tôi đồng ý với <a href="#">điều khoản dịch vụ</a> và cam kết thông tin doanh nghiệp là chính xác.</label></div>
          <button type="button" id='registerEmployer' class="register-submit" disabled><i class="ti ti-building-plus"></i> Đăng ký nhà tuyển dụng</button>
          <div class="form-status">Hoàn tất thông tin doanh nghiệp và đồng ý điều khoản để kích hoạt nút đăng ký.</div>
        </form>
      </div>
    </div>

    
  </section>
</main>

<!-- Modal thêm công ty (đặt ngoài form) -->
 <form >
<div id="addCompanyModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,.45);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:14px;padding:32px 28px 24px;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(15,23,42,.22);position:relative;margin:16px;">
    <button type="button" id="closeCompanyModal" style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:22px;cursor:pointer;color:#8a95a6;" title="Đóng">&times;</button>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
      <span style="width:38px;height:38px;background:#e8f0fd;border-radius:9px;display:flex;align-items:center;justify-content:center;">
        <i class="ti ti-building" style="font-size:20px;color:#2f73d7;"></i>
      </span>
      <span style="font-size:17px;font-weight:700;color:#1a2236;">Thông tin công ty</span>
    </div>
    <div style="margin-bottom:16px;">
      <label for="newCompanyName" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Tên công ty <span style="color:#e53e3e;">*</span></label>
      <div style="display:flex;align-items:center;border:1.5px solid #d7e3f4;border-radius:8px;padding:0 12px;background:#f8faff;">
        <i class="ti ti-building" style="color:#8a95a6;margin-right:8px;"></i>
        <input id="newCompanyName" type="text" placeholder="Nhập tên công ty" style="flex:1;border:none;outline:none;background:transparent;padding:11px 0;font:inherit;color:#1a2236;">
      </div>
      <div id="newCompanyNameError" style="color:#e53e3e;font-size:12px;margin-top:4px;display:none;">Vui lòng nhập tên công ty.</div>
    </div>
    <div style="margin-bottom:24px;">
      <label for="newCompanyTax" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Mã số thuế <span style="color:#e53e3e;">*</span></label>
      <div style="display:flex;align-items:center;border:1.5px solid #d7e3f4;border-radius:8px;padding:0 12px;background:#f8faff;">
        <i class="ti ti-receipt-tax" style="color:#8a95a6;margin-right:8px;"></i>
        <input id="newCompanyTax" type="text" inputmode="numeric" placeholder="10 hoặc 13 chữ số" style="flex:1;border:none;outline:none;background:transparent;padding:11px 0;font:inherit;color:#1a2236;">
      </div>
      <div id="newCompanyTaxError" style="color:#e53e3e;font-size:12px;margin-top:4px;display:none;">Mã số thuế phải gồm 10 hoặc 13 chữ số.</div>
    </div>
    <button type="button" id="saveCompanyBtn" style="width:100%;padding:12px;background:#2f73d7;color:#fff;border:none;border-radius:9px;font:inherit;font-weight:700;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:background .2s;" onmouseover="this.style.background='#1a5bbf'" onmouseout="this.style.background='#2f73d7'">
      <i class="ti ti-device-floppy"></i> Lưu
    </button>
    </form>
  </div>
</div>

<script>
(function(){
  var configs = {
    candidate: {title:'Đăng ký tài khoản ứng viên', subtitle:'Nhập đầy đủ thông tin để mở nút đăng ký tài khoản.', icon:'ti-user-plus'},
    student: {title:'Tìm kiếm và tạo tài khoản sinh viên', subtitle:'Nhập họ tên và mã sinh viên để hệ thống tìm kiếm trước khi tạo tài khoản.', icon:'ti-school'},
    employer: {title:'Đăng ký tài khoản nhà tuyển dụng', subtitle:'Khai báo thông tin liên hệ và doanh nghiệp để bắt đầu đăng tin tuyển dụng.', icon:'ti-building-plus'}
  };
  var tabs = document.querySelectorAll('.register-tab');
  var forms = document.querySelectorAll('.register-form');
  var title = document.getElementById('registerTitle');
  var subtitle = document.getElementById('registerSubtitle');

  function setText(target){
    var config = configs[target];
    if(!config || !title || !subtitle) return;
    title.innerHTML = '<i class="ti ' + config.icon + '"></i><span>' + config.title + '</span>';
    subtitle.textContent = config.subtitle;
  }

  function fieldWrapper(input){
    if(input.type === 'checkbox') return input.closest('.terms-row');
    return input.closest('.register-field');
  }

  function setError(input, message){
    var wrap = fieldWrapper(input);
    if(!wrap || wrap.classList.contains('terms-row')) return;
    var error = wrap.querySelector('.register-error');
    wrap.classList.toggle('invalid', !!message);
    wrap.classList.toggle('valid', !message && input.value.trim().length > 0);
    if(error) error.textContent = message || '';
  }

  function validateInput(input, form, silent){
    var value = input.type === 'checkbox' ? input.checked : input.value.trim();
    var label = input.getAttribute('data-label') || 'Trường này';
    var message = '';
    if(input.hasAttribute('data-required')){
      if(input.type === 'checkbox' && !input.checked) message = 'Vui lòng đồng ý điều khoản dịch vụ.';
      if(input.type !== 'checkbox' && !value) message = label + ' không được để trống.';
    }
    if(!message && input.hasAttribute('data-email') && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) message = 'Email không hợp lệ.';
    if(!message && input.hasAttribute('data-phone') && !/^(0|\+84)([0-9]{9,10})$/.test(value.replace(/\s/g,''))) message = 'Số điện thoại không hợp lệ.';
    // if(!message && input.hasAttribute('data-password') && value.length < 8) message = 'Mật khẩu phải có ít nhất 8 ký tự.';
    if(!message && input.hasAttribute('data-password') && !/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(value)) message = 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.';
    if(!message && input.hasAttribute('data-student-code') && !/^[A-Za-z0-9_-]{4,20}$/.test(value)) message = 'Mã sinh viên từ 4 đến 20 ký tự, không chứa khoảng trắng.';
    if(!message && input.hasAttribute('data-tax') && !/^([0-9]{10}|[0-9]{13})$/.test(value)) message = 'Mã số thuế phải gồm 10 hoặc 13 chữ số.';
    if(!message && input.hasAttribute('data-match')){
      var source = form.querySelector('#' + input.getAttribute('data-match'));
      if(source && value !== source.value.trim()) message = 'Mật khẩu xác nhận không trùng khớp.';
    }
    if(!silent) setError(input, message);
    return !message;
  }

  // Chỉ kiểm tra tính hợp lệ để bật/tắt nút submit, không hiện lỗi
  function checkFormReady(form){
    var inputs = form.querySelectorAll('input[data-required], select[data-required], input[data-email], input[data-phone], input[data-password], input[data-match], input[data-student-code], input[data-tax]');
    var valid = true;
    inputs.forEach(function(input){
      if(!validateInput(input, form, true)) valid = false;
    });
    var submit = form.querySelector('.register-submit');
    var status = form.querySelector('.form-status');
    if(submit) submit.disabled = !valid;
    if(status){
      status.classList.toggle('ready', valid);
      if(valid) status.textContent = 'Thông tin đã hợp lệ. Bạn có thể tiếp tục.';
      else status.textContent = status.getAttribute('data-default') || status.textContent;
    }
    return valid;
  }

  // Validate có hiện lỗi — dùng khi submit hoặc blur
  function validateForm(form){
    var inputs = form.querySelectorAll('input[data-required], select[data-required], input[data-email], input[data-phone], input[data-password], input[data-match], input[data-student-code], input[data-tax]');
    var valid = true;
    inputs.forEach(function(input){
      if(!validateInput(input, form, false)) valid = false;
    });
    var submit = form.querySelector('.register-submit');
    var status = form.querySelector('.form-status');
    if(submit) submit.disabled = !valid;
    if(status){
      status.classList.toggle('ready', valid);
      if(valid) status.textContent = 'Thông tin đã hợp lệ. Bạn có thể tiếp tục.';
      else status.textContent = status.getAttribute('data-default') || status.textContent;
    }
    return valid;
  }

  forms.forEach(function(form){
    var status = form.querySelector('.form-status');
    if(status) status.setAttribute('data-default', status.textContent);

    form.querySelectorAll('input, select').forEach(function(input){
      // Khi nhập: validate field đó + kiểm tra silent toàn form để bật/tắt nút
      input.addEventListener('input', function(){
        if(input._touched) validateInput(input, form, false);
        checkFormReady(form);
      });
      input.addEventListener('change', function(){
        if(input._touched) validateInput(input, form, false);
        checkFormReady(form);
      });
      // Khi blur lần đầu: đánh dấu touched, rồi validate có hiện lỗi
      input.addEventListener('blur', function(){
        input._touched = true;
        validateInput(input, form, false);
        checkFormReady(form);
      });
    });

    form.addEventListener('submit', function(event){
      // Khi submit: validate toàn bộ + hiện hết lỗi
      if(!validateForm(form)) event.preventDefault();
    });

    // Khởi tạo: chỉ kiểm tra silent để đặt trạng thái nút, KHÔNG hiện lỗi đỏ
    checkFormReady(form);
  });

  var companySelect = document.getElementById('companyName');
  var companySearch = document.getElementById('companySearchInput');
  var companyTrigger = document.getElementById('companyComboboxTrigger');
  var companyPanel = document.getElementById('companyComboboxPanel');
  var companyList = document.getElementById('companyComboboxList');
  var companyWrap = document.getElementById('companySelectWrap');

  // Populate company list from select options
  function initCompanyList(){
    if(!companySelect || !companyList) return;
    companyList.innerHTML = '';
    companySelect.querySelectorAll('option').forEach(function(opt){
      if(opt.value && opt.value !== ''){
        var item = document.createElement('button');
        item.type = 'button';
        item.className = 'company-combobox-option';
        item.textContent = opt.textContent;
        item.setAttribute('data-value', opt.value);
        item.addEventListener('click', function(e){
          e.preventDefault();
          companySelect.value = opt.value;
          companySelect.dispatchEvent(new Event('change', {bubbles: true}));
          syncCompanyTrigger();
          closeCompanyPanel();
          var empForm = document.querySelector('.register-form[data-form="employer"]');
          if(empForm) checkFormReady(empForm);
        });
        companyList.appendChild(item);
      }
    });
  }

  function syncCompanyTrigger(){
    if(!companySelect || !companyTrigger) return;
    var val = companySelect.value;
    var opt = companySelect.querySelector('option[value="' + val + '"]');
    if(val && opt){
      companyTrigger.textContent = opt.textContent;
      companyTrigger.classList.add('has-value');
    } else {
      companyTrigger.textContent = 'Chọn hoặc tìm công ty\u2026';
      companyTrigger.classList.remove('has-value');
    }
  }

  function refreshCompanyList(keyword){
    var q = (keyword || '').trim().toLowerCase();
    var items = companyList ? companyList.querySelectorAll('.company-combobox-option') : [];
    var visible = 0;
    items.forEach(function(item){
      var show = item.textContent.toLowerCase().indexOf(q) !== -1;
      item.style.display = show ? 'flex' : 'none';
      if(show) visible++;
    });
  }

  // Initialize company list on page load
  initCompanyList();

  function closeCompanyPanel(){
    if(companyWrap) companyWrap.classList.remove('open');
    if(companyTrigger) companyTrigger.setAttribute('aria-expanded', 'false');
  }

  function openCompanyPanel(){
    if(companyWrap) companyWrap.classList.add('open');
    if(companyTrigger) companyTrigger.setAttribute('aria-expanded', 'true');
    if(companySearch){ companySearch.value = ''; companySearch.focus(); }
    refreshCompanyList('');
  }

  if(companyTrigger){
    companyTrigger.addEventListener('click', function(e){
      e.stopPropagation();
      companyWrap && companyWrap.classList.contains('open') ? closeCompanyPanel() : openCompanyPanel();
    });
  }

  if(companySearch){
    companySearch.addEventListener('input', function(){ refreshCompanyList(companySearch.value); });
    companySearch.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeCompanyPanel(); });
    companySearch.addEventListener('click', function(e){ e.stopPropagation(); });
  }

  document.addEventListener('click', function(e){
    if(companyWrap && !companyWrap.contains(e.target)){
      var modal = document.getElementById('addCompanyModal');
      if(!modal || !modal.contains(e.target)) closeCompanyPanel();
    }
  });

  if(companySelect) companySelect.addEventListener('change', syncCompanyTrigger);
  syncCompanyTrigger();

  document.querySelectorAll('.toggle-password').forEach(function(toggle){
    toggle.addEventListener('click', function(){
      var targetId = this.getAttribute('data-target');
      var input = document.getElementById(targetId);
      if(!input) return;
      if(input.type === 'password'){
        input.type = 'text';
        this.classList.remove('ti-eye');
        this.classList.add('ti-eye-off');
        this.title = 'Ẩn mật khẩu';
      } else {
        input.type = 'password';
        this.classList.remove('ti-eye-off');
        this.classList.add('ti-eye');
        this.title = 'Xem mật khẩu';
      }
    });
  });

  // Modal
  var addBtn = document.getElementById('addCompanyBtn');
  var modal = document.getElementById('addCompanyModal');
  var closeModalBtn = document.getElementById('closeCompanyModal');
  var newNameInput = document.getElementById('newCompanyName');
  var newTaxInput = document.getElementById('newCompanyTax');
  var newNameError = document.getElementById('newCompanyNameError');
  var newTaxError = document.getElementById('newCompanyTaxError');

  function openModal(){
    if(!modal) return;
    modal.style.display = 'flex';
    if(newNameInput){ newNameInput.value = ''; newNameInput.style.borderColor = ''; }
    if(newTaxInput){ newTaxInput.value = ''; newTaxInput.style.borderColor = ''; }
    if(newNameError) newNameError.style.display = 'none';
    if(newTaxError) newTaxError.style.display = 'none';
    setTimeout(function(){ if(newNameInput) newNameInput.focus(); }, 50);
  }

  function closeModal(){
    if(modal) modal.style.display = 'none';
  }

  if(addBtn) addBtn.addEventListener('click', function(e){ e.stopPropagation(); closeCompanyPanel(); openModal(); });
  if(closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
  if(modal) modal.addEventListener('click', function(e){ if(e.target === modal) closeModal(); });

  tabs.forEach(function(tab){
    tab.addEventListener('click', function(){
      var target = tab.getAttribute('data-target');
      tabs.forEach(function(item){
        var active = item === tab;
        item.classList.toggle('active', active);
        item.setAttribute('aria-selected', active ? 'true' : 'false');
      });
      forms.forEach(function(form){
        form.classList.toggle('active', form.getAttribute('data-form') === target);
      });
      setText(target);
      var activeForm = document.querySelector('.register-form.active');
      if(activeForm) checkFormReady(activeForm);
    });
  });
})();
</script>

<?php require "footer.php"; ?>


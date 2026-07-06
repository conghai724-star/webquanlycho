<?php
$forgot_password_form = isset($forgot_password_form) && is_array($forgot_password_form) ? $forgot_password_form : array();
$forgot_password_message = isset($forgot_password_message) ? (string)$forgot_password_message : '';
$forgot_password_message_type = isset($forgot_password_message_type) ? (string)$forgot_password_message_type : '';
$forgot_full_name = trim((string)($forgot_password_form['full_name'] ?? ''));
$forgot_email = trim((string)($forgot_password_form['email'] ?? ''));
$forgot_phone = trim((string)($forgot_password_form['phone'] ?? ''));
require "header.php";
?>

<main class="forgot-password-page">
  <section class="forgot-password-hero">
    <div class="forgot-password-shell">
      <div class="forgot-password-copy">
        <div class="forgot-password-badge"><i class="ti ti-lock-heart"></i> Khôi phục tài khoản</div>
        <h1>Quên mật khẩu</h1>
        <p>Điền đúng email và số điện thoại để hệ thống kiểm tra tài khoản, sau đó gửi đường link đổi mật khẩu về email của bạn.</p>
        <div class="forgot-password-note">
          <strong>Ghi chú.</strong> Hệ thống sẽ gửi đường link đổi mật khẩu về Email của bạn, vui lòng nhập đúng email và kiểm tra email sau khi thực hiện quên mật khẩu.
        </div>
      </div>

      <div class="forgot-password-card">
        <div class="forgot-password-head">
          <div class="forgot-password-icon"><i class="ti ti-mail-forward"></i></div>
          <div>
            <h2>Thông tin nhận link đổi mật khẩu</h2>
            <p>Đường link đổi mật khẩu chỉ có hiệu lực trong vòng 5 phút.</p>
          </div>
        </div>

        <form action="" method="post" class="forgot-password-form" id="forgotPasswordForm" novalidate>
          <div class="forgot-password-field" id="forgotEmailField">
            <label for="forgotEmail">Email</label>
            <div class="forgot-password-input">
              <i class="ti ti-mail"></i>
              <input type="email" id="forgotEmail" name="email" value="<?php echo htmlspecialchars($forgot_email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nhập đúng email để nhận liên kết" autocomplete="email" required>
            </div>
            <small class="forgot-password-error">Vui lòng nhập đúng địa chỉ email.</small>
          </div>

          <button type="submit" class="forgot-password-submit" id="forgotPasswordSubmit">
            <span class="forgot-password-submit-spinner" aria-hidden="true"></span>
            <i class="ti ti-send forgot-password-submit-icon"></i>
            <span class="forgot-password-submit-text">Quên mật khẩu</span>
          </button>

          <div class="forgot-password-hint">
            Hệ thống sẽ gửi đường link đổi mật khẩu về email của bạn. Vui lòng nhập đúng email và kiểm tra hộp thư đến hoặc thư rác sau khi gửi yêu cầu.
          </div>

          <div class="forgot-password-message<?php echo $forgot_password_message !== '' ? ' show '.$forgot_password_message_type : ''; ?>" role="status" aria-live="polite">
            <?php echo htmlspecialchars($forgot_password_message, ENT_QUOTES, 'UTF-8'); ?>
          </div>

          <div class="forgot-password-actions">
            <a href="<?php echo XC_URL; ?>" class="forgot-password-home">Về trang chủ</a>
            <button type="button" class="forgot-password-login js-login-open">Đăng nhập</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<style>
.forgot-password-page {
  background: #eef3f8;
}
.forgot-password-hero {
  padding: 56px 16px 72px;
  background:
    linear-gradient(135deg, rgba(7, 49, 96, .94), rgba(215, 25, 32, .86)),
    url('https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1600&h=1000&fit=crop') center/cover no-repeat;
}
.forgot-password-shell {
  width: min(1140px, 100%);
  margin: 0 auto;
  display: grid;
  grid-template-columns: minmax(0, 1fr) 470px;
  gap: 34px;
  align-items: start;
}
.forgot-password-copy {
  color: #fff;
  padding-top: 30px;
}
.forgot-password-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 14px;
  border-radius: 999px;
  background: rgba(255,255,255,.16);
  border: 1px solid rgba(255,255,255,.22);
  font-size: 13px;
  font-weight: 800;
}
.forgot-password-copy h1 {
  margin: 18px 0 14px;
  font-size: clamp(36px, 5vw, 58px);
  line-height: 1.02;
}
.forgot-password-copy p {
  margin: 0;
  max-width: 640px;
  color: rgba(255,255,255,.92);
  font-size: 17px;
  line-height: 1.75;
}
.forgot-password-note {
  margin-top: 22px;
  max-width: 660px;
  padding: 16px 18px;
  border-radius: 12px;
  background: rgba(255,255,255,.14);
  border: 1px solid rgba(255,255,255,.2);
  color: #fff;
  line-height: 1.7;
}
.forgot-password-card {
  background: #fff;
  border-radius: 18px;
  padding: 28px;
  box-shadow: 0 22px 60px rgba(15, 23, 42, .22);
}
.forgot-password-head {
  display: flex;
  gap: 14px;
  align-items: center;
  margin-bottom: 22px;
}
.forgot-password-icon {
  width: 58px;
  height: 58px;
  border-radius: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #eaf2ff;
  color: #0d4e96;
  font-size: 28px;
  flex: 0 0 auto;
}
.forgot-password-head h2 {
  margin: 0 0 6px;
  color: #14213d;
  font-size: 24px;
}
.forgot-password-head p {
  margin: 0;
  color: #667085;
}
.forgot-password-form {
  display: grid;
  gap: 16px;
}
.forgot-password-field label {
  display: block;
  margin-bottom: 8px;
  color: #22324b;
  font-weight: 700;
}
.forgot-password-input {
  display: flex;
  align-items: center;
  gap: 10px;
  min-height: 54px;
  padding: 0 14px;
  border: 1px solid #d7e2ef;
  border-radius: 12px;
  background: #fff;
  transition: border-color .2s ease, box-shadow .2s ease;
}
.forgot-password-input:focus-within {
  border-color: #0d4e96;
  box-shadow: 0 0 0 4px rgba(13, 78, 150, .12);
}
.forgot-password-input i {
  color: #0d4e96;
  font-size: 20px;
}
.forgot-password-input input {
  width: 100%;
  min-height: 52px;
  border: 0;
  outline: 0;
  background: transparent;
  color: #101828;
  font: inherit;
}
.forgot-password-error {
  display: none;
  margin-top: 7px;
  color: #d92d20;
  font-weight: 700;
}
.forgot-password-field.is-invalid .forgot-password-input {
  border-color: #d92d20;
}
.forgot-password-field.is-invalid .forgot-password-error {
  display: block;
}
.forgot-password-submit {
  min-height: 54px;
  border: 0;
  border-radius: 12px;
  background: linear-gradient(135deg, #d71920, #b91218);
  color: #fff;
  font: inherit;
  font-weight: 800;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  position: relative;
}
.forgot-password-submit:hover {
  filter: brightness(.98);
}
.forgot-password-submit:disabled {
  opacity: .82;
  cursor: wait;
}
.forgot-password-submit-spinner {
  display: none;
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255,255,255,.35);
  border-top-color: #fff;
  border-radius: 50%;
  animation: forgotPasswordSpin .75s linear infinite;
}
.forgot-password-submit.is-loading .forgot-password-submit-spinner {
  display: inline-block;
}
.forgot-password-submit.is-loading .forgot-password-submit-icon {
  display: none;
}
@keyframes forgotPasswordSpin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
.forgot-password-hint {
  padding: 14px 16px;
  border-radius: 12px;
  background: #f8fbff;
  border: 1px solid #d7e7fb;
  color: #36516e;
  line-height: 1.7;
}
.forgot-password-message {
  display: none;
  padding: 14px 16px;
  border-radius: 12px;
  line-height: 1.7;
  font-weight: 700;
}
.forgot-password-message.show {
  display: block;
}
.forgot-password-message.success {
  background: #ecfdf3;
  color: #067647;
}
.forgot-password-message.error {
  background: #fef3f2;
  color: #b42318;
}
.forgot-password-actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
}
.forgot-password-home,
.forgot-password-login {
  color: #0d4e96;
  font-weight: 800;
  text-decoration: none;
}
.forgot-password-login {
  border: 0;
  background: transparent;
  cursor: pointer;
  font: inherit;
}
@media (max-width: 920px) {
  .forgot-password-shell {
    grid-template-columns: 1fr;
  }
  .forgot-password-copy {
    padding-top: 0;
  }
}
@media (max-width: 560px) {
  .forgot-password-hero {
    padding: 34px 12px 48px;
  }
  .forgot-password-card {
    padding: 20px;
    border-radius: 16px;
  }
  .forgot-password-actions {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>

<script>
(function(){
  var form = document.getElementById('forgotPasswordForm');
  var email = document.getElementById('forgotEmail');
  var emailField = document.getElementById('forgotEmailField');
  var submitButton = document.getElementById('forgotPasswordSubmit');
  if(!form || !email || !emailField || !submitButton) return;

  function validEmail(value){
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value || '').trim());
  }

  function refreshState(){
    var hasValue = email.value.trim() !== '';
    emailField.classList.toggle('is-invalid', hasValue && !validEmail(email.value));
  }

  email.addEventListener('input', refreshState);
  email.addEventListener('blur', refreshState);
  form.addEventListener('submit', function(event){
    refreshState();
    if(!validEmail(email.value)){
      event.preventDefault();
      email.focus();
      return;
    }
    submitButton.disabled = true;
    submitButton.classList.add('is-loading');
  });
})();
</script>

<?php require "footer.php"; ?>

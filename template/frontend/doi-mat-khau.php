<?php
$reset_password_state = isset($reset_password_state) && is_array($reset_password_state) ? $reset_password_state : array();
$reset_token = isset($reset_password_state['token']) ? (string)$reset_password_state['token'] : '';
$reset_full_name = trim((string)($reset_password_state['full_name'] ?? ''));
$reset_email = trim((string)($reset_password_state['email'] ?? ''));
$reset_message = isset($reset_password_state['message']) ? (string)$reset_password_state['message'] : '';
$reset_message_type = isset($reset_password_state['message_type']) ? (string)$reset_password_state['message_type'] : '';
$reset_is_valid = !empty($reset_password_state['is_valid']);
require "header.php";
?>

<main class="reset-password-page">
  <section class="reset-password-hero">
    <div class="reset-password-shell">
      <div class="reset-password-copy">
        <div class="reset-password-badge"><i class="ti ti-key"></i> Đổi mật khẩu mới</div>
        <h1>Thiết lập mật khẩu mới</h1>
        <p>Liên kết đổi mật khẩu chỉ có hiệu lực trong vòng 5 phút kể từ lúc hệ thống gửi email. Hãy nhập mật khẩu mới và xác nhận chính xác để hoàn tất.</p>
      </div>

      <div class="reset-password-card">
        <div class="reset-password-head">
          <div class="reset-password-icon"><i class="ti ti-shield-check"></i></div>
          <div>
            <h2>Thông tin đổi mật khẩu</h2>
            <p>Liên kết hợp lệ sẽ cho phép bạn nhập mật khẩu mới ngay tại đây.</p>
          </div>
        </div>

        <div class="reset-password-account">
          <div><strong>Họ và tên:</strong> <?php echo htmlspecialchars($reset_full_name !== '' ? $reset_full_name : 'Đang xác minh', ENT_QUOTES, 'UTF-8'); ?></div>
          <div><strong>Email:</strong> <?php echo htmlspecialchars($reset_email !== '' ? $reset_email : 'Đang xác minh', ENT_QUOTES, 'UTF-8'); ?></div>
        </div>

        <?php if($reset_message !== ''): ?>
          <div class="reset-password-message show <?php echo htmlspecialchars($reset_message_type, ENT_QUOTES, 'UTF-8'); ?>" role="status" aria-live="polite">
            <?php echo htmlspecialchars($reset_message, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>

        <?php if($reset_is_valid): ?>
          <form action="" method="post" class="reset-password-form" id="resetPasswordForm" novalidate>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($reset_token, ENT_QUOTES, 'UTF-8'); ?>">

            <div class="reset-password-field" id="newPasswordField">
              <label for="newPassword">Mật khẩu mới</label>
              <div class="reset-password-input">
                <i class="ti ti-lock"></i>
                <input type="password" id="newPassword" name="new_password" placeholder="Nhập mật khẩu mới" autocomplete="new-password" required>
              </div>
              <small class="reset-password-error">Mật khẩu mới phải có ít nhất 6 ký tự.</small>
            </div>

            <div class="reset-password-field" id="confirmPasswordField">
              <label for="confirmPassword">Xác nhận mật khẩu mới</label>
              <div class="reset-password-input">
                <i class="ti ti-lock-check"></i>
                <input type="password" id="confirmPassword" name="confirm_password" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password" required>
              </div>
              <small class="reset-password-error">Xác nhận mật khẩu mới chưa khớp.</small>
            </div>

            <button type="submit" class="reset-password-submit">
              <i class="ti ti-check"></i>
              Cập nhật mật khẩu
            </button>
          </form>
        <?php else: ?>
          <div class="reset-password-actions">
            <a href="<?php echo XC_URL; ?>/quen-mat-khau.php" class="reset-password-link primary">Thực hiện quên mật khẩu lại</a>
            <button type="button" class="reset-password-link js-login-open">Đăng nhập</button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<style>
.reset-password-page {
  background: #eef3f8;
}
.reset-password-hero {
  padding: 56px 16px 72px;
  background:
    linear-gradient(135deg, rgba(10, 59, 117, .94), rgba(215, 25, 32, .84)),
    url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1600&h=1000&fit=crop') center/cover no-repeat;
}
.reset-password-shell {
  width: min(1080px, 100%);
  margin: 0 auto;
  display: grid;
  grid-template-columns: minmax(0, 1fr) 460px;
  gap: 34px;
  align-items: start;
}
.reset-password-copy {
  color: #fff;
  padding-top: 26px;
}
.reset-password-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 14px;
  border-radius: 999px;
  background: rgba(255,255,255,.16);
  border: 1px solid rgba(255,255,255,.24);
  font-size: 13px;
  font-weight: 800;
}
.reset-password-copy h1 {
  margin: 18px 0 14px;
  font-size: clamp(34px, 5vw, 56px);
  line-height: 1.03;
}
.reset-password-copy p {
  margin: 0;
  max-width: 620px;
  color: rgba(255,255,255,.92);
  font-size: 17px;
  line-height: 1.75;
}
.reset-password-card {
  background: #fff;
  border-radius: 18px;
  padding: 28px;
  box-shadow: 0 22px 60px rgba(15, 23, 42, .22);
}
.reset-password-head {
  display: flex;
  gap: 14px;
  align-items: center;
  margin-bottom: 22px;
}
.reset-password-icon {
  width: 58px;
  height: 58px;
  border-radius: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #eaf2ff;
  color: #0d4e96;
  font-size: 28px;
}
.reset-password-head h2 {
  margin: 0 0 6px;
  color: #14213d;
  font-size: 24px;
}
.reset-password-head p {
  margin: 0;
  color: #667085;
}
.reset-password-account {
  display: grid;
  gap: 8px;
  padding: 14px 16px;
  border-radius: 12px;
  background: #f8fbff;
  border: 1px solid #d7e7fb;
  color: #29425d;
  line-height: 1.65;
  margin-bottom: 16px;
}
.reset-password-form {
  display: grid;
  gap: 16px;
}
.reset-password-field label {
  display: block;
  margin-bottom: 8px;
  color: #22324b;
  font-weight: 700;
}
.reset-password-input {
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
.reset-password-input:focus-within {
  border-color: #0d4e96;
  box-shadow: 0 0 0 4px rgba(13, 78, 150, .12);
}
.reset-password-input i {
  color: #0d4e96;
  font-size: 20px;
}
.reset-password-input input {
  width: 100%;
  min-height: 52px;
  border: 0;
  outline: 0;
  background: transparent;
  color: #101828;
  font: inherit;
}
.reset-password-error {
  display: none;
  margin-top: 7px;
  color: #d92d20;
  font-weight: 700;
}
.reset-password-field.is-invalid .reset-password-input {
  border-color: #d92d20;
}
.reset-password-field.is-invalid .reset-password-error {
  display: block;
}
.reset-password-submit {
  min-height: 54px;
  border: 0;
  border-radius: 12px;
  background: linear-gradient(135deg, #0d4e96, #0a3d75);
  color: #fff;
  font: inherit;
  font-weight: 800;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.reset-password-message {
  display: none;
  margin-bottom: 16px;
  padding: 14px 16px;
  border-radius: 12px;
  line-height: 1.7;
  font-weight: 700;
}
.reset-password-message.show {
  display: block;
}
.reset-password-message.success {
  background: #ecfdf3;
  color: #067647;
}
.reset-password-message.error {
  background: #fef3f2;
  color: #b42318;
}
.reset-password-actions {
  display: grid;
  gap: 12px;
}
.reset-password-link {
  min-height: 48px;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-weight: 800;
  cursor: pointer;
}
.reset-password-link.primary {
  background: #0d4e96;
  color: #fff;
}
.reset-password-link:not(.primary) {
  border: 1px solid #d7e2ef;
  background: #fff;
  color: #0d4e96;
}
@media (max-width: 920px) {
  .reset-password-shell {
    grid-template-columns: 1fr;
  }
  .reset-password-copy {
    padding-top: 0;
  }
}
@media (max-width: 560px) {
  .reset-password-hero {
    padding: 34px 12px 48px;
  }
  .reset-password-card {
    padding: 20px;
    border-radius: 16px;
  }
}
</style>

<script>
(function(){
  var form = document.getElementById('resetPasswordForm');
  if(!form) return;
  var newPassword = document.getElementById('newPassword');
  var confirmPassword = document.getElementById('confirmPassword');
  var newPasswordField = document.getElementById('newPasswordField');
  var confirmPasswordField = document.getElementById('confirmPasswordField');

  function refreshState() {
    var passwordValue = newPassword.value || '';
    var confirmValue = confirmPassword.value || '';
    newPasswordField.classList.toggle('is-invalid', passwordValue.length > 0 && passwordValue.length < 6);
    confirmPasswordField.classList.toggle('is-invalid', confirmValue.length > 0 && confirmValue !== passwordValue);
  }

  newPassword.addEventListener('input', refreshState);
  confirmPassword.addEventListener('input', refreshState);

  form.addEventListener('submit', function(event){
    refreshState();
    if((newPassword.value || '').length < 6){
      event.preventDefault();
      newPassword.focus();
      return;
    }
    if((confirmPassword.value || '') !== (newPassword.value || '')){
      event.preventDefault();
      confirmPassword.focus();
    }
  });
})();
</script>

<?php require "footer.php"; ?>

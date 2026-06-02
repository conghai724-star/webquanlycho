<?php
header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Google reCAPTCHA v2 test keys for localhost/dev only. Replace these in production.
$recaptchaSiteKey = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
$recaptchaSecretKey = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
$formMessage = '';
$formMessageType = '';
$submittedEmail = '';

function verifyRecaptchaToken($secretKey, $token) {
    if ($token === '') {
        return false;
    }

    $payload = http_build_query([
        'secret' => $secretKey,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    if (function_exists('curl_init')) {
        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 10,
            ],
        ]);
        $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    }

    if (!$response) {
        return false;
    }

    $result = json_decode($response, true);
    return !empty($result['success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedEmail = trim($_POST['email'] ?? '');
    $recaptchaToken = $_POST['g-recaptcha-response'] ?? '';

    if (!filter_var($submittedEmail, FILTER_VALIDATE_EMAIL)) {
        $formMessage = 'Vui lòng nhập email hợp lệ.';
        $formMessageType = 'error';
    } elseif (!verifyRecaptchaToken($recaptchaSecretKey, $recaptchaToken)) {
        $formMessage = 'Vui lòng xác thực reCAPTCHA trước khi gửi.';
        $formMessageType = 'error';
    } else {
        $formMessage = 'Yêu cầu đã được ghi nhận. Nếu email hợp lệ, hệ thống sẽ gửi hướng dẫn đặt lại mật khẩu.';
        $formMessageType = 'success';
    }
}

require "header.php";
?>

<main class="forgot-page">
  <section class="forgot-hero" aria-labelledby="forgotTitle">
    <div class="forgot-shell">
      <div class="forgot-copy">
        <div class="forgot-badge"><i class="ti ti-shield-lock"></i> Khôi phục tài khoản</div>
        <h1 id="forgotTitle">Quên mật khẩu?</h1>
        <p>Nhập email đã đăng ký tài khoản. Hệ thống sẽ gửi hướng dẫn đặt lại mật khẩu nếu email tồn tại trong dữ liệu.</p>
        <div class="forgot-steps" aria-label="Quy trình khôi phục mật khẩu">
          <div class="forgot-step"><span>1</span> Nhập email</div>
          <div class="forgot-step"><span>2</span> Xác thực reCAPTCHA</div>
          <div class="forgot-step"><span>3</span> Nhận liên kết đặt lại</div>
        </div>
      </div>

      <div class="forgot-card">
        <div class="forgot-card-head">
          <div class="forgot-icon"><i class="ti ti-mail-forward"></i></div>
          <div>
            <h2>Lấy lại mật khẩu</h2>
            <p>Vui lòng kiểm tra kỹ email trước khi gửi yêu cầu.</p>
          </div>
        </div>

        <form class="forgot-form" id="forgotPasswordForm" action="" method="post" novalidate>
          <div class="forgot-field">
            <label for="forgotEmail">Email</label>
            <div class="forgot-input-wrap">
              <i class="ti ti-mail"></i>
              <input type="email" id="forgotEmail" name="email" placeholder="Nhập email của bạn" autocomplete="email" value="<?php echo htmlspecialchars($submittedEmail, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <small class="forgot-error" id="forgotEmailError">Vui lòng nhập email hợp lệ.</small>
          </div>

          <div class="recaptcha-wrap">
            <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($recaptchaSiteKey, ENT_QUOTES, 'UTF-8'); ?>" data-callback="onForgotRecaptchaSuccess" data-expired-callback="onForgotRecaptchaExpired" data-error-callback="onForgotRecaptchaExpired"></div>
          </div>

          <button type="submit" class="forgot-submit" id="forgotSubmit" disabled>
            <i class="ti ti-send"></i> Gửi
          </button>

          <div class="forgot-message<?php echo $formMessage ? ' show ' . htmlspecialchars($formMessageType, ENT_QUOTES, 'UTF-8') : ''; ?>" id="forgotMessage" role="status" aria-live="polite"><?php echo htmlspecialchars($formMessage, ENT_QUOTES, 'UTF-8'); ?></div>

          <div class="forgot-back">
            Đã nhớ mật khẩu? <button type="button" class="forgot-login-link js-login-open">Đăng nhập</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<style>
.forgot-page {
  background: #f4f7fb;
  min-height: 620px;
}

.forgot-hero {
  padding: 64px 16px 72px;
  background:
    linear-gradient(135deg, rgba(13, 78, 150, .92), rgba(225, 42, 42, .86)),
    url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=1600&h=900&fit=crop') center/cover no-repeat;
}

.forgot-shell {
  width: min(1120px, 100%);
  margin: 0 auto;
  display: grid;
  grid-template-columns: minmax(0, 1fr) 430px;
  gap: 40px;
  align-items: center;
}

.forgot-copy {
  color: #fff;
}

.forgot-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  border-radius: 999px;
  background: rgba(255,255,255,.16);
  border: 1px solid rgba(255,255,255,.24);
  font-weight: 700;
  font-size: 13px;
}

.forgot-copy h1 {
  margin: 18px 0 12px;
  font-size: clamp(34px, 5vw, 58px);
  line-height: 1.05;
  letter-spacing: 0;
}

.forgot-copy p {
  max-width: 620px;
  margin: 0;
  font-size: 17px;
  line-height: 1.7;
  color: rgba(255,255,255,.9);
}

.forgot-steps {
  display: grid;
  gap: 12px;
  margin-top: 28px;
  max-width: 520px;
}

.forgot-step {
  display: flex;
  align-items: center;
  gap: 12px;
  min-height: 48px;
  padding: 10px 14px;
  border-radius: 8px;
  background: rgba(255,255,255,.14);
  border: 1px solid rgba(255,255,255,.18);
  font-weight: 600;
}

.forgot-step span {
  width: 28px;
  height: 28px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #fff;
  color: #0d4e96;
  font-weight: 800;
  flex: 0 0 auto;
}

.forgot-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 22px 60px rgba(16, 24, 40, .22);
  padding: 28px;
}

.forgot-card-head {
  display: flex;
  gap: 14px;
  align-items: center;
  margin-bottom: 24px;
}

.forgot-icon {
  width: 52px;
  height: 52px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #eaf2ff;
  color: #0d4e96;
  font-size: 28px;
  flex: 0 0 auto;
}

.forgot-card h2 {
  margin: 0 0 5px;
  color: #14213d;
  font-size: 24px;
  letter-spacing: 0;
}

.forgot-card p {
  margin: 0;
  color: #667085;
  line-height: 1.5;
}

.forgot-form {
  display: grid;
  gap: 16px;
}

.forgot-field label {
  display: block;
  margin-bottom: 8px;
  color: #24324a;
  font-weight: 700;
}

.forgot-input-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid #d6dce8;
  border-radius: 8px;
  background: #fff;
  padding: 0 14px;
  min-height: 52px;
  transition: border-color .2s, box-shadow .2s;
}

.forgot-input-wrap:focus-within {
  border-color: #0d4e96;
  box-shadow: 0 0 0 4px rgba(13, 78, 150, .12);
}

.forgot-input-wrap i {
  color: #0d4e96;
  font-size: 20px;
}

.forgot-input-wrap input {
  width: 100%;
  border: 0;
  outline: 0;
  min-height: 50px;
  font: inherit;
  color: #14213d;
  background: transparent;
}

.forgot-error {
  display: none;
  margin-top: 7px;
  color: #d92d20;
  font-weight: 600;
}

.forgot-field.is-invalid .forgot-input-wrap {
  border-color: #d92d20;
}

.forgot-field.is-invalid .forgot-error {
  display: block;
}

.recaptcha-wrap {
  min-height: 78px;
  display: flex;
  align-items: center;
  overflow-x: auto;
}

.forgot-submit {
  min-height: 52px;
  border: 0;
  border-radius: 8px;
  background: #d71920;
  color: #fff;
  font: inherit;
  font-weight: 800;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: transform .2s, background .2s, opacity .2s;
}

.forgot-submit:not(:disabled):hover {
  background: #b9141a;
  transform: translateY(-1px);
}

.forgot-submit:disabled {
  opacity: .48;
  cursor: not-allowed;
}

.forgot-message {
  display: none;
  padding: 12px 14px;
  border-radius: 8px;
  font-weight: 700;
  line-height: 1.5;
}

.forgot-message.show {
  display: block;
}

.forgot-message.success {
  background: #ecfdf3;
  color: #067647;
}

.forgot-message.error {
  background: #fef3f2;
  color: #b42318;
}

.forgot-back {
  text-align: center;
  color: #667085;
}

.forgot-login-link {
  border: 0;
  padding: 0;
  background: transparent;
  color: #0d4e96;
  font: inherit;
  font-weight: 800;
  cursor: pointer;
}

@media (max-width: 900px) {
  .forgot-shell {
    grid-template-columns: 1fr;
  }

  .forgot-card {
    max-width: 560px;
  }
}

@media (max-width: 560px) {
  .forgot-hero {
    padding: 36px 12px 48px;
  }

  .forgot-card {
    padding: 20px;
  }

  .forgot-card-head {
    align-items: flex-start;
  }
}
</style>

<script>
window.forgotRecaptchaVerified = false;

function onForgotRecaptchaSuccess() {
  window.forgotRecaptchaVerified = true;
  if (window.refreshForgotSubmitState) window.refreshForgotSubmitState();
}

function onForgotRecaptchaExpired() {
  window.forgotRecaptchaVerified = false;
  if (window.refreshForgotSubmitState) window.refreshForgotSubmitState();
}

(function(){
  var form = document.getElementById('forgotPasswordForm');
  var email = document.getElementById('forgotEmail');
  var submit = document.getElementById('forgotSubmit');
  var message = document.getElementById('forgotMessage');
  if (!form || !email || !submit) return;

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
  }

  window.refreshForgotSubmitState = function() {
    var emailOk = isValidEmail(email.value);
    submit.disabled = !(emailOk && window.forgotRecaptchaVerified);
    email.closest('.forgot-field').classList.toggle('is-invalid', email.value.length > 0 && !emailOk);
    if (message) message.classList.remove('show', 'success', 'error');
  };

  email.addEventListener('input', window.refreshForgotSubmitState);
  email.addEventListener('blur', window.refreshForgotSubmitState);

  form.addEventListener('submit', function(event) {
    if (submit.disabled) {
      event.preventDefault();
      window.refreshForgotSubmitState();
    }
  });

  window.refreshForgotSubmitState();
})();
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php require "footer.php"; ?>
<?php
$is_success = isset($page_status) && $page_status == "success";
$disable_auto_redirect = isset($page_action_label) && trim((string)$page_action_label) !== '';
http_response_code($is_success ? 200 : 404);
require "header.php";
?>

<style>
.simple-404{
  min-height:calc(100vh - 280px);
  display:flex;
  align-items:center;
  justify-content:center;
  padding:80px 20px;
  background:#f4f5f6;
  text-align:center;
}
.simple-404-box{
  width:100%;
  max-width:720px;
}
.simple-404-title{
  font-size:30px;
  line-height:1.35;
  font-weight:800;
  color:#111827;
  margin-bottom:16px;
}
.simple-404-code{
  font-size:150px;
  line-height:.95;
  font-weight:800;
  color:#0d4e96;
  margin-bottom:18px;
}
.simple-404-success-icon{
  position:relative;
  width:132px;
  height:132px;
  margin:0 auto 24px;
  border-radius:50%;
  background:linear-gradient(145deg,#fff7c2,#ffd44d);
  box-shadow:0 18px 42px rgba(13,78,150,.16), inset 0 -10px 18px rgba(198,132,0,.16);
  animation:successFloat 2.4s ease-in-out infinite;
}
.simple-404-success-icon:before,
.simple-404-success-icon:after{
  content:"";
  position:absolute;
  top:42px;
  width:14px;
  height:18px;
  border-radius:50%;
  background:#1f2937;
  animation:successBlink 3.2s infinite;
}
.simple-404-success-icon:before{left:38px}
.simple-404-success-icon:after{right:38px}
.simple-404-smile{
  position:absolute;
  left:50%;
  top:66px;
  width:52px;
  height:28px;
  border:5px solid #1f2937;
  border-top:0;
  border-radius:0 0 52px 52px;
  transform:translateX(-50%);
}
.simple-404-like{
  position:absolute;
  right:-12px;
  bottom:8px;
  width:48px;
  height:48px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:50%;
  background:#16a34a;
  color:#fff;
  font-size:26px;
  box-shadow:0 10px 26px rgba(22,163,74,.35);
  animation:successLike 1.6s ease-in-out infinite;
}
@keyframes successFloat{
  0%,100%{transform:translateY(0) scale(1)}
  50%{transform:translateY(-8px) scale(1.03)}
}
@keyframes successLike{
  0%,100%{transform:rotate(-10deg) scale(1)}
  45%{transform:rotate(8deg) scale(1.14)}
}
@keyframes successBlink{
  0%,92%,100%{transform:scaleY(1)}
  95%{transform:scaleY(.12)}
}
.simple-404-text{
  font-size:15px;
  line-height:1.7;
  color:#5f6b7a;
  margin-bottom:22px;
}
.simple-404-countdown{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:12px 18px;
  border-radius:999px;
  background:#fff;
  border:1px solid #e4eaf2;
  color:#334155;
  font-size:14px;
  font-weight:700;
  box-shadow:0 8px 24px rgba(13,78,150,.08);
}
.simple-404-countdown strong{
  color:#0d4e96;
  font-size:18px;
}
.simple-404-actions{
  margin-top:24px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:12px;
  flex-wrap:wrap;
}
.simple-404-home{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:12px 20px;
  border-radius:12px;
  background:#0d4e96;
  color:#fff;
  font-size:14px;
  font-weight:800;
  transition:.2s;
}
.simple-404-home:hover{
  background:#073763;
  transform:translateY(-2px);
}
.simple-404-secondary{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:12px 20px;
  border-radius:12px;
  background:#fff;
  color:#0d4e96;
  border:1px solid #cfe0f4;
  font-size:14px;
  font-weight:800;
  transition:.2s;
}
.simple-404-secondary:hover{
  transform:translateY(-2px);
  border-color:#0d4e96;
  box-shadow:0 12px 24px rgba(13,78,150,.12);
}
.simple-404-secondary[disabled]{
  opacity:.65;
  cursor:not-allowed;
  transform:none;
  box-shadow:none;
}
.simple-404-feedback{
  margin-top:16px;
  font-size:14px;
  line-height:1.6;
  color:#475569;
}
.simple-404-feedback.is-success{
  color:#15803d;
}
.simple-404-feedback.is-error{
  color:#b42318;
}
@media(max-width:640px){
  .simple-404{padding:58px 16px}
  .simple-404-title{font-size:23px}
  .simple-404-code{font-size:104px}
  .simple-404-success-icon{width:108px;height:108px}
  .simple-404-success-icon:before,
  .simple-404-success-icon:after{top:34px}
  .simple-404-success-icon:before{left:31px}
  .simple-404-success-icon:after{right:31px}
  .simple-404-smile{top:54px;width:44px;height:24px}
  .simple-404-like{width:42px;height:42px;font-size:22px}
  .simple-404-text{font-size:13px}
}
</style>

<main class="simple-404">
  <div class="simple-404-box">
    <h1 class="simple-404-title"><?php echo $page_title; ?></h1>
    <?php if($verify_email == 1){ ?>
    <div class="simple-404-success-icon" aria-hidden="true">
      <span class="simple-404-smile"></span>
      <span class="simple-404-like"><i class="ti ti-thumb-up"></i></span>
    </div>
    <?php }else{ ?>
    <div class="simple-404-code">404</div>
    <?php } ?>
    <p class="simple-404-text"><?php echo $page_description; ?></p>
    <?php if(!$disable_auto_redirect){ ?>
    <div class="simple-404-countdown">
      <i class="ti ti-clock"></i>
      Tự động quay lại trang chủ sau <strong id="countdown404">10</strong> giây
    </div>
    <?php } ?>
    <div class="simple-404-actions">
      <a class="simple-404-home" href="<?php echo XC_URL;?>">
        <i class="ti ti-home"></i>
        Về trang chủ
      </a>
      <?php if(isset($page_action_label) && trim((string)$page_action_label) !== ''){ ?>
      <button type="button" class="simple-404-secondary" id="pageActionButton" data-api="<?php echo isset($page_action_api) ? htmlspecialchars((string)$page_action_api, ENT_QUOTES, 'UTF-8') : ''; ?>">
        <i class="ti ti-mail-forward"></i>
        <?php echo htmlspecialchars((string)$page_action_label, ENT_QUOTES, 'UTF-8'); ?>
      </button>
      <?php } ?>
    </div>
    <div class="simple-404-feedback" id="pageActionFeedback"></div>
  </div>
</main>

<?php if(!$disable_auto_redirect){ ?>
<script>
(function(){
  var seconds = 10;
  var countdown = document.getElementById('countdown404');
  var homeUrl = '<?php echo XC_URL;?>';

  var timer = setInterval(function(){
    seconds -= 1;
    if(countdown){ countdown.textContent = seconds; }

    if(seconds <= 0){
      clearInterval(timer);
      window.location.href = homeUrl;
    }
  }, 1000);
})();
</script>
<?php } ?>

<?php if(isset($page_action_label) && trim((string)$page_action_label) !== ''){ ?>
<script>
(function(){
  var actionButton = document.getElementById('pageActionButton');
  var feedback = document.getElementById('pageActionFeedback');
  var payload = <?php echo json_encode(isset($page_action_payload) ? $page_action_payload : array('user_id' => 0, 'email' => ''), JSON_UNESCAPED_UNICODE); ?>;
  if(!actionButton){ return; }

  actionButton.addEventListener('click', function(){
    var apiUrl = actionButton.getAttribute('data-api') || '';
    if(!apiUrl){
      if(feedback){
        feedback.textContent = 'Không tìm thấy đường dẫn gửi email xác thực.';
        feedback.className = 'simple-404-feedback is-error';
      }
      return;
    }

    actionButton.disabled = true;
    if(feedback){
      feedback.textContent = 'Hệ thống đang gửi lại liên kết xác thực...';
      feedback.className = 'simple-404-feedback';
    }

    fetch(apiUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams({
        user_id: payload && payload.user_id ? payload.user_id : '',
        email: payload && payload.email ? payload.email : ''
      }).toString()
    })
      .then(function(response){ return response.json(); })
      .then(function(result){
        if(!result || Number(result.status) !== 200){
          throw new Error((result && result.message) || 'Không thể gửi lại email xác thực.');
        }
        if(feedback){
          feedback.textContent = result.message;
          feedback.className = 'simple-404-feedback is-success';
        }
      })
      .catch(function(error){
        if(feedback){
          feedback.textContent = error.message;
          feedback.className = 'simple-404-feedback is-error';
        }
      })
      .finally(function(){
        actionButton.disabled = false;
      });
  });
})();
</script>
<?php } ?>

<?php require "footer.php"; ?>

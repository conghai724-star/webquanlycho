<?php
$theme = 'light';
if (isset($_COOKIE['app_theme']) && in_array($_COOKIE['app_theme'], ['light', 'dark'])) {
    $theme = $_COOKIE['app_theme'];
}
$htmlBg = $theme === 'dark' ? '#0f1623' : '#f5f7fb';
?>
<!DOCTYPE html>
<html lang="vi" data-theme="<?php echo $theme; ?>" style="background:<?php echo $htmlBg; ?>">
<head>
    <style>
        html, body { background: <?php echo $htmlBg; ?> !important; }
        @view-transition { navigation: auto; }
        ::view-transition-old(root) { animation: none; }
        ::view-transition-new(root) { animation: 180ms ease both vt-enter; }
        @keyframes vt-enter { from{opacity:0} to{opacity:1} }
        @media (prefers-reduced-motion: reduce) {
            ::view-transition-new(root) { animation-duration: 0s; }
        }
    </style>
    <!-- Pre-paint Theme + cookie sync -->
    <script>
        (function(){
            try {
                var stored = localStorage.getItem('theme');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = stored || (prefersDark ? 'dark' : 'light');
                var html = document.documentElement;
                if (html.getAttribute('data-theme') !== theme) {
                    html.setAttribute('data-theme', theme);
                    html.style.background = theme === 'dark' ? '#0f1623' : '#f5f7fb';
                }
                document.cookie = 'app_theme=' + theme + ';path=/;max-age=31536000;SameSite=Lax';
            } catch(e) {}
        })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Trị Web & Bản Đồ Số</title>
    
    <!-- Favicon & Fonts -->
    <link rel="icon" href="<?php echo XC_URL; ?>/template/app/assets/images/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS chính thức -->
    <link rel="stylesheet" crossorigin href="<?php echo XC_URL; ?>/template/app/assets/css/gentelella.css">
    <link rel="stylesheet" crossorigin href="<?php echo XC_URL; ?>/template/app/assets/css/loading.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" crossorigin href="<?php echo XC_URL; ?>/template/app/assets/css/theme-redesign.css?v=<?php echo time(); ?>">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .error-alert {
            background-color: rgba(211, 47, 47, 0.1);
            border: 1px solid rgba(211, 47, 47, 0.2);
            color: #d32f2f;
            padding: 12px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        [data-theme="dark"] .error-alert {
            background-color: rgba(244, 67, 54, 0.15);
            border-color: rgba(244, 67, 54, 0.25);
            color: #ff8a80;
        }
    </style>
    <script>
        $(document).ready(function(){
            $("#loginForm").on("submit", function(e){
                e.preventDefault();
                var username = $('#username').val();
                var password = $('#password').val();
                var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                var swalBg = isDark ? '#1a2332' : '#ffffff';
                var swalColor = isDark ? '#ffffff' : '#0f1623';

                if (!username || !password) {
                    Swal.fire({
                        icon: 'warning',
                        title: "Chú ý!",
                        text: "Vui lòng nhập tên tài khoản và mật khẩu",
                        background: swalBg,
                        color: swalColor
                    });
                    return false;
                }

                $("#sendlogin").prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang đăng nhập...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo BASE_URL; ?>api/login",
                    data: {
                        'username': username,
                        'email': username,
                        'password': password
                    },
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 200 || data.status == '200'){
                            window.location.href = data.return_url || "<?php echo BASE_URL; ?>admin/dashboard";
                        } else {
                            $("#sendlogin").prop("disabled", false).text("Đăng nhập");
                            Swal.fire({
                              icon: 'error',
                              title: "Đăng nhập thất bại!",
                              text: data.message || "Tên tài khoản hoặc mật khẩu không chính xác.",
                              timer: 2500,
                              showConfirmButton: false,
                              background: swalBg,
                              color: swalColor
                            });
                        }
                    },
                    error: function() {
                        // Nếu AJAX bị lỗi (do đường dẫn API), gửi form dạng POST thông thường
                        $("#loginForm")[0].submit();
                    }
                });
                return false;
            });
        });
    </script>
</head>
<body>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="brand-icon" style="background-color: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">W</div>
            <div class="brand-name">ADMIN WEB <small style="font-weight:400;color:var(--text-muted);font-size:13px;margin-left:2px">v1.0</small></div>
        </div>

        <div class="auth-title">Đăng Nhập Quản Trị Web</div>
        <div class="auth-subtitle">Hệ thống quản trị Web & Biên tập Bản đồ số.</div>

        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if (!empty($error)): ?>
            <div class="error-alert">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Hướng dẫn tài khoản test -->
        <div style="background-color: var(--bg-surface-secondary); padding: 12px; border-radius: 6px; font-size: 11px; color: var(--text-muted); margin-bottom: 20px; border: 1px solid var(--border-color);">
            <strong style="color: var(--text-heading);"><i class="fa-solid fa-circle-info me-1"></i> Tài khoản đăng nhập:</strong>
            <div style="margin-top: 4px;">• <strong>Quản trị Web:</strong> <span style="font-family: monospace; font-weight: bold;">admin</span> | Pass: <span style="font-family: monospace; font-weight: bold;">123456</span></div>
            <div style="margin-top: 2px;">• <strong>Biên tập viên:</strong> <span style="font-family: monospace; font-weight: bold;">editor</span> | Pass: <span style="font-family: monospace; font-weight: bold;">123456</span></div>
        </div>

        <form id="loginForm" action="<?php echo BASE_URL; ?>admin/login" method="POST">
            <!-- Username Input -->
            <div class="form-group">
                <label class="form-label" for="username">Tên đăng nhập / Email</label>
                <div class="input-group">
                    <svg class="input-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Tên tài khoản hoặc email" required autofocus>
                </div>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label class="form-label" for="password">Mật khẩu</label>
                <div class="input-group">
                    <svg class="input-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="7" width="10" height="7" rx="1.5"/><path d="M5 7V5a3 3 0 016 0v2"/></svg>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="auth-actions">
                <label class="form-check">
                    <input type="checkbox" checked> Ghi nhớ đăng nhập
                </label>
            </div>

            <button id="sendlogin" type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:38px;margin-top:10px;">
                Đăng nhập
            </button>
        </form>
    </div>
</div>

<script src="<?php echo XC_URL; ?>/template/app/assets/js/loading.js?v=<?php echo time(); ?>"></script>
</body>
</html>

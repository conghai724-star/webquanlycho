<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang | Ban Quản lý Chợ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-teal: #0f766e;
            --brand-teal-light: #0d9488;
            --brand-teal-bg: #f0fdfa;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-800: #1f2937;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #fafafa;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            overflow-x: hidden;
        }

        .container {
            text-align: center;
            max-width: 600px;
            width: 100%;
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        .illustration {
            width: 180px;
            height: 180px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--brand-teal-bg);
            border-radius: 50%;
            color: var(--brand-teal);
            font-size: 80px;
            font-family: 'Roboto Mono', monospace;
            font-weight: 700;
            position: relative;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 12px;
            color: var(--gray-800);
            letter-spacing: -0.5px;
        }

        p {
            font-size: 16px;
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--brand-teal);
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 12px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(15, 118, 110, 0.2);
        }

        .btn-home:hover {
            background: var(--brand-teal-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 118, 110, 0.3);
        }

        .btn-home:active {
            transform: translateY(0);
        }

        .watermark {
            margin-top: 40px;
            font-size: 12px;
            color: #a1a1aa;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="illustration">404</div>
        <h1>Không Tìm Thấy Trang</h1>
        <p>Đường liên kết bạn vừa truy cập không tồn tại hoặc đã được di chuyển sang một vị trí khác trong hệ thống quản lý chợ.</p>
        <a href="<?php echo BASE_URL; ?>" class="btn-home">Quay lại Trang chủ</a>
        <div class="watermark">Ban Quản lý Chợ</div>
    </div>
</body>
</html>

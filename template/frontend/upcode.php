<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang Nâng Cấp Hệ Thống</title>
    <style>
        :root {
            --primary-blue: #1760a5;
            --primary-orange: #e36928;
            --white: #ffffff;
            --gray: #f4f7f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--gray);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            z-index: 1;
        }

        /* Hiệu ứng vòng tròn xoay phía sau */
        .loader-bg {
            position: absolute;
            width: 300px;
            height: 300px;
            border: 10px solid var(--primary-blue);
            border-top: 10px solid var(--primary-orange);
            border-radius: 50%;
            opacity: 0.1;
            animation: spin 3s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .icon-box {
            font-size: 80px;
            margin-bottom: 20px;
            color: var(--primary-blue);
            position: relative;
        }

        h1 {
            font-size: 2.5rem;
            color: var(--primary-blue);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            color: #555;
        }

        .progress-bar {
            width: 100%;
            background: #ddd;
            height: 12px;
            border-radius: 10px;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 75%; /* Bạn có thể chỉnh sửa % tiến độ tại đây */
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-orange));
            border-radius: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.8; }
            50% { opacity: 1; }
            100% { opacity: 0.8; }
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--primary-orange);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(227, 105, 40, 0.4);
        }

        .contact-info {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #888;
        }

        .contact-info b {
            color: var(--primary-blue);
        }
    </style>
</head>
<body>

    <div class="loader-bg"></div>

    <div class="container">
        <div class="icon-box">
            🛠️
        </div>
        
        <h1>Đang Nâng Cấp</h1>
        <p>Chúng tôi đang thực hiện một số cải tiến để mang lại trải nghiệm tốt nhất cho bạn. Trang web sẽ sớm quay trở lại!</p>

        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>

        <a href="<?php echo XC_URL; ?>" class="btn">Quay lại trang chủ</a>

        <div class="contact-info">
            Cảm ơn bạn đã kiên nhẫn!
        </div>
    </div>

</body>
</html>
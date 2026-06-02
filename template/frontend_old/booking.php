<?php require "header_new.php"; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        /* Tùy chỉnh màu chủ đạo đẹp mắt */
        .booking-wrapper {
            --color-blue: #1760a5; /* Xanh đậm - Chuyên nghiệp */
            --color-orange: #e36928; /* Cam - Nổi bật, Năng lượng */
            --color-bg-form: #f0f7ff; /* Nền form xanh cực nhạt - Tươi mới */
            --color-text-main: #333; /* Chữ chính đậm */
            --color-text-sub: #124d85; /* Chữ phụ xanh */
            --color-border: #d4e3f3; /* Viền ô nhập liệu mảnh */
            
            display: flex;
            justify-content: center;
            padding: 40px 20px;
            width: 100%;
        }

        .booking-container {
            background-color: var(--color-bg-form);
            width: 100%;
            max-width: 900px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(23, 96, 165, 0.08); /* Đổ bóng xanh nhẹ chuyên nghiệp */
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .booking-container h1 {
            color: var(--color-blue);
            text-align: center;
            text-transform: uppercase;
            margin: 0 0 15px 0;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        /* Thêm đường gạch dưới tiêu đề */
        .booking-container h1::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--color-orange);
            margin: 10px auto 0 auto;
            border-radius: 2px;
        }

        .booking-container .form-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Tăng khoảng cách giữa các ô */
        }

        .booking-container .form-group {
            flex: 1 1 calc(50% - 20px);
            min-width: 250px;
            position: relative;
        }

        .booking-container .full-width {
            flex: 1 1 100%;
        }

        .booking-container .row-three {
            flex: 1 1 calc(33.33% - 20px);
            min-width: 180px;
        }

        /* Scope tất cả input/select/textarea trong container */
        .booking-container input, 
        .booking-container select, 
        .booking-container textarea {
            width: 100%;
            padding: 14px 18px; /* Tăng padding để ô nhập liệu rộng rãi hơn */
            border: 1px solid var(--color-border);
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            color: var(--color-text-main);
            transition: all 0.3s;
            box-sizing: border-box;
            background-color: #fff;
        }

        .booking-container input:focus, 
        .booking-container select:focus, 
        .booking-container textarea:focus {
            border-color: var(--color-blue);
            box-shadow: 0 0 0 3px rgba(23, 96, 165, 0.1);
        }

        /* Thêm icon cho ô Ngày khám */
        .booking-container .date-wrapper i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-blue);
            pointer-events: none;
            font-size: 18px;
        }

        /* Custom Select */
        .booking-container select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231760a5'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            background-size: 20px;
        }

        .booking-container textarea {
            height: 140px;
            resize: vertical;
        }

        .booking-container .note-text {
            display: block;
            text-align: center;
            color: var(--color-text-sub);
            font-style: italic;
            margin: 25px 0;
            font-size: 15px;
            font-weight: 500;
        }

        /* Nút xác nhận - Nổi bật với màu Cam */
        .booking-container .btn-submit {
            display: block;
            width: fit-content;
            margin: 0 auto;
            background-color: var(--color-orange);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 30px; /* Bo góc tròn hơn chuyên nghiệp */
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
            letter-spacing: 1px;
            box-shadow: 0 4px 12px rgba(227, 105, 40, 0.2);
        }

        .booking-container .btn-submit:hover {
            background-color: #cc5c20; /* Màu cam đậm hơn khi hover */
            transform: translateY(-2px); /* Hiệu ứng nổi lên */
            box-shadow: 0 6px 18px rgba(227, 105, 40, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-container .form-group, 
            .booking-container .row-three {
                flex: 1 1 100%;
            }
            .booking-container {
                padding: 25px;
            }
            .booking-container h1 { font-size: 24px; }
        }
    </style>
    
</section>    
<div id="breadcrumbs">
   <div class="ctn">
      <div id="crumbs"><a href="index.html">Trang chủ</a> <span>/</span> <a class="current">Đặt lịch khám</a></div>
   </div>
</div>
<div class="ctn">
    <div class="booking-wrapper">
    <div class="booking-container">
        <h1>Đặt Lịch Khám</h1>
         <form action="#" method="POST" id="myForm" onsubmit="return false;">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" placeholder="Họ & tên (*)" required id='booking_person_name' name ='booking_person_name'> 
                </div>
                <div class="form-group">
                    <input type="tel" placeholder="Điện thoại (*)" required id='booking_person_phone' name='booking_person_phone'>
                </div>
                <div class="form-group">
                    <select required id='booking_person_gender' name='booking_person_gender'>
                        <option value="" disabled selected>Giới tính (*)</option>
                        <option value="1">Nam</option>
                        <option value="0">Nữ</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Tuổi (VD: 1980) (*)" required min='4' max=4 id='booking_person_year' name='booking_person_year'>
                </div>

                <div class="form-group full-width">
                    <input type="text" placeholder="Địa chỉ (*)" id='booking_person_address' name='booking_person_address'>
                </div>
                <div class="form-group date-wrapper">
                    <input type="text" placeholder="Ngày khám *" readonly id='booking_date' name='booking_date'>
                    <i class="fas fa-calendar-alt"></i>
                </div>

                <div class="form-group ">
                    <select id='booking_hour' name='booking_hour'>
                        <option value="" disabled selected >Giờ khám *</option>
                        <optgroup label="Buổi sáng">
                            <option value="07:00">07:00</option>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                        </optgroup>
                        <optgroup label="Buổi chiều">
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="15:00">14:00</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group full-width">
                    <textarea placeholder="Nhập tình trạng sức khỏe của bạn..." id='booking_description'  name='booking_description'></textarea>
                </div>
            </div>

            <p class="note-text">Ghi chú: Vui lòng điền thông tin đầy đủ để được chăm sóc tốt nhất</p>

            <button type="submit" class="btn-submit" id='btnBooking'>Xác nhận đặt hẹn &rsaquo;</button>
        </form>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#booking_date", {
            locale: "vn",          // Chuyển sang tiếng Việt
            dateFormat: "d/m/Y",   // Định dạng hiển thị: Ngày/Tháng/Năm
            
            // CHÍNH XÁC YÊU CẦU:
            minDate: "today",      // Không cho phép chọn ngày trước ngày hôm nay
            
            disableMobile: "true", // Giữ giao diện lịch đẹp trên cả điện thoại
            animate: true,         // Hiệu ứng mượt mà
            
            // Tùy chọn thêm: Nếu muốn mặc định chọn sẵn ngày hôm nay
            // defaultDate: "today", 
        });
    });
</script>
<?php require "footer_new.php"; ?>
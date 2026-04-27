<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - Cổng việc làm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <style>
        :root {
            --primary-blue: #1760a5;
            --primary-orange: #e36928;
            --bg-light: #f4f7f6;
            --white: #ffffff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        body {
            background-color: var(--bg-light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            background: var(--white);
            width: 100%;
            max-width: 900px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: var(--primary-blue);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .role-selection {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background: #eef2f5;
        }

        .role-card {
            flex: 1; margin: 0 10px; padding: 15px;
            background: white; border: 2px solid transparent;
            border-radius: 10px; text-align: center;
            cursor: pointer; transition: all 0.3s ease;
        
        }

        .role-card i { font-size: 24px; margin-bottom: 8px; color: var(--primary-blue); }
        
        .role-card.active { border-color: var(--primary-orange); background: #fff8f5; }
        
        .role-card.active i { color: var(--primary-orange); }

        .form-content { padding: 30px; display: none; }
        
        .form-content.active { display: block; }

        .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .form-group { margin-bottom: 15px; }
        
        .form-group.full-width { grid-column: span 2; }

        label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9rem; }
        input, select {
            width: 100%; padding: 12px;
            border: 1px solid #ddd; border-radius: 8px;
            outline: none; transition: border-color 0.3s;
        }

        input:focus { border-color: var(--primary-blue); }

        .btn-register, .btn-search {
            width: 100%; padding: 15px;
            background-color: var(--primary-orange);
            color: white; border: none; border-radius: 8px;
            font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-search { background-color: var(--primary-blue); margin-top: 27px; }

        .btn-register:disabled { background-color: #ccc; cursor: not-allowed; opacity: 0.7; }

        /* Style cho danh sách kết quả tìm kiếm sinh viên */
        #student-results {
            margin-top: 25px;
            border: 1px solid #eee;
            border-radius: 10px;
            background: #fafafa;
            display: none;
            padding: 15px;
        }

        .student-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .student-info h4 { color: var(--primary-blue); margin-bottom: 4px; }
        .student-info p { font-size: 0.85rem; color: #666; }

        .btn-action-create {
            background: var(--primary-orange);
            color: white; border: none;
            padding: 8px 16px; border-radius: 5px;
            cursor: pointer; font-weight: 600;
        }

        .terms-container {
            margin-top: 15px; font-size: 0.9rem;
            display: flex; align-items: flex-start; gap: 10px;
        }

        .terms-container input { width: 18px; height: 18px; cursor: pointer; }

        @media (max-width: 600px) {
            .grid-form { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
            .role-selection { flex-direction: column; }
            .role-card { margin-bottom: 10px; }
        }
    </style>
    <script>
        $(document).ready(function(){
		 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"employee_phone": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 8
			},
			"employee_national_id": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 5
			},
			"employee_name":{
				required: true
			},
			"employee_branch":{
				required: true
			},
			"employee_position":{
				required: true
			},
			"employee_address":{
				required: true
			},
			"employee_birthday":{
				required: true
			},
			"employee_gender":{
				required: true
			},
			"employee_email":{
				required: true
			},
			"employee_department":{
				required: true
			},
			"employee_issue_date":{
				required: true
			},
			"employee_issue_by":{
				required: true
			},
			"employee_issue_date":{
				required: true
			}
			
			
			
		},
		messages:{
				employee_national_id: {
					required: "Vui lòng số CMND",
					minlength: "số CMND phải vượt quá 5 ký tự",
					maxlength: "số CMND phải ngắn hơn 15 ký tự"
				},
				employee_phone: {
					required: "Vui lòng nhập số điện thoại",
					minlength: "Số điện thoại phải vượt quá 8 ký tự",
					maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
				},
				employee_name: "Vui lòng nhập tên nhân viên",
				employee_branch: "Vui lòng chọn đơn vị",
				employee_position: "Vui lòng chọn chức danh",
				employee_address: "Vui lòng nhập địa chỉ",
				employee_birthday: "Vui lòng nhập ngày sinh",
				employee_gender: "Vui lòng chọn giới tính",
				employee_email: "Vui lòng nhập email",
				employee_department: "Vui lòng chọn phòng ban",
				employee_issue_date: "Vui lòng nhập ngày cấp",
				employee_issue_by: "Vui lòng nhập nơi cấp"
				
			}
	});
		
		
    $("#add-student").click(function(e) {
    e.preventDefault();

    var s_code = $("#s_code").val();
    var s_name = $("#s_name").val();

    //Hiển thị loading ngay khi click
    Swal.fire({
        title: 'Đang kiểm tra...',
        text: 'Vui lòng chờ trong giây lát',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        type: "POST",
        url: "<?php echo XC_URL; ?>/api/addstudent",
        data: {
            student_code: s_code,
            student_name: s_name
        },
        dataType: 'json',

        success: function(data){
            // Delay 0.5s để tạo cảm giác đang xử lý
            setTimeout(function(){

                if(data.status == 200){
                    Swal.fire({
                        icon: 'success',
                        title: "Sinh viên: " + s_name,
                        text: "Tài khoản đã được tạo thành công",
                        footer: 'User: <b>'+data.username+'</b><br>Password: <b>'+data.default_password+'</b>',
                        timer: 10000
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cảnh báo',
                        text: data.message
                    });
                }

            }, 700); // 👈 0.5 giây
        },

        error: function(){
            Swal.fire({
                icon: 'error',
                title: 'Lỗi server',
                text: 'Không thể kết nối API'
            });
        }
    });
});
	
		
	
		
	});
    </script>
</head>
<body>

<div class="register-container">
    <div class="header">
        <h2>Tham gia cùng chúng tôi</h2>
        <p>Hệ thống kết nối việc làm chuyên nghiệp</p>
    </div>

    <div class="role-selection">
        <div class="role-card active" data-target="form-candidate">
            <i class="fas fa-user-tie"></i>
            <div>Ứng viên</div>
        </div>
        <div class="role-card" data-target="form-student">
            <i class="fas fa-user-graduate"></i>
            <div>Sinh viên</div>
        </div>
        <div class="role-card" data-target="form-employer">
            <i class="fas fa-building"></i>
            <div>Nhà tuyển dụng</div>
        </div>
    </div>

    <form id="form-candidate" class="form-content active">
        <div class="grid-form">
            <div class="form-group"><label>Họ và tên</label><input type="text" name="fullname" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mật khẩu</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Số điện thoại</label><input type="tel" name="phone" required></div>
        </div>
        <div class="terms-container">
            <input type="checkbox" class="terms-check">
            <label>Tôi đồng ý với <a href="#">điều khoản dịch vụ</a></label>
        </div>
        <button type="submit" class="btn-register" disabled>Đăng ký Ứng viên</button>
    </form>

    <form id="form-student" class="form-content">
        <div class="grid-form">
            <div class="form-group">
                <label>Mã số sinh viên</label>
                <input type="text" id="s_code" placeholder="Ví dụ: SV001">
            </div>
            <div class="form-group">
                <label>Họ và tên sinh viên</label>
                <input type="text" id="s_name" placeholder="Nhập tên để tìm...">
            </div>
            <div class="form-group full-width" style="margin-top: -10px;">
                <button type="button" class="btn-search" id='add-student'>
                    <i class="fas fa-search"></i> Tìm kiếm sinh viên
                </button>
            </div>
            
        </div>
        
        <div id="student-results">
            <p style="font-weight: 600; margin-bottom: 10px; font-size: 0.9rem;">Kết quả tìm kiếm:</p>
            <div id="student-list-box">
                </div>
        </div>
    </form>

    <form id="form-employer" class="form-content">
        <div class="grid-form">
            <div class="form-group"><label>Tên người liên hệ</label><input type="text" name="fullname" required></div>
            <div class="form-group"><label>Email công ty</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mật khẩu</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Số điện thoại</label><input type="tel" name="phone" required></div>
            <div class="form-group full-width"><label>Tên công ty</label><input type="text" name="company" required></div>
            <div class="form-group full-width"><label>Mã số thuế</label><input type="text" name="taxcode" required></div>
        </div>
        <div class="terms-container">
            <input type="checkbox" class="terms-check">
            <label>Tôi đồng ý với <a href="#">điều khoản dịch vụ</a></label>
        </div>
        <button type="submit" class="btn-register" disabled>Đăng ký Nhà tuyển dụng</button>
    </form>
</div>

<script>
    // Dữ liệu mẫu (Thay thế bằng kết quả từ Database sau này)
    const database_sinhvien = [
        { id: 101, code: "SV001", name: "Nguyễn Văn An", major: "Công nghệ thông tin" },
        { id: 102, code: "SV002", name: "Lê Thị Bình", major: "Kế toán" },
        { id: 103, code: "SV003", name: "Trần Minh Chiến", major: "Quản trị kinh doanh" }
    ];

    const roleCards = document.querySelectorAll('.role-card');
    const forms = document.querySelectorAll('.form-content');

    // Chuyển đổi giữa các Form
    roleCards.forEach(card => {
        card.addEventListener('click', () => {
            roleCards.forEach(c => c.classList.remove('active'));
            forms.forEach(f => f.classList.remove('active'));
            card.classList.add('active');
            const targetId = card.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // Hàm tìm kiếm sinh viên
    function handleStudentSearch() {
        const code = document.getElementById('s_code').value.trim().toUpperCase();
        const name = document.getElementById('s_name').value.trim().toLowerCase();
        const listBox = document.getElementById('student-list-box');
        const resultsContainer = document.getElementById('student-results');

        if (!code && !name) {
            Swal.fire('Chú ý', 'Vui lòng nhập Mã SV hoặc Tên để tìm kiếm', 'warning');
            return;
        }

        // Lọc dữ liệu
        const results = database_sinhvien.filter(s => 
            (code && s.code.includes(code)) || (name && s.name.toLowerCase().includes(name))
        );

        listBox.innerHTML = ''; // Clear cũ

        if (results.length > 0) {
            resultsContainer.style.display = 'block';
            results.forEach(student => {
                listBox.innerHTML += `
                    <div class="student-item">
                        <div class="student-info">
                            <h4>${student.name}</h4>
                            <p>Mã SV: ${student.code} | Ngành: ${student.major}</p>
                        </div>
                        <button type="button" class="btn-action-create" onclick="confirmCreateAccount('${student.name}')">
                            Tạo tài khoản
                        </button>
                    </div>
                `;
            });
        } else {
            resultsContainer.style.display = 'none';
            Swal.fire('Thông báo', 'Không tìm thấy sinh viên này trong hệ thống', 'info');
        }
    }

   

    // Logic validate cho Candidate và Employer (giữ nguyên của bạn)
    function validateForm(form) {
        const btn = form.querySelector('.btn-register');
        if (!btn) return;
        
        const terms = form.querySelector('.terms-check');
        const requiredInputs = form.querySelectorAll('input[required]');
        
        let allFilled = true;
        requiredInputs.forEach(input => {
            if (input.value.trim() === "") allFilled = false;
        });

        if (terms) {
            btn.disabled = !(allFilled && terms.checked);
        } else {
            btn.disabled = !allFilled;
        }
    }

    forms.forEach(form => {
        form.addEventListener('input', () => validateForm(form));
        const terms = form.querySelector('.terms-check');
        if (terms) terms.addEventListener('change', () => validateForm(form));
        
        form.addEventListener('submit', (e) => {
            if(form.id === 'form-student') return; // Sinh viên xử lý qua nút tìm kiếm/tạo riêng
            e.preventDefault();
            Swal.fire({
                title: 'Thành công!',
                text: 'Hệ thống đã ghi nhận thông tin đăng ký.',
                icon: 'success',
                confirmButtonColor: '#1760a5'
            });
        });
    });
    // Xác nhận tạo tài khoản với hiệu ứng xử lý (Loading)
function confirmCreateAccount(studentName) {
    Swal.fire({
        title: 'Xác nhận',
        text: `Hệ thống sẽ khởi tạo tài khoản cho sinh viên ${studentName}. Bạn có chắc chắn không?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1760a5',
        cancelButtonColor: '#ccc',
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Hiển thị trạng thái đang xử lý
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading(); // Hiển thị icon xoay tròn
                }
            });

            // Giả lập thời gian chờ 2.5 giây (2500ms)
            setTimeout(() => {
                Swal.fire({
                    title: 'Thành công',
                    text: `Tài khoản cho sinh viên ${studentName} đã được tạo thành công!`,
                    icon: 'success',
                    confirmButtonColor: '#1760a5'
                });
            }, 2500);
        }
    });
}
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký mua thuốc - Nhà thuốc Trường Cao Đẳng Kon Tum</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1760a5;
            --accent-orange: #e36928;
            --light-bg: #f4f7f9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 850px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-top: 6px solid var(--primary-blue);
        }

        h1 {
            color: var(--primary-blue);
            text-align: center;
            text-transform: uppercase;
            font-size: 1.4rem;
            margin-bottom: 30px;
            line-height: 1.4;
        }
         h3 {
            color: #e36928;
            text-align: center;
            text-transform: uppercase;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.4;
        }

        .section-title {
            background-color: var(--primary-blue);
            color: white;
            padding: 10px 15px;
            margin: 25px 0 15px 0;
            border-radius: 4px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .section-title i { margin-right: 10px; }

        .form-group { margin-bottom: 15px; position: relative; }

        label { display: block; margin-bottom: 5px; font-weight: 600; color: #444; }

        input[type="text"], input[type="tel"], select, textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input:focus, select:focus, textarea:focus { border-color: var(--accent-orange); outline: none; box-shadow: 0 0 5px rgba(227, 105, 40, 0.2); }

        .radio-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 10px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
        }

        #other_time_container { margin-top: 10px; display: none; animation: fadeIn 0.3s ease-in-out; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

        /* Style cho phần Search Autocomplete */
        .autocomplete-suggestions {
            position: absolute;
            z-index: 1000;
            background: #fff;
            width: 100%;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 4px 4px;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: none;
        }

        .suggestion-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .suggestion-item:hover { background-color: #f1f1f1; }

        .suggestion-item .item-name { font-weight: bold; color: var(--primary-blue); display: block; }

        .suggestion-item .item-spec { font-size: 0.85rem; color: #666; }

        .product-input-card { background: #fff; border: 2px dashed #ddd; padding: 15px; border-radius: 4px; margin-bottom: 20px; }

        .grid-3 { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 10px; }

        .btn-add { background-color: var(--primary-blue); color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; transition: 0.3s; }

        .btn-add:hover { background-color: #0f4a81; }

        .table-container { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: white; }

        th { background: #f2f2f2; color: var(--primary-blue); text-align: left; padding: 12px; border: 1px solid #ddd; }

        td { padding: 12px; border: 1px solid #ddd; font-size: 14px; }

        .btn-submit { background-color: var(--accent-orange); color: white; border: none; padding: 15px 30px; font-size: 16px; font-weight: bold; border-radius: 4px; cursor: pointer; width: 100%; margin-top: 30px; transition: 0.3s; }

        .btn-submit:hover { background-color: #c5561e; transform: translateY(-2px); }

        .note-box { font-size: 0.85rem; color: #666; font-style: italic; margin-top: 5px; }

        @media (max-width: 600px) {
            .grid-3 { grid-template-columns: 1fr; }
            .radio-group { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum</h1>
    <h3>PHIẾU ĐĂNG KÝ NHU CẦU MUA THUỐC VÀ SẢN PHẨM CHĂM SÓC SỨC KHỎE</h3>

    <form id="mainForm">
        <div class="section-title"><i class="fas fa-user-circle"></i> PHẦN 1: THÔNG TIN NGƯỜI ĐẶT MUA</div>
        <div class="form-group">
            <label>1. Họ và tên *</label>
            <input type="text" name="fullname" required placeholder="Nhập họ và tên">
        </div>

        <div class="form-group">
            <label>2. Bạn thuộc đối tượng nào?</label>
            <div class="radio-group">
                <label><input type="radio" name="user_type" value="Viên chức"> Viên chức</label>
                <label><input type="radio" name="user_type" value="Người lao động"> Người lao động</label>
                <label><input type="radio" name="user_type" value="Học sinh/Sinh viên"> Học sinh / Sinh viên</label>
                <label><input type="radio" name="user_type" value="Người thân"> Người thân VC-NLĐ-HSSV</label>
                <label><input type="radio" name="user_type" value="Khác"> Khách hàng khác</label>
            </div>
        </div>

        <div class="form-group">
            <label>3. Đơn vị / Lớp / Bộ phận công tác</label>
            <input type="text" name="department" placeholder="Nhập đơn vị công tác">
        </div>

        <div class="form-group">
            <label>4. Số điện thoại liên hệ *</label>
            <input type="tel" name="phone" required placeholder="Số điện thoại của bạn">
        </div>

        <div class="section-title"><i class="fas fa-map-marker-alt"></i> PHẦN 2: THÔNG TIN GIAO HÀNG</div>
        <div class="form-group">
            <label>5. Hình thức nhận hàng</label>
            <div class="radio-group">
                <label><input type="radio" name="ship_method" value="Nhà thuốc"> Nhận tại Nhà thuốc</label>
                <label><input type="radio" name="ship_method" value="Cơ quan"> Giao tại cơ quan / trường</label>
                <label><input type="radio" name="ship_method" value="Tận nhà"> Giao tận nhà</label>
            </div>
        </div>

        <div class="form-group">
            <label>6. Địa chỉ nhận hàng (Bắt buộc nếu chọn giao hàng)</label>
            <textarea name="address" rows="2" placeholder="Số nhà, tên đường, phường/xã..."></textarea>
        </div>

        <div class="form-group">
            <label>7. Thời gian mong muốn nhận hàng</label>
            <select name="receive_time" id="receive_time_select" onchange="toggleOtherTime()">
                <option value="Trong ngày">Trong ngày</option>
                <option value="Trong 24 giờ">Trong 24 giờ</option>
                <option value="Thời gian khác">Thời gian khác (ghi cụ thể)</option>
            </select>
            
            <div id="other_time_container">
                <label style="color: var(--accent-orange); margin-top: 10px;">Nhập thời gian cụ thể *</label>
                <input type="text" id="other_time_input" placeholder="Ví dụ: Sáng thứ 2, sau 17h...">
            </div>
        </div>

        <div class="section-title"><i class="fas fa-shopping-cart"></i> PHẦN 3: SẢN PHẨM CẦN MUA</div>
        
        <div class="product-input-card">
            <div class="grid-3">
                <div class="form-group">
                    <label>Tên thuốc/sản phẩm *</label>
                    <input type="text" id="p_name" placeholder="Nhập tên thuốc để tìm..." autocomplete="off">
                    <div id="p_suggestions" class="autocomplete-suggestions"></div>
                </div>
                <div class="form-group">
                    <label>Hàm lượng</label>
                    <input type="text" id="p_spec" placeholder="Ví dụ: 500mg">
                </div>
                <div class="form-group">
                    <label>Số lượng *</label>
                    <input type="text" id="p_qty" placeholder="1 vỉ/hộp">
                </div>
            </div>
            <div class="form-group">
                <label>Ghi chú cho sản phẩm này</label>
                <input type="text" id="p_note" placeholder="Ví dụ: Lấy loại của Pháp">
            </div>
            <button type="button" class="btn-add" onclick="addItem()">
                <i class="fas fa-plus-circle"></i> THÊM THUỐC
            </button>
        </div>

        <div class="table-container">
            <table id="productTable">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Hàm lượng</th>
                        <th>Số lượng</th>
                        <th>Ghi chú</th>
                        <th width="50px"></th>
                    </tr>
                </thead>
                <tbody id="listBody">
                    <tr id="emptyMsg">
                        <td colspan="5" style="text-align: center; color: #999; padding: 20px;">
                            Chưa có sản phẩm nào được chọn.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-title"><i class="fas fa-file-medical"></i> PHẦN 4: TẢI LÊN ĐƠN THUỐC</div>
        <div class="form-group">
            <label>Tải ảnh/PDF đơn thuốc (nếu có)</label>
            <input type="file" accept="image/*,.pdf">
            <p class="note-box">Vui lòng chụp rõ đơn thuốc để Nhà thuốc tư vấn chính xác nhất.</p>
        </div>

        <div class="section-title"><i class="fas fa-wallet"></i> PHẦN 5: THANH TOÁN</div>
        <div class="radio-group">
            <label><input type="radio" name="pay" value="COD"> Thanh toán sau khi nhận hàng COD</label>
            <label><input type="radio" name="pay" value="Direct"> Tại Nhà thuốc</label>
        </div>

        <div class="section-title"><i class="fas fa-edit"></i> PHẦN 6: GHI CHÚ THÊM</div>
        <div class="form-group">
            <textarea name="extra_note" rows="3" placeholder="Yêu cầu khác (Ví dụ: giao ngoài giờ...)"></textarea>
        </div>

        <div class="section-title"><i class="fas fa-check-double"></i> PHẦN 7: XÁC NHẬN</div>
        <div class="form-group">
            <label style="display: flex; gap: 10px; align-items: flex-start; cursor: pointer;">
                <input type="checkbox" required style="margin-top: 5px;">
                <span>Tôi xác nhận các thông tin trên là chính xác.</span>
            </label>
        </div>

        <button type="submit" class="btn-submit">GỬI PHIẾU ĐĂNG KÝ</button>
    </form>
</div>
<?php
$medicineData = [];

foreach ($products as $item) {
    $medicineData[] = [
        "name" => $item->product_name,
        // Nếu spec là NULL, ta để chuỗi rỗng hoặc giá trị mặc định
        "spec" => $item->product_spec ?? "" 
    ];
}

// Chuyển sang JSON để dùng cho JavaScript
$jsonResult = json_encode($medicineData, JSON_UNESCAPED_UNICODE);
?>
<script>

    // --- DANH SÁCH THUỐC MẪU (Dữ liệu này có thể lấy từ Database/API) ---
    const medicineData = <?php echo $jsonResult; ?>;
    console.log(medicineData);

    const pNameInput = document.getElementById('p_name');
    const pSpecInput = document.getElementById('p_spec');
    const suggestionsBox = document.getElementById('p_suggestions');

    // Xử lý tìm kiếm khi gõ phím
    pNameInput.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        suggestionsBox.innerHTML = '';
        
        if (!value) {
            suggestionsBox.style.display = 'none';
            return;
        }

        const filtered = medicineData.filter(m => m.name.toLowerCase().includes(value));

        if (filtered.length > 0) {
            filtered.forEach(item => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.innerHTML = `
                    <span class="item-name">${item.name}</span>
                    <span class="item-spec">${item.spec}</span>
                `;
                div.addEventListener('click', function() {
                    pNameInput.value = item.name;
                    pSpecInput.value = item.spec;
                    suggestionsBox.style.display = 'none';
                });
                suggestionsBox.appendChild(div);
            });
            suggestionsBox.style.display = 'block';
        } else {
            suggestionsBox.style.display = 'none';
        }
    });

    // Ẩn danh sách khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (e.target !== pNameInput) {
            suggestionsBox.style.display = 'none';
        }
    });

    function toggleOtherTime() {
        const select = document.getElementById('receive_time_select');
        const container = document.getElementById('other_time_container');
        const input = document.getElementById('other_time_input');
        
        if (select.value === 'Thời gian khác') {
            container.style.display = 'block';
            input.setAttribute('required', 'true');
        } else {
            container.style.display = 'none';
            input.removeAttribute('required');
            input.value = '';
        }
    }

    function addItem() {
        const name = pNameInput.value;
        const spec = pSpecInput.value;
        const qty = document.getElementById('p_qty').value;
        const note = document.getElementById('p_note').value;

        if (!name || !qty) {
            alert("Vui lòng điền Tên sản phẩm và Số lượng!");
            return;
        }

        const emptyMsg = document.getElementById('emptyMsg');
        if (emptyMsg) emptyMsg.remove();

        const tbody = document.getElementById('listBody');
        const row = tbody.insertRow();

        row.innerHTML = `
            <td>${name}</td>
            <td>${spec}</td>
            <td>${qty}</td>
            <td>${note}</td>
            <td style="text-align: center;">
                <button type="button" onclick="deleteItem(this)" style="color: #e74c3c; border:none; background:none; cursor:pointer;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        pNameInput.value = '';
        pSpecInput.value = '';
        document.getElementById('p_qty').value = '';
        document.getElementById('p_note').value = '';
        pNameInput.focus();
    }

    function deleteItem(btn) {
        btn.closest('tr').remove();
        const tbody = document.getElementById('listBody');
        if (tbody.rows.length === 0) {
            tbody.innerHTML = `<tr id="emptyMsg"><td colspan="5" style="text-align: center; color: #999; padding: 20px;">Chưa có sản phẩm nào được chọn.</td></tr>`;
        }
    }

    document.getElementById('mainForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert("Đã gửi đơn đăng ký thành công!");
    });
</script>

</body>
</html>
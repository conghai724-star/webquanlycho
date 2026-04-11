<?php require "header_new.php"; ?>

<style>
    /* Reset nhỏ gọn */
* {
    box-sizing: border-box;
}

/* Container */
.ctn {
    width: 100%;
    max-width: 1200px;
    padding: 0 20px;
    margin: 0 auto 40px auto;
}

/* Tiêu đề */
#pctHead {
    text-align: center;
    margin-bottom: 40px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: clamp(20px, 2.5vw, 32px);
}

/* Grid contact */
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 60px;
}

/* Block */
.contact-info-block,
.contact-map-block {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border: 1px solid #eef2f6;
    overflow: hidden;
}

/* Header block */
.sbHead {
    background-color: #1760a5;
    color: #fff;
    padding: 18px 20px;
    font-weight: 600;
    font-size: clamp(14px, 1.2vw, 18px);
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Contact content */
.contact-container {
    padding: 25px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
}

.icon-box {
    background-color: #e8f1f8;
    color: #1760a5;
    width: 45px;
    height: 45px;
    min-width: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 10px;
    transition: 0.3s;
}

.contact-item:hover .icon-box {
    background-color: #1760a5;
    color: #fff;
}

.content {
    font-size: clamp(14px, 1vw, 16px);
    line-height: 1.6;
    overflow-wrap: break-word;
}

.bold-text {
    font-weight: 700;
    color: #1760a5;
}

/* Map responsive */
#pctMaps-img {
    position: relative;
    width: 100%;
    padding-bottom: 60%;
    height: 0;
}

#pctMaps-img iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Form */
.form-container {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
    padding: 50px 40px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    border: 1px solid #eef2f6;
}

.form-container h2 {
    text-align: center;
    margin-bottom: 35px;
    font-size: clamp(18px, 2vw, 26px);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.form-column {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.form-container input,
.form-container textarea {
    width: 100%;
    padding: 14px 18px;
    border: 1px solid #e0e6ed;
    border-radius: 8px;
    font-size: 15px;
    background: #fcfdfe;
    transition: 0.3s;
}

.form-container input:focus,
.form-container textarea:focus {
    border-color: #1760a5;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(23,96,165,0.15);
    outline: none;
}

.form-container textarea {
    min-height: 180px;
    resize: vertical;
}

/* Button */
.btn-submit {
    margin-top: 25px;
    background: linear-gradient(135deg, #1760a5 0%, #69bce3 100%);
    color: #fff;
    border: none;
    padding: 14px 40px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(23,96,165,0.3);
}
.btn-wrap {
    text-align: center;
    margin-top: 30px;
}

/* ================= RESPONSIVE ================= */

/* Tablet */
@media (max-width: 992px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-container {
        padding: 40px 25px;
    }
}

/* Mobile */
@media (max-width: 576px) {
    .ctn {
        padding: 0 15px;
    }

    .contact-container {
        padding: 20px;
    }

    .icon-box {
        width: 40px;
        height: 40px;
    }

    .btn-submit {
        width: 100%;
        justify-content: center;
    }
}
</style>
</section>    
<div id="breadcrumbs">
   <div class="ctn">
      <div id="crumbs"><a href="index.html">Trang chủ</a> <span>/</span> <a class="current">Liên hệ</a></div>
   </div>
</div>
<div class="ctn">
    <h1 id="pctHead" style="color:#1760a5;">THÔNG TIN LIÊN HỆ & GÓP Ý</h1>

    <div class="contact-grid">
        <div class="contact-info-block">
            <div class="sbHead">
                <i class="fa-solid fa-circle-info"></i>
                Phòng khám Đa khoa & Nhà thuốc Trường Cao đẳng Kon Tum
            </div>
            <div class="contact-container">
                <div class="contact-item">
                    <div class="icon-box"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="content">
                        <span class="bold-text">Địa chỉ:</span><br>
                        <?php echo $this->helper->get_config('site_address'); ?>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="icon-box"><i class="fa-solid fa-phone"></i></div>
                    <div class="content">
                        <span class="bold-text">Hotline:</span><br>
                        <?php echo $this->helper->get_config('site_hotline'); ?>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="icon-box"><i class="fa-solid fa-envelope"></i></div>
                    <div class="content">
                        <span class="bold-text">Email:</span><br>
                        <?php echo $this->helper->get_config('site_email'); ?>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="icon-box"><i class="fa-solid fa-clock"></i></div>
                    <div class="content">
                        <span class="bold-text">Giờ làm việc:</span><br>
                        <i class="fa-regular fa-circle-check" style="color: #27ae60;"></i> <b>Phòng khám Đa khoa: </b><br> - Sáng 07:00 - 11:00 <br> - Chiều 13:00 - 17:00 <br>(Thứ Hai - Thứ Sáu)<br>
                        <i class="fa-regular fa-circle-check" style="color: #27ae60;"></i> <b>Nhà thuốc:</b> <br> 07:00 - 21:00 (Hằng ngày)
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-map-block">
            <div class="sbHead">
                <i class="fa-solid fa-map-location-dot"></i>
                Hướng dẫn chỉ đường
            </div>
            <div id="pctMaps-img">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d966.3126413926132!2d108.001433!3d14.354906000000001!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x316bffa0ba9f2cb5%3A0x66766298d169597f!2zMzQ3IELDoCBUcmnhu4d1LCBQaMaw4budbmcgUXV54bq_dCBUaOG6r25nLCBLb24gVHVtLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2sus!4v1772011905251!5m2!1svi!2sus" referrerpolicy="no-referrer-when-downgrade" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <div class="form-container">
        <h2>PHẢN HỒI KHÁCH HÀNG</h2>

       <form action="#" method="POST" id="myForm" onsubmit="return false;">
            <div class="form-row">
                <div class="form-column">
                    <input type="text" placeholder="Họ tên *" id='customer_name' name='customer_name'>
                    <input type="text" placeholder="Điện thoại *" id='customer_phone' name='customer_phone'>
                    <input type="email" placeholder="Email" id='customer_email'>
                    <input type="text" placeholder="Địa chỉ" id='customer_address'>
                </div>

                <div class="form-column">
                    <textarea placeholder="Ý kiến của bạn... *" id='content' name='content'></textarea>
                </div>
            </div>
           <div class="btn-wrap">
                <button type="submit" class="btn-submit" id='send'>
                    GỬI THÔNG TIN <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require "footer_new.php"; ?>
<script>
    jQuery(function($) {
    $(document).ready(function(){
         $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#myForm").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"customer_name": {
				required: true
			},
			"customer_phone": {
				required: true
			},
			"content":{
				required: true
			}
			
		},
		messages:{
				customer_name: {
					required: "Bạn chưa nhập họ và tên"
				},
				customer_phone: {
					required: "Bạn chưa nhập số điện thoại"
				},
				content: "Bạn chưa nhập ý kiến phản hồi"
				
			}
	});
		
		$('#send').click(function(e) {
		    if ($("#myForm").valid()) {
				var formData = new FormData();
				formData.append('customer_name', $('#customer_name').val());
				formData.append('customer_phone', $('#customer_phone').val());
				formData.append('customer_email', $('#customer_email').val());
				formData.append('customer_address', $('#customer_address').val());
				formData.append('content', $('#content').val());
				formData.append('rating', 0);
				
				
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addFeedback",
				data:formData,
				dataType: 'json',
				enctype: 'multipart/form-data',
				processData: false,
				contentType: false,
				success: function(data){
					if (data.status == 200) {
						console.log(data);
						let timerInterval;

						Swal.fire({
							icon: 'success',
							title: 'Gửi phản hồi thành công!',
							html: data.message, // Bạn có thể thay bằng data.message
							footer: 'Hệ thống tự động chuyển hướng sau <b id="countdown" style="color:red; padding: 0 5px;">5</b> giây',
							timer: 5000,
							timerProgressBar: true,
							allowOutsideClick: false, // Ngăn người dùng tắt thông báo sớm
							didOpen: () => {
								const b = Swal.getFooter().querySelector('#countdown');
								let timeLeft = 10;
								timerInterval = setInterval(() => {
									timeLeft--;
									if (b) b.textContent = timeLeft;
								}, 1000);
							},
							willClose: () => {
								clearInterval(timerInterval);
							}
						}).then((result) => {
							// Sau 5 giây hoặc khi bấm OK sẽ nhảy về trang chủ
							window.location.href = '<?php echo XC_URL;?>'; // Thay index.php bằng link trang chủ của bạn
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Lỗi',
							text: data.message
						});
					}
				}
			});
		    }
			
		});
// 	flatpickr("#booking_date", {
//     dateFormat: "d/m/Y",
//     minDate: "today", // Không cho chọn ngày quá khứ
//     locale: "vn"      // Nếu muốn tiếng Việt
// });
		

	});
		});
</script>

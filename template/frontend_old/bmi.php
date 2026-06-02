<?php include "header_new.php";?>
<style>
        #bmi-calculator-plugin {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
            display: grid;
            /*grid-template-columns: 2fr 1fr;*/
            gap: 20px;
            color: #333;
        }

        #bmi-calculator-plugin .header-title {
            grid-column: 1 / -1;
            color: #1760a5;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        #bmi-calculator-plugin .input-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        #bmi-calculator-plugin .bmi-card {
            border: 1px solid #e1e1e1;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            background: #fff;
            position: relative;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        #bmi-calculator-plugin .bg-icon {
            position: absolute;
            right: 10px;
            bottom: 10px;
            opacity: 0.05;
            z-index: 1;
        }

        #bmi-calculator-plugin .card-label {
            color: #1760a5;
            font-weight: bold;
            margin-bottom: 15px;
            z-index: 2;
        }

        #bmi-calculator-plugin .gender-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            z-index: 2;
        }

        #bmi-calculator-plugin .gender-item {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            color: #666;
            font-size: 14px;
        }

        #bmi-calculator-plugin .value-num {
            font-size: 28px;
            font-weight: bold;
            z-index: 2;
        }

        #bmi-calculator-plugin .unit { font-size: 16px; margin-left: 2px; }

        #bmi-calculator-plugin input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
            outline: none;
            margin-top: 10px;
        }

        #bmi-calculator-plugin input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            background: #fff;
            border: 2px solid #1760a5;
            border-radius: 50%;
            cursor: pointer;
        }

        #bmi-calculator-plugin .btn-group {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        #bmi-calculator-plugin .btn {
            padding: 10px 25px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        #bmi-calculator-plugin .btn-calc { background: #3498db; color: white; }
        #bmi-calculator-plugin .btn-reset { background: #fff; border: 1px solid #ccc; color: #666; }

        #bmi-calculator-plugin .result-box {
            margin-top: 25px;
            display: flex;
            background-color: #f0f7ff;
            border: 1px solid #e1e1e1;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        #bmi-calculator-plugin .result-val {
            background-color: #ececec;
            width: 90px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: bold;
            color: #e36928;
        }

        #bmi-calculator-plugin .result-desc {
            padding: 15px;
            font-size: 14px;
            display: flex;
            align-items: center;
            flex: 1;
        }

        #bmi-calculator-plugin .sidebar-blue {
            background-color: #1760a5;
            color: white;
            padding: 25px;
            border-radius: 4px;
            text-align: center;
        }

        #bmi-calculator-plugin .hotline-num {
            font-size: 24px;
            font-weight: bold;
            display: block;
            margin: 10px 0;
            color: #3498db;
        }

        #bmi-calculator-plugin .btn-contact {
            background-color: #3498db;
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            #bmi-calculator-plugin { grid-template-columns: 1fr; }
            #bmi-calculator-plugin .input-grid { grid-template-columns: 1fr; }
        }
    </style>
</section>	
<div id="breadcrumbs">
   <div class="ctn">
      <div id="crumbs"><a href="index.html">Trang chủ</a> <span>/</span> <a class="current"><?php echo $introduce->introduce_name; ?></a></div>
   </div>
</div>
<div class="ctn">
   <div class="row">
      <div id="main" class="col-12 clg-8">
         <article id="catePts">
            <div id="pstDetail">
               <!-- <h1 id="pstpTitle"></h1> -->
               <div id="pstCntn">
                  <div id="bmi-calculator-plugin">
    <div class="bmi-left-col">
      <p>  <b class="header-title" style='text-align:center; color: #e36928;'>Đo chỉ số cân nặng - chiều cao (BMI) Online</b></p>
        
        <div class="input-grid">
            <div class="bmi-card">
                <span class="card-label">Giới tính của bạn</span>
                <div class="gender-group">
                    <label class="gender-item"><input type="radio" name="sex" checked> Nam</label>
                    <label class="gender-item"><input type="radio" name="sex"> Nữ</label>
                </div>
            </div>

            <div class="bmi-card">
                <span class="card-label">Chiều cao của bạn</span>
                <div class="value-num"><span id="lH">0</span><span class="unit">cm</span></div>
                <input type="range" id="iH" min="0" max="220" value="0" oninput="u()">
                <svg class="bg-icon" width="60" height="60" viewBox="0 0 24 24"><path d="M7,2H17A2,2 0 0,1 19,4V20A2,2 0 0,1 17,22H7A2,2 0 0,1 5,20V4A2,2 0 0,1 7,2M7,4V8H10V9H7V11H12V12H7V14H10V15H7V17H12V18H7V20H17V4H7Z"/></svg>
            </div>

            <div class="bmi-card">
                <span class="card-label">Cân nặng của bạn</span>
                <div class="value-num"><span id="lW">0</span><span class="unit">kg</span></div>
                <input type="range" id="iW" min="0" max="150" value="0" oninput="u()">
                <svg class="bg-icon" width="60" height="60" viewBox="0 0 24 24"><path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12C20,12.5 19.96,13 19.88,13.5L18,12V11H17V10H16V9H15V8H14V7H10V8H9V9H8V10H7V11H6V12L4.12,13.5C4.04,13 4,12.5 4,12A8,8 0 0,1 12,4Z"/></svg>
            </div>
        </div>

        <div class="btn-group">
            <button class="btn btn-calc" onclick="calc()">Xem kết quả</button>
            <button class="btn btn-reset" onclick="reset()">Đặt lại</button>
        </div>

        <div class="result-box" id="resBox">
            <div class="result-val" id="rV">0</div>
            <div class="result-desc" id="rM">
                Điều chỉnh thanh trượt chiều cao và cân nặng để xem kết quả (BMI) Online
            </div>
        </div>
    </div>
</div>
                  
                  
                  <?php echo $introduce->introduce_content; ?>
                 </div>
            </div>
         </article>
         
      </div>
      <!-- sidebar -->
      <aside id="sidebar" class="col-12 clg-4 hidden_mobi">
         <!-- <div id="sbSrch" class="sbbox">
            <form class="sbSearch" method="get" action="https://phongkhamcdkontum.com.vn" role="search">
                <input class="search-input" type="search" name="s" placeholder="Tìm kiếm">
                <button class="search-submit" type="submit" role="button"><i class="fas fa-search"></i></button>
            </form>
            </div> -->
         <div class="sbsticky">
            <div id="sbPost" class="sbbox">
                
               <div class="sbHead">
                  <i class="sbHead-ic allicon"></i>
                  <span style="color: #fff;">Tin mới nhất</span>
               </div>
               <div class="sbCntn">
                <?php foreach($events as $event){?>
                  <a href="<?php echo $this->helper->permalink($event->id,'event'); ?>" class="sbPost">
                     <div class="sbPost-thumb">
                        <img data-src="<?php echo XC_URL; ?>/uploads/events/<?php echo $event->event_image; ?>" alt="<?php echo $event->event_name; ?>">
                     </div>
                     <div class="sbPost-inf">
                        <h5 class="sbPost-tit" style="color: #fff;"><?php echo $this->helper->limit_text($event->event_description,'50'); ?></h5>
                        <!-- <div class="sbPost-meta">
                           <span><i class="far fa-clock"></i> 09/02/2026</span>
                           <span><i class="fas fa-eye"></i> 16</span>   
                           </div> -->
                     </div>
                  </a>
                  <?php }?>
                  
               </div>
            </div>
            
         </div>
      </aside>
      <!-- /sidebar -->		
   </div>
</div>
</main>
<?php include "footer_new.php";?>
<script>
        function u() {
            document.getElementById('lH').innerText = document.getElementById('iH').value;
            document.getElementById('lW').innerText = document.getElementById('iW').value;
            // Reset màu box nếu người dùng đang điều chỉnh
            document.getElementById('resBox').style.backgroundColor = "#f0f7ff";
        }

        function calc() {
            const hVal = document.getElementById('iH').value;
            const wVal = document.getElementById('iW').value;
            const resVal = document.getElementById('rV');
            const resMsg = document.getElementById('rM');
            const resBox = document.getElementById('resBox');

            // Ràng buộc kiểm tra
            if (hVal == 0 || wVal == 0) {
                resVal.innerText = "!";
                resVal.style.color = "#e36928";
                resMsg.innerHTML = "<span style='color: #d93025; font-weight: bold;'>Cảnh báo: Vui lòng điều chỉnh chiều cao và cân nặng của bạn để tính BMI!</span>";
                resBox.style.backgroundColor = "#fff0f0"; // Đổi nền sang đỏ nhạt để cảnh báo
                return;
            }

            // Nếu đã chọn, tiến hành tính toán
            const hMet = hVal / 100;
            const bmi = (wVal / (hMet * hMet)).toFixed(1);
            
            resVal.innerText = bmi;
            resVal.style.color = "#e36928";
            resBox.style.backgroundColor = "#f0f7ff";

            let comment = "";
            if (bmi < 18.5) comment = " Chỉ số BMI cho thấy: Bạn đang thiếu cân (Gầy).";
            else if (bmi < 24.9) comment = " Chỉ số cơ thể bình thường. Tuyệt vời!";
            else if (bmi < 29.9) comment = " Chỉ số BMI cho thấy: Bạn đang bị thừa cân.";
            else comment = " Chỉ số BMI cho thấy: Bạn đang ở mức béo phì.";

            resMsg.innerHTML = "<b>Nhận xét BMI của bạn:</b> " + comment;
        }

        function reset() {
            document.getElementById('iH').value = 0;
            document.getElementById('iW').value = 0;
            u();
            document.getElementById('rV').innerText = "0";
            document.getElementById('rV').style.color = "#999";
            document.getElementById('rM').innerText = "Điều chỉnh thanh trượt chiều cao và cân nặng để xem kết quả (BMI) Online";
            document.getElementById('resBox').style.backgroundColor = "#f0f7ff";
        }
    </script>
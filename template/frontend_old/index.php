<?php include "header_new.php"; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
   .date-input {
    position: relative;
    width: 100%;
}

.date-input input {
    width: 100%;
    padding-right: 42px; /* chừa chỗ icon */
}

.calendar-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: #60a5fa;
    cursor: pointer;
    pointer-events: auto;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const fp = flatpickr("#ngaykham", {
        dateFormat: "d/m",
        allowInput: false,
        clickOpens: true,
        disableMobile: true
    });

    document.querySelector("#ngaykham").addEventListener("click", function () {
        fp.open();
    });
</script>
</section>    
<section id="sec1">
   <div class="ctn">
      <div class="tac">
         <div class="secHead">
            <i class="fa fa-hospital"></i>
            <span>Chuyên khoa điều trị</span>
         </div>
      </div>
      <div id="sec1Wrp">
         <?php foreach($chuyenkhoa as $chuyenkhoa){ ?>
         <a href="#" class="sec1Box">
         <span class="sec1Box-ic"><img id='cateIc-2' src="<?php echo XC_URL; ?>/uploads/images/<?php echo $chuyenkhoa->depart_image; ?>"></span>
         <b><?php echo $chuyenkhoa->depart_name; ?></b>
         </a>
         <?php } ?>
      </div>
      <div class="sec1Info row">
   <div class="col-12 clg-5">
      <div class="sec1Info-thumb">
         <img src="<?php echo XC_URL; ?>/uploads/images/<?php echo $this->helper->get_images(4)->image_url; ?>" alt="<?php echo $this->helper->get_images(4)->image_name; ?>">
      </div>
   </div>
   <div class="col-12 clg-7">
         <div class="sec1Info-content">
            <h3 style="color:#1760a5; font-weight: bold; margin-bottom: 5px;">PHÒNG KHÁM ĐA KHOA & NHÀ THUỐC</h3>
            <h2 style="color:#e36928; font-weight: 800; margin-bottom: 15px;">TRƯỜNG CAO ĐẲNG KON TUM</h2>
            <p style="line-height: 1.6; color: #444; margin-bottom: 25px;"> 
               <?php echo $this->helper->get_config('website_description'); ?>
            </p>
            
            <div class="sec1Info-action">
               <a href="<?php echo XC_URL; ?>/dang-ky-lich-kham.html" class="btn-booking">
                  <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>
                  ĐẶT HẸN KHÁM NGAY
               </a>
            </div>
         </div>
      </div>
   </div>
   </div>
</section>
<!--<section id="sec2" class="bgGreen">-->
<!--   <div class="ctn">-->
<!--      <div class="tac">-->
<!--         <div class="secHead">-->
<!--            <i class="allicon" id="sec2Ic-1"></i>-->
<!--            <span>Ưu đãi hấp dẫn</span>-->
<!--         </div>-->
<!--      </div>-->
<!--      <div id="sec2Wrp">-->
<!--                <a href="https://chat.bstuvan.com.vn/LR/Chatpre.aspx?id=MMW77888888&lng=en" title="Phòng khám và nhà thuốc trường Cao đẳng kon tum" class="sec2Box">-->
<!--                    <img data-lazy="https://phongkhamcdkontum.com.vn/sharing/anh-shortcode-uudai/tong/uudai-nk.jpg" alt="">-->
<!--                </a>-->
<!--                <a href="https://chat.bstuvan.com.vn/LR/Chatpre.aspx?id=MMW77888888&lng=en" title="Phòng khám và nhà thuốc trường Cao đẳng kon tum" class="sec2Box">-->
<!--                    <img data-lazy="https://phongkhamcdkontum.com.vn/sharing/anh-shortcode-uudai/tong/uudai-pk.jpg" alt="">-->
<!--                </a>-->
<!--                <a href="https://chat.bstuvan.com.vn/LR/Chatpre.aspx?id=MMW77888888&lng=en" title="Phòng khám và nhà thuốc trường Cao đẳng kon tum" class="sec2Box">-->
<!--                    <img data-lazy="https://phongkhamcdkontum.com.vn/sharing/anh-shortcode-uudai/tong/uudai-bxh.jpg" alt="">-->
<!--                </a>-->
<!--                <a href="https://chat.bstuvan.com.vn/LR/Chatpre.aspx?id=MMW77888888&lng=en" title="Phòng khám và nhà thuốc trường Cao đẳng kon tum" class="sec2Box">-->
<!--                    <img data-lazy="https://phongkhamcdkontum.com.vn/sharing/anh-shortcode-uudai/tong/uudai-tri.jpg" alt="">-->
<!--                </a>-->
<!--            </div>-->
<!--   </div>-->
<!--</section>-->
<!--<section id="sec3" class="bgGray">-->
<!--   <div class="ctn">-->
<!--     <div id="sec3Wrp" class="row">-->
<!--                <div class="col-12 clg-12">-->
<!--                    <div class="tac">-->
<!--                        <div class="secHead">-->
<!--                            <i class="allicon" id="sec3Ic-1"></i>-->
<!--                            <span>Không gian phòng khám</span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-12 clg-5">-->
<!--                    <div id="bsWrp">-->
<!--                        <div class="bg-home">-->
<!--                            <img src="https://phongkhamcdkontum.com.vn/wp-content/themes/dkcd/img/home/pic.jpg" alt="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

                <!-- <div class="is_mobi" id="line2W"></div> -->

<!--                <div class="col-12 clg-7">-->
<!--                    <div id="mtpkWrp" class="">-->
<!--                     <?php foreach ($pic as $pic) {?>-->
<!--                    <div class="mtpkBox" style="" aria-hidden="true" tabindex="-1" role="option" aria-describedby="slick-slide30">-->
                     
<!--                            <img src="<?php echo XC_URL; ?>/uploads/images/<?php echo $pic->image_url; ?>" alt="<?php echo $pic->image_name; ?>">-->
                           
<!--                        </div>-->
<!--                         <?php } ?>-->
                       
<!--                        </div>-->
<!--                </div>-->

<!--            </div>-->
<!--   </div>-->
<!--</section>-->
<section id="sec7" class="bgGreen">
   <div class="ctn">
      <div class="tac">
         <div class="secHead">
            <i class="allicon" id="sec3Ic-2"></i>
            <span>Đội ngũ bác sĩ</span>
         </div>
      </div>
      <div id="sec7Wrp">
        <?php foreach($employee as $employee){ ?>
         <div class="slick-slide sec2Box">
            <div class="bsBox-home">
               <div class="bsBox-home-img">
                  <img src="<?php echo XC_URL; ?>/uploads/doctors/<?php echo $employee-> employee_image;?>" alt="<?php echo $employee-> employee_name;?>">
               </div>
               <div class="bsBox-home-inf">
                  <div class="bsBox-home-name"><span><?php echo $employee-> employee_name;?></span></div>
                  <span>Chuyên khoa: <b><?php echo $employee-> depart_name;?></b></span>
               </div>
               <!-- <div class="bsBox-home-exp">
                  <ul class="bs-exp-list">
                     <li><span>Chuyên môn</span> <b>Phụ khoa</b></li>
                     </b>
                  </ul>
               </div> -->
               <a href="<?php echo $this->helper->permalink($employee->employeeid,'doctors');?>" class="rmBtn bsBox-link">Xem thêm bác sĩ <i class="far fa-angle-double-right"></i></a>
            </div>
        </div>
        <?php } ?>
        
         
        
         
         
      </div>
   </div>
</section>
<section id="sec8">
   <div class="ctn">
      <div class="tac">
         <div class="secHead">
            <i class="fas fa-medkit"></i>
            <span>ưu điểm của phòng khám</span>
         </div>
      </div>
      <div id="sec8Wrp">
         <ul id="sec8S">
            <li class="sec8S-item current" data-id="#tab6">Đội ngũ bác sĩ <br> giàu kinh nghiệm</li>
            <li class="sec8S-item" data-id="#tab1">Quy trình khám chữa bệnh nhanh chóng</li>
            <li class="sec8S-item" data-id="#tab3">trang thiết bị <br>hiện đại</li>
            <li class="sec8S-item" data-id="#tab5">Chi phí điều<br>trị hợp lý</li>
            <li class="sec8S-item" data-id="#tab2">Bảo mật<br>thông tin</li>
         </ul>
         <div id="sec8B">
            <div class="sec8B-item" id="tab1">
               <img src="<?php echo $template_path; ?>/assets/themes/dkcd/img/home/p3_1.png" alt="">
               <div class="sec8B-text">
                  <div class="sec8B-tit">QUY TRÌNH KHÁM CHỮA BỆNH NHANH CHÓNG – KHOA HỌC – HIỆU QUẢ.</div>
                  <p>Phòng khám xây dựng quy trình tiếp nhận và khám chữa bệnh khoa học, rõ ràng và tối ưu từng khâu. Thủ tục đơn giản, hướng dẫn cụ thể, phân luồng hợp lý giúp người bệnh hạn chế thời gian chờ đợi, được thăm khám kịp thời và thuận tiện.</p>
                <p>Các bước từ khám lâm sàng, cận lâm sàng đến tư vấn điều trị đều được thực hiện đồng bộ, bảo đảm tính chính xác và liên tục. Kết quả được trả nhanh chóng, giải thích rõ ràng, giúp người bệnh chủ động trong quá trình chăm sóc sức khỏe.</p>
          <p> Chúng tôi tối ưu quy trình để mỗi lần thăm khám đều là một trải nghiệm chuyên nghiệp, tiết kiệm thời gian và hiệu quả.</p>
               </div>
            </div>
            <div class="sec8B-item" id="tab2">
               <img src="<?php echo $template_path; ?>/assets/themes/dkcd/img/home/p1.jpg" alt="">
               <div class="sec8B-text">
                  <div class="sec8B-tit">BẢO MẬT THÔNG TIN – TÔN TRỌNG – TRÁCH NHIỆM</div>
                  <p>Mọi thông tin cá nhân và hồ sơ bệnh án của người bệnh đều được quản lý và lưu trữ theo quy trình bảo mật chặt chẽ, tuân thủ quy định của ngành y tế.</p>
                <p>Phòng khám cam kết không cung cấp, chia sẻ thông tin khi chưa có sự đồng ý của người bệnh, bảo đảm quyền riêng tư và sự an tâm trong suốt quá trình thăm khám và điều trị.</p>
                <p>Tôn trọng người bệnh không chỉ trong chuyên môn mà còn trong trách nhiệm bảo vệ thông tin cá nhân.
</p>
               </div>
            </div>
            <div class="sec8B-item" id="tab3">
               <img src="<?php echo $template_path; ?>/assets/themes/dkcd/img/home/p5.png" alt="">
               <div class="sec8B-text">
                  <div class="sec8B-tit">TRANG THIẾT BỊ HIỆN ĐẠI – ĐỒNG BỘ – AN TOÀN.</div>
                  <p>Phòng khám đầu tư hệ thống trang thiết bị y tế hiện đại, được kiểm định và bảo trì định kỳ theo quy định. Máy móc chẩn đoán và xét nghiệm được cập nhật phù hợp với yêu cầu chuyên môn, hỗ trợ phát hiện sớm và theo dõi chính xác tình trạng bệnh lý.</p>
                  <p>Không gian khám chữa bệnh được bố trí khoa học, bảo đảm vô trùng, an toàn và thân thiện.</p>  
                   <p> Sự kết hợp giữa chuyên môn bác sĩ và công nghệ y tế hiện đại giúp nâng cao chất lượng chẩn đoán và hiệu quả điều trị.</p>

               </div>
            </div>
            <div class="sec8B-item" id="tab5">
               <img src="<?php echo $template_path; ?>/assets/themes/dkcd/img/home/p4.jpg" alt="">
               <div class="sec8B-text">
                  <div class="sec8B-tit">CHI PHÍ ĐIỀU TRỊ HỢP LÝ – MINH BẠCH – CÔNG KHAI.</div>
                  <p>Phòng khám thực hiện công khai, minh bạch các khoản chi phí khám và điều trị theo đúng quy định. Người bệnh được tư vấn rõ ràng trước khi thực hiện các dịch vụ, bảo  đảm quyền được biết và được lựa chọn.</p>
               <p> Phác đồ điều trị được xây dựng phù hợp với tình trạng bệnh lý và điều kiện thực tế, tối ưu hiệu quả nhưng vẫn giảm thiểu gánh nặng tài chính.</p>
               <p> Chúng tôi hướng đến mục tiêu cung cấp dịch vụ y tế chất lượng với chi phí hợp lý, để chăm sóc sức khỏe không còn là nỗi lo của người bệnh.
.</p>
               </div>
            </div>
            <div class="sec8B-item tab-show" id="tab6">
               <img src="<?php echo $template_path; ?>/assets/themes/dkcd/img/home/pic_7.jpg" alt="">
               <div class="sec8B-text">
                  <div class="sec8B-tit">ĐỘI NGŨ BÁC SĨ CHUYÊN NGHIỆP – TẬN TÂM – CHUẨN MỰC.</div>
                  <p>Phòng khám tự hào quy tụ đội ngũ bác sĩ giàu kinh nghiệm, từng công tác trong môi trường quân đội và các cơ sở y tế uy tín trong khu vực. Với nền tảng rèn luyện kỷ luật nghiêm túc, tác phong chuẩn mực và tinh thần trách nhiệm cao, các bác sĩ luôn đặt y đức và sự an toàn của người bệnh lên hàng đầu. Bên cạnh đó là đội ngũ bác sĩ – giảng viên có trình độ chuyên môn sâu, nhiều năm giảng dạy và trực tiếp khám chữa bệnh. Sự kết hợp giữa kiến thức học thuật cập nhật và kinh nghiệm lâm sàng thực tiễn giúp nâng cao độ chính xác trong chẩn đoán, tối ưu phác đồ điều trị và mang lại hiệu quả chăm sóc sức khỏe người bệnh toàn diện. Chúng tôi không chỉ khám bệnh, mà còn đồng hành cùng người bệnh trên hành trình bảo vệ và nâng cao sức khỏe</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<section id="sec4" class="bgGreen">
   <div class="ctn">
      <div class="tac">
         <div class="secHead">
            <i class="allicon" id="sec4Ic-1"></i>
            <span>Bài viết nổi bật</span>
         </div>
      </div>
      <div id="sec4Wrp" class="row">
         <?php foreach($events as $event){ ?>
         <div class="col-12 clg-3 item-0">
            <div class="sec4Box">
               <a href="<?php echo $this->helper->permalink($event->id,'event');?>" class="sec4Box-thumb">
               <img src="<?php echo XC_URL; ?>/uploads/events/<?php echo $event->event_image; ?>" alt="<?php echo $event->event_name; ?>">
               </a>
               <div class="sec4Box-txt">
                  <a href="<?php echo $this->helper->permalink($event->id,'event');?>" class="sec4Box-name"><?php echo $event->event_name; ?></a>
                  <p><?php echo $this->helper->limit_text($event->event_description,'50'); ?></p>
               </div>
            </div>
         </div>
         <?php } ?>
         
         
         
         
      </div>
   </div>
</section>
<section id="sec5">
   <div class="ctn">
      <div class="tac">
         <div class="secHead">
            <i class="allicon" id="sec5Ic-1"></i>
            <span>Phản hồi của bệnh nhân</span>
         </div>
      </div>
      <div id="phbnWrp">
         <div class="slick-slide phbnBox">
            <p>Mình có đặt lịch trước nên dễ dàng vào khám theo mã số và thời gian đặt lịch, không phải đợi lâu, thủ tục nhanh gọn không mất thời gian như ở bệnh viện, được bác sĩ, dược sĩ tư vấn nhiệt tình và kê đơn thuốc nên mình rất yên tâm. Phòng khám khang trang sạch sẽ, nhân viên lễ phép, mình đã đăng ký khám phụ khoa định kỳ tại đây.</p>
            <div class="phbnBox-inf">
               <img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/bn-2.png" alt="" class="phbnBox-thumb">
               <div class="phbnBox-txt">
                  <b>Chị L.A</b> 30 tuổi, Kon Rẫy - Quảng Ngãi
               </div>
            </div>
         </div>
         
         
         <div class="slick-slide phbnBox">
            <p>Chưa bao giờ đi khám bệnh mà tôi được đội ngũ nhân viên y tá hỗ trợ nhiệt tình như thế. Thủ tục giấy tờ rõ ràng, minh bạch, chi phí điều trị tôi thấy cũng có nhiều ưu đãi. Khi thăm khám các bác sĩ cũng nhẹ nhàng hỗ trợ để giúp tôi hiểu rõ hơn về tình trạng bản thân đang mắc phải.</p>
            <div class="phbnBox-inf">
               <img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/bn-3.png" alt="" class="phbnBox-thumb">
               <div class="phbnBox-txt">
                  <b>Anh N.T.H</b> 36 tuổi - Kon Tum, Quảng Ngãi
               </div>
            </div>
         </div>
         
      </div>
      <div id="videoPk">
          
         <img src="<?php echo $template_path; ?>/assets/themes/dkcd/img/webp/home/iframe_video.webp" alt="" >
         <div class="bgVideo">
           
             <iframe
                src="https://www.youtube.com/embed/a3FbMqt0QDA?autoplay=1&mute=1&controls=0&rel=0&modestbranding=1&fs=0&iv_load_policy=3&loop=1&playlist=a3FbMqt0QDA"
                allow="autoplay"
                allowfullscreen style='width:100%; height:100%; pointer-events: none;'>
            </iframe>
           
         </div>
      </div>
   </div>
   <div class="bgGreen1">
      <div class="ctn">
         <div id="sec5Wrp" class="row">
            <div class="col">
               <div class="sec5Box">
                  <div class="sec5Box-ic"><i id="sec5Ic-2" class="allicon ic3"></i></div>
                  <span>Đội ngũ bác sĩ nhiều năm kinh nghiệm.</span>
               </div>
            </div>
            <div class="col">
               <div class="sec5Box">
                  <div class="sec5Box-ic"><i id="sec5Ic-3" class="allicon ic3"></i></div>
                  <span>Đặt lịch online dễ dàng, miễn phí lấy số khám ưu tiên</span>
               </div>
            </div>
            <div class="col">
               <div class="sec5Box">
                  <div class="sec5Box-ic"><i id="sec5Ic-4" class="allicon ic3"></i></div>
                  <span>Kỹ thuật tiên tiến, đảm bảo hiệu quả điều trị</span>
               </div>
            </div>
            <div class="col">
               <div class="sec5Box">
                  <div class="sec5Box-ic"><i id="sec5Ic-5" class="allicon ic3"></i></div>
                  <span>Bảo mật thông tin, quá trình thăm khám riêng tư</span>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- <section id="sec6">
   <div class="ctn">
      <div class="tac">
         <div class="secHead">
            <i class="allicon" id="sec6Ic-1"></i>
            <span>Truyền thông nói gì về Phòng khám việt sing</span>
         </div>
      </div>
      <div id="sec6Baochi">
         <div class="slick-slide items"><img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/partners/google.png" alt=""></div>
         <div class="slick-slide items"><img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/partners/coccoc.png" alt=""></div>
         <div class="slick-slide items"><img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/partners/facebookl.png" alt=""></div>
         <div class="slick-slide items"><img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/partners/zalo.png" alt=""></div>
         <div class="slick-slide items"><img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/partners/zing.png" alt=""></div>
         <div class="slick-slide items"><img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/partners/tiktok.png" alt=""></div>
         <div class="slick-slide items"><img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/partners/youtube.png" alt=""></div>
         <div class="slick-slide items"><img data-lazy="<?php echo $template_path; ?>/assets/themes/dkcd/img/partners/24h.png" alt=""></div>
      </div>
   </div>
</section> -->
<!-- <div class="is_mobi">
   <div class="ctn">
      <div id="ftFrm">
         <div id="ftFrm-head" class="tac">
            <i id="ftIc-3" class="allicon"></i>
            <span>Đặt lịch khám</span>
         </div>
         <div class="d_contact">
            <input type="text" placeholder="Họ và tên (*)" require />
            <input type="number" class="d-phone" placeholder="Số điện thoại (*)" require/>
            <div class="row">
               <div class="col-6">
                  <select id = 'booking_person_gender' require>
                     <option value="0" selected>Giới tính (*)</option>
                     <option value="1">Nam</option>
                     <option value="2">Nữ</option>
                  </select>
               </div>
               <div class="col-6">
                  <input type="number" class="d-phone" placeholder="Tuổi (VD:1989) (*)" require max='4' min='4'/>
               </div>
            </div>
            <input type="text" placeholder="Địa chỉ (*)" require />
            <div class="row">
               <div class="col-6">
                <div class="date-input">
                  <input type="date"  id="ngaykham"  placeholder="Ngày khám" >
                  <i class="fa-fa calendar"></i>
               </div>
               </div>
               <div class="col-6">
                  <select name="hour_book" class="chosen-select hour_book" id="booking_hour">
                     <option value="">Giờ khám *</option>
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
            </div>
            <textarea class="d-content" placeholder="Triệu chứng"></textarea>
            <button class="d-send"><i class="fas fa-paper-plane"></i> Đặt lịch</button>
         </div>
      </div>
   </div>
</div> -->
</main>

<?php include "footer_new.php";?>
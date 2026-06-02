<script>
   $(document).ready(function() {
       /*$.validator.addMethod("check_num_phone", function(value, element) {
         return this.optional(element) || value.match(/((^0)+([1-9]{1,1})+([1-9]{1,1})+([0-9]{7,7})\b)/g);
       }, "Số điện thoại không hợp lệ. Nhập từ 9 chữ số trở lên" );*/
   
       var validator = $("#fMaillList").validate({
           rules: {
               full_name: {
                   required: true,
                   minlength: 2,
                   maxlength: 150
               },
               f_phone: {
                   required: true,
                   minlength: 8,
                   maxlength: 15
               },
               f_email: {
                   email: true,
                   required: true,
                   minlength: 5,
                   maxlength: 250
               },
               country: {
                   required: false,
                   minlength: 2,
                   maxlength: 150
               }
           },
           messages: {
             
           },
           errorElement: "em",
           errorPlacement: function ( error, element ) {
               // Add the `help-block` class to the error element
               error.addClass( "help-block" );
   
               // Add `has-feedback` class to the parent div.form-group
               // in order to add icons to inputs
               element.parents( ".div_input" ).addClass( "has-feedback" );
   
               // Add the span element, if doesn't exists, and apply the icon classes to it.
               if ( !element.next( "span" )[ 0 ] ) {
                   $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
               }
           },
           success: function ( label, element ) {
               // Add the span element, if doesn't exists, and apply the icon classes to it.
               if ( !$( element ).next( "span" )[ 0 ] ) {
                   $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
             }
           },
           highlight: function ( element, errorClass, validClass ) {
               $( element ).parents( ".div_input" ).addClass( "has-error" ).removeClass( "has-success" );
               $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
           },
           unhighlight: function ( element, errorClass, validClass ) {
               $( element ).parents( ".div_input" ).addClass( "has-success" ).removeClass( "has-error" );
               $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
           },
           submitHandler: function(form) {
               var _this_btn = $(".submit_maillist");
               var btn_txt = _this_btn.attr('data-text');
               var btn_envelope = _this_btn.attr('data-envelope');
               var btn_txt_loading = _this_btn.attr('data-loading-text');
               _this_btn.addClass('isDisabled');
               _this_btn.html(btn_txt_loading);
   
               var mydata = $("#fMaillList").serialize();
               setTimeout(function(){
                   $.ajax({
                     url: ROOT + '/load_ajax.php?do=box_maillist',
                     type: 'POST',
                     dataType: 'json',
                     data: mydata
                   })
                   .done(function(data) {
                     if (data.ok) {
                       vnTScript.alert_notifi(data.mess, 'success');
                     }else{
                       vnTScript.alert_notifi(data.mess, 'error');
                     }
                   })
                   .always(function() {
                       $("#mailLetterRs").trigger('click');
                       _this_btn.removeClass('isDisabled');
                       _this_btn.html(btn_txt);
                   });
               }, 2000);
               return false;
           }
       });
   });
</script>
<div class="vhcenfoot">
   <div class="wrapper">
      <div class="hpcenfoot">
         <div class="grcen">
            <div class="mcol">
               <div class="">
                  <div class="tplogofoot">
                     <h2>
                        <a href="#" class='text-center'>
                           <trust><img alt="logo" src="<?php echo $template_path; ?>/assets/images/logo.png" width="100" height="100" style=' margin: 0 auto; display: block;'/><span class="ck_desc_img "></span></trust>
                        </a>
                     </h2>
                  </div>
                  <div class="tpaddrfoot">
                     <div class="dctitle" style='font-size: 16px; margin: 0 auto; display: block;'>
                        <h5>Phòng khám đa khoa Cao đẳng Kon Tum</h5>
                     </div>
                     <div class="dcconts">
                        <ul>
                           <li class="fa-map-marker-alt"><a href="https://www.google.com/maps/dir//829A+831+Đ.+3+Tháng+2,+Phường+7,+Quận+11,+TP.+Hồ+Chí+Minh/@10.7634817,106.5774123,12z/data=!4m8!4m7!1m0!1m5!1m1!1s0x31752eeeb0e82b19:0x539908ab8a980b0a!2m2!1d106.6598142!2d10.7634926?entry=ttu&amp;g_ep=EgoyMDI0MDkyMy4wIKXMDSoASAFQAw%3D%3D">347 đường&nbsp;Bà Triệu, Phường Kon Tum, Tỉnh Quảng Ngãi</a></li>
                           <li class="fa-phone-alt">999999 - 000000</li>
                           <li class="fa-fax">(028) 9999 9999</li>
                           <li class="fa-envelope">email.vn</li>
                        </ul>
                     </div>
                  </div>
                  <ul>
                     <li>
                        <p>
                           <trust><img alt="Biểu tượng đồng hồ màu xanh" src="https://banner2.cleanpng.com/20180204/jxe/av29m310z.webp" style="height: 17px; width: 16px;" /><span class="ck_desc_img"></span></trust>
                           &nbsp;&nbsp;<strong>Giờ làm việc:</strong> 7:00 - 11:00, 13:00 - 17:00 (Thứ Hai&nbsp; - Thứ Sáu)<br />
                           <!-- &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 6:00 - 11:30 (Chủ Nhật)</p> -->
                     </li>
                  </ul>
               </div>
               <div class="tpcopyfoot hidden-xs">
                  <div class="">
                     <div class="copy">Copyright © 2025 <a href="#"><strong>Phòng khám đa khoa Cao đẳng Kon Tum</strong></a></div>
                  </div>
               </div>
            </div>
            <div class='hcol '>
               <div class='tplinkfoot'>
                  <div class='lktitle'>
                     <h3>THÔNG TIN</h3>
                  </div>
                  <div class='lkconts'>
                     <ul>
                        <li>
                           <a href='<?php echo XC_URL; ?>/gioi-thieu/2-ve-chung-toi.htmll' target='_self'>Về Phòng khám Đa khoa Cao đẳng Kon Tum</a>
                        </li>
                        <li>
                           <a href='vn/chuyen-khoa.html' target='_self'>Chuyên khoa</a>
                        </li>
                        <li>
                           <a href='vn/doi-ngu-bac-si.html' target='_self'>Bác sĩ</a>
                        </li>
                        <li>
                           <a href='vn/huong-dan.html' target='_self'>Hướng dẫn khám bệnh</a>
                        </li>
                        <li>
                           <a href='vn/tin-tuc.html' target='_self'>Tin tức</a>
                        </li>
                        <li>
                           <a href='vn/lien-he.html' target='_self'>Liên hệ</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            <div class='bcol '>
               <div class='tplinkfoot'>
                  <div class='lktitle'>
                     <h3>Dịch vụ</h3>
                  </div>
                  <div class='lkconts'>
                     <ul>
                        <li>
                           <a href='vn/goi-kham-lai-xe.html' target='_self'>Khám sức nội tổng quát</a>
                        </li>
                        <li>
                           <a href='vn/goi-kham-lai-xe.html' target='_self'>Khám sức khỏe</a>
                        </li>
                        <li>
                           <a href='javascript:;' target='_self'>Các dịch vụ khác</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            <div class="tcol">
               <div class='tppartfoot '>
                  <div class='pntitle'>
                     <h3>Liên kết</h3>
                  </div>
                  <div class='pnconts'>
                     <ul>
                        <li>
                           <!-- <div class='vlogo'>
                              <a href='https://medinet.gov.vn/tin-tuc-su-kien-c1780.aspx' target='_self'>
                                  <img  src="<?php echo $template_path; ?>/assets/vnt_upload/menu/logoyt.png"   width='25px' alt = 'Cổng thông tin điện tử Sở Y tế TP. Hồ Chí Minh'  />
                              </a>
                              </div>
                              <div class='vtext'>
                              <a href='https://medinet.gov.vn/tin-tuc-su-kien-c1780.aspx' target='_self'>
                                  Cổng thông tin điện tử Sở Y tế TP. Hồ Chí Minh
                              </a>
                              </div>
                              </li><li>
                              <div class='vlogo'>
                              <a href='https://medinet.gov.vn/thong-bao-tb1013.aspx' target='_self'>
                                <img  src="<?php echo $template_path; ?>/assets/vnt_upload/menu/logoyt.png"   width='25px' alt = 'Cập nhật thông báo Sở Y tế TP. Hồ Chí Minh'  />
                              </a>
                              </div>
                              <div class='vtext'>
                              <a href='https://medinet.gov.vn/thong-bao-tb1013.aspx' target='_self'>
                                Cập nhật thông báo Sở Y tế TP. Hồ Chí Minh
                              </a>
                              </div> -->
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="tpfanpfoot  ">
                  <iframe src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/ktcc.edu.vn&amp;tabs=timeline&amp;width=270&amp;height=130&amp;small_header=false&amp;adapt_container_width=true&amp;hide_cover=false&amp;show_facepile=false&amp;appId=1305829822896074" width="270" height="130" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
               </div>
               <div class="tpsocifoot ">
                  <ul>
                     <!-- <li><a href="https://www.facebook.com/ktcc.edu.vn" target="_blank"  rel="nofollow"><img alt="Facebook" src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/Facebook_Circled.svg" width="50px" /></a></li> -->
                  </ul>
               </div>
               <div class="tpcopyfoot hidden-sm hidden-md hidden-lg">
                  <div class="copy">Copyright © 2024 <a href="#"><strong>Phòng khám đa khoa Cao đẳng Kon Tum</strong></a></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="vhbotfoot">
   <div class="wrapper ">
      <div class="hpbotfoot ">
         <div class="grbot">
            <div class="mcol">
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="menu_mobile">
   <div class="divmm">
      <div class="mmContent">
         <div class="mmMenu">
            <div class="mmtools">
               <div class="tplinkhead">
                 <ul> 
                                        <li class="vmm"><a href="login.html" target=_self><i class='fa fa-user'></i><span>Đăng nhập</span></a>
                                         
                                    </li>
                                    <ul>
                  <ul>
                     <li class="vhh"><a href="dang-ky-lich-kham.html" target=_self><img src="<?php echo $template_path; ?>/assets/vnt_upload/menu/iedit.png" alt="Đặt lịch khám"><span>Đặt lịch khám</span></a></li>
                  </ul>
               </div>
            </div>
            <ul class="mmMain">
               <li ><a href='<?php echo XC_URL; ?>/gioi-thieu/2-ve-chung-toi.html' target='_self'>Về chúng tôi</a></li>
               <li >
                  <a href='chuyen-khoa.html' target='_self'>Chuyên khoa & Gói khám</a>
                  
               </li>
               <li ><a href='<?php echo $this->helper->permalink(1,'nhathuoc')?>' target='_self'>Nhà thuốc</a></li>
               <li ><a href='doi-ngu-bac-si.html' target='_self'>Đội ngũ bác sĩ</a></li>
               <li ><a href='<?php echo XC_URL;?>/lich-cong-tac.html' target='_self'>Lịch công tác</a></li>
               <li ><a href='<?php echo XC_URL;?>/tin-tuc-su-kien.html' target='_self'>Tin tức & Sự kiện</a></li>
               <li ><a href='<?php echo XC_URL;?>/lien-he.html' target='_self'>Liên hệ</a></li>
            </ul>
            <div class="mmsearch">
               <div class="ajax_search"></div>
            </div>
         </div>
      </div>
      <div class="divmmbg"></div>
   </div>
</div>
<div class="vnt-tool">
   <div id="vnt-menu-fixed" class="hidden-xs hidden-sm ">
      <ul>
         <li class='fxphone'>
            <a href='javascript:;' style='background-color: #ed2024;' rel='nofollow'><img src='<?php echo $template_path; ?>/assets/vnt_upload/menu/callw.png' alt='Hotline' class='vhrung' /></a>
            <span style='background: #ed2024;'>
            Hotline: <span> <a href='tel:0828 228 339'>0828 228 339</a></span>
            </span>
         </li>
         <li class='fxmess'>
            <a href='https://m.me/378129682322155' target='_blank' style='background-color: #8c38eb;' rel='nofollow'><img src='<?php echo $template_path; ?>/assets/vnt_upload/menu/pngwing.com_16_04_2025.webp' alt='Chát cùng chúng tôi' /></a>
            <span style='background: #8c38eb;'><a href='https://m.me/378129682322155' target='_blank' rel='nofollow'>Chát cùng chúng tôi</a></span>
         </li>
         <li class='fxtop go_top'>
            <a href='javascript:;' target='_self' onclick="jQuery('html,body').animate({scrollTop: 0},1000);" style='background-color: #6bc0e7;' rel='nofollow'><i class='fa fa-arrow-up'></i></a>
            <span style='background: #6bc0e7;'>Đi lên trên</span>
         </li>
      </ul>
   </div>
   <div class="support-hotline hidden-md hidden-lg">
      <ul>
         <li class='fxphone'>
            <a href='tel:0828 228 339'>
            <img src='<?php echo $template_path; ?>/assets/vnt_upload/menu/callw.png' alt='Hotline' width='16px' />
            <span class='txt'>Hotline</span>
            </a>
         </li>
         <li class='fxchat'>
            <a href='https://m.me/378129682322155' target='_blank' rel='nofollow'>
            <img src='<?php echo $template_path; ?>/assets/vnt_upload/menu/pngwing.com_16_04_2025.webp' alt='Chát cùng chúng tôi' width='20px' />
            <span class='txt'>Messenger</span>
            </a>
         </li>
         <li class='backFx'>
            <a href='javascript:;' target='_self' onclick="jQuery('html,body').animate({scrollTop: 0},1000);" rel='nofollow'>
            <i class='fa fa-arrow-up'></i>
            <span class='txt'>Top</span>
            </a>
         </li>
      </ul>
   </div>
</div>
<script src="<?php echo $template_path; ?>/assets/js/jquery_alerts/jquery.alerts.js" defer></script>
<script src="<?php echo $template_path; ?>/assets/js/menumobile/menumobile.js" defer></script>
<script src="<?php echo $template_path; ?>/assets/js/alert/iao-alert.jquery.js" defer></script>
<script src="<?php echo $template_path; ?>/assets/js/coread59.js?vs=1.0.9" defer></script>
<script src="<?php echo $template_path; ?>/assets/js/javascriptad59.js?vs=1.0.9" defer></script>
<script>
   $(document).ready(function() {
       $(".tplinkhead li.sub > a").click(function(e) {
           if ($(this).parents("li").hasClass("active")) {
               $(this).parents("li").removeClass("active");
           }else{
               $(this).parents("li").addClass("active");
           }
       });
   });
</script>
<script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "../connect.facebook.net/en_US/all.js#xfbml=1&appId=1305829822896074";  fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>
<!-- <script src="../analytics.ahrefs.com/analytics.js" data-key="x8qk8L0G8w+PXipDFbcLSQ" async></script> -->
<script type="application/ld+json">{"@context": "http://schema.org", "@type": "BreadcrumbList","itemListElement": [{"@type": "ListItem","position": 1,"item": {"@id": "https://phongkhamcaodangkontum.edu.vn/","name": "Trang chủ"}},{"@type": "ListItem", "position": 2,  "item": { "@id": "https://phongkhamcaodangkontum.edu.vn/vn/", "name": "Trang chủ" }}]}</script>
<div class="zalo-chat-widget" data-oaid="568877904837740052" data-welcome-message="PKĐK Cao đẳng Kon Tum xin được hỗ trợ bạn!" data-autopopup="0" data-width="" data-height=""></div>
<script src="../sp.zalo.me/plugins/sdk.js"></script>
</body>
</html>
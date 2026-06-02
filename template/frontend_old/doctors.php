<?php require "header_new.php"; ?>
<div class="ctn">
   <div id="main">
      <div id="breadcrumbs">
         <div id="crumbs"><a href="<?php echo XC_URL; ?>">Trang chủ</a> <span>/</span> <a class="current">Đội ngũ bác sĩ</a></div>
      </div>
      <article id="lbsWrp" class="row">
      <?php foreach($employess as $employess){ ?>
         <div class="col-12 clg-4">
            <a href="<?php echo $this->helper->permalink($employess->eid,'doctors');?>" class="bsBox-home lbsBox">
               <div class="bsBox-home-img">
                  <img src="<?php echo XC_URL; ?>/uploads/doctors/<?php echo $employess-> employee_image;?>" alt="<?php echo $employess-> employee_name;?>">
               </div>
               <div class="bsBox-home-inf">
                  <div class="bsBox-home-name"><?php echo $employess-> employee_name;?></span></div>
                  <span>Chuyên khoa: <b><?php echo $employess-> depart_name;?></b></span>
               </div>
               <div class="bsBox-home-exp">
                  <!-- <ul class="bs-exp-list">
                     <li><span>Kinh nghiệm</span> <b>40 năm</b></li>
                     <li><span>Chuyên môn</span> <b>Phụ khoa</b></li>
                     <li><span>Đánh giá</span> <b><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></li></b>
                  </ul> -->
               </div>
               <span class="rmBtn bsBox-link">Xem thêm bác sĩ <i class="far fa-angle-double-right"></i></span>
            </a>
         </div>
         <?php }?>
      </article>
      
   </div>
</div>
</main>
<?php require "footer_new.php"; ?>
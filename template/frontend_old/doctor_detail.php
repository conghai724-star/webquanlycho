<?php require "header_new.php"; ?>
</section>
<div id="breadcrumbs">
    <div class="ctn">
        <div id="crumbs"><a href="<?php echo XC_URL; ?>">Trang chủ</a> <span>/</span> <a href="<?php echo XC_URL; ?>/doi-ngu-bac-si.html">Bác sĩ</a> <span>/</span> <a class="current"><?php echo $doctor_detail->employee_name; ?></a></div>
    </div>
</div>
<div class="ctn">
    <div class="row">
        <div id="main" class="col-12 clg-8">
            <div id="pbsInf">
                <div id="pbsInf-img" class="lbsBox-thumb">
                    <img src="<?php echo XC_URL; ?>/uploads/doctors/<?php echo $doctor_detail-> employee_image;?>" alt="<?php echo $doctor_detail->employee_name; ?>">
                </div>
                <div id="pbsInf-text" class="lbsBox-inf">
                    <div class="lbsBox-name"><?php echo $doctor_detail->employee_name; ?></b></div>
                    <div class="lbsBox-chuyenkhoa">Chuyên khoa: <b><?php echo $doctor_detail-> depart_name;?></b></div>
                    <!-- <table class="lbsBox-more">
                        <tr>
                            <td class="lbsBox-o1"><i class="fas fa-stethoscope"></i> Kinh nghiệm</td>
                            <td><b>30 năm</b></td>
                        </tr>
                        <tr>
                            <td class="lbsBox-o1"><i class="far fa-calendar-edit"></i> Hạng mục khám</td>
                            <td><b>Phục hồi chức năng</b></td>
                        </tr>
                        <tr>
                            <td class="lbsBox-o1"><i class="far fa-star"></i> Đánh giá</td>
                            <td>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </td>
                        </tr>
                    </table> -->
                </div>
            </div>
            <div id="pbsDeltail">
                <div id="pstCntn">
                    <p><strong><?php echo $doctor_detail-> employee_des;?></strong></p>
                </div>
               
            </div>
            
            
        </div>
        <!-- sidebar -->
        <aside id="sidebar" class="col-12 clg-4 hidden_mobi">
            <div class="sbsticky">
                <div id="bsWrp" class="sbbacsi">
                    <?php foreach($doctor_other as $doctor_other){?>
                    <a href="<?php echo $this->helper->permalink($doctor_other->eid,'doctors');?>" class="bsBox">
                        <div class="bsBox-img">
                            <img src="<?php echo XC_URL; ?>/uploads/doctors/<?php echo $doctor_other-> employee_image;?>" alt="<?php echo $doctor_other-> employee_name;?>">
                        </div>
                        <div class="bsBox-inf">
                            <div class="bsBox-name"><?php echo $doctor_other-> employee_name;?></div>
                            <span><i class="fas fa-medkit"></i><?php echo $doctor_other-> depart_name;?></span>
                        </div>
                    </a>
                    <?php }?>
                    
                </div>
            </div>
        </aside>
        <!-- /sidebar -->
    </div>
</div>
</main>
<?php require "footer_new.php"; ?>
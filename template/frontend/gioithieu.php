<?php include "header_new.php";?>
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
            <div class="is_pc">
               <div id="pstNote">
                  <img data-src="<?php echo $template_path;?>/assets/images/logo.png" alt="">
                  <span><?php echo $this->helper->get_config('website_description');?></span>
               </div>
            </div>
            <div class="is_mobi">
               <div id="pstNote">
                  <img data-src="<?php echo $template_path;?>/assets/images/logo.png"" alt="">
                  <span><?php echo $this->helper->get_config('website_description');?></span>
               </div>
            </div>
            <div id="pstDetail">
               <!-- <h1 id="pstpTitle"></h1> -->
               <div id="pstCntn">
                  <!-- <div class="toc-container-content is_mobi">
                     
                  </div> -->
                  <?php echo $introduce->introduce_content; ?>
                 </div>
            </div>
         </article>
         <div id="bsWrp" class="pBs" style="margin-top: 30px">
            <?php foreach($employee as $employee){ ?>
            <div class="pitemBs">
               <a href=" <?php echo $this->helper->permalink($employee->id,'employee'); ?>" class="bsBox">
                  <div class="bsBox-img">
                     <img data-src="<?php echo XC_URL; ?>/uploads/doctors/<?php echo $employee-> employee_image;?>" alt="<?php echo $employee-> employee_name;?>">
                  </div>
                  <div class="bsBox-inf">
                     <div class="bsBox-name"><?php echo $employee-> employee_name;?></div>
                     <span><i class="fas fa-medkit"></i> <?php echo $employee-> depart_name;?></span>
                  </div>
               </a>
            </div>
            <?php }?>
           
         </div>
      </div>
      <!-- sidebar -->
      <aside id="sidebar" class="col-12 clg-4 hidden_mobi">
         <!-- <div id="sbSrch" class="sbbox">
            <form class="sbSearch" method="get" action="https://phongkhamcdkontum.com.vn/" role="search">
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
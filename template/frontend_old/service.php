<?php require "header_new.php"; ?>
</section>    
<div id="breadcrumbs">
   <div class="ctn">
      <div id="crumbs"><a href="<?php echo XC_URL; ?>">Trang chủ</a> <span>/</span> <a class="current"> <?php echo $category_name; ?> </a></div>
   </div>
</div>
<div class="ctn">
   <div class="row">
      <div id="main" class="col-12 clg-8">
         <article id="catePts">
            
             <?php foreach($services as $service){  ?>
           
            <article class="ntry">
               <a href="<?php echo $this->helper->permalink($service->sid,'service_detail');?>" class="pThmb">
               <img src="<?php echo XC_URL; ?>/uploads/services/<?php echo $service->service_image; ?>" alt="<?php echo $service->service_name; ?>">
               </a>
               <div class="pCntn">
                  <h4><a href="<?php echo $this->helper->permalink($service->sid,'service_detail');?>"><?php echo $this->helper->limit_text($service->service_name,100); ?></a></h4>
                  <!--<p class="hidden_mobi"><?php echo $this->helper->limit_text($service->service_description,250); ?></p>-->
                  <div class="pMeta">
                     <span><i class="far fa-clock"></i> <?php echo date('d/m/Y', strtotime($service->service_created_date)); ?></span>
                     <!-- <span><i class="fas fa-eye"></i> 194</span> -->
                  </div>
               </div>
            </article>
            <?php }?>
            
            
         </article>
         <!-- pagination -->
         <div class="pagination">
            <div class="paginate_links">
                  <?php
                        for($i = 1; $i <= $total_pages; $i++){
                            if($i == $page){
                                echo " <span aria-current='page' class='page-numbers current'>$i</span>";
                            } else {
                              echo "<a class='page-numbers' href='tin-tuc-su-kien/page/$i.html'>$i</a>";
                            }
                        }
            ?>
               
            </div>
         </div>
         <!-- /pagination -->
         
      </div>
      <!-- sidebar -->
      <aside id="sidebar" class="col-12 clg-4 hidden_mobi">
         <!-- <div id="sbSrch" class="sbbox">
            <form class="sbSearch" method="get" action="https://phongkhamquoctevietsing.vn" role="search">
                <input class="search-input" type="search" name="s" placeholder="Tìm kiếm">
                <button class="search-submit" type="submit" role="button"><i class="fas fa-search"></i></button>
            </form>
            </div> -->
         <div class="sbsticky">
            <div id="sbPost" class="sbbox">
               <div class="sbHead">
                  <i class="sbHead-ic allicon"></i>
                  <span style="color:#fff">Tin nổi bật</span>
               </div>
               <div class="sbCntn">
                  <?php foreach($event_hot as $event_hot){ ?>
                <a href="<?php echo $this->helper->permalink($event_hot->id,'event');?>" class="sbPost">
                     <div class="sbPost-thumb">
                        <img src="<?php echo XC_URL; ?>/uploads/services/<?php echo $event_hot->event_image; ?>" alt="<?php echo $event_hot->event_name; ?>">
                     </div>
                     <div class="sbPost-inf">
                        <h5 class="sbPost-tit"><?php echo $this->helper->limit_text($event_hot->event_name,100); ?></h5>
                        <div class="sbPost-meta">
                           <span><i class="far fa-clock"></i>&nbsp;<?php echo date('d/m/Y', strtotime($event_hot->event_created_date)); ?></span>
                           </div>
                     </div>
                  </a>
                 <?php }?>
               </div>
            </div>
            
            </div>
         </div>
      </aside>
      <!-- /sidebar -->        
   </div>
</div>
</main>
<?php require "footer_new.php";?>
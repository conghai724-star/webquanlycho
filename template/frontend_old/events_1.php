<?php include_once "header.php";?>
<div class="content">
   <div class="container">
      <div class="row">
         <div class="col-md-4 col-lg-4 col-xl-3 theiaStickySidebar">
            <div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none; left: 243.5px; top: 0px;">
			   <div class="card search-filter">
				  <div class="card-header">
					 <h4 class="card-title mb-0">Lọc sự kiện</h4>
				  </div>
				  <div class="card-body">
					 <div class="filter-widget">
						<h4>Thời gian</h4>
						<div>
							<input type="text" class="form-control" id="event_filter_date_range" placeholder="Select Date">
						</div>
						
					 </div>
					 <div class="filter-widget">
						<h4>Loại sự kiện</h4>
						<div>
						   <label class="custom_check">
						   <input type="checkbox" name="gender_type" checked="">
						   <span class="checkmark"></span> Online
						   </label>
						</div>
						<div>
						   <label class="custom_check">
						   <input type="checkbox" checked="" name="gender_type">
						   <span class="checkmark"></span> Offline
						   </label>
						</div>
					 </div>
					 <div class="filter-widget">
						<h4>Danh mục</h4>
						<?php
						foreach($categories as $category)
						{
						?>
						<div>
						   <label class="custom_check">
						   <input type="checkbox" name="select_specialist" checked="">
						   <span class="checkmark"></span> <?php echo $category->category_name;?>
						   </label>
						</div>
						<?php
						}
						?>
					 </div>
					 <div class="btn-search">
						<button type="button" class="btn btn-block w-100">Tìm kiếm</button>
					 </div>
				  </div>
			   </div>
			   <div class="resize-sensor" style="position: absolute; inset: 0px; overflow: hidden; z-index: -1; visibility: hidden;">
				  <div class="resize-sensor-expand" style="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;">
					 <div style="position: absolute; left: 0px; top: 0px; transition: all 0s ease 0s; width: 370px; height: 1710px;"></div>
				  </div>
				  <div class="resize-sensor-shrink" style="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;">
					 <div style="position: absolute; left: 0; top: 0; transition: 0s; width: 200%; height: 200%"></div>
				  </div>
			   </div>
			</div>
         </div>
         <div class="col-md-8 col-lg-8 col-xl-9">
            <div class="card event-service">
               <div class="card-body">
					<div class="row row-grid">
							<?php
							foreach($events as $event)
							{
							?>
                           <div class="col-md-6 col-lg-4 testimonial-slider event-slider slider event-page">
                              <div class="profile-widget">
                                    <div class="doc-img">
                                        <a href="#">
                                            <img class="img-fluid" alt="Speaker Avatar" src="https://digifox.vn/wp-content/uploads/2022/06/facebooksharing-768x402.jpeg" />
                                        </a>
										<a href="javascript:void(0)" class="fav-btn" title="Inactive">
											<i class="far fa-heart"></i>
										</a>
                                    </div>
                                    <div class="pro-content">
                                        <div class="date-sec">
                                            <h3>
                                                <?php echo date("d",strtotime($event->event_from));?>
                                                <span><?php echo date("m",strtotime($event->event_from));?></span>
                                            </h3>
                                        </div>
                                        <h3 class="title">
                                            <span><?php echo $event->category_name;?></span>
                                            <a href="#"><?php echo $event->event_title;?></a>
                                        </h3>
                                        <p class="add-cont"><?php echo ($event->event_location == 1)? "Offline" : "Online"; ?> | <?php echo ($event->event_location == 2) ? $event->channel_name : $event->event_address; ?></p>
                                        <div class="profile-info d-flex">
                                            <a href="#" class="profile-img">
                                                <img src="https://cestalk.com/wp-content/uploads/2022/06/Hung-Vu.png" alt="" />
                                            </a>
                                            <a href="#">
                                                <span class="profile-name"><?php echo $event->host_fullname;?></span>
                                                <span class="profile-pro"><?php echo $event->org_job_title;?> of <?php echo $event->org_name;?></span>
                                            </a>
                                        </div>
                                        <div class="row row-sm seat-details">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);"><img src="<?php echo $template_path; ?>/assets/img/icon-04.png" alt="" /></a>
                                                    <a href="#">
                                                        <span class="available-info">Vé còn lại</span>
                                                        <span class="price-info"><?php echo number_format($event->event_ticket - $event->event_ticket_sold,0);?>/<?php echo number_format($event->event_ticket,0);?></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);"><img src="<?php echo $template_path; ?>/assets/img/icon-05.png" alt="" /></a>
                                                    <a href="javascript:void(0);">
                                                        <span class="available-info">Thời gian</span>
                                                        <span class="price-info"><?php echo date("H:i",strtotime($event->event_from));?> - <?php echo date("H:i",strtotime($event->event_to));?></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row row-sm align-items-center d-flex">
                                            <div class="col-6">
                                                <a href="#" class="now-btn">Mua vé <i class="fas fa-long-arrow-alt-right"></i></a>
                                            </div>
                                            <div class="col-6 text-end">
                                                <a href="javascript:void(0);" class="amt-txt"><?php echo number_format($event->event_price,0,",",".");?>đ</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           </div>
						   <?php
							}
						   ?>
                        </div>
                  
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include_once "footer.php";?>
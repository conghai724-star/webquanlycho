<div class="profile-sidebar">
               <div class="widget-profile pro-widget-content">
                  <div class="profile-info-widget">
                     <a href="#" class="booking-doc-img">
                     <img src="<?php echo ($user->user_avatar)? $this->helper->__upload_url("user")."/".$user->user_avatar : $this->helper->__upload_url("user")."/default-avatar.jpg";?>" alt="User Image">
                     </a>
                     <div class="profile-det-info">
                        <h3><?php echo $user->user_firstname." ".$user->user_lastname;?></h3>
                        <div class="customer-details">
                           <h5><i class="fas fa-birthday-cake"></i> <?php echo date("d-m-Y",strtotime($user->user_birthday));?></h5>
                           <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> VIET NAM, USA</h5>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="dashboard-widget">
                  <nav class="dashboard-menu">
                     <ul>
                        <li class="<?php echo ($page_sub == "user_dashboard")? "active" : "";?>">
                           <a href="#">
                           <i class="fas fa-columns"></i>
                           <span>Tổng quan</span>
                           </a>
                        </li>
                        <li class="<?php echo ($page_sub == "user_saved_event")? "active" : "";?>">
                           <a href="favourites.html">
                           <i class="fas fa-bookmark"></i>
                           <span>Chương trình đã lưu</span>
                           </a>
                        </li>
                        <li>
                           <a href="chat.html">
                           <i class="fas fa-comments"></i>
                           <span>Tin nhắn</span>
                           <small class="unread-msg">23</small>
                           </a>
                        </li>
                        <li class="<?php echo ($page_sub == "user_setting")? "active" : "";?>">
                           <a href="profile-settings.html">
                           <i class="fas fa-user-cog"></i>
                           <span>Cài đặt</span>
                           </a>
                        </li>
                        <li>
                           <a href="change-password.html">
                           <i class="fas fa-lock"></i>
                           <span>Đổi mật khẩu</span>
                           </a>
                        </li>
						<li class="<?php echo ($page_sub == "user_speaker_register")? "active" : "";?>">
                           <a href="change-password.html">
                           <i class="fas fa-circle-arrow-up"></i>
                           <span>Đăng ký làm diễn giả</span>
                           </a>
                        </li>
                        <li>
                           <a href="index.html">
                           <i class="fas fa-sign-out-alt"></i>
                           <span>Đăng xuất</span>
                           </a>
                        </li>
                     </ul>
                  </nav>
               </div>
            </div>
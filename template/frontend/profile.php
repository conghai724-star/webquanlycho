<?php include_once "header.php"; ?>

<div class="content">
   <div class="container">
      <div class="row">
         <div class="col-md-4 col-lg-4 col-xl-3 theiaStickySidebar">
            <div class="profile-sidebar">
               <div class="widget-profile pro-widget-content">
                  <div class="profile-info-widget">
                     <a href="#" class="booking-doc-img">
					 <?php if($user->user_avatar != ''){
						 echo '<img src='.$template_path.'/assets/img/customers/'.$user->user_avatar.' alt="User Image">';
					 }else{
						  echo '<img src="'.$template_path.'"/assets/img/customers/default.png" alt="User Image">';
					 }?>
                    
                     </a>
                     <div class="profile-det-info">
                        <h3><?php echo $user->user_firstname." ".$user->user_lastname;?></h3>
                        <div class="customer-details">
                           <h5><i class="fas fa-birthday-cake"></i> <?php echo date("d-m-Y",strtotime($user->user_birthday));?></h5>
                           <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> <?php echo $user->user_address;?></h5>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="dashboard-widget">
                  <nav class="dashboard-menu">
                     <ul>
                        <li class="active">
                           <a href="#">
                           <i class="fas fa-columns"></i>
                           <span>Tổng quan</span>
                           </a>
                        </li>
                        <li>
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
                        <li>
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
         </div>
         <div class="col-md-8 col-lg-8 col-xl-9">
            <div class="card">
               <div class="card-body pt-0">
                   <nav class="user-tabs mb-4">
                     <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                        <li class="nav-item">
                           <a class="nav-link active" href="#pat_appointments" data-bs-toggle="tab">Bookings</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="#pat_Programss" data-bs-toggle="tab">Programs</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="#pat_medical_records" data-bs-toggle="tab"><span class="med-records">Event Info</span></a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="#pat_billing" data-bs-toggle="tab">Billing</a>
                        </li>
                     </ul>
                  </nav> 
                  <div class="tab-content pt-0">
                     <div id="pat_appointments" class="tab-pane fade show active">
                        <div class="card card-table mb-0">
                           <div class="card-body">
                              <div class="table-responsive">
                                 <table class="table table-hover table-center mb-0">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Email</th>
                                          <th>Register Date</th>
                                          <th>Approved Date</th>
                                          <th>Address</th>
                                          <th>Status</th>
                                          <th></th>
                                       </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($user_speakers as $user_speaker){?>
                                       <tr>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
												<?php if($user_speaker->user_avatar != ''){
													 echo '<img class="avatar-img rounded-circle" src="'.$template_path.'/assets/img/customers/'.$user_speaker->user_avatar.'" alt="User Image">';
												}else{
													echo '<img class="avatar-img rounded-circle" src="'.$template_path.'/assets/img/customers/default.png" alt="User Image">';
												}?>
                                               
                                                </a>
                                                <a href="speaker-profile.html"><?php echo $user_speaker->user_firstname . " " . $user_speaker->user_lastname;?></a>
                                             </h2>
                                          </td>
                                          <td><?php echo $user_speaker->user_email;?> </td>
										  
                                          <td><?php echo date("d/m/Y",strtotime($user_speaker->speaker_register_date));?> <span class="d-block text-info"><?php echo date("H:i:s",strtotime($user_speaker->speaker_register_date));?></span></td></td>
										  
                                          <td><?php echo date("d/m/Y",strtotime($user_speaker->speaker_approved_time));?> <span class="d-block text-info"><?php echo date("H:i:s",strtotime($user_speaker->speaker_approved_time));?></span></td></td>
										  
                                          <td><?php echo $user_speaker->user_address;?></td>
                                          <td><span class="badge badge-pill bg-success-light">Confirm</span></td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
									<?php }?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade" id="pat_Programss">
                        <div class="card card-table mb-0">
                           <div class="card-body">
                              <div class="table-responsive">
                                 <table class="table table-hover table-center mb-0">
                                    <thead>
                                       <tr>
                                          <th>Date </th>
                                          <th>Name</th>
                                          <th>Created by </th>
                                          <th></th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>14 Nov 2020</td>
                                          <td>Songs</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-01.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Blaine Skipper <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>13 Nov 2020</td>
                                          <td>Party</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-02.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Wayte Barlow <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>12 Nov 2020</td>
                                          <td>Games</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-03.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Meerta Tyson <span>DJ Reader</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>11 Nov 2020</td>
                                          <td>story Books Dance</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-04.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Rhodes Glaser <span>DJ, Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>10 Nov 2020</td>
                                          <td>Welcome Speech</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-05.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Dallin Donaldson <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>9 Nov 2020</td>
                                          <td>Songs</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-06.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Mykah Derr <span>DJ, Artist</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>8 Nov 2020</td>
                                          <td>Dinner</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-07.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Ozella Barbee <span>DJ, Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>7 Nov 2020</td>
                                          <td>Party</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-08.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Mayeer Busch <span>Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>6 Nov 2020</td>
                                          <td>Sangeet</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-09.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Farren Blalock <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>5 Nov 2020</td>
                                          <td>Party</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-10.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Sissel Browne <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="pat_medical_records" class="tab-pane fade">
                        <div class="card card-table mb-0">
                           <div class="card-body">
                              <div class="table-responsive">
                                 <table class="table table-hover table-center mb-0">
                                    <thead>
                                       <tr>
                                          <th>ID</th>
                                          <th>Date </th>
                                          <th>Events</th>
                                          <th>Created</th>
                                          <th></th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0010</a></td>
                                          <td>14 Nov 2020</td>
                                          <td>Workshop</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-01.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Blaine Skipper <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0009</a></td>
                                          <td>13 Nov 2020</td>
                                          <td>Culturals</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-02.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Wayte Barlow <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0008</a></td>
                                          <td>12 Nov 2020</td>
                                          <td>Party</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-03.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Meerta Tyson <span>DJ Reader</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0007</a></td>
                                          <td>11 Nov 2020</td>
                                          <td>Workshop</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-04.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Rhodes Glaser <span>DJ, Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0006</a></td>
                                          <td>10 Nov 2020</td>
                                          <td>Song Recording</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-05.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Dallin Donaldson <span>Artist & DJ</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0005</a></td>
                                          <td>9 Nov 2020</td>
                                          <td>Meeting Hall</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-06.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Mykah Derr <span>DJ, Artist</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0004</a></td>
                                          <td>8 Nov 2020</td>
                                          <td>Party Event</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-07.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Ozella Barbee <span>DJ, Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0003</a></td>
                                          <td>7 Nov 2020</td>
                                          <td>Workshop</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-08.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Mayeer Busch <span>Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0002</a></td>
                                          <td>6 Nov 2020</td>
                                          <td>Conference</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-09.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Farren Blalock <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><a href="javascript:void(0);">#MR-0001</a></td>
                                          <td>5 Nov 2020</td>
                                          <td>Seminar</td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-10.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Sissel Browne <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="pat_billing" class="tab-pane fade">
                        <div class="card card-table mb-0">
                           <div class="card-body">
                              <div class="table-responsive">
                                 <table class="table table-hover table-center mb-0">
                                    <thead>
                                       <tr>
                                          <th>Invoice No</th>
                                          <th>Speaker</th>
                                          <th>Amount</th>
                                          <th>Paid On</th>
                                          <th></th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0010</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-01.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Ruby Perrin <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td>$450</td>
                                          <td>14 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0009</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-02.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Wayte Barlow <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td>$300</td>
                                          <td>13 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0008</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-03.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Meerta Tyson <span>DJ Reader</span></a>
                                             </h2>
                                          </td>
                                          <td>$150</td>
                                          <td>12 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0007</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-04.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Rhodes Glaser <span>DJ, Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td>$50</td>
                                          <td>11 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0006</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-05.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Dallin Donaldson <span>Artist & DJ</span></a>
                                             </h2>
                                          </td>
                                          <td>$600</td>
                                          <td>10 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0005</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-06.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Mykah Derr <span>DJ, Artist</span></a>
                                             </h2>
                                          </td>
                                          <td>$200</td>
                                          <td>9 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0004</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-07.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Ozella Barbee <span>DJ, Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td>$100</td>
                                          <td>8 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0003</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-08.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Mayeer Busch <span>Mix Engineer</span></a>
                                             </h2>
                                          </td>
                                          <td>$250</td>
                                          <td>7 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0002</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-09.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Farren Blalock <span>DJ, Producer</span></a>
                                             </h2>
                                          </td>
                                          <td>$175</td>
                                          <td>6 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <a href="invoice-view.html">#INV-0001</a>
                                          </td>
                                          <td>
                                             <h2 class="table-avatar">
                                                <a href="speaker-profile.html" class="avatar avatar-sm me-2">
                                                <img class="avatar-img rounded-circle" src="assets/img/speakers/speaker-thumb-10.jpg" alt="User Image">
                                                </a>
                                                <a href="speaker-profile.html">Sissel Browne <span>#0010</span></a>
                                             </h2>
                                          </td>
                                          <td>$550</td>
                                          <td>5 Nov 2020</td>
                                          <td class="text-end">
                                             <div class="table-action">
                                                <a href="invoice-view.html" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> View
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-primary-light">
                                                <i class="fas fa-print"></i> Print
                                                </a>
                                             </div>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
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
</div>
<?php include_once "footer.php"; ?>
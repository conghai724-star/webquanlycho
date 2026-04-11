<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
  
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
      <title><?php echo ($pagetitle)? $pagetitle." - " : "";?><?php echo $this->helper->get_config("website_name");?> - CloudERP</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
      <link rel="shortcut icon" href="<?php echo $template_path; ?>/assets/img/favicon.png">

<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/css/bootstrap.min.css">

<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/plugins/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/plugins/fullcalendar/fullcalendar.min.css">
<link href="<?php echo $template_path;?>/assets/plugins/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/plugins/datatables/datatables.min.css">

<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/css/style.css">
      <!--[if lt IE 9]>
      <script src="<?php echo $template_path; ?>/assets/js/html5shiv.min.js"></script>
      <script src="<?php echo $template_path; ?>/assets/js/respond.min.js"></script>
      <![endif]-->
	    
<script src="<?php echo $template_path; ?>/assets/js/jquery-3.6.0.min.js"></script>

<script src="<?php echo $template_path; ?>/assets/js/popper.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/js/bootstrap.min.js"></script>

<script src="<?php echo $template_path; ?>/assets/js/feather.min.js"></script>

<script src="<?php echo $template_path; ?>/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<script src="<?php echo $template_path; ?>/assets/plugins/select2/js/select2.min.js"></script>

<script src="<?php echo $template_path; ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/plugins/datatables/datatables.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/js/jquery-ui.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/js/number.js"></script>




<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   </head>
   <body>
      <div class="main-wrapper">
         <div class="header">
            <div class="header-left">
               <a href="index.html" class="logo">
               <img src="<?php echo $template_path; ?>/assets/img/logo.png" alt="Logo">
               </a>
               <a href="index.html" class="logo logo-small">
               <img src="<?php echo $template_path; ?>/assets/img/logo-small.png" alt="Logo" width="30" height="30">
               </a>
            </div>
            <a href="javascript:void(0);" id="toggle_btn">
            <i class="fas fa-bars"></i>
            </a>
            <div class="top-nav-search">
               <form>
                  <input type="text" class="form-control" placeholder="Search here">
                  <button class="btn" type="submit"><i class="fas fa-search"></i></button>
               </form>
            </div>
            <a class="mobile_btn" id="mobile_btn">
            <i class="fas fa-bars"></i>
            </a>
            <ul class="nav nav-tabs user-menu">
               <li class="nav-item dropdown has-arrow flag-nav">
                  <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                  <img src="<?php echo $template_path; ?>/assets/img/flags/us.png" alt="" height="20"> <span>Tiếng Việt</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                     <a href="javascript:void(0);" class="dropdown-item">
                     <img src="<?php echo $template_path; ?>/assets/img/flags/vn.png" alt="" height="16"> Tiếng Việt
                     </a>
                     <a href="javascript:void(0);" class="dropdown-item">
                     <img src="<?php echo $template_path; ?>/assets/img/flags/us.png" alt="" height="16"> English
                     </a>
                     <a href="javascript:void(0);" class="dropdown-item">
                     <img src="<?php echo $template_path; ?>/assets/img/flags/es.png" alt="" height="16"> Spanish
                     </a>
                     <a href="javascript:void(0);" class="dropdown-item">
                     <img src="<?php echo $template_path; ?>/assets/img/flags/de.png" alt="" height="16"> German
                     </a>
                  </div>
               </li>
               <li class="nav-item dropdown">
                  <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                  <i data-feather="bell"></i> <span class="badge rounded-pill">5</span>
                  </a>
                  <div class="dropdown-menu notifications">
                     <div class="topnav-dropdown-header">
                        <span class="notification-title">Notifications</span>
                        <a href="javascript:void(0)" class="clear-noti"> Clear All</a>
                     </div>
                     <div class="noti-content">
                        <ul class="notification-list">
                           <li class="notification-message">
                              <a href="activities.html">
                                 <div class="media d-flex">
                                    <span class="avatar avatar-sm">
                                    <img class="avatar-img rounded-circle" alt="" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-02.jpg">
                                    </span>
                                    <div class="media-body">
                                       <p class="noti-details"><span class="noti-title">Brian Johnson</span> paid the invoice <span class="noti-title">#DF65485</span></p>
                                       <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li class="notification-message">
                              <a href="activities.html">
                                 <div class="media d-flex">
                                    <span class="avatar avatar-sm">
                                    <img class="avatar-img rounded-circle" alt="" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-03.jpg">
                                    </span>
                                    <div class="media-body">
                                       <p class="noti-details"><span class="noti-title">Marie Canales</span> has accepted your estimate <span class="noti-title">#GTR458789</span></p>
                                       <p class="noti-time"><span class="notification-time">6 mins ago</span></p>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li class="notification-message">
                              <a href="activities.html">
                                 <div class="media d-flex">
                                    <div class="avatar avatar-sm">
                                       <span class="avatar-title rounded-circle bg-primary-light"><i class="far fa-user"></i></span>
                                    </div>
                                    <div class="media-body">
                                       <p class="noti-details"><span class="noti-title">New user registered</span></p>
                                       <p class="noti-time"><span class="notification-time">8 mins ago</span></p>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li class="notification-message">
                              <a href="activities.html">
                                 <div class="media d-flex">
                                    <span class="avatar avatar-sm">
                                    <img class="avatar-img rounded-circle" alt="" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-04.jpg">
                                    </span>
                                    <div class="media-body">
                                       <p class="noti-details"><span class="noti-title">Barbara Moore</span> declined the invoice <span class="noti-title">#RDW026896</span></p>
                                       <p class="noti-time"><span class="notification-time">12 mins ago</span></p>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li class="notification-message">
                              <a href="activities.html">
                                 <div class="media d-flex">
                                    <div class="avatar avatar-sm">
                                       <span class="avatar-title rounded-circle bg-info-light"><i class="far fa-comment"></i></span>
                                    </div>
                                    <div class="media-body">
                                       <p class="noti-details"><span class="noti-title">You have received a new message</span></p>
                                       <p class="noti-time"><span class="notification-time">2 days ago</span></p>
                                    </div>
                                 </div>
                              </a>
                           </li>
                        </ul>
                     </div>
                     <div class="topnav-dropdown-footer">
                        <a href="activities.html">View all Notifications</a>
                     </div>
                  </div>
               </li>
               <li class="nav-item dropdown has-arrow main-drop">
                  <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                  <span class="user-img">
                  <img src="<?php echo $template_path; ?>/assets/img/profiles/avatar-01.jpg" alt="">
                  <span class="status online"></span>
                  </span>
                  <span><?php if(isset($_SESSSION['user']['id']) && $_SESSSION['user']['id'] != ''){
					  echo $_SESSSION['user']['email'];
				  }?></span>
                  </a>
                  <div class="dropdown-menu">
                     <a class="dropdown-item" href="profile.html"><i data-feather="user" class="me-1"></i> Profile</a>
                     <a class="dropdown-item" href="settings.html"><i data-feather="settings" class="me-1"></i> Settings</a>
                     <a class="dropdown-item" href="<?php echo XC_URL;?>/logout"><i data-feather="log-out" class="me-1"></i> Logout</a>
                  </div>
               </li>
            </ul>
         </div>
         <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
               <div id="sidebar-menu" class="sidebar-menu">
                  <ul>
                     <li class="menu-title"><span>Tính năng</span></li>
                     <li class="<?php echo ($active_menu == "dashboard")? "active" : "";?>">
                        <a href="<?php echo XC_URL;?>"><i data-feather="home"></i> <span>Tổng quan</span></a>
                     </li>
					 <li class="submenu">
                        <a href="#"><i data-feather="aperture"></i> <span>CRM</span> <span class="menu-arrow"></span></a>
                        <ul>
                           <li><a class="<?php echo ($active_menu == "customers")? "active" : "";?>" href="<?php echo XC_URL;?>/app/customers">Khách hàng/NCC</a></li>
                           <li><a class="<?php echo ($active_menu == "promotions")? "active" : "";?>" href="<?php echo XC_URL;?>/app/promotions">Khuyến mãi</a></li>
                           <li><a href="#">Khách hàng thân thiết</a></li>
                           <li><a href="#">Email Marketing</a></li>
                        </ul>
                     </li>
					 <li class="submenu">
                        <a href="#"><i data-feather="pie-chart"></i> <span> Tài chính</span> <span class="menu-arrow"></span></a>
                        <ul>
							<li >
								<a class="<?php echo ($active_menu == "income")? "active" : "";?>" href="<?php echo XC_URL;?>/app/incomes">Phiếu thu</a>
							</li>
							<li>
								<a href="expenses.html">Phiếu chi</a>
							</li>
                           <li><a href="sales-report.html">Dòng tiền</a></li>
                           <li><a href="expenses-report.html">Báo cáo kinh doanh</a></li>
                           <li><a href="profit-loss-report.html">Kho hàng</a></li>
                           <li><a href="taxs-report.html">Công cụ dụng cụ</a></li>
                           <li><a href="taxs-report.html">Tài sản cố định</a></li>
                        </ul>
                     </li>
                     <li class="submenu">
                        <a href="#"><i data-feather="box"></i> <span>Kho hàng</span> <span class="menu-arrow"></span></a>
                        <ul>
                           
                           <li><a class="<?php echo ($active_menu == "quotes")? "active" : "";?>" href="<?php echo XC_URL;?>/app/quotes"><i data-feather="shopping-bag"></i> Báo giá</a></li>
						   <li><a class="<?php echo ($active_menu == "sell")? "active" : "";?>" href="#"><i data-feather="shopping-bag"></i> Bán hàng</a></li>
                           <li><a class="<?php echo ($active_menu == "product_return")? "active" : "";?>" href="#"><i data-feather="shopping-bag"></i> Đổi trả hàng</a></li>
                           <li><a class="<?php echo ($active_menu == "product_categories")? "active" : "";?>" href="#"><i data-feather="shopping-bag"></i> Nhóm hàng</a></li>
                           <li><a class="<?php echo ($active_menu == "products")? "active" : "";?>" href="<?php echo XC_URL;?>/app/products"><i data-feather="truck"></i> Danh mục hàng hoá</a></li>
                           <li><a class="<?php echo ($active_menu == "warehouse")? "active" : "";?>" href="<?php echo XC_URL;?>/app/warehouse"><i data-feather="archive"></i> Danh mục kho</a></li>
						   <li><a class="<?php echo ($active_menu == "orders")? "active" : "";?>" href="<?php echo XC_URL;?>/app/orders/order"><i data-feather="shopping-cart"></i> Mua hàng</a></li>
						   <li><a class="<?php echo ($active_menu == "stock_transfer")? "active" : "";?>" href="#"><i data-feather="archive"></i> Chuyển kho</a></li>
						   <li><a class="<?php echo ($active_menu == "barcode")? "active" : "";?>" href="#"><i data-feather="shopping-bag"></i> In Barcode</a></li>
                        </ul>
                     </li>
					 
					 
                    <li class='<?php echo ($active_menu == "toolinstruments")? "active" : "";?>'>
                        <a href="<?php echo XC_URL;?>/app/toolinstruments""><i data-feather="layers"></i> <span>Công cụ dụng cụ</span></a>
                     </li>
					 <li>
                        <a href="<?php echo XC_URL;?>/app/fixedAsset"><i data-feather="codesandbox"></i> <span>Tài sản cố định</span></a>
                     </li>
                     <li class="submenu">
                        <a href="#"><i data-feather="pie-chart"></i> <span> Báo cáo</span> <span class="menu-arrow"></span></a>
                        <ul>
                           <li><a href="sales-report.html">Dòng tiền</a></li>
                           <li><a href="expenses-report.html">Báo cáo kinh doanh</a></li>
                           <li><a href="profit-loss-report.html">Kho hàng</a></li>
                           <li><a href="taxs-report.html">Công cụ dụng cụ</a></li>
                           <li><a href="taxs-report.html">Tài sản cố định</a></li>
                        </ul>
                     </li>
                     
                     
                     <li class="menu-title">
                        <span>Hệ thống</span>
                     </li>
					 <li>
                        <a href="<?php echo XC_URL?>/app/employees"><i data-feather="users"></i> <span>Nhân viên</span></a>
                     </li>
					 
                     <li class="<?php echo ($active_menu == "categories")? "active" : "";?>">
                        <a href="<?php echo XC_URL;?>/app/categories/users"><i data-feather="database"></i> <span>Danh mục</span></a>
                     </li>
                     
					 <li class="<?php echo ($active_menu == "settings")? "active" : "";?>">
                        <a href="<?php echo XC_URL;?>/app/setting"><i data-feather="settings"></i> <span>Thiết lập</span></a>
                     </li>
                     
                  </ul>
               </div>
            </div>
         </div>
		 <div class="page-wrapper">
<?php require "config.php";?>
<?php
$adminAllowedKeys = isset($allowed_admin_menu) && is_array($allowed_admin_menu) ? $allowed_admin_menu : array();
$adminCan = function($key) use ($adminAllowedKeys){
    return in_array('*', $adminAllowedKeys, true) || in_array($key, $adminAllowedKeys, true);
};
$adminCanAny = function($keys) use ($adminCan){
    foreach($keys as $key){ if($adminCan($key)){ return true; } }
    return false;
};
?>
<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Cổng thông tin việc làm trường cao đẳng Kon Tum</title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="<?php echo $admintemplate_path; ?>/assets/images/favicon.ico">
      
      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="<?php echo $admintemplate_path; ?>/assets/css/core/libs.min.css?v=<?php echo time(); ?>">
      
      <!-- Aos Animation Css -->
      <link rel="stylesheet" href="<?php echo $admintemplate_path; ?>/assets/vendor/aos/dist/aos.css?v=<?php echo time(); ?>">
      
      <!-- Hope Ui Design System Css -->
      <link rel="stylesheet" href="<?php echo $admintemplate_path; ?>/assets/css/hope-ui.min.css?v=<?php echo time(); ?>">
      
      <!-- Custom Css -->
      <link rel="stylesheet" href="<?php echo $admintemplate_path; ?>/assets/css/custom.min.css?v=<?php echo time(); ?>">
      
      <!-- Dark Css -->
      <link rel="stylesheet" href="<?php echo $admintemplate_path; ?>/assets/css/dark.min.css?v=<?php echo time(); ?>">
      
      <!-- Customizer Css -->
      <link rel="stylesheet" href="<?php echo $admintemplate_path; ?>/assets/css/customizer.min.css?v=<?php echo time(); ?>">
      
      <!-- RTL Css -->
      <link rel="stylesheet" href="<?php echo $admintemplate_path; ?>/assets/css/rtl.min.css?v=<?php echo time(); ?>">

      <!-- Admin responsive fixes -->
      <link rel="stylesheet" href="<?php echo $admintemplate_path; ?>/assets/css/admin-responsive.css?v=<?php echo time(); ?>">
      
    <script src="<?php echo $admintemplate_path; ?>/assets/js/core/libs.min.js"></script>
     
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <link rel='stylesheet' href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
      <!-- <script src="<?php echo $admintemplate_path;?>/assets/js/core.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
      
  </head>
  <body class="  ">
    <!-- loader Start -->
    <div id="loading">
      <div class="loader simple-loader">
          <div class="loader-body">
          </div>
      </div>    </div>
    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
        <div class="sidebar-header d-flex align-items-center justify-content-start">
            <a href="#" class="navbar-brand">
                
                <!--Logo start-->
                <div class="logo-main">
                    <div class="logo-normal">
                        <svg class=" icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                            <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="logo-mini">
                        <svg class=" icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                            <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <!--logo End-->
                
                
                
                
                <h4 class="logo-title">Cổng Việc làm</h4>
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar">
            <div class="sidebar-list">
                <!-- Sidebar Menu Start -->
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">Home</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>
                    <?php if($adminCan('dashboard')): ?>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="<?php echo XC_URL;?>/admin">
                            <i class="icon">
                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-20">
                                    <path opacity="0.4" d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z" fill="currentColor"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="currentColor"></path>
                                </svg>
                            </i>
                            <span class="item-name">Trang chủ</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    
                    <?php if($adminCanAny(array('employers','employer_posts','candidates','students','events','news_comments','google_meet','market_results','customer_feedbacks','job_support_customers','users','groups'))): ?>
                    <li><hr class="hr-horizontal"></li>
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">Trang quản lý</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>
                    <?php endif; ?>
                   

                    <?php if($adminCan('employers') || $adminCan('employer_posts')): ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-employee" role="button" aria-expanded="false" aria-controls="sidebar-user">
                              <i class="fa-solid fa-city"></i>
                            <span class="item-name ">Nhà tuyển dụng</span>
                            <i class="right-icon">
                                <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse" id="sidebar-employee" data-bs-parent="#sidebar-menu">
                            <?php if($adminCan('employers')): ?>
                            <li class="nav-item">
                                <a class="nav-link " href="<?php echo XC_URL;?>/admin/employers">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name <?php echo (isset($active_menu) && $active_menu == 'employers') ? 'active' : ''; ?>">QL nhà tuyển dụng</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($adminCan('employer_posts')): ?>
                            <li class="nav-item">
                                <a class="nav-link " href="<?php echo XC_URL;?>/admin/employers/posts">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name <?php echo (isset($active_menu) && $active_menu == 'post_employers') ? 'active' : ''; ?>">Quản lý bài đăng</span>
                                </a>
                            </li>
                            <?php endif; ?>
                         
                        </ul>
                    </li>
                    <?php endif; ?>
                     <?php if($adminCan('candidates')): ?>
                     <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'candidates') ? 'active' : ''; ?>"  href="<?php echo XC_URL;?>/admin/candidates">
                          <i class="fa-solid fa-chalkboard-user"></i>
                            <span class="item-name">Ứng viên</span>
                        </a>
                    </li>
                    <?php endif; ?>
                     <?php if($adminCan('students')): ?>
                     <li class="nav-item">
                        <a class="nav-link "  href="<?php echo XC_URL;?>/admin/students">
                         <i class="fa-solid fa-user-graduate"></i>
                            <span class="item-name">Sinh viên</span>
                        </a>
                    </li>
                    <?php endif; ?>
                     <?php if($adminCan('events') || $adminCan('news_comments')): ?>
                     <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-event" role="button" aria-expanded="false" aria-controls="sidebar-event">
                            <i class="fa-solid fa-calendar-plus"></i>
                            <span class="item-name ">Tin tức & sự kiện</span>
                            <i class="right-icon">
                                <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse" id="sidebar-event" data-bs-parent="#sidebar-menu">
                            <?php if($adminCan('events')): ?>
                            <li class="nav-item">
                                <a class="nav-link " href="<?php echo XC_URL;?>/admin/events">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name <?php echo (isset($active_menu) && $active_menu == 'events') ? 'active' : ''; ?>">QL Tin tức & Sự kiện</span>
                                </a>
                            </li>
                            <?php endif; ?>
                             <?php if($adminCan('news_comments')): ?>
                             <li class="nav-item">
                                <a class="nav-link " href="<?php echo XC_URL;?>/admin/newscomments">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name <?php echo (isset($active_menu) && $active_menu == 'newscomments') ? 'active' : ''; ?>">QL Bình luận Tin tức</span>
                                </a>
                            </li>
                            <?php endif; ?>
                         
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <!-- <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'newscomments') ? 'active' : ''; ?>"  href="<?php echo XC_URL;?>/admin/newscomments">
                         <i class="fa-solid fa-comments"></i>
                            <span class="item-name">Bình luận tin tức</span>
                        </a>
                    </li> -->
                    <?php if($adminCan('google_meet')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'googlemeet') ? 'active' : ''; ?>"  href="<?php echo XC_URL;?>/admin/googlemeet">
                         <i class="fa-brands fa-google"></i>
                            <span class="item-name">Sàn việc làm online</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if($adminCan('market_results')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'marketresults') ? 'active' : ''; ?>"  href="<?php echo XC_URL;?>/admin/marketresults">
                         <i class="fa-solid fa-chart-line"></i>
                            <span class="item-name">Kết quả sàn</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if($adminCan('customer_feedbacks')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'customerfeedbacks') ? 'active' : ''; ?>"  href="<?php echo XC_URL;?>/admin/customerfeedbacks">
                         <i class="fa-solid fa-envelope-open-text"></i>
                            <span class="item-name">Phản hồi KH</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if($adminCan('job_support_customers')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'jobsupportcustomers') ? 'active' : ''; ?>" href="<?php echo XC_URL;?>/admin/jobsupportcustomers">
                         <i class="fa-solid fa-address-book"></i>
                            <span class="item-name">Khách hàng</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if($adminCan('users') || $adminCan('groups')): ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-user" role="button" aria-expanded="false" aria-controls="sidebar-user">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.9488 14.54C8.49884 14.54 5.58789 15.1038 5.58789 17.2795C5.58789 19.4562 8.51765 20.0001 11.9488 20.0001C15.3988 20.0001 18.3098 19.4364 18.3098 17.2606C18.3098 15.084 15.38 14.54 11.9488 14.54Z" fill="currentColor"></path>
                                    <path opacity="0.4" d="M11.949 12.467C14.2851 12.467 16.1583 10.5831 16.1583 8.23351C16.1583 5.88306 14.2851 4 11.949 4C9.61293 4 7.73975 5.88306 7.73975 8.23351C7.73975 10.5831 9.61293 12.467 11.949 12.467Z" fill="currentColor"></path>
                                    <path opacity="0.4" d="M21.0881 9.21923C21.6925 6.84176 19.9205 4.70654 17.664 4.70654C17.4187 4.70654 17.1841 4.73356 16.9549 4.77949C16.9244 4.78669 16.8904 4.802 16.8725 4.82902C16.8519 4.86324 16.8671 4.90917 16.8895 4.93889C17.5673 5.89528 17.9568 7.0597 17.9568 8.30967C17.9568 9.50741 17.5996 10.6241 16.9728 11.5508C16.9083 11.6462 16.9656 11.775 17.0793 11.7948C17.2369 11.8227 17.3981 11.8371 17.5629 11.8416C19.2059 11.8849 20.6807 10.8213 21.0881 9.21923Z" fill="currentColor"></path>
                                    <path d="M22.8094 14.817C22.5086 14.1722 21.7824 13.73 20.6783 13.513C20.1572 13.3851 18.747 13.205 17.4352 13.2293C17.4155 13.232 17.4048 13.2455 17.403 13.2545C17.4003 13.2671 17.4057 13.2887 17.4316 13.3022C18.0378 13.6039 20.3811 14.916 20.0865 17.6834C20.074 17.8032 20.1698 17.9068 20.2888 17.8888C20.8655 17.8059 22.3492 17.4853 22.8094 16.4866C23.0637 15.9589 23.0637 15.3456 22.8094 14.817Z" fill="currentColor"></path>
                                    <path opacity="0.4" d="M7.04459 4.77973C6.81626 4.7329 6.58077 4.70679 6.33543 4.70679C4.07901 4.70679 2.30701 6.84201 2.9123 9.21947C3.31882 10.8216 4.79355 11.8851 6.43661 11.8419C6.60136 11.8374 6.76343 11.8221 6.92013 11.7951C7.03384 11.7753 7.09115 11.6465 7.02668 11.551C6.3999 10.6234 6.04263 9.50765 6.04263 8.30991C6.04263 7.05904 6.43303 5.89462 7.11085 4.93913C7.13234 4.90941 7.14845 4.86348 7.12696 4.82926C7.10906 4.80135 7.07593 4.78694 7.04459 4.77973Z" fill="currentColor"></path>
                                    <path d="M3.32156 13.5127C2.21752 13.7297 1.49225 14.1719 1.19139 14.8167C0.936203 15.3453 0.936203 15.9586 1.19139 16.4872C1.65163 17.4851 3.13531 17.8066 3.71195 17.8885C3.83104 17.9065 3.92595 17.8038 3.91342 17.6832C3.61883 14.9167 5.9621 13.6046 6.56918 13.3029C6.59425 13.2885 6.59962 13.2677 6.59694 13.2542C6.59515 13.2452 6.5853 13.2317 6.5656 13.2299C5.25294 13.2047 3.84358 13.3848 3.32156 13.5127Z" fill="currentColor"></path>
                                </svg>
                            </i>
                            <span class="item-name ">Tài khoản</span>
                            <i class="right-icon">
                                <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse" id="sidebar-user" data-bs-parent="#sidebar-menu">
                            <?php if($adminCan('users')): ?>
                            <li class="nav-item">
                                <a class="nav-link " href="<?php echo XC_URL;?>/admin/users">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name <?php echo (isset($active_menu) && $active_menu == 'users') ? 'active' : ''; ?>">Quản lý Tài khoản</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($adminCan('groups')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo XC_URL;?>/admin/groups">
                                    <i class="fa-solid fa-user-shield"></i>
                                    <span class="item-name <?php echo (isset($active_menu) && $active_menu == 'groups') ? 'active' : ''; ?>">Quản lý Nhóm Quyền</span>
                                </a>
                            </li>
                            <?php endif; ?>
                          <!-- <li class="nav-item">
                                <a class="nav-link " href="<?php echo XC_URL;?>/admin/users/role">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> U </i>
                                    <span class="item-name <?php echo (isset($active_menu) && $active_menu == 'users_role') ? 'active' : ''; ?>">Quản lý Quyền TK</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="<?php echo XC_URL;?>/admin/groups">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> G </i>
                                    <span class="item-name <?php echo (isset($active_menu) && $active_menu == 'groups') ? 'active' : ''; ?>">Quản lý Nhóm Quyền</span>
                                </a>
                            </li> -->
                            
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if($adminCan('images') || $adminCan('videos')): ?>
                    <li><hr class="hr-horizontal"></li>
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">Quản lý danh mục</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>
                    <?php if($adminCan('images')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'images') ? 'active' : ''; ?>" href="<?php echo XC_URL;?>/admin/images">
                            <i class="fa-solid fa-image">
                                
                            </i>
                            <span class="item-name">Thư viện hình ảnh</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if($adminCan('videos')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'videos') ? 'active' : ''; ?>" href="<?php echo XC_URL;?>/admin/videos">
                            <i class="fa-solid fa-video">
                            </i>
                            <span class="item-name">Thư viện video</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php if($adminCan('config') || $adminCan('settings')): ?>
                    <li><hr class="hr-horizontal"></li>
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="<?php echo XC_URL;?>/admin/system" tabindex="-1">
                            <span class="default-icon">Cấu hình hệ thống</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>
                    <?php if($adminCan('config')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($active_menu) && $active_menu == 'config') ? 'active' : ''; ?>" href="<?php echo XC_URL;?>/admin/config">
                            <i class="fa-solid fa-gears">
                                
                            </i>
                            <span class="item-name">DM Tham số</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if($adminCan('settings')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo XC_URL;?>/admin/settings">
                            <i class="fa-solid fa-wrench">
                            </i>
                            <span class="item-name">Cài đặt hệ thống</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                   <br><br>
                </ul>
                <!-- Sidebar Menu End -->        </div>
        </div>
        
        <div class="sidebar-footer"></div>
    </aside>    <main class="main-content">
      <div class="position-relative iq-banner">
        <!--Nav Start-->
        <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
          <div class="container-fluid navbar-inner">
            <a href="../../dashboard/index.html" class="navbar-brand">
                
                <!--Logo start-->
                <div class="logo-main">
                    <div class="logo-normal">
                        <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                            <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="logo-mini">
                        <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                            <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <!--logo End-->
                
                
                
                
                <h4 class="logo-title">Cổng việc làm CĐKT</h4>
            </a>
            <button class="sidebar-toggle admin-mobile-menu-toggle" type="button" data-toggle="sidebar" data-active="true" aria-label="Mở menu quản trị" aria-expanded="false">
                <i class="icon">
                 <svg  width="20px" class="icon-20" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                </svg>
                </i>
            </button>
            <div class="input-group search-input">
              <span class="input-group-text" id="search-input">
                <svg class="icon-18" width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
              <input type="search" class="form-control" placeholder="Search...">
            </div>
            <button class="navbar-toggler collapsed" type="button" data-toggle="sidebar" data-active="true" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Mở menu quản trị">
              <span class="navbar-toggler-icon">
                  <span class="mt-2 navbar-toggler-bar bar1"></span>
                  <span class="navbar-toggler-bar bar2"></span>
                  <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
               
                <!-- <li class="nav-item dropdown">
                    <a href="#" class="search-toggle nav-link" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="../../assets/images/Flag/flag001.png" class="img-fluid rounded-circle" alt="user" style="height: 30px; min-width: 30px; width: 30px;">
                    <span class="bg-primary"></span>
                    </a>
                    <div class="p-0 sub-drop dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                        <div class="m-0 border-0 shadow-none card">
                            <div class="p-0 ">
                                <ul class="p-0 list-group list-group-flush">
                                    <li class="iq-sub-card list-group-item"><a class="p-0" href="#"><img src="../../assets/images/Flag/flag-03.png" alt="img-flaf" class="img-fluid me-2" style="width: 15px;height: 15px;min-width: 15px;"/>Spanish</a></li>
                                    <li class="iq-sub-card list-group-item"><a class="p-0" href="#"><img src="../../assets/images/Flag/flag-04.png" alt="img-flaf" class="img-fluid me-2" style="width: 15px;height: 15px;min-width: 15px;"/>Italian</a></li>
                                    <li class="iq-sub-card list-group-item"><a class="p-0" href="#"><img src="../../assets/images/Flag/flag-02.png" alt="img-flaf" class="img-fluid me-2" style="width: 15px;height: 15px;min-width: 15px;"/>French</a></li>
                                    <li class="iq-sub-card list-group-item"><a class="p-0" href="#"><img src="../../assets/images/Flag/flag-05.png" alt="img-flaf" class="img-fluid me-2" style="width: 15px;height: 15px;min-width: 15px;"/>German</a></li>
                                    <li class="iq-sub-card list-group-item"><a class="p-0" href="#"><img src="../../assets/images/Flag/flag-06.png" alt="img-flaf" class="img-fluid me-2" style="width: 15px;height: 15px;min-width: 15px;"/>Japanese</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li> -->
                
                
                <li class="nav-item dropdown">
                  <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="caption ms-3 d-none d-md-block ">
                        <h6 class="mb-0 caption-title"><?php echo isset($_SESSION['user']['full_name']) ? $_SESSION['user']['full_name'] : 'Admin'; ?></h6>
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?php echo XC_URL; ?>/admin/change_password">Đổi mật khẩu</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo XC_URL; ?>/admin/logout">Logout</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>          <!-- Nav Header Component Start -->
          <div class="iq-navbar-header" style="height: 75px;">
            
             
          </div>          <!-- Nav Header Component End -->
        <!--Nav End-->
      </div>

<?php include "header.php";?>
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
         <img src="<?php echo $template_path; ?>/assets/img/flags/us.png" alt="" height="20"> <span>English</span>
         </a>
         <div class="dropdown-menu dropdown-menu-right">
            <a href="javascript:void(0);" class="dropdown-item">
            <img src="<?php echo $template_path; ?>/assets/img/flags/us.png" alt="" height="16"> English
            </a>
            <a href="javascript:void(0);" class="dropdown-item">
            <img src="<?php echo $template_path; ?>/assets/img/flags/fr.png" alt="" height="16"> French
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
         <span>Admin</span>
         </a>
         <div class="dropdown-menu">
            <a class="dropdown-item" href="profile.html"><i data-feather="user" class="me-1"></i> Profile</a>
            <a class="dropdown-item" href="settings.html"><i data-feather="settings" class="me-1"></i> Settings</a>
            <a class="dropdown-item" href="login.html"><i data-feather="log-out" class="me-1"></i> Logout</a>
         </div>
      </li>
   </ul>
</div>
<div class="sidebar" id="sidebar">
   <div class="sidebar-inner slimscroll">
      <div id="sidebar-menu" class="sidebar-menu">
         <ul>
            <li class="menu-title"><span>Main</span></li>
            <li>
               <a href="index.html"><i data-feather="home"></i> <span>Dashboard</span></a>
            </li>
            <li>
               <a href="customers.html"><i data-feather="users"></i> <span>Customers</span></a>
            </li>
            <li>
               <a href="estimates.html"><i data-feather="file-text"></i> <span>Estimates</span></a>
            </li>
            <li>
               <a href="invoices.html"><i data-feather="clipboard"></i> <span>Invoices</span></a>
            </li>
            <li>
               <a href="payments.html"><i data-feather="credit-card"></i> <span>Payments</span></a>
            </li>
            <li>
               <a href="expenses.html"><i data-feather="package"></i> <span>Expenses</span></a>
            </li>
            <li class="submenu">
               <a href="#"><i data-feather="pie-chart"></i> <span> Reports</span> <span class="menu-arrow"></span></a>
               <ul>
                  <li><a href="sales-report.html">Sales Report</a></li>
                  <li><a href="expenses-report.html">Expenses Report</a></li>
                  <li><a href="profit-loss-report.html">Profit & Loss Report</a></li>
                  <li><a href="taxs-report.html">Taxs Report</a></li>
               </ul>
            </li>
            <li>
               <a href="settings.html"><i data-feather="settings"></i> <span>Settings</span></a>
            </li>
            <li class="submenu">
               <a href="#"><i data-feather="grid"></i> <span> Application</span> <span class="menu-arrow"></span></a>
               <ul>
                  <li><a href="chat.html">Chat</a></li>
                  <li><a href="calendar.html">Calendar</a></li>
                  <li><a href="inbox.html">Email</a></li>
               </ul>
            </li>
            <li class="menu-title">
               <span>Pages</span>
            </li>
            <li>
               <a href="profile.html"><i data-feather="user-plus"></i> <span>Profile</span></a>
            </li>
            <li class="submenu">
               <a href="#"><i data-feather="lock"></i> <span> Authentication </span> <span class="menu-arrow"></span></a>
               <ul>
                  <li><a href="login.html"> Login </a></li>
                  <li><a href="register.html"> Register </a></li>
                  <li><a href="forgot-password.html"> Forgot Password </a></li>
                  <li><a href="lock-screen.html"> Lock Screen </a></li>
               </ul>
            </li>
            <li class="submenu">
               <a href="#"><i data-feather="alert-octagon"></i> <span> Error Pages </span> <span class="menu-arrow"></span></a>
               <ul>
                  <li><a href="error-404.html">404 Error </a></li>
                  <li><a href="error-500.html">500 Error </a></li>
               </ul>
            </li>
            <li class="active">
               <a href="users.html"><i data-feather="user"></i> <span>Users</span></a>
            </li>
            <li>
               <a href="blank-page.html"><i data-feather="file"></i> <span>Blank Page</span></a>
            </li>
            <li>
               <a href="maps-vector.html"><i data-feather="map-pin"></i> <span>Vector Maps</span></a>
            </li>
            <li class="menu-title">
               <span>UI Interface</span>
            </li>
            <li>
               <a href="components.html"><i data-feather="layers"></i> <span>Components</span></a>
            </li>
            <li class="submenu">
               <a href="#"><i data-feather="columns"></i> <span> Forms </span> <span class="menu-arrow"></span></a>
               <ul>
                  <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                  <li><a href="form-input-groups.html">Input Groups </a></li>
                  <li><a href="form-horizontal.html">Horizontal Form </a></li>
                  <li><a href="form-vertical.html"> Vertical Form </a></li>
                  <li><a href="form-mask.html"> Form Mask </a></li>
                  <li><a href="form-validation.html"> Form Validation </a></li>
               </ul>
            </li>
            <li class="submenu">
               <a href="#"><i data-feather="layout"></i> <span> Tables </span> <span class="menu-arrow"></span></a>
               <ul>
                  <li><a href="tables-basic.html">Basic Tables </a></li>
                  <li><a href="data-tables.html">Data Table </a></li>
               </ul>
            </li>
         </ul>
      </div>
   </div>
</div>
<div class="page-wrapper">
   <div class="content container-fluid">
      <div class="page-header">
         <div class="row align-items-center">
            <div class="col">
               <h3 class="page-title">Users</h3>
               <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                  <li class="breadcrumb-item active">Users</li>
               </ul>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <div class="card card-table">
               <div class="card-header">
                  <h4 class="card-title">List of Users</h4>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-stripped table-center table-hover datatable">
                        <thead class="thead-light">
                           <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Registered On</th>
                              <th>Role</th>
                              <th>Status</th>
                              <th class="text-end">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-01.jpg" alt="User Image"> Charles Hafner</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="0b68636a79676e78636a6d656e794b6e736a667b676e25686466">[email&#160;protected]</a></td>
                              <td>16 Nov 2020</td>
                              <td><span class="text-success">Admin</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-02.jpg" alt="User Image"> Brian Johnson</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="5537273c343b3f3a3d3b263a3b15302d34382539307b363a38">[email&#160;protected]</a></td>
                              <td>16 Nov 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-03.jpg" alt="User Image"> Marie Canales</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="b2dfd3c0dbd7d1d3dcd3ded7c1f2d7cad3dfc2ded79cd1dddf">[email&#160;protected]</a></td>
                              <td>8 Nov 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-danger-light">Inactive</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-04.jpg" alt="User Image"> Barbara Moore</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="9af8fbe8f8fbe8fbf7f5f5e8ffdaffe2fbf7eaf6ffb4f9f5f7">[email&#160;protected]</a></td>
                              <td>24 Oct 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-05.jpg" alt="User Image"> Greg Lynch</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="a1c6d3c4c6cdd8cfc2c9e1c4d9c0ccd1cdc48fc2cecc">[email&#160;protected]</a></td>
                              <td>11 Oct 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-danger-light">Inactive</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-06.jpg" alt="User Image"> Karlene Chaidez</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="503b31223c353e353338313934352a103528313d203c357e333f3d">[email&#160;protected]</a></td>
                              <td>29 Sep 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-danger-light">Inactive</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-07.jpg" alt="User Image"> John Blair</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="89e3e6e1e7ebe5e8e0fbc9ecf1e8e4f9e5eca7eae6e4">[email&#160;protected]</a></td>
                              <td>13 Aug 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-08.jpg" alt="User Image"> Russell Copeland</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="4331363030262f2f202c33262f222d2703263b222e332f266d202c2e">[email&#160;protected]</a></td>
                              <td>2 Jul 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-danger-light">Inactive</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-09.jpg" alt="User Image"> Leatha Bailey</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="b1ddd4d0c5d9d0d3d0d8ddd4c8f1d4c9d0dcc1ddd49fd2dedc">[email&#160;protected]</a></td>
                              <td>20 Jun 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-10.jpg" alt="User Image"> Joseph Collins</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="4b2124382e3b23282427272225380b2e332a263b272e65282426">[email&#160;protected]</a></td>
                              <td>9 May 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-11.jpg" alt="User Image"> Jennifer Floyd</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="a5cfc0cbcbccc3c0d7c3c9cadcc1e5c0ddc4c8d5c9c08bc6cac8">[email&#160;protected]</a></td>
                              <td>17 Apr 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-12.jpg" alt="User Image"> Alex Campbell</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="76171a130e15171b0614131a1a36130e171b061a135815191b">[email&#160;protected]</a></td>
                              <td>30 Mar 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <h2 class="table-avatar">
                                    <a href="profile.html"><img class="avatar avatar-sm me-2 avatar-img rounded-circle" src="<?php echo $template_path; ?>/assets/img/profiles/avatar-13.jpg" alt="User Image"> Wendell Ward</a>
                                 </h2>
                              </td>
                              <td><a href="https://kanakku.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="bccbd9d2d8d9d0d0cbddced8fcd9c4ddd1ccd0d992dfd3d1">[email&#160;protected]</a></td>
                              <td>22 Feb 2020</td>
                              <td><span class="text-info">Customer</span></td>
                              <td><span class="badge badge-pill bg-success-light">Active</span></td>
                              <td class="text-end">
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                                 <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
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
<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="<?php echo $template_path; ?>/assets/js/jquery-3.6.0.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/js/popper.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/js/feather.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/plugins/datatables/datatables.min.js"></script>
<script src="<?php echo $template_path; ?>/assets/js/script.js"></script>
</body>
<!-- Mirrored from kanakku.dreamguystech.com/html/template/users.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 17 Aug 2021 09:22:31 GMT -->
</html>
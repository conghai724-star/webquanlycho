<?php include "config.php";?>
<!doctype html>
<html lang="vi" prefix="og: http://ogp.me/ns#" class="no-js">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="UTF-8">
      <link href="<?php echo $template_path; ?>/assets/images/logo.png" type="image/x-icon" rel="shortcut icon">
      <link href="<?php echo $template_path; ?>/assets/images/logo.png" rel="apple-touch-icon-precomposed">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
      <link rel="stylesheet" href="<?php echo $template_path; ?>/assets/themes/dkcd/style3447.css?v=<?php echo time();?>">
      <title><?php echo $this->helper->get_config('website_name'); ?></title>
      <link rel="canonical" href="index.html" />
      <meta property="og:locale" content="vi_VN" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="<?php echo $this->helper->get_config('website_name'); ?>" />
      <meta property="og:url" content="index.html" />
      <meta property="og:site_name" content="<?php echo $this->helper->get_config('website_name'); ?>" />
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content="<?php echo $this->helper->get_config('website_name'); ?>" />
      <!-- / Yoast SEO plugin. -->
      <link rel='dns-prefetch' href='http://s.w.org/' />
      <style type="text/css">
         img.wp-smiley,
         img.emoji {
         display: inline !important;
         border: none !important;
         box-shadow: none !important;
         height: 1em !important;
         width: 1em !important;
         margin: 0 .07em !important;
         vertical-align: -0.1em !important;
         background: none !important;
         padding: 0 !important;
         }
      </style>
      <link rel='stylesheet' href='<?php echo $template_path; ?>/assets/cwp_toc/assets/css/cwp-toc.css' media='all' />
      <link rel='stylesheet' href='<?php echo $template_path; ?>/assets/pkvs-hide-category/assets/css/style.css' media='all' />
      <link rel='stylesheet' href='<?php echo $template_path; ?>/assets/sc_boai/assets/style8a54.css?ver=1.0.0' media='all' />
      <link rel='stylesheet' href='<?php echo $template_path; ?>/assets/themes/dkcd/normalize20b9.css?ver=1.0.2' media='all' />
      <link rel='stylesheet' href='<?php echo $template_path; ?>/assets/wpcomp-quiz/assets/css/style.css' media='all' />
      <link rel='stylesheet' href='<?php echo $template_path; ?>/assets/wpmini-script-injector/assets/css/style.css' media='all' />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

      <div id="bannerTop">
         <a href="#">
            <picture>
               <source media="(max-width: 767px)" srcset="<?php echo $template_path; ?>/assets/themes/dkcd/img/banner/baner_top_mb.png" loading="lazy">
               <source media="(min-width: 768px)" srcset="<?php echo $template_path; ?>/assets/themes/dkcd/img/banner/banner_top.png" loading="lazy">
               <img src="<?php echo $template_path; ?>/assets/themes/dkcd/img/banner_top.png" alt="Phòng khám chất lượng cao &#8211; Phòng khám đa khoa cao đẳng Kon Tum" width="1920" height="120" loading="lazy" style="width: 100%; height: auto;" />
            </picture>
         </a>
      </div>
      <header id="header">
         <div id="headerTop" class="is_pc">
            <div class="ctn">
               <div id="headerTop-hotline">
                  <span>
                  <i id="ftIc-1" class="allicon"></i>
                  <strong><?php echo $this->helper->get_config('site_address'); ?></strong> </span>
                  <span> <i class="fas fa-phone-alt"></i>
                  Hotline tư vấn: <b><a href="tel:<?php echo $this->helper->get_config('site_phone'); ?>"><?php echo $this->helper->get_config('site_phone'); ?></a></b></span>
               </div>
            </div>
         </div>
         <div id="headerMid">
            <div class="ctn">
               <div id="headerMid-wrp" class="row">
                  <button id="mainMenu-btn" class="is_mobi">
                  <i class="fas fa-list-ul"></i>
                  </button>
                  <!-- <a href="index.html" id="logo" title="Phòng khám đa khoa cao đẳng Kon Tum">
                  <img data-src="<?php echo $template_path; ?>/assets/images/logo.png" alt="" width="80px" height="80px">
                  </a> -->
                  <!-- <picture>
                     <source media="(max-width: 767px)" 
                              srcset="<?php echo $template_path; ?>/assets/themes/dkcd/img/logo_mobile.png">
                     
                     <img src="<?php echo $template_path; ?>/assets/images/logo.png"
                           alt="Phòng khám chất lượng cao"  width="80px" height="80px">
                  </picture> -->

               <!--   <picture>-->
               <!--   <source media="(max-width: 767px)" srcset="<?php echo $template_path; ?>/assets/themes/dkcd/img/logo_mobile.png" loading="lazy" style="margin-left: 57px;">-->
               <!--   <source media="(min-width: 768px)" srcset="<?php echo $template_path; ?>/assets/images/logo.png" loading="lazy" width="80px" height="80px">-->
               <!--   <img src="<?php echo $template_path; ?>/assets/images/logo.png" alt="Phòng khám chất lượng cao &#8211; Phòng khám đa khoa cao đẳng Kon Tum" loading="lazy" />-->
               <!--</picture>-->
                  <!-- <button id="search-btn" class="is_mobi">
                     <i class="fas fa-search"></i>
                     </button> -->
                  <nav id="mainMenu" class="hidden_mobi">
                     <ul id="mainmenu">
                        <li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-13"><a href="<?php echo XC_URL;?>"><i class="fa fa-home"></i></a></li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children menu-item-4511">
                           <a href="#">Giới thiệu</a>
                           <ul class="sub-menu">
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5121"><a href="<?php echo XC_URL; ?>/gioi-thieu/1-ve-chung-toi.html">Về chúng tôi</a></li>
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5122"><a href="<?php echo XC_URL; ?>/gioi-thieu/3-co-cau-to-chuc.html">Cơ cấu tổ chức</a></li>
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5124"><a href="<?php echo XC_URL; ?>/gioi-thieu/2-co-so-ha-tang.html">Cơ sở hạ tầng</a></li>
                           </ul>
                        </li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children menu-item-4511">
                           <a href="#">Dịch vụ phòng khám</a>
                           <ul class="sub-menu">
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5121"><a href="<?php echo $this->helper->permalink('6','services'); ?>">Khám chữa bệnh dịch vụ</a></li>
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5122"><a href="<?php echo $this->helper->permalink('7','services'); ?>">Khám chữa bệnh BHYT</a></li>
                           </ul>
                        </li>
                       
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children menu-item-4511">
                           <a href="#">Nhà thuốc</a>
                           <ul class="sub-menu">
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5121"><a href="<?php echo XC_URL; ?>/gioi-thieu/5-gioi-thieu-nha-thuoc.html">Giới thiệu nhà thuốc</a></li>
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5122"><a href="<?php echo XC_URL; ?>/nha-thuoc.html">Danh mục sản phẩm</a></li>
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5122"><a href="<?php echo XC_URL; ?>/gioi-thieu/6-chinh-sach-ban-hang.html">Chính sách bán hàng</a></li>
                              
                           </ul>
                        </li>
                        
                        
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-38039"><a href="<?php echo XC_URL; ?>/doi-ngu-bac-si.html">Bác sĩ</a></li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-38039"><a href="<?php echo XC_URL; ?>/lich-cong-tac.html">Lịch công tác</a></li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-38039"><a href="<?php echo XC_URL; ?>/tin-tuc-su-kien.html">Tin tức & Sự kiện</a></li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-38039"><a href="<?php echo XC_URL; ?>/lien-he.html">Liên hệ</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
         </div>
         <div id="header-bottom-search">
            <div id="search-wp">
               <!-- <form method="GET" action="<?php echo $template_path; ?>">
                  <input type="text" name="s" value="" placeholder="Tìm kiếm..." id="s" required="required">
                  <button type="submit" id="btn-s" aria-label="tìm kiếm">Tìm</button>
               </form> -->
            </div>
         </div>
      </header>
      <main role="main">
      <section id="slider">
      <div class="is_mobi">
         <div class="slider">
            <?php 
            $slider_mb = $this->helper->get_banners("1");
            foreach($slider_mb as $item){ ?>
            <a href="#" title="<?php echo $item->image_name; ?>"><img data-lazy="<?php echo XC_URL; ?>/uploads/images/<?php echo $item->image_url; ?>" alt="<?php echo $item->image_name; ?>"></a>
            <?php } ?>
            
         </div>
      </div>
      <div class="is_pc">
         <div class="slider">
            <?php
             $slider_pc = $this->helper->get_banners("0");
            foreach($slider_pc as $item){ ?>
            <a href="#" title="<?php echo $item->image_name; ?>"><img data-lazy="<?php echo XC_URL; ?>/uploads/images/<?php echo $item->image_url; ?>" alt="<?php echo $item->image_name; ?>"></a>
            <?php } ?>
         </div>
      </div>
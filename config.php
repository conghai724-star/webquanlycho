<?php
/**
 * Project: quanlycho.
 * author: cuongvx.ktm
 * File: config.php.

 */
 
// Config file is commonly included multiple times. Guard against:
// - duplicated constants (PHP Notice: Constant ... already defined)
// - duplicated session_start() (PHP Notice: A session had already been started)
if (!defined('XVN_CONFIG_LOADED')) {
	define('XVN_CONFIG_LOADED', 1);

	// Start session only if it hasn't been started yet
	if (session_id() === '') {
		session_start();
	}

	date_default_timezone_set('Asia/Ho_Chi_Minh');

	//=============== Custom configuration ==================//
	define('DB_NAME', 'quanlycho.vn'); //database name
	define('DB_USER', 'root'); //database user
	define('DB_PASSWORD', ''); //database password
	define('DB_HOST', '127.0.0.1:3307'); //sql server
	define('DB_PORT', '3307');

	//=============== Cấu hình Đồng bộ từ App Quản Lý (Hỗ trợ 2 Host riêng biệt) ==================//
	// 1. Đồng bộ qua API Cầu nối (Khuyên dùng khi 2 web nằm ở 2 hosting/domain khác nhau)
	define('APP_SYNC_API_URL', 'http://localhost/quanlycho.vn/sync_api.php');
	define('APP_SYNC_SECRET_KEY', 'CHO_QN_SYNC_SECURE_KEY_2026_ABCXYZ');

	// 2. Đồng bộ CSDL trực tiếp (Dự phòng khi 2 DB chung 1 máy chủ MySQL)
	define('APP_DB_HOST', '127.0.0.1:3307');
	define('APP_DB_NAME', 'quanlycho.vn');
	define('APP_DB_USER', 'root');
	define('APP_DB_PASSWORD', '');
	/*** define mailer ***/
	define('MAIL_PROTOCOL', 'SMTP');
	define('MAIL_HOST', 'smtp.gmail.com');
	define('MAIL_ACC', 'vuxuancuong98gl@gmail.com');
	define('MAIL_PASS', 'sgap bhor woox labx');
	define('MAIL_PORT', 587);
	define('MAIL_AUTH', true);
	define('MAIL_SECURE', 'tls');

	/*** define Xiao SMS ***/
	define('SMS_API_KEY', 'key-c4a8d21f56a2827fa24a41b3a63dcbb7');
	define('SMS_API_SECRECT', 'C09E8A7C0D6A47BA117A3964A94EB8');

	/*** define Theme ***/
	define('ThemeMaster', 'frontend'); //Replace xpanel by your theme's name
	define('AdminTheme', 'app'); //Replace xpanel by your admin theme's name
	define('AdminThemeMaster', 'adminmaster'); //Replace xpanel by your admin theme's name

	/*** define site path ***/
	define('XC_URL','http://localhost/quanlycho.vn'); //Replace by your site url
	define('BASE_URL', XC_URL . '/');
	define('DIR_ROOT', __DIR__);
	define('DIR_TEMPLATE', __DIR__ . '/template/' . AdminTheme);
	define('ADMINMASTER_URL','http://localhost/quanlycho.vn/admin'); //Replace by your site url
	define('APP_URL','http://localhost/quanlycho.vn/app'); //Replace by your site url

	// ponytail: permissionService tự chứa cấu hình mặc định cho dự án Chợ.
	// Khi dùng cho dự án khác, gọi permissionService::init([...]) tại đây để ghi đè.
}

$siteurl = XC_URL;
/*** template path ***/
$template_path = XC_URL.'/template/'.ThemeMaster; //Warning: Don't change here
$admintemplate_path = XC_URL.'/template/'.AdminTheme; //Warning: Don't change here
$adminmastertemplate_path = XC_URL.'/template/'.AdminThemeMaster; //Warning: Don't change here
$upload_path = XC_URL.'/uploads';
$image_path = XC_URL.'/uploads/images';

/*** Set Application Name ***/
$app_name = 'Hệ thống phần mềm quản lý chợ tỉnh Quảng Ngãi';
?>

<?php

Class baseView {


/*
 * @Variables array
 * @access public
 */
public $data = array();
private static $instance;

/**
 *
 * @constructor
 *
 * @access public
 *
 * @return void
 *
 */
function __construct() {
	$this->home  = home::getInstance();
	$this->url  = general::getInstance();
	$this->helper  = general::getInstance();
	$this->shop  = shop::getInstance();
	$this->erp  = erp::getInstance();
	$this->pdf  = pdf::getInstance();
	$this->render  = render::getInstance();
}

public static function getInstance() {
	if (!self::$instance)
	{	
		self::$instance = new baseView();
	}
	return self::$instance;
}
	
 /**
 *
 * @set undefined vars
 *
 * @param string $index
 *
 * @param mixed $value
 *
 * @return void
 *
 */
//  public function __set($index, $value)
//  {
//         $this->vars[$index] = $value;
//  }


function show($name) {
	$baseDir = realpath(__SITE_PATH . '/template/' . ThemeMaster);
	$path = __SITE_PATH . '/template/' . ThemeMaster . '/' . ltrim($name, '/') . '.php';
	$realPath = realpath($path);

	// Kiểm tra bắt buộc: File phải tồn tại và nằm TRONG thư mục template
	if ($realPath === false || strpos($realPath, $baseDir) !== 0 || !is_file($realPath)) {
		$path = $baseDir . '/404.php';
	} else {
		$path = $realPath;
	}

	// Load variables
	foreach ($this->data as $key => $value)
	{
		$$key = $value;
	}

	if (file_exists($path)) {
		include ($path);
	}
}

function adminmaster($name, array $data = array()) {
	$this->app($name, $data);
}

function app($name, array $data = array()) {
	$baseDir = realpath(__SITE_PATH . '/template/' . AdminTheme);
	$path = __SITE_PATH . '/template/' . AdminTheme . '/' . ltrim($name, '/') . '.php';
	$realPath = realpath($path);

	// Kiểm tra bắt buộc: File phải tồn tại và nằm TRONG thư mục admin template
	if ($realPath === false || strpos($realPath, $baseDir) !== 0 || !is_file($realPath)) {
		$path = $baseDir . '/404.php';
	} else {
		$path = $realPath;
	}
	
	foreach (array_merge($this->data, $data) as $key => $value)
	{
		$$key = $value;
	}

	$isStandaloneView = strpos($name, 'auth/') === 0;
	if (!$isStandaloneView) {
		include __SITE_PATH . '/template/' . AdminTheme . '/layouts/header.php';
		include __SITE_PATH . '/template/' . AdminTheme . '/layouts/sidebar.php';
		include __SITE_PATH . '/template/' . AdminTheme . '/layouts/navbar.php';
	}

	if (file_exists($path)) {
		include ($path);
	}

	if (!$isStandaloneView) {
		include __SITE_PATH . '/template/' . AdminTheme . '/layouts/footer.php';
	}
}

}

?>

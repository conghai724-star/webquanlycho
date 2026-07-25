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
	// echo __SITE_PATH;
	$path = __SITE_PATH . '/template/' .ThemeMaster. '/' . $name . '.php';
	// echo 'link:'. $path;
	if (file_exists($path) == false)
	{
		$path = __SITE_PATH . '/template/' .ThemeMaster. '/404.php';
		//throw new Exception('Template not found in '. $path);
		//return false;
	}

	// Load variables
	foreach ($this->data as $key => $value)
	{
		$$key = $value;
	}

	include ($path);               
}
function adminmaster($name, array $data = array()) {
	$this->app($name, $data);
}
function app($name, array $data = array()) {
	
	$path = __SITE_PATH . '/template/' .AdminTheme. '/' . $name . '.php';
	if (file_exists($path) == false)
	{
		$path = __SITE_PATH . '/template/' .AdminTheme. '/404.php';
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

	include ($path);

	if (!$isStandaloneView) {
		include __SITE_PATH . '/template/' . AdminTheme . '/layouts/footer.php';
	}
}

}

?>

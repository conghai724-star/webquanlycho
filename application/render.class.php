<?php
class render {
	private static $instance;
	
	function __construct() {
		
	}
   public static function getInstance() {
		if (!self::$instance)
		{	
			self::$instance = new render();
		}
		return self::$instance;
	}
	
    ///coder here
    public function redertest(){
        return "render test";
    }
}

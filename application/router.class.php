<?php

class router {
 /*
 * @the registry
 */
 private $registry;

 /*
 * @the controller path
 */
 private $path;

 private $args = array();

 public $file;

 public $controller;

 public $action; 

 function __construct($registry) {
        $this->registry = $registry;
 }

 /**
 *
 * @set controller directory path
 *
 * @param string $path
 *
 * @return void
 *
 */
 function setPath($path) {

	/*** check if path i sa directory ***/
	if (is_dir($path) == false)
	{
		throw new Exception ('Invalid controller path: `' . $path . '`');
	}
	/*** set the path ***/
 	$this->path = $path;
}


 /**
 *
 * @load the controller
 *
 * @access public
 *
 * @return void
 *
 */
 public function loader()
 {
	/*** check the route ***/
	$this->getController();

	/*** if the file is not there diaf ***/
	if (is_readable($this->file) == false)
	{
		$this->file = $this->path.'/error404.php';
                $this->controller = 'error404';
	}

	/*** include the controller ***/
	include $this->file;

	/*** a new controller class instance ***/
	$class = $this->controller . 'Controller';
	$controller = new $class($this->registry);

	/*** check if the action is callable ***/
	if (is_callable(array($controller, $this->action)) == false)
	{
		$action = 'index';
	}
	else
	{
		$action = $this->action;
	}
	/*** run the action ***/
	$controller->$action($this->args);
	/*
	if(!empty($this->args))
		$controller->$action($this->args);
	else
	{
		$this->args = array();
		$controller->$action();
	}
	*/
 }


 /**
 *
 * @get the controller
 *
 * @access private
 *
 * @return void 
 *
 */
private function getController() {

	/*** get the route from the url ***/
	$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

	if (empty($route))
	{
		$route = 'index';
	}
	else
	{
		/*** get the parts of the route ***/
		$parts = explode('/', $route);
		if($parts[0] == "login")
		{
			// var_dump(222);
			$this->controller = "member";
			$this->action = "login";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			} 
		}elseif($parts[0] == "dang-ky-tai-khoan.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "register";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}
		elseif($parts[0] == "verify_email")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "verify_email";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}
		elseif($parts[0] == "lien-he.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "contact";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}
		elseif($parts[0] == "logout")
		{
			$this->controller = "admin";
			$this->action = "logout";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}
		//Router page
		elseif($parts[0] == "gioi-thieu.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "introduce";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "upcode.html")
		{
			// echo $parts[0];
			$this->controller = "page";
			$this->action = "upcode";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}
		elseif($parts[0] == "quan-ly-viec-lam.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "manage_jobs";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}
		elseif($parts[0] == "quan-ly-ung-vien.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "manage_applicants";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "gioi-thieu-san-viec-lam.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "introduce_jobs";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "tin-tuc-su-kien.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "events";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "tin-tuc-su-kien")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "events";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "quy-trinh-san-viec-lam.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "introduce_process";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "ket-qua-san-viec-lam.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "results_jobs";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "san-viec-lam-online.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "online_jobs";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "huong-dan.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "guidelines";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "quan-ly-nha-tuyen-dung.html")
		{
			// echo $parts[0];
			$this->controller = "home";
			$this->action = "employers";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}elseif($parts[0] == "gioi-thieu")
		{
			// echo $parts[0];
			$this->controller = "page";
			$this->action = "introduce";
			if(isset( $parts[1]))
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 1; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
		}
		//Router backend
		// elseif($parts[0] == "admin")
		// {
		// 	$this->controller = "admin";
		// 	$this->action = "index";
		// 	// echo $this->action;
		// 	if(isset( $parts[1]))
		// 	{
		// 		$count_args = count($parts);
		// 		$k = 1;
		// 		$args = array();
		// 		for($i = 1; $i < $count_args; $i++)
		// 			$args[$k++] = $parts[$i]; 
		// 		$this->args = $args;
		// 	}
		// }
		else 
		{
			
			$this->controller = $parts[0];
			if(isset($parts[1]))
			{
				$this->action = $parts[1];
			}
			if(isset( $parts[2]) && $parts[2] != "")
			{
				$count_args = count($parts);
				$k = 1;
				$args = array();
				for($i = 2; $i < $count_args; $i++)
					$args[$k++] = $parts[$i]; 
				$this->args = $args;
			}
			else
			{
				$this->args = array();
			}
		} 
	}

	if (empty($this->controller))
	{
		$this->controller = 'index';
	}

	/*** Get action ***/
	if (empty($this->action))
	{
		$this->action = 'index';
	}

	/*** set the file path ***/
	$this->file = $this->path .'/'. $this->controller . 'Controller.php';
}


}

?>

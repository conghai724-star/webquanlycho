<?php
Class viewerController extends baseController
{
	public function index()
	{}
	public function doc($para)
	{
		$bookid = $para[1];
		global $db;
		$db->query("SELECT * FROM xiaob_book WHERE bookid = '".$bookid."' LIMIT 1");
		$url = $db->fetch_object(true)->bookfile;
		if(pathinfo($url, PATHINFO_EXTENSION) == "")
		{
			$url ="thuviengiaoducintro.pdf";
		}
		$this->view->data['url'] = $url;
		$this->view->show("viewerpdf");
	
	}
}
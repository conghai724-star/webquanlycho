<?php
Abstract Class baseController {

/*
 * @registry object
 */
protected $registry;
protected $model;
protected $view;

function __construct($registry) {
	$this->registry = $registry;
	$this->model = baseModel::getInstance();
	$this->view  = baseView::getInstance();
	$this->func  = general::getInstance();
	$this->helper  = general::getInstance();
	$this->home  = home::getInstance();
	$this->shop  = shop::getInstance();
	$this->book = book::getInstance();
	$this->member = member::getInstance();
	$this->pdf = pdf::getInstance();
	$this->mail = baseMailler::getInstance();
}


/**
 * @all controllers must contain an index method
 */
abstract function index();
}

?>

<?php
/**
 * Project: thuvien.
 * File: tourController.php.
 * Author: Ken Zaki
 * Email: kenzaki@xiao.vn
 * Create Date: 09:54 - 07/10/2016
 * Website: www.xiao.vn
 */
Class hotelController extends baseController
{
    public function index()
    {
		$this->view->show("tour");
    }
	public function detail($para)
	{
		global $db;
		$db->query("SELECT * FROM gt_hotels WHERE hid = '".$para[1]."'");
		$this->view->data["hotel"] = $db->fetch_object(true);
		$this->view->show("hotel-detail");
	}
	public function checkout()
	{
		global $db;
		$db->query("SELECT * FROM gt_hotel_rooms WHERE hrid = '".$_POST['hotel_room']."' LIMIT 1");
		$this->view->data["room"] = $db->fetch_object(true);
		$this->view->show("checkout");
	}
}
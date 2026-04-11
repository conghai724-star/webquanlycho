<?php 
Class userModel extends baseModel
{
	public function get_user($uid){
		global $db;
		$db->query("SELECT * FROM hicrm_users WHERE id = '".$uid."'");
		return $db->fetch_object(true);
	}
}
?>
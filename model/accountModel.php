<?php 
Class accountModel extends baseModel
{
	public function get_account_list($order = "id", $sort = "ASC" ,$limit = 10)
	{
		global $db;
		$db->query("SELECT * FROM hicrm_accounts ORDER BY ".$order." ".$sort." LIMIT ".$limit);
		return $db->fetch_object();
	}
	public function get_list_user(){
		global $db;
		$db->query("SELECT * FROM hicrm_users ORDER BY user_created_date DESC");
		return $db->fetch_object();
	}
}
?>
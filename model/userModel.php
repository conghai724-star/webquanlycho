<?php 
Class userModel extends baseModel
{
	
	public function get_user_list(){
		global $db;
		$db->query("SELECT *, u.id as uid FROM hicrm_users as u 
		LEFT JOIN hicrm_user_groups as g ON u.user_group = g.id
		LEFT JOIN hicrm_status as s ON u.user_status = s.id
		WHERE u.user_status NOT IN(99) ORDER BY u.user_created_at DESC");
		return $db->fetch_object();
	}
	public function get_user_category(){
		global $db;
		$db->query("SELECT * FROM hicrm_user_category WHERE user_category_status NOT IN(99)");
		return $db->fetch_object();
	}
	public function role_user(){
		global $db;
		$db->query("SELECT * FROM hicrm_user_groups WHERE group_status NOT IN(99)");
		return $db->fetch_object();
	}
	public function role_user_detail($id){
		global $db;
		$db->query("SELECT * FROM hicrm_user_groups WHERE id = '".$id."'");
		return $db->fetch_object(true);
	}
	public function get_user_role(){
		global $db;
		$db->query("SELECT * FROM hicrm_user_role WHERE role_status NOT IN(99)");
		return $db->fetch_object();
	}
	public function get_user($id){
		global $db;
		$db->query("SELECT *, u.id as uid FROM hicrm_users as u 
		LEFT JOIN hicrm_user_groups as g ON u.user_group = g.id
		LEFT JOIN hicrm_status as s ON u.user_status = s.id
		LEFT JOIN hicrm_user_category as c ON u.user_category = c.id
		WHERE u.user_status NOT IN(99) AND u.id = '".$id."'");
		return $db->fetch_object(true);
	}
}
?>
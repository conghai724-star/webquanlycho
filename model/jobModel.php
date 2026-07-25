<?php 
Class jobModel extends baseModel
{
	public function get_job_list(){
        global $db;
        $db->query("SELECT *, j.id as jid FROM hicrm_jobs as j 
        LEFT JOIN hicrm_status as s ON j.job_status = s.stall_id
        WHERE j.job_status NOT IN(99) ORDER BY j.job_created_date DESC");
        return $db->fetch_object();
    }
	
}
?>
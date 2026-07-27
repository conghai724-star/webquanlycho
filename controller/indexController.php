<?php

Class indexController Extends baseController
{
	public function index()
    {
		// ponytail: legacy HiCRM code removed — all hicrm_* tables don't exist in this DB
		header("Location: " . BASE_URL . "admin/stalls");
		exit();
	}
	
	private function countorderbydate($date)
	{
		global $db;
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE date(order_time) = '".date("Y-m-d",strtotime($date))."'");
		return $db->fetch_object(true)->countorder;
	}
	private function buildHomeJobFilters($fromClause, $whereClause)
	{
		global $db;
		$filters = array(
			'all' => array(
				array('value' => 'all', 'label' => 'Tất cả')
			),
			'location' => array(
				array('value' => 'all', 'label' => 'Tất cả')
			),
			'salary' => array(
				array('value' => 'all', 'label' => 'Tất cả')
			),
			'experience' => array(
				array('value' => 'all', 'label' => 'Tất cả')
			),
			'industry' => array(
				array('value' => 'all', 'label' => 'Tất cả')
			)
		);

		$db->query("SELECT pr.id, pr.province_name, COUNT(p.id) AS total
			".$fromClause."
			".$whereClause." AND p.province_id IS NOT NULL AND p.province_id <> 0
			GROUP BY pr.id, pr.province_name
			ORDER BY total DESC, pr.province_name ASC");
		foreach((array)$db->fetch_object() as $item){
			$filters['location'][] = array(
				'value' => 'loc_'.(int)$item->id,
				'label' => $item->province_name
			);
		}

		$db->query("SELECT s.id, s.salary_name, COUNT(p.id) AS total
			".$fromClause."
			".$whereClause." AND p.salary_id IS NOT NULL AND p.salary_id <> 0
			GROUP BY s.id, s.salary_name
			ORDER BY s.id ASC, total DESC");
		foreach((array)$db->fetch_object() as $item){
			$filters['salary'][] = array(
				'value' => 'sal_'.(int)$item->id,
				'label' => $item->salary_name
			);
		}

		$db->query("SELECT experience_value, COUNT(*) AS total
			FROM (
				SELECT CASE
					WHEN COALESCE(NULLIF(TRIM(p.experience_years), ''), '0') REGEXP '^[0-9]+$'
						THEN CAST(COALESCE(NULLIF(TRIM(p.experience_years), ''), '0') AS UNSIGNED)
					ELSE 0
				END AS experience_value
				".$fromClause."
				".$whereClause."
			) exp
			GROUP BY experience_value
			ORDER BY experience_value ASC");
		foreach((array)$db->fetch_object() as $item){
			$value = (int)$item->experience_value;
			$filters['experience'][] = array(
				'value' => 'exp_'.$value,
				'label' => $value <= 0 ? 'Chưa có kinh nghiệm' : ($value === 1 ? '1 năm' : $value.' năm')
			);
		}

		$db->query("SELECT c.id, c.job_category_name, COUNT(p.id) AS total
			".$fromClause."
			".$whereClause." AND p.job_category_id IS NOT NULL AND p.job_category_id <> 0
			GROUP BY c.id, c.job_category_name
			ORDER BY total DESC, c.job_category_name ASC");
		foreach((array)$db->fetch_object() as $item){
			$filters['industry'][] = array(
				'value' => 'cat_'.(int)$item->id,
				'label' => $item->job_category_name
			);
		}

		return $filters;
	}
	private function countorderbydatedeposited($date)
	{
		global $db;
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE order_status > 1 AND date(order_time) = '".date("Y-m-d",strtotime($date))."'");
		return $db->fetch_object(true)->countorder;
	}
	
}

?>
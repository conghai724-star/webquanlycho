<?php
define('__SITE_PATH', realpath(dirname(__FILE__)));
include_once 'config.php';
include_once 'includes/init.php';
global $db;
$db->query("DESCRIBE hicrm_website_visits");
$schema = $db->fetch_object();
print_r($schema);

$db->query("SELECT * FROM hicrm_website_visits LIMIT 5");
$data = $db->fetch_object();
print_r($data);
?>

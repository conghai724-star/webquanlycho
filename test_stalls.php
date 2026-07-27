<?php
define('__SITE_PATH', __DIR__);
include_once 'config.php';
include_once 'includes/init.php';
$db = database::getInstance();

try {
    $res = $db->select("SELECT * FROM system_statuses WHERE status_domain = 'stall'");
    print_r($res);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

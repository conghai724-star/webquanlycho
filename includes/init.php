<?php

 /*** include the model class ***/
 include __SITE_PATH . '/application/' . 'model_base.class.php';
 /*** include the controller class ***/
 include __SITE_PATH . '/application/' . 'controller_base.class.php';
 
 /*** include the template class ***/
 include __SITE_PATH . '/application/' . 'view_base.class.php';


 /*** include the registry class ***/
 include __SITE_PATH . '/application/' . 'registry.class.php';
 include __SITE_PATH . '/application/' . 'general.class.php';
 include __SITE_PATH . '/application/' . 'member.class.php';
 include __SITE_PATH . '/application/' . 'book.class.php';
 include __SITE_PATH . '/application/' . 'captcha.class.php';
 include __SITE_PATH . '/application/' . 'tour.class.php';
 include __SITE_PATH . '/application/' . 'crm.class.php';
 include __SITE_PATH . '/application/' . 'hr.class.php';
 include __SITE_PATH . '/application/' . 'mail_base.class.php';
 include __SITE_PATH . '/application/' . 'booking.class.php';
 include __SITE_PATH . '/application/' . 'sms.class.php';
 include __SITE_PATH . '/application/' . 'home.class.php';
 include __SITE_PATH . '/application/' . 'shop.class.php';
 include __SITE_PATH . '/application/' . 'erp.class.php';
 include __SITE_PATH . '/application/' . 'pdf.class.php';

 /*** include the router class ***/
 include __SITE_PATH . '/application/' . 'router.class.php';
 
 /*** include the template class ***/
 include __SITE_PATH . '/application/' . 'database.class.php';


 /*** a new registry object ***/
 $registry = new registry;

 /*** create the database registry object ***/
$db = Database::getInstance();
?>

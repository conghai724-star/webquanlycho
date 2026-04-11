<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$list = array("1439903976","1983893889","1100388563","1598749440","2073725621");
$path = "https://api.telegram.org/bot2057082594:AAH5vg3LuSJ9zywg0O2ASxOf0xRDwG-JO3I";
$id = "ACS".rand(4822000,6999111);
$content = "[An Loc CRM]%0aCó Khách hàng mở tài khoản mới vào lúc: <b>".date("H:i d/m/Y")."</b>.%0aID Khách hàng: <b>".$id."</b>";
foreach($list as $to)
{
	file_get_contents($path."/sendmessage?parse_mode=html&chat_id=".$to."&text=".$content);
}
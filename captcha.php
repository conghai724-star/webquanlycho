<?php
/**
 * Project: akrao.
 * File: captchar.php.
 * Author: Ken Zaki
 * Email: kenzaki@xiao.vn
 * Create Date: 14:34 - 29/11/2013
 * Website: www.xiao.vn
 */
session_start();
function create_image()
{
    $md5_hash = md5(rand(0,999));
    $security_code = substr($md5_hash, 15, 5);
    $_SESSION["security_code"] = $security_code;
    $width = 80;
    $height = 30;
    $image = ImageCreate($width, $height);
    $white = ImageColorAllocate($image, 255, 255, 255);
    $black = ImageColorAllocate($image, 0, 0, 0);
    ImageFill($image, 0, 0, $black);
    ImageString($image, 15, 20, 7, strtoupper($security_code), $white);
    header("Content-Type: image/jpeg");
    ImageJpeg($image);
    ImageDestroy($image);
}
create_image() ;
exit();
<?php
session_start();
$code=md5(microtime());
$code=substr($code,0,6);
$_SESSION['captcha']=$code;
$capt=imagecreatefromjpeg("captchabg.jpg");
$txtcol=imagecolorallocate($capt,0,0,0);
imagestring($capt,6,20,10,$code,$txtcol);
header("Content-type:image/jpeg");
imagejpeg($capt);
?>
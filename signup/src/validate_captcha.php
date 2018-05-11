<?php session_start();
include_once '../vendor/securimage/securimage.php';

$securimage = new Securimage();
$captchaCode = $_POST['captcha_code'];

if ($securimage->check($captchaCode) == false)
{ 
 //header("location:javascript://history.go(-1)");
	echo false;
}
else
{
	echo true;
}
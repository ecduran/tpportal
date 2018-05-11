<?php session_start();

//$link = $_REQUEST['q'];
$_SESSION['sponsorName']      = $_POST['sponsorName'];
$_SESSION['sponsorScreenID']  = $_POST['sponsorScreenID'];
$_SESSION['countryName']      = $_POST['countryName'];
$_SESSION['companyName']      = $_POST['sponsorCompany'];
$_SESSION['countryCode']	  = $_POST['countryCode'];		

//var_dump($_REQUEST);
header('Location: ../public/create_screenid.php');
exit();

//echo $link;
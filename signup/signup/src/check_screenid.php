<?php 
declare(strict_types = 1);

include_once '../config/db-config.php';

$database = new Database();

$db =  $database->getConnection();

include_once 'model/Affiliate.php';

$affiliate = new Affiliate($db);

$q = $_GET["q"];

$affiliate->screenID = $q;

$results = $affiliate->getNumRowByScreenID();

$strln = strlen($q);

if (($strln < 7 || $strln > 16) || !preg_match("/^[a-z\d_]+$/", $q)) 
{
	echo "<p class='font-weight-bold text-danger'>Invalid screenID. Try again.</p>";
	
} 
else if ($results > 0)
{
	echo "<p class='font-weight-bold text-danger'>ScreenID is already taken.</p>";

} 
else 
{
	echo "<p class='font-weight-bold text-success'>Available screenID!</p>";
}


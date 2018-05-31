<?php declare(strict_types = 1);

include_once '../config/db-config.php';
$database = new Database();
$db = $database->getConnection();

include_once 'model/Affiliate.php';

$affiliate = new Affiliate($db);

$affiliate->screenID = $_GET["q"];

$results = $affiliate->getNumRowByScreenID();

if($results > 0)
{
	echo "<p class='font-weight-bold text-success'>screenID exist!</p>";
}
else
{
	echo "<p class='font-weight-bold text-danger'>screenID does not exist.</p>";

}


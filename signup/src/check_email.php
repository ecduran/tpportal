<?php 
declare(strict_types = 1);

include_once '../config/db-config.php';

$database = new Database();

$db = $database->getConnection();

include_once 'model/Affiliate.php';

$affiliate = new Affiliate($db);

$q = $_GET['q'];

$affiliate->emailAdd = $q;

$results = $affiliate->checkEmailAdd();


if ( !(filter_var($q, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $q) ) )
{

	echo "<p class='font-weight-bold'>Invalid email. Try another one.</p>";
	

}
else if ($results > 0)
{

	echo "<p class='font-weight-bold'>Email is already taken.</p>";

}
else
{

	echo "<p class='font-weight-bold text-success'>Available Email!</p>";

}
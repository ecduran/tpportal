<?php declare(strict_types = 1);

include_once '../config/db-config.php';

$database = new Database();

$db = $database->getConnection();

include_once 'model/Country.php';

$country = new Country($db);

$results = $country->getAllCountry();

foreach($results as $row) 
{
	
	echo "<option value=".$row['code'].">".$row['country_name']."</option>";

} 
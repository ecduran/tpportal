<?php declare(strict_types = 1); 

include_once "../config/db-config.php";

$database = new Database();

$db = $database->getConnection();

include_once 'model/Affiliate.php';

$affiliate = new Affiliate($db);

$affiliate->screenID = $_POST['screenID'];

$results = $affiliate->getDataByScreenID();

echo json_encode($results);
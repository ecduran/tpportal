<?php

$country_login = "country/als20" . $country_c . ".php";

include($country_login);

require_once __DIR__.'/../vendor/phpxmlrpc/Autoloader.php';
PhpXmlRpc\Autoloader::register();

use PhpXmlRpc\Value;
use PhpXmlRpc\Request;
use PhpXmlRpc\Client;


$client = new Client("http://".$server."/xmlrpc/object");

//require_once '../vendor/phpxmlrpc-3/xmlrpc.inc';

//$client = new xmlrpc_client("http://".$server."/xmlrpc/object"); 

?>
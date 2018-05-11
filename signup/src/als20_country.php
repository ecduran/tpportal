<?php

$country_login = "country/als20" . $country_c . ".php";

include($country_login);

require_once('xmlrpc.inc');

$client = new xmlrpc_client("http://".$server."/xmlrpc/object"); 

?>
<?php

// OPENERP ACCESS

error_log("OPENERP COUNTRY LOGIN ". $openerp_country ,0);

switch($openerp_country)
	{
		CASE "MAIN":
			$server = '149.56.179.250:8069';  	// IP or host name and xml-rpc port of server
			$dbname = "tpportal";
			$user_id = 1;               		// Admin user always have database id 1. for Other users pleae check database id of user in OpenERP
			$opass =  "admin";					// Admin User Password
			break;

		CASE "CA":
			$server = '149.56.179.250:8069';
			$dbname = "tpportalca";
			$user_id = 1;               		// Admin user always have database id 1. for Other users pleae check database id of user in OpenERP
			$opass = "admin";					// Admin User Password
			break;
		
		CASE "US":
			$server = '149.56.179.250:8069';
			$dbname = "tpportalus";
			$user_id = 1;               		// Admin user always have database id 1. for Other users pleae check database id of user in OpenERP
			$opass = "admin";					// Admin User Password
			break;

		default:
			$server = '149.56.179.250:8069';
			$dbname = "tpportalca";
			$user_id = 1;               		// Admin user always have database id 1. for Other users pleae check database id of user in OpenERP
			$opass = "admin";					// Admin User Password
			break;

	}

?>

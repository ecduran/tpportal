<?php 

error_reporting(E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR);

require_once __DIR__.'/../vendor/phpxmlrpc/Autoloader.php';
PhpXmlRpc\Autoloader::register();

use PhpXmlRpc\Value;
use PhpXmlRpc\Request;
use PhpXmlRpc\Client;

include_once '../config/db-config.php';


$client = new Client("http://".$server."/xmlrpc/object");


function get_country_id($country_code)
{

  $database = new Database();
  $db = $database->getConnection();

  include_once '../src/model/Country.php';
  $country = new Country($db);
  $country->countryCode = $country_code;
   
  $result = $country->getCountryByCountryCode();

  foreach ($result as $value)
  {
  	$country_name  = $value['country_name'];
  }
   // $country_name = $row_country['country_name'];

	$condition = array(
					new PhpXmlRpc\Value(
						array(
							new PhpXmlRpc\Value('name','string'),
							new PhpXmlRpc\Value('=','string'),
							new PhpXmlRpc\Value($country_name,'string')
						),
					'array'),
	);

	$msg = new PhpXmlRpc\Request('execute');

	$msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'], "string"));
	$msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'], "int"));
	$msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'], "string"));
	$msg->addParam(new PhpXmlRpc\Value("res.country", "string"));
	$msg->addParam(new PhpXmlRpc\Value("search", "string"));
	$msg->addParam(new PhpXmlRpc\Value($condition, "array"));
	$resp = $GLOBALS['client']->send($msg);

	if ($resp->faultCode())
	{ 
    	echo 'OpenERP Server Error: '.$resp->faultString();
    	$resp = array();
	}
	else
	{
   		$resp = $resp->value();
    	$resp = $resp->scalarval();
	}  

    $country_id = False;

    foreach ($resp as $val)
    {
        $value = $val->scalarval();
        $country_id = $value;
        break;
    }   
    
    return $country_id;
}


function get_state_id($state_code, $country_code, $country_id)
{
    $database = new Database();
    $db = $database->getConnection();

    include_once '../src/model/State.php';
    $state = new State($db);

    $state->stateCode = $state_code;
    $state->countryCode = $country_code;
    $state->countryID = $country_id;


    $result = $state->getAllStateByCountryCode();

    if (!$result)
    {
        $state_name = "";
    }
    else
    {
        foreach ($result as $value) {
          $state_name = $value['state_name'];
        }
 
    }
    
	$condition = array(
					new PhpXmlRpc\Value(
						array(
							new PhpXmlRpc\Value('name','string'),
							new PhpXmlRpc\Value('=','string'),
							new PhpXmlRpc\Value($state_name,'string')
						),
					'array'),

					new PhpXmlRpc\Value(
						array(
							new PhpXmlRpc\Value('country_id','string'),
							new PhpXmlRpc\Value('=','string'),
							new PhpXmlRpc\Value($country_id,'int')
						),
					'array'),
  	);

	$msg = new PhpXmlRpc\Request('execute');

  $msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'], "string"));
  $msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'], "int"));
  $msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'], "string"));
  $msg->addParam(new PhpXmlRpc\Value("res.country.state", "string"));
	$msg->addParam(new PhpXmlRpc\Value("search", "string"));
	$msg->addParam(new PhpXmlRpc\Value($condition, "array"));
    $resp = $GLOBALS['client']->send($msg);

	if ($resp->faultCode())
	{ 
    	echo 'OpenERP Server Error: '.$resp->faultString();
    	$resp = array();
	}
	else
	{
    	$resp = $resp->value();
    	$resp = $resp->scalarval();
	}  

    $state_id = False;

    foreach ($resp as $val)
    {
        $value = $val->scalarval();
        $state_id = $value;
        break;
    }

    if (!$state_id)
    {

        $country_vals = array(
          	'name'	        => new PhpXmlRpc\Value($state_name ,"string"),
          	'country_id'	=> new PhpXmlRpc\Value($country_id ,"int"),
          	'code'        	=> new PhpXmlRpc\Value($state_code ,"string"),
        );    
    
        $msg = new PhpXmlRpc\Request('execute');
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'], "string"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'], "int"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'], "string"));
        $msg->addParam(new PhpXmlRpc\Value("res.country.state", "string"));
        $msg->addParam(new PhpXmlRpc\Value("create", "string"));
        $msg->addParam(new PhpXmlRpc\Value($country_vals, "struct"));
        $resp = $GLOBALS['client']->send($msg);

        if ($resp->faultCode())
        { 
             echo 'Server Error: '.$resp->faultString(); 
        }
        else
        { 
            $resp = $resp->value();
            $state_id = $resp->scalarval();
        }
    }

    return $state_id;
}


function create_update_address($partner_id, $addressVal)
{
	$condition = array(
					new PhpXmlRpc\Value(
						array(
							new PhpXmlRpc\Value("partner_id","string"),
							new PhpXmlRpc\Value("=","string"),
							new PhpXmlRpc\Value($partner_id,"string")
						),"array"
					)
	);

	$msg = new PhpXmlRpc\Request('execute');
    $msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'],"string"));
    $msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'],"int"));
    $msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'],"string"));
	$msg->addParam(new PhpXmlRpc\Value("res.partner.address","string"));
	$msg->addParam(new PhpXmlRpc\Value("search","string"));
	$msg->addParam(new PhpXmlRpc\Value($condition,"array"));
    $resp = $GLOBALS['client']->send($msg);


    if ($resp->faultCode())
	{ 
        echo 'OpenERP Server Error: '.$resp->faultString();
        $resp = array();
	}
	else
	{
        $resp = $resp->value();
        $resp = $resp->scalarval();
	}		
          
    $parnter_addr_id = False;

    foreach ($resp as $val)
    {
        $value = $val->scalarval();
        $partner_addr_id = $value;
        break;
    }

    if ($partner_addr_id)
    {
        $msg = new PhpXmlRpc\Request('execute');
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'], "string"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'], "int"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'], "string"));
        $msg->addParam(new PhpXmlRpc\Value("res.partner.address", "string"));
        $msg->addParam(new PhpXmlRpc\Value("write", "string"));
        $msg->addParam(new PhpXmlRpc\Value($partner_id, "int"));
        $msg->addParam(new PhpXmlRpc\Value($addressVal, "struct"));
        $resp = $GLOBALS['client']->send($msg);

          if ($resp->faultCode())
          { 
               echo 'Server Error: '.$resp->faultString(); 
          }
          else
          { 
              $resp = $resp->value();
              $resp = $resp->scalarval();
          }
    }
    else
    {
        $msg = new PhpXmlRpc\Request('execute');

        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'], "string"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'], "int"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'], "string"));
        $msg->addParam(new PhpXmlRpc\Value("res.partner.address", "string"));
        $msg->addParam(new PhpXmlRpc\Value("create", "string"));
        $msg->addParam(new PhpXmlRpc\Value($addressVal, "struct"));
        $resp = $GLOBALS['client']->send($msg);

          if ($resp->faultCode())
          { 
               echo 'Server Error: '.$resp->faultString(); 
          }
          else
          { 
              $resp = $resp->value();
              $addr_id = $resp->scalarval();
          }
    }

}

function create_update_affiliate($vals)
{
	extract($vals);

	$country_id = get_country_id($country);

	$state_id = get_state_id($state, $country, $country_id);

	$addressVal = array(
						'city' 			=> new PhpXmlRpc\Value($city, "string"),
						'country_id' 	=> new PhpXmlRpc\Value($country_id, "int"),
						'name'			=> new PhpXmlRpc\Value($first_name." ".$last_name,"string"),
						'state_id'		=> new PhpXmlRpc\Value($state_id,"int"),
						'street' 		=> new PhpXmlRpc\Value($address,"string"),
						'street2'		=> new PhpXmlRpc\Value($unit,"string"),
						'zip'			=> new PhpXmlRpc\Value($zip,"string")
	);

	$partnerVal = array(
						'name'				=> new PhpXmlRpc\Value($first_name." ".$last_name,"string"),
						'last_name'			=> new PhpXmlRpc\Value($last_name,"string"),
						'first_name'		=> new PhpXmlRpc\Value($first_name,"string"),
						'reg_id'			=> new PhpXmlRpc\Value($id,"int"),
						'username'			=> new PhpXmlRpc\Value($username,"string"),
						'email'				=> new PhpXmlRpc\Value($email,"string"),
						'sponsor_id' 		=> new PhpXmlRpc\Value($sponsor_id,"int"),
						'sponsor_username' 	=> new PhpXmlRpc\Value($sponsor_username,"string")	
	);


	$contextVal = array(
						'lang'		=> new PhpXmlRpc\Value('en_US',"string"),
						'tz'		=> new PhpXmlRpc\Value(0,"boolean"),
						'hc'		=> new PhpXmlRpc\Value(1,"boolean")	
	);


	$condition = array(
						new PhpXmlRpc\Value(array(
								new PhpXmlRpc\Value("username","string"),
								new PhpXmlRpc\Value("=","string"),
								new PhpXmlRpc\Value($username,"string")	
						),"array"),

						new PhpXmlRpc\Value(array(
								new PhpXmlRpc\Value("reg_id","string"),
								new PhpXmlRpc\Value("=","string"),
								new PhpXmlRpc\Value($reg_id,"string")
						),"array")
	);


	$msg = new PhpXmlRpc\Request('execute');

	$msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'],"string"));
	$msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'],"int"));
	$msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'],"string"));
	$msg->addParam(new PhpXmlRpc\Value("res.partner","string"));
	$msg->addParam(new PhpXmlRpc\Value("search","string"));
	$msg->addParam(new PhpXmlRpc\Value($condition,"array"));
	$resp = $GLOBALS['client']->send($msg);

	if ($resp->faultCode())
	{
		echo 'OpenERP Server Error: '.$resp->faultString();
		$resp = array();
	}
	else
	{
		$resp = $resp->value();
		$resp = $resp->scalarval();
	}

	$partner_id = False;

	foreach($resp as $val)
	{
		$value = $val->scalarval();
		$partner_id = $value;
		break;
	}


	if ($partner_id)
	{
		$msg = new PhpXmlRpc\Request('execute');
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'],"string"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'],"int"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'],"string"));
        $msg->addParam(new PhpXmlRpc\Value("res.partner","string"));
        $msg->addParam(new PhpXmlRpc\Value("write","string"));
        $msg->addParam(new PhpXmlRpc\Value($partner_id,"int"));
        $msg->addParam(new PhpXmlRpc\Value($partnerVal,"struct"));
        $msg->addParam(new PhpXmlRpc\Value($contextVal,"struct"));
        $resp = $GLOBALS['client']->send($msg);

          if ($resp->faultCode()){ 
               echo 'Server Error: '.$resp->faultString(); 
          }else{ 
              $resp = $resp->value();
              $openerp_id = $resp->scalarval();
          }
	}
	else
	{
        $msg = new PhpXmlRpc\Request('execute');
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['dbname'], "string"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['user_id'], "int"));
        $msg->addParam(new PhpXmlRpc\Value($GLOBALS['opass'], "string"));
        $msg->addParam(new PhpXmlRpc\Value("res.partner", "string"));
        $msg->addParam(new PhpXmlRpc\Value("create", "string"));
        $msg->addParam(new PhpXmlRpc\Value($partnerVal, "struct"));
        $msg->addParam(new PhpXmlRpc\Value($contextVal, "struct"));
        $resp = $GLOBALS['client']->send($msg);

          if ($resp->faultCode())
          { 
              echo 'Server Error: '.$resp->faultString(); 
          }
          else
          { 
              $resp = $resp->value();
              $partner_id = $resp->scalarval();
          }		
	}

	$addressVal['partner_id'] = new PhpXmlRpc\Value($partner_id,"int");

	create_update_address($openerp_id, $addressVal);
}


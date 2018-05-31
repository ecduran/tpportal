<?php 

include_once '../config/db-config.php';

require_once '../vendor/phpxmlrpc-3/xmlrpc.inc';

$client = new xmlrpc_client("http://".$server."/xmlrpc/object"); 

function get_country_id($country_code)
{
/*    $sql_country = "SELECT * FROM tpp_main.country_codes WHERE code='$country_code'";
    $res_country = mysql_query($sql_country) or die(mysql_error());
    $row_country = mysql_fetch_assoc($res_country);

    $country_name = $row_country['country_name'];*/


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

		$condition = array(
						new xmlrpcval(array(new xmlrpcval('name','string'),new xmlrpcval('=','string'),new xmlrpcval($country_name,'string')),'array'),
	  );

		$msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
    $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
    $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
		$msg->addParam(new xmlrpcval("res.country", "string"));
		$msg->addParam(new xmlrpcval("search", "string"));
		$msg->addParam(new xmlrpcval($condition, "array"));
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
/*	$state_code = addslashes($state_code);
    $sql_state = "SELECT * FROM tpp_main.statedb WHERE country_code like '%$country_code%' AND state='$state_code'";
    
    $res_state = mysql_query($sql_state) or die(mysql_error());
    $row_state = mysql_fetch_assoc($res_state);
                      
    $state_name = addslashes($row_state['state_name']);*/

    $database = new Database();
    $db = $database->getConnection();

    include_once '../src/model/State.php';
    $state = new State($db);

    $state->stateCode = $state_code;
    $state->countryCode = $country_code;
    $state->countryID = $country_id;


    $result = $state->getAllStateByCountryCode();

		$condition = array(
						new xmlrpcval(array(new xmlrpcval('name','string'),new xmlrpcval('=','string'),new xmlrpcval($state_name,'string')),'array'),
						new xmlrpcval(array(new xmlrpcval('country_id','string'),new xmlrpcval('=','string'),new xmlrpcval($country_id,'int')),'array'),
	  );

		$msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
    $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
    $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
		$msg->addParam(new xmlrpcval("res.country.state", "string"));
		$msg->addParam(new xmlrpcval("search", "string"));
		$msg->addParam(new xmlrpcval($condition, "array"));
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
          	'name'	        => new xmlrpcval($state_name ,"string"),
          	'country_id'	=> new xmlrpcval($country_id ,"int"),
          	'code'        =>  new xmlrpcval($state_code ,"string"),
        );    
    
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
        $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
        $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
        $msg->addParam(new xmlrpcval("res.country.state", "string"));
        $msg->addParam(new xmlrpcval("create", "string"));
        $msg->addParam(new xmlrpcval($country_vals, "struct"));
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
						new xmlrpcval(array(new xmlrpcval('partner_id','string'),new xmlrpcval('=','string'),new xmlrpcval($partner_id,'string')),'array'),
	  );

		$msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
    $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
    $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
		$msg->addParam(new xmlrpcval("res.partner.address", "string"));
		$msg->addParam(new xmlrpcval("search", "string"));
		$msg->addParam(new xmlrpcval($condition, "array"));
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
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
        $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
        $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
        $msg->addParam(new xmlrpcval("res.partner.address", "string"));
        $msg->addParam(new xmlrpcval("write", "string"));
        $msg->addParam(new xmlrpcval($partner_id, "int"));
        $msg->addParam(new xmlrpcval($addressVal, "struct"));
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
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
        $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
        $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
        $msg->addParam(new xmlrpcval("res.partner.address", "string"));
        $msg->addParam(new xmlrpcval("create", "string"));
        $msg->addParam(new xmlrpcval($addressVal, "struct"));
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
      	'city'	        => new xmlrpcval($city ,"string"),
      	'country_id'	=> new xmlrpcval($country_id ,"int"),
        'name'      	=> new xmlrpcval($first_name." ".$last_name ,"string"),
        'state_id'      => new xmlrpcval($state_id ,"int"),
        'street'        => new xmlrpcval($address,"string"),
        'street2'       => new xmlrpcval($unit,"string"),
        'zip'           => new xmlrpcval($zip,"string"),  
    );    

    $partnerVal = array(
                            'name'        => new xmlrpcval($first_name." ".$last_name ,"string"),
                            'last_name' 	=> new xmlrpcval($last_name ,"string"),
                            'first_name'  => new xmlrpcval($first_name ,"string"),
                            'reg_id'          => new xmlrpcval($id,"int"),
                            'username'    => new xmlrpcval($username,"string"),
                            'email'      	=> new xmlrpcval($email ,"string"),

                            'sponsor_id'          => new xmlrpcval($sponsor_id1,"int"),
                            'sponsor_username'    => new xmlrpcval($sponsor_username,"string"),
      );

      $contextVal = array(
                            'lang'  => new xmlrpcval('en_US' ,"string"),
                            'tz' => new xmlrpcval(0 ,"boolean"), 
                            'hc' => new xmlrpcval(1 ,"boolean")
                          );

    //First search if its already exist, if its exist then update it

		$condition = array(
						new xmlrpcval(array(new xmlrpcval('username','string'),new xmlrpcval('=','string'),new xmlrpcval($username,'string')),'array'),
						new xmlrpcval(array(new xmlrpcval('reg_id','string'),new xmlrpcval('=','string'),new xmlrpcval($id,'string')),'array'),
	  );


		$msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
    $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
    $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
		$msg->addParam(new xmlrpcval("res.partner", "string"));
		$msg->addParam(new xmlrpcval("search", "string"));
		$msg->addParam(new xmlrpcval($condition, "array"));
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

    $parnter_id = False;
    foreach ($resp as $val)
    {
        $value = $val->scalarval();
        $partner_id = $value;
        break;
    }

    if ($partner_id)
    {
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
        $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
        $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
        $msg->addParam(new xmlrpcval("res.partner", "string"));
        $msg->addParam(new xmlrpcval("write", "string"));
        $msg->addParam(new xmlrpcval($partner_id, "int"));
        $msg->addParam(new xmlrpcval($partnerVal, "struct"));
        $msg->addParam(new xmlrpcval($contextVal, "struct"));
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
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
        $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
        $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
        $msg->addParam(new xmlrpcval("res.partner", "string"));
        $msg->addParam(new xmlrpcval("create", "string"));
        $msg->addParam(new xmlrpcval($partnerVal, "struct"));
        $msg->addParam(new xmlrpcval($contextVal, "struct"));
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
    
    $addressVal['partner_id'] =  new xmlrpcval($partner_id ,"int");
    
    create_update_address($openerp_id, $addressVal);

}
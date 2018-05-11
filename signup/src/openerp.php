<?php
ini_set('display_errors', 0);
// OPENERP ACCESS
include "openerp_login.php";


$sales_journal    = 2;
$expense_journal  = 4;


require_once('xmlrpc.inc');

$client = new xmlrpc_client("http://".$server."/xmlrpc/object"); 


function get_list($object, $condition=array())
{
		
		$msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
    $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
    $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
		$msg->addParam(new xmlrpcval($object, "string"));
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

    $fields = array(new xmlrpcval('code','string'), new xmlrpcval('name','string'));
		
		$msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
    $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
    $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
		$msg->addParam(new xmlrpcval($object, "string"));
		$msg->addParam(new xmlrpcval("read", "string"));
		$msg->addParam(new xmlrpcval($resp, "array"));
		$msg->addParam(new xmlrpcval($fields, "array"));
		$resp = $GLOBALS['client']->send($msg);

		if ($resp->faultCode())
		{ 
			echo 'OpenERP Server Error: '.$resp->faultString();
		}
		else
		{
			  $resp = $resp->value();
			  $resp = $resp->scalarval();
		}		
		
		$ret_array = array();
    foreach ($resp as $value)
    {
	      $data = $value->scalarval();
	      $ret_array[$data['code']->scalarval()] = $data['name']->scalarval();
    }
		
		return $ret_array;
}

function create_move($journal, $invoice_no, $move_lines, $date=False)
{
    if ( $journal == 'sale')
        $journal_id = 3;
    elseif ( $journal == 'expense')
        $journal_id = 2;
    elseif ( $journal == 'payment')
        $journal_id = 4;

    if (!$date)
      $date = date('m/d/Y');            

    $moveVal = array(   
                        'journal_id' => new xmlrpcval($journal_id ,"int"),
                        'narration'  => new xmlrpcval('' ,"string"),
                        'ref'        => new xmlrpcval($invoice_no ,"string"),
                        'line_id'    => new xmlrpcval($move_lines,"array"),
                        'date'       => new xmlrpcval($date ,"string"),
                    );

    $contextVal = array(
                          'lang'  => new xmlrpcval('en_US' ,"string"),
                          'tz'    => new xmlrpcval(0 ,"boolean"),
                          'hyper_cash' => new xmlrpcval(1 ,"boolean")
                       );
error_log("JOURNAL ID " . $journal_id,0);
error_log("INVOICE NO " . $invoice_no,0);
error_log("DATE " . $date,0);
error_log("DBNAME " . $GLOBALS['dbname'],0);
error_log(print_r($move_lines,TRUE));

    $msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
    $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
    $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
    $msg->addParam(new xmlrpcval("account.move", "string"));
    $msg->addParam(new xmlrpcval("create", "string"));
    $msg->addParam(new xmlrpcval($moveVal, "struct"));
    $msg->addParam(new xmlrpcval($contextVal, "struct"));
    $resp = $GLOBALS['client']->send($msg);

    if ($resp->faultCode()) 
         echo 'Server Error: '.$resp->faultString(); 
    else 
        $resp = $resp->value();
        $resp = $resp->scalarval();
}


function create_movemain($journal, $invoice_no, $country_sale, $division_code, $inventory_type, $move_lines, $date=False)
{
    if ( $journal == 'sale')
        $journal_id = 3;
    elseif ( $journal == 'expense')
        $journal_id = 2;
    elseif ( $journal == 'payment')
        $journal_id = 4;

    if (!$date)
      $date = date('m/d/Y');            

    $moveVal = array(   
                        'journal_id'       => new xmlrpcval($journal_id ,"int"),
                        'narration'        => new xmlrpcval('' ,"string"),
                        'ref'              => new xmlrpcval($invoice_no ,"string"),
                        'line_id'          => new xmlrpcval($move_lines,"array"),
                        'date'             => new xmlrpcval($date ,"string"),
                        'country_code'     => new xmlrpcval($country_sale,"string"),
                        'division_code'    => new xmlrpcval($division_code,"string"),
                        'inventory_code'   => new xmlrpcval($inventory_type ,"string"),
                    );

    $contextVal = array(
                          'lang'  => new xmlrpcval('en_US' ,"string"),
                          'tz'    => new xmlrpcval(0 ,"boolean"),
                          'hyper_cash' => new xmlrpcval(1 ,"boolean")
                       );
error_log("JOURNAL ID " . $journal_id,0);
error_log("INVOICE NO " . $invoice_no,0);
error_log("DATE " . $date,0);
error_log("DBNAME " . $GLOBALS['dbname'],0);
error_log("IN CREATE_MOVEMAIN",0);
;

    $msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($GLOBALS['dbname'], "string"));
    $msg->addParam(new xmlrpcval($GLOBALS['user_id'], "int"));
    $msg->addParam(new xmlrpcval($GLOBALS['opass'], "string"));
    $msg->addParam(new xmlrpcval("account.move", "string"));
    $msg->addParam(new xmlrpcval("create", "string"));
    $msg->addParam(new xmlrpcval($moveVal, "struct"));
    $msg->addParam(new xmlrpcval($contextVal, "struct"));
    $resp = $GLOBALS['client']->send($msg);

    if ($resp->faultCode()) 
         echo 'Server Error: '.$resp->faultString(); 
    else 
        $resp = $resp->value();
        $resp = $resp->scalarval();
}


function get_move_lines($customer_ref, $reference, array $move_lines, $name='/', $type='cr')
{
    $lines_array = array();
    foreach ($move_lines as $acc_id => $amt)
    {
            $lines_array[] = getline($customer_ref, $reference, $acc_id, $amt, $name, $type);
    }
    return $lines_array;
}



function getline($customer_ref, $reference, $accout_id, $amount, $name='/', $type='cr')
{
			    $linveVal = array (
                        'account_id'    =>new xmlrpcval($accout_id , "string"),
                        'ref_num'       =>new xmlrpcval($customer_ref , "string"),
                        'ref'           =>new xmlrpcval($reference , "string"),
                        'name'          =>new xmlrpcval($name, "string")
                    );


      if ($type == 'dr')
          $linveVal['debit']  = new xmlrpcval($amount, "double");
      else
          $linveVal['credit'] = new xmlrpcval($amount, "double");

      return new xmlrpcval(array(new xmlrpcval("0" , "int"), new xmlrpcval("0" , "int"), new xmlrpcval($linveVal,'struct')),'array');
}

function getlinemain($customer_ref, $reference, $accout_id, $amount, $name='/', $country_sale, $business_code, $inventory_type, $type='cr')
{
		    $linveVal = array (
                        'account_id'     =>new xmlrpcval($accout_id , "string"),
                        'ref_num'        =>new xmlrpcval($customer_ref , "string"),
                        'ref'            =>new xmlrpcval($reference , "string"),
                        'name'           =>new xmlrpcval($name, "string"),
                        'country_code'   =>new xmlrpcval($country_sale, "string"),
                        'division_code'  =>new xmlrpcval($business_code, "string"),
                        'inventory_code' =>new xmlrpcval($inventory_type, "string")
                    );


      if ($type == 'dr')
          $linveVal['debit']  = new xmlrpcval($amount, "double");
      else
          $linveVal['credit'] = new xmlrpcval($amount, "double");

      return new xmlrpcval(array(new xmlrpcval("0" , "int"), new xmlrpcval("0" , "int"), new xmlrpcval($linveVal,'struct')),'array');
}

function get_country_id($country_code)
{
    $sql_country = "SELECT * FROM tpp_main.country_codes WHERE code='$country_code'";
    $res_country = mysql_query($sql_country) or die(mysql_error());
    $row_country = mysql_fetch_assoc($res_country);

    $country_name = $row_country['country_name'];

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
	$state_code = addslashes($state_code);
    $sql_state = "SELECT * FROM tpp_main.statedb WHERE country_code like '%$country_code%' AND state='$state_code'";
    
    $res_state = mysql_query($sql_state) or die(mysql_error());
    $row_state = mysql_fetch_assoc($res_state);
                      
    $state_name = addslashes($row_state['state_name']);

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

function sync_affiliates($res_all)
{
    while($row = mysql_fetch_assoc($res_all))
    {
        create_update_affiliate($row);
    }
}


?>

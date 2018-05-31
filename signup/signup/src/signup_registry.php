<?php session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/Exception.php';
require '../vendor/phpmailer/PHPMailer.php';
require '../vendor/phpmailer/SMTP.php';


$sponsorName = filter_var($_SESSION['sponsorName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$sponsorScreenID = filter_var($_SESSION['sponsorScreenID'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$screenID = filter_var($_SESSION['screenID'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$countryCode = filter_var($_SESSION['countryCode'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$countryName = filter_var($_SESSION['countryName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$req = array('companyType', 'firstName', 'lastName', 'genderType', 'email1', 'email2', 'password1', 'password2', 'captcha_code');

$error = false;
foreach ($req as $field) {
	if(empty($_POST[$field]))
	{
		$error = true;
	}
}

if ($error)
{
	return false;
}
else
{

$companyName = filter_var($_POST['companyName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$companyType = filter_var($_POST['companyType'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$nameTitle = filter_var($_POST['nameTitle'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$firstName = filter_var($_POST['firstName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$middleName = filter_var($_POST['middleName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$lastName = filter_var($_POST['lastName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$gender = filter_var($_POST['genderType'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$email = filter_var($_POST['email2'], FILTER_SANITIZE_EMAIL);

$password1 = $_POST['password2'];

$password = password_hash($password1, PASSWORD_DEFAULT);


$fullName = $firstName." ".$lastName;


$str1 = substr($lastName, 0,2);
$str2 = date("His");
$str3 = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 4);

$username = $str1.$str2.$str3;


$signupDate = date("Y-m-d H:i:s");



if(!empty($_SERVER['HTTP_CLIENT_IP']))
{
    $ipAdd = $_SERVER['HTTP_CLIENT_IP'];
}
else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
{
    $ipAdd = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
    $ipAdd = $_SERVER['REMOTE_ADDR'];
}


$affiliateType = "Customer"; 


$token = md5(time());



include_once '../config/db-config.php';

$db = new Database();

$db = $db->getConnection();

include_once '../src/model/Affiliate.php';

$affiliate = new Affiliate($db);

$affiliate->sponsorScreenID = $sponsorScreenID;
$affiliate->screenID = $screenID;
$affiliate->signupDate = $signupDate;
$affiliate->companyName = $companyName;
$affiliate->companyType = $companyType;
$affiliate->title = $nameTitle;
$affiliate->firstName = $firstName;
$affiliate->middleName = $middleName;
$affiliate->lastName = $lastName;
$affiliate->gender = $gender;
$affiliate->emailAdd = $email;
$affiliate->username = $username;
$affiliate->password = $password; //hashed password 
$affiliate->password1 = $password1; //unhashed password
$affiliate->affiliateType = $affiliateType; //customer type column 
$affiliate->ipAdd = $ipAdd;
$affiliate->token = $token;
$affiliate->country = $countryCode;
$affiliate->lastUpdate = $signupDate;

$affiliate->createAffiliate(); // insert user info to the db 


$affiliate->copyData(); 

	
$server = '149.56.179.250:8069';    // IP or host name and xml-rpc port of server
$dbname = "tpportal";
$user_id = 1;                       // Admin user always have db id 1. for Other users pleae check db id of user in OpenERP
$opass =  "admin";          // Admin User Password
$openerp_country = "MAIN";


include_once 'openerp_test.php'; //UPDATE ALS20 OPENERP FIRST

$row_data = $affiliate->getDataByUsername();

create_update_affiliate($row_data);

include_once '../src/model/Openerp.php';

$openerp = new Openerp($db);

$openerpResult = $openerp->getCountryCode();

error_log(print_r($openerpResult,TRUE));

foreach ($openerpResult as $value) {

    $country_c = strtolower($value['country_code']);
    error_log(print_r($country_c,TRUE));
    include 'als20_country.php';
    error_log(print_r("Above",TRUE));
    create_update_affiliate($row_data);
    error_log(print_r("Below",TRUE));
}

include_once '../src/model/Config.php';

$config = new Config($db);

$results = $config->getData();

foreach ($results as $value) {
    $fromName = $value['from_name'];
    $fromEmail = $value['from_email'];
    $fromAddress = $value['cycler_url'];
}
	

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions

try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.sparkpostmail.com';	              // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'SMTP_Injection';                   // SMTP username
    $mail->Password = '71772dfc12a2c2314d5beddd146813137b7a2ce9';// SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($email, $fullName);     			  // Add a recipient

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'TP Portal Email Confirmation';
    $mail->Body    = "<html>
                              <body>
								Dear <b>$fullName</b> <br><br>
								Thank you for your interest in our company with your registration. <br><br>
								The following information can not be changed once you confirm your email. Please verify the information is correct before you press \"confirm\" below:<br><br>
								Sponsor's Name: <b> $sponsorName </b> <br><br>
								Sponsor's Screen ID: <b> $sponsorScreenID </b> <br><br>
								Your Name: <b> $firstName $lastName </b> <br><br>
								Your Screen ID: <b> $screenID </b> <br><br>
								Company Name ( if you are a Company) <b>$companyName</b> <br><br>
								If you find an error please contact admin@tpportal.biz and explain the error <br><br>

								Please click only once on the link below to confirm your registration. Confirm; with your confirmation you are stating that the registration information is true and accurate.
								<a href='http://173.208.200.236/confirmsignup.php?id=$screenID&&token=$token'>Confirm</a>
                              </body>
                            </html>";


    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    //$mail->send();
} 
catch (Exception $e) 
{
     echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
   



$mail = new PHPMailer(true);                              // Passing `true` enables exceptions

try
{
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.sparkpostmail.com';               // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'SMTP_Injection';                   // SMTP username
    $mail->Password = '71772dfc12a2c2314d5beddd146813137b7a2ce9';// SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $emailAdmin = 'webadmin@itaprivate.com'; 
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($emailAdmin);                       // Add a recipient


    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'NEW Customer SIGN UP Not Verified';
    $mail->Body = "<html>
                        <body>
                            Date: <b>$signupDate</b> <br>
                            Screen ID: <b>$screenID</b> <br>
                            Email: <b>$email</b><br>
                            IP Address: <b>$ipAdd</b> <br>
                            Country: <b>$countryName</b><br>  
                            Sponsor's Screen ID: <b>$sponsorScreenID</b> <br>
                        </body>
                   </html>";

    //$mail->send();

} 
catch (Exception $e)
{
     echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}



echo "../public/registration_successful.php";
}

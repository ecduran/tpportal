<?php session_start();

$screenID = $_SESSION['screenID'];

include_once '../config/db-config.php';

$database = new Database();

$db = $database->getConnection();

include_once '../src/model/Affiliate.php';

$affiliate = new Affiliate($db);

$affiliate->screenID = $screenID;

$result = $affiliate->getDataByScreenID();


foreach ($result as $value) {
	$email = $value['email'];
}

include_once 'templates/header.php';

?>

<div class="container">
	<p>Registration Successful!</p>
	<p>A confirmation email was sent to your email address below.
	Please click the link in the mail to activate your account.</p>
	<p>This must be done before you can login.</p>
	<p>Your Email: <?php echo $email; ?></p>
	<p>Please Note: If you do not receive the email, First check your spam folder.<br>You can also request another email confirmation from the Login Page.</p>
	<p>If you made a mistake and used the wrong email address (misspelled it) you can use our Email Correction System.</p>
	<p></p>
</div>
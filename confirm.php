<?
// confirm.php

require_once('classes/EmailRegistration.class.php');

// let's define some outcomes for this page
define('STATUS_DEFAULT', 0);
define('STATUS_SUCCESS', 1);
define('STATUS_ERROR', 2);
$status = STATUS_DEFAULT;

$confirmationCode = $_GET['confirmationCode'];

$EmailRegistration = new EmailRegistration();
try {
	$EmailRegistration->fetchByConfirmationCode($confirmationCode);
	$EmailRegistration->confirm();
	
	$Email = new Email();
	$Email->subject = "Confirm Registration";
	$Email->recipient = $EmailRegistration->email;
	$Email->sender = 'noreply@example.com';
	$Email->message_html = file_get_contents('emails/confirm.htm');
	$Courier = new Courier();
	$Courier->send($Email);
	
	$status = STATUS_SUCCESS;
} catch(Exception $e) {
	$status = STATUS_ERROR;
}

switch($status) {
	case STATUS_SUCCESS:
?>
	
	<h1>Success</h1>
	<p>Your email address, <?= $EmailRegistration->email; ?> has been confirmed.</p>
	
<?
	
	break;
	
	case STATUS_ERROR:
?>
	
	<h1>Error</h1>
	<p>There was a problem confirming your registration.</p>
	
<?
	
	break;
	
	default:
?>
	
	
	<h1>Hello</h1>
	<p>You have come to this page in error.</p>
	
	
<?
}

?>
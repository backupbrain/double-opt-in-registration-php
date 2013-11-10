<?
// unsubscribe.php

require_once('classes/EmailRegistration.class.php');

// let's define some outcomes for this page
define('STATUS_DEFAULT', 0);
define('STATUS_SUCCESS', 1);
define('STATUS_ERROR', 2);
$status = STATUS_DEFAULT;

$email = $_GET['email'];

$EmailRegistration = new EmailRegistration();
try {
	$EmailRegistration->fetchByEmail($email);
	$EmailRegistration->unsubscribe();
	$status = STATUS_SUCCESS;
} catch(Exception $e) {
	$status = STATUS_ERROR;
}

switch($status) {
	case STATUS_SUCCESS:
?>
	
	<h1>Success</h1>
	<p>Your email address, <?= $EmailRegistration->email; ?> has been unsubscribed.</p>
	
<?
	
	break;
	
	case STATUS_ERROR:
?>
	
	<h1>Error</h1>
	<p>There was a problem unsubscribing your email address.</p>
	
<?
	
	break;
	
	default:
?>
	
	
	<h1>Hello</h1>
	<p>You have come to this page in error.</p>
	
	
<?
}

?>
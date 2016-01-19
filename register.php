<?
// register.php

require_once('classes/Email.class.php');
require_once('classes/Courier.class.php');
require_once('classes/EmailRegistration.class.php');

if ($_POST) {
	$email = $_POST['email'];
	
	$EmailRegistration = new EmailRegistration();
	try {
		$EmailRegistration->initialize($email);
		$Email = new Email();
		$Email->subject = "Confirm Registration";
		$Email->recipient = $EmailRegistration->email;
		$Email->sender = 'noreply@example.com';
		$Email->message_html = file_get_contents('emails/register.htm');
		$Courier = new Courier();
		$Courier->send($Email);
		$registered = true;
	} catch (Exception $e) {
		$email_error = true;
	}
}

// if the user has registered, let them know it worked
if ($registered) { 
?>
<h1>Thank You</h1>
<p>Your registration has been processed.</p>
<p>You will receive an email with instructions to confirm your registration.</p>
<?
} else { // otherwise display the form
?>
<form action="register.php" method="post">
<?
if ($email_error) {
?>
<div class="error">You entered an invalid email address.</div>
<?
}
?>
<input type="email" name="email" id="email" placeholder="email@example.com" value="<?= htmlentities($email); ?>" />
<input type="submit" value="Subscribe" />
</form>
<? } // registered ?>

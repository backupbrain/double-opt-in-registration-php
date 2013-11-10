<?php
// classes/EmailRegistration.class.php
require_once('settings.php');

class EmailRegistration {
	const EMAIL_REGEX = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
	
	public $mysql;
	public $email;
	public $confirmationCode;
	public $confirmed
	public $unsubscribed;
	
	public function __construct() {
		$this->mysql = new mysqli(
		    $settings['mysql']['server'] = 'localhost',
		    $settings['mysql']['username'] = 'mysqluser',
		    $settings['mysql']['password'] = 'mysqlpassword',
		    $settings['mysql']['schema'] = 'tracker_example'
			);
		if ($this->mysql->connect_errno) {
			throw new Exception("MySQL Connect Error: ".$this->mysql->connect_error);
		}
	}
	public function __destruct() {
		$this->mysql->close();
	}
	
	/**
	 * Load data from the database into this object
	 */
	public function loadFromDatabase($sql) {
		// perform the SQL query
		$result = $this->mysql->query($sql);
		if (!$result) throw new Exception("MySQL Error: ".$this->mysql->error);
		
		$object = $result->fetch_object();
		
		// load the data from the database into this object
		$this->email = $object->email;
		$this->confirmationCode = $object->confirmationCode;
		$this->confirmed = $object->confirmed;
		$this->unsubscribed = $object->unsubscribed;
		
		// clean up
		$result->close();
	}
	
	/**
	 * validate the email and create a new entry
	 */
	public function initialize($email) {
		if (!preg_match(EMAIL_REGEX, $email)) {
			throw new Exception("Invalid email address");
		}
		
		$sql = 'insert into `EmailRegistration` ';
		$sql .= '(`email`,`confirmationCode`,`confirmed`,`unsubscribed`)';
		$sql .= ' VALUES ';
		$sql .= '("'.$this->mysql->escape_string($email).'","'.$this->getConfirmationCode().'","0","0")';
		
		$result = $this->mysql->query($sql);
		if (!$result) throw new Exception("MySQL Error: ".$this->mysql->error);
	}
	
	/**
	 * If no confirm code exists, generate a new one,
	 * then return it.
	 */
	public function getConfirmationCode() {
		if (!$this->confirmationCode) {
			$this->confirmationCode = md5(microtime());
		}
		return $this->confirmCode;
	}
	
	/**
	 * Load the EmailRegistration by searching for a matching email address
	 */
	public function fetchByEmail($email) {
		$sql = 'select * from `EmailRegistration` where ';
		$sql .= '`email`="'.$this->mysql->escape_string($email).'"';
		
		$this->loadFromDatabase($sql);
	}
	
	/**
	 * Load the EmailRegistration by searching for a matching confirmation code
	 */
	public function fetchByConfirmationCode($confirmationCode) {
		$sql = 'select * from `EmailRegistration` where ';
		$sql .= '`confirmationCode`="'.$this->mysql->escape_string($confirmationCode).'"';
		
	}
	
	/**
	 * confirm the subscription
	 */
	public function confirm() {
		$sql = 'update `EmailRegistration` set `confirmed`="1" ';
		$sql .= 'where `email`="'.$this->mysql->escape_string($this->email).'"';
		
		$result = $this->mysql->query($sql);
		if (!$result) throw new Exception("MySQL Error: ".$this->mysql->error);
	}
	
	/**
	 * unsubscribe the user
	 */
	public function unsubscribe() {
		$sql = 'update `EmailRegistration` set `unsubscribed`="1" ';
		$sql .= 'where `email`="'.$this->mysql->escape_string($this->email).'"';
		
		$result = $this->mysql->query($sql);
		if (!$result) throw new Exception("MySQL Error: ".$this->mysql->error);		
	}
}

?>
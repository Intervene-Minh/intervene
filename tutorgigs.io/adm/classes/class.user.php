<?php
require_once ('resources/databasePDO.php');
require_once ('class.phpmailer.php');
class User extends DatabasePDO {
	private $user_name;
	private $password;
	private $email;
	private $type;
    private $table;
	
    
    function __construct($type){
    	$this->type=$type;
    	if(strtoupper($type)=="T"){
    		$this->table="tutorUsers";
    	}else{
    		$this->table="studentUsers";
    	}
    }
	/**
	 * Checks if email exists in database
	 *
	 * @param String $email        	
	 */
	public function IsValidUserEmail($email) {
		$sql = "Select id from ".$this->table." where email=:email";
		try {
			$db = $this->getConnection ();
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( "email", $email );
			$stmt->execute ();
			$u = $stmt->fetch ();
			if ($u ['id']) {
				return 1;
			} else {
				return 0;
			}
		} catch ( PDOException $e ) {
			echo $sql."<br/>";
			echo $e->getMessage ();
			return 0;
		}
	}
	
	/**
	 *
	 * @param String $email        	
	 *
	 *
	 */
	public function BeginPasswordRecovery($email) {
		// generate verify code from email
		$code = $this->generateVerifyCode ();
		
		// save code to associated email addess
		$sql = "update ".$this->table." set verify_code=:code where email=:email";
		try {
			$db = $this->getConnection ();
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( "code", $code );
			$stmt->bindParam ( "email", $email );
			$stmt->execute ();
			// send email with code
			$this->sendPasswordRecoveryEmail ( $email, $code );
		} catch ( PDOException $e ) {
			return false;
		}
		return true;
	}
	
	/**
	 *
	 * check if password recovery code is valid for email address
	 *
	 * @param String $code        	
	 * @param String $email        	
	 * @return boolean
	 *
	 *
	 */
	public function HasBeganRecovery($code, $email) {
		$sql = "select * from ".$this->table." where verify_code=:code and email=:email";
		try {
			$db = $this->getConnection ();
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( "code", $code );
			$stmt->bindParam ( "email", $email );
			$stmt->execute ();
			$u = $stmt->fetch ();
			if ($u) {
				return true;
			}
		} catch ( PDOException $e ) {
			return false;
		}
		return false;
	}
	
	/**
	 * update password with verification code and email, and then remove verification code
	 *
	 * @param String $code        	
	 * @param String $email        	
	 * @param String $password        	
	 *
	 *
	 */
	public function ChangePassword($code, $email, $password) {
		$salt=dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $this->hashPassword ( $password,$salt );
		$sql = "update ".$this->table." set salt=:salt,password=:password,verify_code=null where verify_code=:code and email=:email";
		try {
			$db = $this->getConnection ();
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( "salt", $salt );
			$stmt->bindParam ( "password", $pass );
			$stmt->bindParam ( "email", $email );
			$stmt->bindParam ( "code", $code );
			$stmt->execute ();
		} catch ( PDOException $e ) {
			return false;
		}
		return true;
	}
	
	/**
	 * Generates password hash using blowfish
	 *
	 * @param unknown $password        	
	 */
	private function hashPassword($password,$salt) {
		$p = hash('sha256', $password . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$p = hash('sha256', $p . $salt);
		}
		return $p;
		//return password_hash ( $password, PASSWORD_BCRYPT );
	}

	private function generateVerifyCode() {
		return md5 ( uniqid ( rand (), true ) );
	}
	
	/**
	 * Sends user email with verification code to reset their password
	 *
	 * @param unknown $email        	
	 * @param unknown $code        	
	 */
	private function sendPasswordRecoveryEmail($email, $code) {
		$mail = new PHPMailer ();
		$mail->From = "No-reply@p2g.org";
		$mail->Subject = "Password Reset Request";
		$mail->addAddress ( $email );
		$mail->isHTML ( true );
		$mail->Body = 'Click the following link or copy and paste it into your browser to reset your password<br/><br/><a href="https://".$site_name."/recover.php?type='.$this->type.'&key=' . $code . '&email=' . $email . '">recover.php?type='.$this->type.'&key=' . $code . '&email=' . $email . '</a>';
		$mail->AltBody = 'Copy and paste the following link into your browser to reset your password<br/><br/>https://".$site_name."/recover.php?type='.$this->type.'&key=' . $code . '&email=' . $email;
		
		if (! $mail->send ()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
			return false;
		} else {
			return true;
		}
	}
}
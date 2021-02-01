<?php
//function check http or https
function isSSL() { return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443; }

function getDirURL(){
	$url = $_SERVER['REQUEST_URI']; //returns the current URL
	$parts = explode('/',$url);
	$dir = $_SERVER['SERVER_NAME'];
	for ($i = 0; $i < count($parts) - 1; $i++) {
	 $dir .= $parts[$i] . "/";
	}
	return $dir;
}

function generateCode(){
	do{
		$code = substr( md5(rand()), 0, 10);
		$sql = "SELECT `active_code` FROM `users` WHERE `active_code` = '$code'";
		$return = mysql_query($sql);
		
	}while(mysql_num_rows($return)>0);
	return $code;
}

function sendEmailToActive($user_id, $password = null){
	require_once 'PHPMailer-master/PHPMailerAutoload.php';
	
	$sql = "SELECT `email`, `first_name`, `active_code` FROM `users` WHERE `id` = $user_id AND `status` = 0 LIMIT 1";
	$check_email  = mysql_query($sql);
	$email = '';
	$active_user_url = '';
	
	if($check_email && mysql_num_rows($check_email)>0){
		$user_info = mysql_fetch_assoc($check_email);
		$email = $user_info['email'];
		$fname = $user_info['first_name'];
		$active_code  = $user_info['active_code']; 
	}
	
	// code to active
	if($active_code == '')
	{
	$code = generateCode();	
	$sql_code = "UPDATE `users` SET `active_code` = '$code' WHERE `id` = $user_id";
	$check_code = mysql_query($sql_code);
	if(!$check_code){
		return false;
	}
	    $active_user_url = 'https://intervene.io/questions/active-user.php?code='.$code;
	}
	else{
		$active_user_url = 'https://intervene.io/questions/active-user.php?code='.$active_code;
	}
	$http = 'http://';
	if(isSSL()){
		$http = 'https://';
	}
	
	// $active_user_url = $http . getDirURL().'active-user.php?code='.$code;
	//$active_user_url = 'https://intervene.io/questions/active-user.php?code='.$code;
	
	$sendPass = ($password == null) ? "" : "<br /><br />
	Your Intervene's account has been created successfully with this temporary password: $password";
	
	// The message html
	$message = "Dear {$fname},
	" . $sendPass . "
	<br /><br />
	Please click here <a href='" . $active_user_url . "'>" . $active_user_url . "</a> to confirm email. You may have to refresh the page on our site once you click the link. If you have any trouble, we're here to help! Just reply to this message and we'll get back to you as soon as possible.
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br/>
	www.intervene.io<br />
	<br /><br />
	<img alt='Intervene Team' src='https://intervene.io/questions/images/logo.png'>";
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Set who the message is to be sent from
	$mail->setFrom('learn@intervene.io', 'Intervene Support');
	//Set an alternative reply-to address
	$mail->addReplyTo('learn@intervene.io', 'Intervene Support');
	// Set who the message is to be sent to
	$mail->addAddress($email, '');
	//Set the subject line
	$mail->Subject = 'Please confirm email';
	//Replace the plain text body with one created manually
	$mail->Body = $message;
	$mail->AltBody = $message;
	//send the message, check for errors
	if (!$mail->send()) {
		return false;
	} else {
		return true;
	}
}

function activeUser($code,$bonus){
	$sql = "SELECT * FROM `users` WHERE `status` = 0 AND `active_code`='$code' LIMIT 1;";
	$check_code = mysql_query($sql);

	if($check_code && mysql_num_rows($check_code)>0){
		$data_code = mysql_fetch_assoc($check_code);
		$sql_status = "UPDATE `users` SET `status` = 1, `q_remaining` = `q_remaining` + $bonus WHERE `status` = 0 AND `active_code`='$code' LIMIT 1;";
		$_SESSION['login_status'] = 1;
		return mysql_query($sql_status);
	}
	return false;
}
?>
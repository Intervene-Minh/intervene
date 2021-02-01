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

function sendEmailToActive($user_id){
	$sql = "SELECT `email`, `first_name` FROM `users` WHERE `id` = $user_id AND `status` = 0 LIMIT 1";
	$check_email  = mysql_query($sql);
	$email = '';
	if($check_email && mysql_num_rows($check_email)>0){
		$user_info = mysql_fetch_assoc($check_email);
		$email = $user_info['email'];
		$fname = $user_info['first_name'];
	}
	
	//code to active
	$code = generateCode();
	
	
	$sql_code = "UPDATE `users` SET `active_code` = '$code' WHERE `id` = $user_id";
	$check_code = mysql_query($sql_code);
	if(!$check_code){
		return false;
	}
	
	$http = 'http://';
	if(isSSL()){
		$http = 'https://';
	}
	
	
	$active_user_url = $http . getDirURL().'active-user.php?code='.$code;
	
	// The message html
	$message = "Dear {$fname},
	<br /><br />
	Please click here <a href='" . $active_user_url . "'>" . $active_user_url . "</a> to confirm email. You may have to refresh the page on our site once you click the link. If you have any trouble, we're here to help! Just reply to this message and we'll get back to you as soon as possible.
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br/>
	www.intervene.io<br />
	<br /><br />
	<img alt='Less Test Prep' src='https://intervene.io/questions/images/logo.png'>";
	
	require 'PHPMailer-master/PHPMailerAutoload.php';
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Set who the message is to be sent from
	$mail->setFrom('pathways2greatness@gmail.com', 'Intervene Support');
	//Set an alternative reply-to address
	$mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Support');
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
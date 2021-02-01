<?php
//function check http or https
function isSSL() { return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443; }
require 'PHPMailer-master/PHPMailerAutoload.php';
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
		$sql = "SELECT `active_code` FROM `demo_users` WHERE `active_code` = '$code'";
		$return = mysql_query($sql);
		
	}while(mysql_num_rows($return)>0);
	return $code;
}

function sendEmailToActive($user_id){
	$sql = "SELECT `email`, `first_name` FROM `demo_users` WHERE `id` = $user_id AND `status` = 0 LIMIT 1";
	$check_email  = mysql_query($sql);
	$email = '';
	if($check_email && mysql_num_rows($check_email)>0){
		$user_info = mysql_fetch_assoc($check_email);
		$email = $user_info['email'];
		$fname = $user_info['first_name'];
	}
	
	//code to active
	$code = generateCode();
	
	
	$sql_code = "UPDATE `demo_users` SET `active_code` = '$code' WHERE `id` = $user_id";
	$check_code = mysql_query($sql_code);
	if(!$check_code){
		return false;
	}
	
	$http = 'http://';
	if(isSSL()){
		$http = 'https://';
	}
	
	
	$active_user_url = $http . getDirURL().'questions/demo-active-user.php?code='.$code;
	
        $message = "Welcome to Intervene! {$first_name}
	<br /><br />
        You’ve taken the first step to making your intervention planning more purposeful.
        <br /><br />
        There are 5 step to successful demo use:
        <br /><br />
        1.Add a class under “Manage Classes”
        <br />
        2. Add students to that class
        <br />
        3. Assign a ready-made STAAR assessment by grade-level under “Manage Assignments”
        <br />
        4. Print out student login sheets
        <br />
        5. Check out the results on your Data Dash!
        <br /><br />
	<a href='" . $active_user_url . "' style=' font-weight:bolder;'>Click here</a>, to confirm email or copy and paste below URL in browser:
	<br />
        <a href='" . $active_user_url . "'>" . $active_user_url . "</a>
        <br /><br />
        You have 20 days of full access to our products. If you have any questions, feel
        <br />
        free to contact us at learn@p2g.org. We’re here to help.
        <br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong>
        <br/>
	www.intervene.io
	<br /><br />
        Tel +185534-LEARN
        <br />
        Email: learn@p2g.org
        <br /><br />
	<img alt='Less Test Prep' src='https://intervene.io/questions/images/logo.png'>";
        
	// pathways2greatness@gmail.com
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Set who the message is to be sent from
	$mail->setFrom('learn@p2g.org', 'Intervene Support');
	//Set an alternative reply-to address
	$mail->addReplyTo('learn@p2g.org', 'Intervene Support');
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
function sendNoticeToAdmin($first_name, $last_name,$school_mail_name,$dist_mail_name,$role_name) {
    //require_once './questions/inc/PHPMailer-master/PHPMailerAutoload.php';
    $to = 'learn@p2g.org';
    $datetime = date('M-d-Y H:i:s');
    $message = "You have a new demo user,
	<br /><br />
	First Name: {$first_name} 
	<br />
        Last Name: {$last_name} 
	<br />
        Title: {$role_name} 
	<br />
        School: {$school_mail_name} 
	<br />
        District: {$dist_mail_name} 
	<br />
        Date/Time:: {$datetime} 
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br />";

    // Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set who the message is to be sent from
    $mail->setFrom('pathways2greatness@gmail.com', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
    $mail->AddCC("aaron.mccloud@p2g.org", ''); 
    // Set the subject line
    $mail->Subject = 'New Demo User';
    // Replace the plain text body with one created manually
    $mail->Body = $message;
    $mail->AltBody = $message;
    // send the message, check for errors
    if (!$mail->send()) {
        //return $message;
        return false;
    } else {
         //return "Not Send! ".$message;
       return true;
    }
}

/*------------------- For expired user send admin mail---------------*/
function sendExpiredToAdmin($name,$email,$school_mail_name,$dist_mail_name,$role_name) {
    //require_once './questions/inc/PHPMailer-master/PHPMailerAutoload.php';
    $to = 'learn@p2g.org';
    $message = "Expired User,
	<br /><br />
	First Name: {$name} 
	<br />
        Email: {$email} 
	<br />
        Title: {$role_name} 
	<br />
        School: {$school_mail_name} 
	<br />
        District: {$dist_mail_name} 
	<br />
        Date/Time:: {$datetime} 
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br />";

    // Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set who the message is to be sent from
    $mail->setFrom('learn@p2g.org', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('learn@p2g.org', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
    $mail->AddCC("aaron.mccloud@p2g.org", ''); 
    // Set the subject line
    $mail->Subject = 'New Demo User';
    // Replace the plain text body with one created manually
    $mail->Body = $message;
    $mail->AltBody = $message;
    // send the message, check for errors
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}

//function activeUser($code,$bonus){
//	$sql = "SELECT * FROM `demo_users` WHERE `status` = 0 AND `active_code`='$code' LIMIT 1;";
//	$check_code = mysql_query($sql);
//
//	if($check_code && mysql_num_rows($check_code)>0){
//		$data_code = mysql_fetch_assoc($check_code);
//		$sql_status = "UPDATE `demo_users` SET `status` = 1, `q_remaining` = `q_remaining` + $bonus WHERE `status` = 0 AND `active_code`='$code' LIMIT 1;";
//		$_SESSION['login_status'] = 1;
//		return mysql_query($sql_status);
//	}
//	return false;
//}
?>
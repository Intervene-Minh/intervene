<?php

// fun_assign_tut
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


// sendEmailToActive
function sendEmails($user_id,$em_to,$body){

       ######$active_user_url = $http . getDirURL().'questions/demo-active-user.php?code='.$code;###############

             // $message
        $email ="rohitd448@gmail.com";#sentToemail
        $message="Your Group Tutor Session scheduled for {Date} at {Time} has been cancelled.";
        $email=$em_to;
        //$message=$body;
        // $message=$body."-EmailTo-".$email;#tst//
         $message = "Hi, <br /><br/> {$body}
	
       
        <br /><br />
	Best regards,<br />
	<strong>Tutorgigs Team</strong>
        <br/>
	www.tutorgigs.io
	<br /><br />
        Tel +185534-LEARN
        <br />
        Email: learn@p2g.org
        <br /><br />
	<img alt='Less Test Prep' src='https://tutorgigs.io/myadmin/images/logo.png'>";
         
         
         
	// pathways2greatness@gmail.com
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Set who the message is to be sent from
	$mail->setFrom('learn@p2g.org', 'Tutorgigs Support');
	//Set an alternative reply-to address
	$mail->addReplyTo('learn@p2g.org', 'Tutorgigs Support');
	// Set who the message is to be sent to
	$mail->addAddress($email, '');
	//Set the subject line
	$mail->Subject = 'Tutor Assigned Info';
	//Replace the plain text body with one created manually
	$mail->Body = $message;
	$mail->AltBody = $message;
	//send the message, check for errors
	if (!$mail->send()) {
            return 'NotSent-'.$message;
          // return false;
	} else {
            return 'Mail Sent';
		//return true;
	}
}



/*------------------- For expired user send admin mail---------------*/

?>
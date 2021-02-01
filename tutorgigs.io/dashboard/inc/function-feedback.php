<?php
session_start();

if( !isset($_POST['action']) || $_POST['action'] != 'send_feedback' )
	die('No valid action requested!');

include('connection.php');

$message_1= $_POST['message'];

$user_id = $_SESSION['login_id'];
$user = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = " . $user_id));
$user_name = $user['user_name'];
$user_mail = $user['email'];

$utype="Live User";
////////////////////
if(isset($_SESSION['demo_user_id'])){
  $user_id = $_SESSION['demo_user_id'];
$user = mysql_fetch_array(mysql_query("SELECT * FROM `demo_users` WHERE `id` = " . $user_id));  
    $user_name = $user['first_name'];
$user_mail = $user['email'];
$utype="Demo User";
}



// The message html
$messageHTML  = "<p><strong>New feedback from Intervene</strong></p>";
$messageHTML .= "<p>Name: $user_name</p>";
$messageHTML .= "<p>E-mail: $user_mail</p>";
$messageHTML .= "<p>Message: $message_1</p>";

// The message
$message  = "New feedback from Intervene\r\n <br/><br/>";
$message .= "Name: $user_name\r\n  <br/>";
$message .= "User Type: $utype\r\n  <br/>";
$message .= "E-mail: $user_mail\r\n  <br/>";
$message .= "Message: $message_1\r\n";

require 'PHPMailer-master/PHPMailerAutoload.php';
//Create a new PHPMailer instance
/****
 * $mail = new PHPMailer;
//Set who the message is to be sent from
$mail->setFrom('pathways2greatness@gmail.com', 'Intervene Feedback');
//Set an alternative reply-to address
$mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Feedback');
// Set who the message is to be sent to
$mail->addAddress('learn@p2g.org', '');
//Set the subject line
$mail->Subject = 'Intervene Feedback';
//Replace the plain text body with one created manually
$mail->Body = $messageHTML;
$mail->AltBody = $message;
//send the message, check for errors
echo ($mail->send()) ? 'ok' : 'fail';
die();
 * 
 * 
 * ***/
//$to="rohit@srinfosystem.com"; 
$to="learn@p2g.org"; 
 $mail = new PHPMailer;
    // Set who the message is to be sent from
    $mail->setFrom('learn@p2g.org', 'Intervene Feedback');
    // Set an alternative reply-to address
    $mail->addReplyTo('learn@p2g.org', 'Intervene Feedback');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
    $mail->AddCC("aaron.mccloud@p2g.org", ''); 
    // Set the subject line
    $mail->Subject = 'Feedback';
    // Replace the plain text body with one created manually
    $mail->Body = $message;
    $mail->AltBody = $message;
     if (!$mail->send()) {
        //  return false;
        echo 'fail';
    } else {
        //return true;
        echo 'ok';
    }


?>
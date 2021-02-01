<?php
session_start();

include('inc/connection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
    if(isset($_POST['invite'])) {
        
       
        
  ///////////////////Testing Mail at-invite//////////////////////////    
          $curr_from_email="learn@intervene.io";                      
         $tut_ses_status=(isset($_POST['sessions_access_allow']))?'yes':'no';
        //echo '<pre>';         print_r($_POST); die;
		$messageHTML = 'body';
		$sql = mysql_query("SELECT * FROM `schools` WHERE `SchoolId` = {$_SESSION['schools_id']}");
		$schools_info = mysql_fetch_assoc($sql);
		$schools_name = $schools_info['SchoolName'];
		
		$share_grades = serialize($_POST['folders']);
$data_dash = serialize($_POST['teacher_invite_permission']);
       
		require 'inc/PHPMailer-master/PHPMailerAutoload.php';
		
		$sent = false;
                
		foreach( $_POST['email'] as $item ) {
			if( $item != "" ) {
				// Process save to database
				mysql_query("INSERT INTO `invitation` (`schoolId`, `teacherEmail`, `shareItems`, `data_dash`,`session_allowed`)
				VALUES ('{$_SESSION['schools_id']}', '{$item}', '{$share_grades}', '{$data_dash}','{$tut_ses_status}')");
                                
                                
				 $messageHTML = "Dear Teachers,
		<br /><br />
		Your administrator has enrolled {$schools_name} to access the Intervene System, which includes a question database and data analysis tools.<br />
		Please click www.intervene.io/questions/signup.php to get started or simply<br />
		1. Go to www.intervene.io<br />
		2. Click login<br />
		3. Click 'Teachers, Click Here to Signup'
		<br /><br />
		If you need assistance, then please don't hesitate to email us at learn@intervene.io.<br />
		<br />
		Best regards,<br />
		<strong>Intervene Team</strong><br />
		www.intervene.io
		<br /><br />
		<img alt='Intervene Team' src='https://intervene.io/questions/images/logo.png' />";
                // echo $messageHTML ; die;
				// Create a new PHPMailer instance
				$mail = new PHPMailer;
				// Set who the message is to be sent from #
				#$mail->setFrom('pathways2greatness@gmail.com', 'Intervene Support');
                                $mail->setFrom('learn@intervene.io', 'Intervene Support');
				// Set an alternative reply-to address
				$mail->addReplyTo('learn@intervene.io', 'Intervene Support');
				// Set who the message is to be sent to
				$mail->addAddress($item, '');
				// Set the subject line
				$mail->Subject = 'Welcome to Intervene!!';
				// Replace the plain text body with one created manually
				$mail->Body = $messageHTML;
				$mail->AltBody = $messageHTML;
				// send the message, check for errors
				if( $mail->send() )
					$sent = true;
			}
		}
                ////////////////////
                print('<script>window.location.href="school.php?invited=true";</script>');
        //if( $sent ) {
			//echo "Message sent successfully!";
			//print('<script>window.location.href="school.php?invited=true";</script>');
        //}
    }
?>
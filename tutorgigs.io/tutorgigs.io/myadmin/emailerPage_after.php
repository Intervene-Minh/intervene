<?php

function send_email($to,$message,$subj='')
{

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	// More headers
	$headers .= 'From: <learn@drhomework.com.>' . "\r\n";
	if(mail($to,$subj,$message,$headers)) 
		return true;
	else 
		return false;
 }

function _parentNotifyEmail($time,$name,$email)
{

			$message = "
			<h3> Dear ".$name." ,</h3>


			Unfortunately,<br>
			we were not able to fill the tutoring session for <b>".date_format(date_create($time), 'F d,Y')." at ".date_format(date_create($time), 'h:i A')." (CST) </b>. <br>We apologize for the inconvenience.  A session has been added back to your ‘Remaining Sessions’ count.


			<p> Regards,</p>
			<p>The Dr. Homework Team</p>
			<p> <img alt='' src='https://drhomework.com/drhw.png' style='width:200px'></p>
			";
			send_email($email,$message,$subj='Session was not filled');	
			//echo $email;
			}	

?> 

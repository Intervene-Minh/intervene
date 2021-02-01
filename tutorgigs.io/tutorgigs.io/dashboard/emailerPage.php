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
		<p>
		The tutoring job for  <b>".date_format(date_create($time), 'F d,Y')." at ".date_format(date_create($time), 'h:i A')." (CST) </b>has been filled! 
		</p>

		<p>Instructions: </p>
		<ul>

		<li>Student will log into their account on Dr. Homework (username and password is found on your parent page under Manage Students)</li>
		<li>Click “View” next to the correct session.</li>
		<li>Click “Join Tutoring Session”</li>
		<li>The tutor will be waiting in the session for the student at the correct time.</li>

		<li>Please note that all tutoring sessions are booked in Central Standard Time.</li>
		<li>If you have any questions or need support during the session, please contact us at <br>learn@drhomework.com </li>
		</ul>

		<p> Regards,</p>
		<p>The Dr. Homework Team</p>
		<p> <img alt='' src='https://drhomework.com/drhw.png' style='width:200px'></p>
		";
		send_email($email,$message,$subj='Tutoring Session has been Filled!');	
		//echo $email;
}	

?>
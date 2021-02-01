<?php
	include('connection.php');
	session_start();
	ob_start();

	// print_r($_SESSION);
	$user_id = $_SESSION['login_id'];
	$question_id = $_POST['question_id'];
	$error_subject = $_POST['error_subject'];
	$error_comment = $_POST['error_comment'];
			

	$sql = "SELECT * FROM `users` WHERE `role`=0 LIMIT 1";
	$result_admin = mysql_fetch_assoc(mysql_query($sql));
	
	$sql_user = "SELECT * FROM `users` WHERE `id`=$user_id";
	$result_user = mysql_fetch_assoc(mysql_query($sql_user));
	
	$sql_ques = "SELECT * FROM `questions` WHERE `id`=$question_id";
	$result_ques = mysql_fetch_assoc(mysql_query($sql_ques));
	// print_r($result);
	$site_url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$profile_url =  str_replace("inc/ajax-send-error.php","profile.php?id=$user_id",$site_url);
	$question_url =  str_replace("inc/ajax-send-error.php","single-question.php?question=$question_id",$site_url);
	
	$to      = $result_admin['email'];
	// $to = 'liuwanthang@gmail.com';
	$subject = 'Question Error';
	
	$message = 'Subject: '.$error_subject. "<br>" .
	'Comment: '.$error_comment. "<br>" .
	'Question: <a href="'.$question_url.'">'.$result_ques['name'].'</a>'. "<br>" .
	'User name: <a href="'.$profile_url.'">'.$result_user['user_name'].'</a>'. "<br>".
	'User email: '.$result_user['email']."<br>";
	
	$headers = 'Content-type: text/html';

	$result_mail = mail($to, $subject, $message, $headers);
	$return['check']=$result_mail; 
	echo json_encode($return);
	die();
	
?>
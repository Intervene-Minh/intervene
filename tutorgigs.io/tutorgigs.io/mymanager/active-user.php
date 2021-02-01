<?php
	include('inc/connection.php');
	
	session_start();
	ob_start();
	
	require_once('inc/function-active-user.php');
	
	if(isset($_GET['code']) && $_GET['code']!=''):
		$bonus_question = 20;
		$code = $_GET['code'];
		if(activeUser($_GET['code'],$bonus_question)){
			echo 'Success Active';


			$sql = "SELECT * FROM `users` WHERE  `active_code`='$code' LIMIT 1;";
			$check_code = mysql_query($sql);

			$data_code = mysql_fetch_assoc($check_code);

			$_SESSION['login_id']=$data_code['id'];
			$_SESSION['login_user']=$data_code['user_name']; // Initializing Session
			$_SESSION['login_mail']=$data_code['email'];
			$_SESSION['login_role']=$data_code['role'];
			$_SESSION['login_status']=1;


			//header('Location: profile.php?active=true');
			print('<script>window.location.href="profile.php?active=true";</script>');
			exit;
		}else{
			echo 'Fail Active';
			//header('Location: profile.php?active=false');
			print('<script>window.location.href="profile.php?active=false";</script>');
			exit;
		}
	
	else:
		//reActive user
		if(isset($_SESSION['login_id']) && is_numeric($_SESSION['login_id'])){
			if(sendEmailToActive($_SESSION['login_id'])){
				echo 'We have successfully sent an email for verification. To return to the site, use the back button on your browser.';
				//header('Location: profile.php?send=true');
				print('<script>window.location.href="profile.php?send=true";</script>');
				exit;
			}else{
				echo 'We were unable to send an email for verification. Please go back using your browser and try again.';
				//header('Location: profile.php?send=false');
				print('<script>window.location.href="profile.php?send=false";</script>');
				exit;
			}
		}
		
		//no action access -> login
		//header('Location: login.php');
			print('<script>window.location.href="login.php";</script>');
			exit;
		
	endif;
	
?>
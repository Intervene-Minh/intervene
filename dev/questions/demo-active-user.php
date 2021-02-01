<?php
//  
 // echo 'Update status'; die;
	include('inc/connection.php');
	
	session_start();
	ob_start();
	
	require_once('inc/demo_function-active-user.php');
	
	if(isset($_GET['code']) && $_GET['code']!=''):
		$bonus_question = 20;
		$code = $_GET['code'];
		if(1){
			echo 'Success Active';


			$sql = "SELECT * FROM `demo_users` WHERE  `active_code`='$code' LIMIT 1;";
			$check_code = mysql_query($sql);
                       
			$row = mysql_fetch_assoc($check_code);
                        $user_id = $row['id'];
			$_SESSION['demo_user_id']=$row['id'];
                        $_SESSION['demo_login_user']=$row['first_name'];
			$_SESSION['demo_user_mail']=$row['email'];
			$_SESSION['demo_user_role']='demo_user';
			$_SESSION['login_status']=$row['status'];
                        $_SESSION['data_dash']=$row['data_dash'];
                        $_SESSION['school_id']=$row['school_id'];
			$_SESSION['login_status']=1;
                        mysql_query("UPDATE demo_users SET `status` = 1 WHERE id='$user_id'");
                       // echo 'Status updated';

			//header('Location: profile.php?active=true');
			print('<script>window.location.href="demo_profile.php?active=true";</script>');
			exit;
		}else{
			echo 'Fail Active';
			//header('Location:demo_profile.php?active=false');
			print('<script>window.location.href="demo_profile.php?active=false";</script>');
			exit;
		}
	
	else:
		//reActive user
            // echo 'Send request back for activation.--';die;
		if(isset($_SESSION['demo_user_id']) && is_numeric($_SESSION['demo_user_id'])){
			if(sendEmailToActive($_SESSION['demo_user_id'])){
				//echo 'We have successfully sent an email for verification. To return to the site, use the back button on your browser.';
				header('Location:demo_profile.php?send=true');exit;
				//print('<script>window.location.href="demo_profile.php?send=true";</script>');
				//exit;
			}else{
				$msg= 'xWe were unable to send an email for verification. Please go back using your browser and try again.';
                               // $msg
				//header('Location: demo_profile.php?send=false');
				print('<script>alert('.$msg.');window.location.href="demo_profile.php?send=false";</script>');
				exit;
		}
		}
		
		//no action access -> login
		//header('Location: login.php');
			//print('<script>window.location.href="login.php";</script>');
			//exit;
		
	endif;
	
?>
<?php
include('../config/connection.php'); 
session_start();
ob_start();
/**
 * login_user ::Name at Top OR email
 * login_mail :: email of user login
 * login_role :: on , teach ==1
 * login_status : login or not 
 * 
 * login_id ==>ses_teacher_id
 * ses_teacher_id
 * login_user, login_mail ::public info 
 * 
 * **/

if( isset($_SESSION['login_user']) )unset($_SESSION['login_user']);

if(isset($_SESSION['ses_teacher_id'])){


mysql_query("UPDATE  gig_teachers SET  `loginStatus`='0' WHERE id='".$_SESSION['ses_teacher_id']."'", $link);
unset($_SESSION['ses_teacher_id']);

}
	
if(isset($_SESSION['login_role']))unset($_SESSION['login_role']);
	
if(isset($_SESSION['login_status']))unset($_SESSION['login_status']);
session_unset();
session_destroy();
//header('Location: login.php');
header('Location:../login.php');exit;
?>
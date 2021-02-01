<?php
session_start();
ob_start();
$url="login.php";  //def
if( isset($_SESSION['login_user']) )unset($_SESSION['login_user']);

if(isset($_SESSION['login_id']))unset($_SESSION['login_id']);
	
if(isset($_SESSION['login_role']))unset($_SESSION['login_role']);
	
if(isset($_SESSION['login_status']))unset($_SESSION['login_status']);

if(isset($_SESSION['list']))unset($_SESSION['list']);

if( isset($_SESSION['student_id']) )unset($_SESSION['student_id']);
// Demo Teacher User OR Demo Student --demo-logn page
if( isset($_SESSION['demo_user_id'])||(isset($_SESSION['is_demo_student'])&&$_SESSION['is_demo_student']==1) ){
unset($_SESSION['demo_user_id']);
unset($_SESSION['is_demo_student']);
$url="demo_login.php";
}

if( isset($_SESSION['demo_login_user']) )unset($_SESSION['demo_login_user']);
if( isset($_SESSION['demo_user_mail']) )unset($_SESSION['demo_user_mail']);
if( isset($_SESSION['demo_user_role']) )unset($_SESSION['demo_user_role']);

session_unset();
session_destroy();

header('Location:'.$url);
exit;
?>
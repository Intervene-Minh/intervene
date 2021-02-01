<?php
session_start();
ob_start();
if( isset($_SESSION['login_user']) )unset($_SESSION['login_user']);

if(isset($_SESSION['login_id']))unset($_SESSION['login_id']);
	
if(isset($_SESSION['login_role']))unset($_SESSION['login_role']);
	
if(isset($_SESSION['login_status']))unset($_SESSION['login_status']);

if(isset($_SESSION['list']))unset($_SESSION['list']);





session_unset();
session_destroy();

header('Location:../login.php');
exit;
?>
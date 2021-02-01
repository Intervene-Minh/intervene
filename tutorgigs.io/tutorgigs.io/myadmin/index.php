<?php
session_start();
ob_start();
/***
 * $_SESSION['login_id']
 * if( isset($_SESSION['login_user']) ) {
	header('Location: folder.php');
	exit;
} else if( isset($_SESSION['demo_login_user']) ){
    header('Location: demo_folder.php');
	exit;
}else{
   // echo 'else part'; die;
	header('Location: login.php');
	exit;
}
 * 
 * 
 * ****/

//if( isset($_SESSION['login_id'])&&isset($_SESSION['login_user']) ) {
if( isset($_SESSION['login_id'])&&$_SESSION['login_id']>0) {
	header('Location:tutor-list.php');
	exit;
}else{
    header('Location:https://tutorgigs.io/login.php');exit; // live
}



?>
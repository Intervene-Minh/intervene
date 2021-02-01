<?php
session_start();
ob_start();
/***
 *   $_SESSION['ses_teacher_id']."teacher id";
 * **/

//if( isset($_SESSION['login_user']) ) {
//	header('Location:home.php');
//	exit;
//}else{
//    header('Location: login.php');exit;
//}  // application.php home.php


if( isset($_SESSION['ses_teacher_id']) ) {
	header('Location:home.php');
	exit;
}else{
    header('Location:logout.php');exit;
}



?>
<?php
session_start();
ob_start();

///////Sub-admin login///////
if(isset($_SESSION['ses_subdmin'])&&$_SESSION['ses_subdmin']=='yes'&& isset($_SESSION['login_id'])){ 
	header('Location:questions.php');exit;

  }elseif( isset($_SESSION['login_user']) ) {



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
?>
<?php
/**
@ School : arrange assessment : Question 
$_SESSION['ses_school_list'] @ School only
@@
**/

	session_start();
	ob_start();

    if(!isset($_SESSION['schools_id']))exit('Sorry, School login authentication failed! ');
    /////////////////////

	if(isset($_POST['sort'])&&isset($_SESSION['ses_school_list'])){
		$_SESSION['ses_school_list'] = explode(",", $_POST['sort']);
	}
?>
<?php
/**
#removeall-in-list :: All Questions

***/
	session_start();
	ob_start();
 //   echo '===Delete testing==';
	// print_r($_SESSION);  die; 

	///////////////////
	if(isset($_POST['remove_in_list'])&&isset($_SESSION['ses_school_list'])){
		if(($key = array_search($_POST['remove_in_list'], $_SESSION['ses_school_list'])) !== false) {
			unset($_SESSION['ses_school_list'][$key]); unset($_SESSION['qn_list']);
		}
	}
	if(isset($_POST['removeall_in_list'])&&isset($_SESSION['ses_school_list'])){
		if($_POST['removeall_in_list']){
			unset($_SESSION['ses_school_list']); unset($_SESSION['qn_list']);
		}
	}
	if(!isset($_SESSION['ses_school_list']) || empty($_SESSION['ses_school_list']))unset($_SESSION['is_passage']);
?>
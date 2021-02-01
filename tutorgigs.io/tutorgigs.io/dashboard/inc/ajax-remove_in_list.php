<?php
	session_start();
	ob_start();
	if(isset($_POST['remove_in_list'])&&isset($_SESSION['list'])){
		if(($key = array_search($_POST['remove_in_list'], $_SESSION['list'])) !== false) {
			unset($_SESSION['list'][$key]);
		}
	}
	if(isset($_POST['removeall_in_list'])&&isset($_SESSION['list'])){
		if($_POST['removeall_in_list']){
			unset($_SESSION['list']);
		}
	}
	if(!isset($_SESSION['list']) || empty($_SESSION['list']))unset($_SESSION['is_passage']);
?>
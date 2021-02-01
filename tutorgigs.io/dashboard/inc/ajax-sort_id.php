<?php
	session_start();
	ob_start();
	if(isset($_POST['sort'])&&isset($_SESSION['list'])){
		$_SESSION['list'] = explode(",", $_POST['sort']);
	}
?>
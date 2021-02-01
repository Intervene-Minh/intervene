<?php
	require_once('connection.php');
	session_start();
	ob_start();
	// require_once('update-user-question.php');
	// print_r($_SESSION);

	if(isset($_SESSION['is_passage'])){
		$check = false;
		if($_POST['is_passage']>0)$check = true;
		if($_SESSION['is_passage']!=$check){
			echo json_encode(array('check'=>false));
			die();
		}
	}
	if(isset($_POST['add_to_list'])&&isset($_SESSION['list'])){
		if (!in_array($_POST['add_to_list'], $_SESSION['list'])) {
			array_push($_SESSION['list'],$_POST['add_to_list']);
			// print_r($_SESSION['list']);
			if($_POST['is_passage']>0)$_SESSION['is_passage'] = true;
			else $_SESSION['is_passage'] = false;
			
			$count = count($_SESSION['list']);
			//get Question
			// $remaining = getQuestionsRemaining($_SESSION['login_id']) - $count;
			// $is_unlimited = is_unlimited($_SESSION['login_id']);
			// echo json_encode(array('check'=>true,'count'=>$count,'remaining'=>$remaining,'is_unlimited'=>$is_unlimited));
			echo json_encode(array('check'=>true,'count'=>$count));
			die();
		}
	}
	echo json_encode(array('check'=>false));
	die();
	
?>
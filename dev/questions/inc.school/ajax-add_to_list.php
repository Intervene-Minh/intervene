<?php
	require_once('connection.php');
	session_start();
	ob_start();
	// require_once('update-user-question.php');
      // echo json_encode(array('check'=>$_SESSION['is_passage'].'@'.$_SESSION['login_role']));
	// print_r($_SESSION);
       //  die;

	if(isset($_SESSION['is_passage'])){
		$check = false;
		if($_POST['is_passage']>0)$check = true;
		if($_SESSION['is_passage']!=$check){
			echo json_encode(array('check'=>false));
			die();
		}
	}
	if(isset($_POST['add_to_list'])&&isset($_SESSION['ses_school_list'])){
		if (!in_array($_POST['add_to_list'], $_SESSION['ses_school_list'])) {
			array_push($_SESSION['ses_school_list'],$_POST['add_to_list']);
			// #######

			
			if($_POST['is_passage']>0)$_SESSION['is_passage'] = true;
			else $_SESSION['is_passage'] = false;
			
			$count = count($_SESSION['ses_school_list']);
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
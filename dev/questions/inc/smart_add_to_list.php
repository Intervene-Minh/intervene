<?php
/**User:teacher Smart question to list
 * **/
session_start();ob_start();
require_once('connection.php');
	
	

	if(isset($_SESSION['is_passage'])){
		$check = false;
		if($_POST['is_passage']>0)$check = true;//passage_id>0
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
                        else unset($_SESSION['is_passage']);
                        
                        
			//else $_SESSION['is_passage'] = false;
			
			$count = count($_SESSION['list']);
			//get Question
			echo json_encode(array('check'=>true,'count'=>$count));
			die();
		}
	}
	echo json_encode(array('check'=>false));
	die();
	
?>
<?php
	require "inc/connection.php";
	@session_start();
	@extract($_REQUEST);
	$str="SELECT * FROM `students` WHERE id='".$uiId."'";
	$StuList = mysql_query($str);
	$Row = mysql_fetch_assoc($StuList);
	
	$_SESSION['student_id'] = $Row['id'];
	$_SESSION['student_name'] = $Row['first_name'];
	$_SESSION['last_name'] = $Row['last_name'];
	$_SESSION['teacher_id'] = $Row['teacher_id'];
	$_SESSION['schools_id'] = $Row['school_id'];
	$_SESSION['class_id'] = $Row['class_id'];
	//print_r($_SESSION);

	include_once('MoodleWebServices/create_student_account.php');
?>



<?php
	include_once('inc/connection.php');
	@session_start();
	extract($_REQUEST);

	$str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`='".$_SESSION['student_id']."' ORDER BY ID DESC
	LIMIT 1";
	$moodle_data = mysql_fetch_assoc(mysql_query($str));
	$TeluserID =$moodle_data['TelUserID'];
	$InterUserID=$_SESSION['student_id'];
	$course_id = $el;
	$categoryid = $cr;
	include_once('MoodleWebServices/enroll_one_course.php');?>
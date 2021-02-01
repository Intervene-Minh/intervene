<?php
include('inc/connection.php'); 
session_start();
extract($_REQUEST);

if(!empty($course_id)) {


/* get telpas user id*/
$str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`='".$_SESSION['student_id']."' ORDER BY ID DESC LIMIT 1";

$moodle_data = mysql_fetch_assoc(mysql_query($str));

$TelPasUserID =$moodle_data['TelUserID'];

$InterveneID=$_SESSION['student_id'];


if($CourseType=='speaking'){

 $tbl='telpas_student_speaking_grades';

}


else{$tbl='telpas_student_writing_grades';}

/* check duplicate result */
$str = "SELECT count(telpas_student_id) cnt FROM $tbl WHERE telpas_student_id='".$TelPasUserID."'
AND course_id='".$course_id."'";

$res=mysql_query($str);

$row = mysql_fetch_assoc($res);

$checkdup = $row['cnt'];

/* check duplicate result */
$str = "SELECT count(IsComplete) cnt FROM `Tel_CourseComplete` WHERE TelUserID='".$TelPasUserID."'
	 AND CourseID='".$course_id."'";

	$Telpas_students=mysql_query($str);

	$row = mysql_fetch_assoc($Telpas_students);

	$checkDuplicate = $row['cnt'];

	if($checkDuplicate ==0 && $checkdup > 0){

		$strIn="INSERT INTO Tel_CourseComplete SET intervene_student_id='".$InterveneID."',
			TelUserID='".$TelPasUserID."',CourseID='".$course_id."',IsComplete='1'";
			mysql_query($strIn);
			echo 1;
			exit;
	}
	else{ echo 1 ;}

}
?>



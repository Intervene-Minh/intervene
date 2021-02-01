<?php
include('inc/connection.php'); 
session_start();
extract($_REQUEST);


$couserID=$c;

include('MoodleWebServices/get-coursename.php'); 

$course_cat_id=$responsName['courses'][0]['categoryid']; 
$courseName= strtolower($responsName['courses'][0]['displayname']);
if($courseName=='speaking' || $courseName=='writing') {

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

    }

else{


include('MoodleWebServices/get-stdent-grade.php'); 
/* get telpas user id*/
$str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`='".$_SESSION['student_id']."' ORDER BY ID DESC LIMIT 1";

$moodle_data = mysql_fetch_assoc(mysql_query($str));

$TelPasUserID =$moodle_data['TelUserID'];

$InterveneID=$_SESSION['student_id'];

/*hit Web Services for score*/

$score = getProcessGread($course_id,$TelPasUserID);

/* set compelete status*/
if($iscom=='yes')
{

	$str = "SELECT count(IsComplete) cnt FROM `Tel_CourseComplete` WHERE TelUserID='".$TelPasUserID."'
	 AND CourseID='".$course_id."'";

	$Telpas_students=mysql_query($str);

	$row = mysql_fetch_assoc($Telpas_students);

	 $checkDuplicate = $row['cnt'];


	if($checkDuplicate > 0 ){

		/* update code*/
		$str="SELECT ID ,count(ID) as cnt FROM `telpas_course_score_logs` WHERE `telpas_student_id`='".$TelPasUserID."' AND`course_id`=".$course_id;
		$Res=mysql_query($str);
		$row = mysql_fetch_assoc($Res);
		
		if($score > 0 ){

				//$str="UPDATE `telpas_course_score_logs` SET `score_percent`='".$score."' WHERE ID=".$row['ID'];
                   $str="UPDATE `telpas_course_score_logs` SET `score_percent`='".$score."' WHERE `telpas_student_id`='".$TelPasUserID."' AND`course_id`=".$course_id;

				mysql_query($str);
				//echo'Score update error telpas_course_score_logs'.$row['ID'];
			}
			
				//echo'Score is less than 0 error update';
				echo$redirect=1;
				exit;
	   
	}
	else{

			 $strIn="INSERT INTO Tel_CourseComplete SET intervene_student_id='".$InterveneID."',
			TelUserID='".$TelPasUserID."',CourseID='".$course_id."',IsComplete='1'";
			mysql_query($strIn);
			$L_id = mysql_insert_id();

			if($score > 0 ){

				 $str="INSERT INTO `telpas_course_score_logs` SET `teacher_id`='".$_SESSION['teacher_id']."',`course_cat_id`='".$course_cat_id."',`course_id`= '".$course_id."',`telpas_student_id`='".$TelPasUserID."',`intervene_student_id`='".$InterveneID."',`score_percent`='".$score."',
				    `course_type`='".$courseName."' ";

				mysql_query($str);
				$last_id = mysql_insert_id();
				//echo'Score Insert error telpas_course_score_logs'.$last_id;

			}
			
				//echo'insert Tel_CourseComplete'.$L_id;
				echo $redirect=1;
				exit;
	}
}
else{
	echo'not hit any event';
}

}
?>
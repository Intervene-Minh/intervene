<?php
include('inc/connection.php'); 
session_start();
extract($_REQUEST);

$uID = base64_decode($u);
if($uID > 0 ){ 

	if (!isset($_SESSION['student_id']) || $_SESSION['student_id'] == '')
	{

		require_once'checklogin.php';
		

	}
}

/* get telpas user id*/
$str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`='".$_SESSION['student_id']."' ORDER BY ID DESC LIMIT 1";

$moodle_data = mysql_fetch_assoc(mysql_query($str));

$TelPasUserID =$moodle_data['TelUserID'];

$InterveneID=$_SESSION['student_id'];

$couserID=$c;
$course_id =$c;
include('MoodleWebServices/get-coursename.php'); 

$course_cat_id=$responsName['courses'][0]['categoryid']; 
$courseName= strtolower($responsName['courses'][0]['displayname']);

if(!empty($course_id) &&  $course_id > 0)
{

		/*get speaking data by API*/
	if($courseName=='speaking'){

			require_once('MoodleWebServices/get-stdent-grade.php'); 
			$totalquestion = get_total_question($course_id,$TelPasUserID);

			
			 $totalattemp = getattemp($course_id,$TelPasUserID);
			require_once'speaking-apiFile.php';
		

	}
	else if($courseName=='writing'){
		
		require_once('MoodleWebServices/get-stdent-grade.php'); 
		$totalquestion = get_total_question($course_id,$TelPasUserID);
		$totalattemp = getattemp($course_id,$TelPasUserID);
		require_once'writing-apiFile.php';
	}
else{



			include('MoodleWebServices/get-stdent-grade.php'); 

			/*hit Web Services for score*/
			  $score = getProcessGread($course_id,$TelPasUserID);


			 $str = "SELECT *  FROM `Tel_CourseComplete` WHERE TelUserID='".$TelPasUserID."'AND CourseID='".$course_id."'";

			$Telpas_students=mysql_query($str);

			$row = mysql_fetch_assoc($Telpas_students);
			 $checkDuplicate = mysql_num_rows($Telpas_students);
			if($checkDuplicate > 0 ){

				if($score > 0 ){

				   $str="UPDATE `telpas_course_score_logs` SET `score_percent`='".$score."' WHERE `telpas_student_id`='".$TelPasUserID."' AND`course_id`=".$course_id;
			          
			          mysql_query($str);
					
					}
					$redirect=1;
				}
			else{


				$str = "SELECT * FROM `Tel_CourseComplete` WHERE TelUserID='".$TelPasUserID."'  AND CourseID='".$course_id."'";

			   $Telpas_students=mysql_query($str);
			   $checkDuplicate = mysql_num_rows($Telpas_students);
			   if($checkDuplicate==0){

			   		$strIn="INSERT INTO Tel_CourseComplete SET intervene_student_id='".$InterveneID."',
					TelUserID='".$TelPasUserID."',CourseID='".$course_id."',IsComplete='1'";
					mysql_query($strIn);
					$L_id = mysql_insert_id();
				}

				$str="SELECT  * FROM `telpas_course_score_logs` WHERE `telpas_student_id`='".$TelPasUserID."' AND`course_id`=".$course_id;

				$qr=mysql_query($str);
				 $dupScorr = mysql_num_rows($qr);

				if($dupScorr==0){

$str="INSERT INTO `telpas_course_score_logs` SET `teacher_id`='".$_SESSION['teacher_id']."',`course_cat_id`='".$course_cat_id."',`course_id`= '".$course_id."',`telpas_student_id`='".$TelPasUserID."',`intervene_student_id`='".$InterveneID."',`score_percent`='".$score."',
				`course_type`='".$courseName."' ";
				mysql_query($str);
				$last_id = mysql_insert_id();


			}
					$redirect=1;
						}


					}
			}

if($redirect==1){

	header("location:https://englishpro.us/questions/telpas_practice.php?t=y");

}
?>
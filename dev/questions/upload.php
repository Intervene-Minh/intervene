<?php
// include('../iframe-telpass-header.php'); 
include('../inc/connection.php'); 
global $base_url;
$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
session_start();
ob_start();
echo '===';
print_r($_SESSION); die;
//////////////////

$login_student_id=$_SESSION['studentId'];
$Techer=mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE `id` = '".$login_student_id."'"));


$table_name='telpas_student_course_audios';
$course_id=5;
$studentId=$_SESSION['studentId'];
$student_name=$Techer['student_name'];
$teacherid=$Techer['teacher_id'];


$question_id=25; 

/* Uplaod file in directory and save in data base */

if(isset($_FILES['file']) and !$_FILES['file']['error']){

	$FileAudio = $student_name.'_'.time().'q_25'.".wav";
 $file_name=$FileAudio;
	if(move_uploaded_file($_FILES['file']['tmp_name'], "uploads/recordings/" . $FileAudio))
	{

		//////////SAVE inFO. ///////////////////
  $sql="INSERT INTO ".$table_name." (course_id,telpas_student_id,file_audio,question_id,intervene_student_id,teacher_id) 
  VALUES('$course_id','$studentId','$file_name','$question_id','$studentId','".$teacherid."')";
   $insert=mysql_query($sql);
	}
}
ob_end();
?>

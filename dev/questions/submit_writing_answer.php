<?php
/* connection file*/
include('inc/connection.php'); 
/* extract request data*/
extract($_REQUEST);
/* session start*/
session_start();
ob_start();


if(!isset($_SESSION['student_id']))
exit('Student login required!!');

$login_student_id=$_SESSION['student_id'];

$str="SELECT TelUserID FROM `Tel_UserDetails` WHERE `IntervenID` = '".$login_student_id."'";
$TelPasStuID=mysql_fetch_assoc(mysql_query($str));

//print_r($TelPasStuID);
$telpas_student_id=$TelPasStuID['TelUserID'];

$Techer=mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE `id` = '".$login_student_id."'"));

$table_name='telpas_student_course_audios';

$studentId=$_SESSION['student_id'];


$teacherid=(isset($_SESSION['teacher_id']))?$_SESSION['teacher_id']:$Techer['teacher_id'];


if(!empty($writeAns) && empty($writeID) ){
  
  $str="INSERT INTO  `telpas_student_writing_grades` SET `teacher_id`='".$teacherid."',`course_id`='".$course_id."',
  `telpas_student_id`='".$telpas_student_id."',
  `intervene_student_id`='".$studentId."',`question_id`='".$question_id."',`question_answer`='".$writeAns."'";
  mysql_query($str);
}
else{

      $str="UPDATE `telpas_student_writing_grades` SET `question_answer`='".$writeAns."' WHERE `id`=".$writeID;
      mysql_query($str); 
}



$str="SELECT question_answer FROM `telpas_student_writing_grades` WHERE `intervene_student_id`='".$studentId."' AND `question_id`='".$question_id."'";
$result=mysql_query($str);
echo $audioListTotal=mysql_num_rows($result); 
?>

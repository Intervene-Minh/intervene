<?php

extract($_REQUEST);
include('inc/connection.php'); 

 $Techer=mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE `id` = '".$interveneStuID."'"));


$str="INSERT INTO telpas_student_course_audios SET `course_id`='".$courseID."', `telpas_student_id`='".$telpasStuID."',`file_audio` ='".$file_name."',`question_id` ='".$questionID."',`intervene_student_id` ='".$interveneStuID."',`course_type` ='quiz',`course_code` ='".$courseRcordID."',`teacher_id`='".$Techer['teacher_id']."'";
 if(mysql_query($str)){

    echo " File save";

 }

    
?>

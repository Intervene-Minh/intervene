<?php
/* connection file*/
include('../inc/connection.php'); 
/* extract request data*/
extract($_REQUEST);
/* session start*/
session_start();
ob_start();

if(!isset($_SESSION['student_id']))
exit('Student login required!!');

$login_student_id=$_SESSION['student_id'];
$TelPasStuID=mysql_fetch_assoc(mysql_query("SELECT TelUserID FROM `Tel_UserDetails` WHERE `IntervenID` = '".$login_student_id."'"));

$telpas_student_id=$TelPasStuID['TelUserID'];


$Techer=mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE `id` = '".$login_student_id."'"));
$table_name='telpas_student_course_audios';
$studentId=$_SESSION['student_id'];
$student_name=$Techer['student_name'];
$teacherid=(isset($_SESSION['teacher_id']))?$_SESSION['teacher_id']:$Techer['teacher_id'];

if($data!='data:'){

$file_name=$data;
        $sql="INSERT INTO ".$table_name." (course_id,telpas_student_id,audio_file,question_id,intervene_student_id,teacher_id) VALUES('$course_id','$telpas_student_id','$file_name','$question_id','$studentId','".$teacherid."')";
        mysql_query($sql);
        $file_audio_id = mysql_insert_id();

        if($file_audio_id > 0){

            $delStr="DELETE FROM `telpas_student_speaking_grades` WHERE  `question_id`='".$question_id."' and 
            `telpas_student_id`=".$telpas_student_id;
            mysql_query($delStr);
            $str="INSERT INTO  `telpas_student_speaking_grades` SET  `teacher_id`='".$teacherid."', `course_id`='".$course_id."', `telpas_student_id`='".$telpas_student_id."',`intervene_student_id`='".$studentId."',`question_id`='".$question_id."',
            `file_audio`='".$file_name."',
            `file_audio_id`='".$file_audio_id."'";
            mysql_query($str);
            $_SESSION['audio_id'] = mysql_insert_id();

        }
            
    
}

$str="SELECT audio_file FROM `telpas_student_course_audios` WHERE `intervene_student_id`='".$studentId."' AND `question_id`='".$question_id."'";
$result=mysql_query($str);
echo $audioListTotal=mysql_num_rows($result);


?>

<?php

include('inc/connection.php'); 
session_start();
extract($_REQUEST);


$str="SELECT * FROM `students` WHERE  `uid` = '".$id."' AND `status` = 1 ";
$student_id = mysql_query($str);
if( mysql_num_rows($student_id) == 0 ) {
$error = 'Your information is invalid!';
} else {
$students = mysql_fetch_assoc($student_id);
$_SESSION['student_id'] = $students['id'];
$_SESSION['student_name'] = $students['first_name'];
$_SESSION['last_name'] = $students['last_name'];
$_SESSION['teacher_id'] = $students['teacher_id'];
$_SESSION['schools_id'] = $students['school_id'];
$_SESSION['class_id'] = $students['class_id'];
header("Location:welcome.php"); exit;
}
?>
<?php

 include('inc/connection.php'); 
session_start(); 
ob_start();
// if (!$_SESSION['student_id']) {
//     header('Location: login.php');
//     exit;
// }

#include("student_inc.php");
#include("ses_live_inc.php");# Tutor sesion Live 

// List of session-student : whose result not store but.
// quiz result
 $sql="SELECT tutses_id,student_id,class_id,teacher_id FROM students_x_quiz WHERE 1 GROUP BY  student_id";
 $res=mysql_query($sql);
 //echo '=='.mysql_num_rows($res); die; 

 while ($row= mysql_fetch_assoc($res)){  
 
  $student_id=$row['student_id'];
  $stuent_line=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE id=".$student_id));
  // uppdate teacher_id , class_id in result 
  if($stuent_line){
    //upate
     $teacher=$stuent_line['teacher_id'];
  $class=$stuent_line['class_id'];
     // print_r($stuent_line);die;
      $update="UPDATE students_x_quiz
SET teacher_id='$teacher',class_id='$class' WHERE student_id=".$student_id ;
     $save=mysql_query($update);  //mysql_query

     // print_r($save);die;
           

  }else{
    echo '==student_id='.$student_id.' Found!,<br/>';
  }
 
 
 
  
 }


?>
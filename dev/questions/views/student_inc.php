<?php
/**
//
include('inc/connection.php'); 
session_start();
  ob_start();

  # Students :teacher_id school_id grade_level_id class_id created
**/
 //echo '===Students======='; die; 
  $sql_student='SELECT * FROM `students` WHERE id='.$_SESSION['student_id'];

  $student_det= mysql_fetch_assoc(mysql_query($sql_student));
  // Student Teacher 
  $teacher=mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE id=".$student_det['teacher_id']));
  //print_r($student_det);
  $student_school=$student_det['school_id'];
$student_teacher=$student_det['teacher_id'];//
 $studentId=$_SESSION['student_id'];
   $cur_time= date("Y-m-d H:i:s");  // die;

//////////Top menu ////////

 $count_arr=array();
 //Quiz pending and assigned. 
   function get_count_incomplete_quiz($num=1){
    global $studentId;
    $cr_time= date("Y-m-d H:i:s");  // die;
    // $num==1 : retrn rows count
     $studentId=$_SESSION['student_id'];
$sql="SELECT sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$studentId' ";
$sql.=" AND sd.quiz_status!='Completed' AND ses.ses_end_time<'$cr_time' ";

    // return $sql;
   $result=mysql_query($sql);
   return  mysql_num_rows($result); //($result);
   // Row Data : 

   }

   ///Upcoming session /////
   function get_count_pending_assessments($num=1){
   	global $studentId;  global $student_teacher;
   	$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '$studentId' ";

$sql.=" AND sa.status!='Completed' ";

  //return $sql;

$result=mysql_query($sql);
  $total=mysql_num_rows($result);
   return  $total; //($result);



   }
   // result: ///////
   function get_completed_assessments($num=1){
   	global $studentId;  global $student_teacher;
   	$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '$studentId' ";

$sql.=" AND sa.status='Completed' ";

 // return $sql;

$result=mysql_query($sql);
  $total=mysql_num_rows($result);
   return  $total; //($result);



   }

   /** // Quiz result  completed_quiz
   **/

   function get_completed_quiz($num=1){
   	global $studentId;  global $student_teacher;
$sql=" SELECT sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$studentId' ";

 $sql.=$change=" AND sd.quiz_status='Completed' ";
 
   	

  //return $sql;

$result=mysql_query($sql);
  $total=mysql_num_rows($result);
   return  $total; //($result);



   }

   


  ?>
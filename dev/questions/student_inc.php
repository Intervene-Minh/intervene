<?php
///////////////////////
$sql_student='SELECT * FROM `students` WHERE id='.$_SESSION['student_id'];
$student_det= mysql_fetch_assoc(mysql_query($sql_student));

$stuI =$_SESSION['student_id'];

$str="SELECT tch.teacher_id FROM `students_x_class` as stu INNER JOIN `class_x_teachers` AS tch ON stu.class_id=tch.class_id 
WHERE stu.student_id=$stuI";

$res=mysql_query($str);
$teachD= mysql_fetch_assoc();


$arrTID=[];
while ($rID= mysql_fetch_assoc($res)) {  


$arrTID[$rID['teacher_id']]= $rID['teacher_id'];
}


$teachD=implode(',',$arrTID);

// Student Teacher 


//print_r($student_det);
$student_school=$student_det['school_id'];
$student_teacher=$teachD;//
$studentId=$_SESSION['student_id'];
$cur_time= date("Y-m-d H:i:s"); 
$count_arr=array();
 //Quiz pending and assigned. 
   function get_count_incomplete_quiz($num=1){
        global $studentId;
        $cr_time= date("Y-m-d H:i:s");  // die;
        // $num==1 : retrn rows count
        $studentId=$_SESSION['student_id'];
        $sql="SELECT sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$studentId' ";
        $sql.=" AND sd.quiz_status!='Completed' AND ses.ses_end_time<'$cr_time'";
        // return $sql;
        $result=mysql_query($sql);
        return  mysql_num_rows($result); //($result);
        // Row Data : 

   }

   ///Upcoming session /////
   function get_count_pending_assessments($num=1){

         global $student_teacher;
         global $studentId;
        $sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
        Left Join assessments a ON  sa.assessment_id=a.id
        WHERE sa.teacher_id  IN ($student_teacher) AND sa.student_id= '$studentId'";
         $sql.=" AND sa.status!='Completed' ";
         //return $sql;
          $result=mysql_query($sql);
        $total=mysql_num_rows($result);
        return  $total; //($result);
      }
   // result: ///////
   function get_completed_assessments($num=1){

        global $studentId; 
         global $student_teacher;
        $sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
        Left Join assessments a ON  sa.assessment_id=a.id
        WHERE sa.teacher_id IN ($student_teacher) AND sa.student_id= '$studentId' ";
        $sql.=" AND sa.status='Completed'";
        // return $sql;
         $result=mysql_query($sql);
        $total=mysql_num_rows($result);
        return  $total; //($result);
      }

   /** // Quiz result  completed_quiz
   **/

   function get_completed_quiz($num=1){

          global $studentId; 
          global $student_teacher;
          $sql=" SELECT sd.quiz_status,sd.completion_date,sd.student_id,ses.* FROM int_schools_x_sessions_log ses INNER JOIN int_slots_x_student_teacher sd ON sd.slot_id =ses.id WHERE sd.student_id='$studentId' ";
          $sql.=$change=" AND sd.quiz_status='Completed' ";
          $result=mysql_query($sql);
          $total=mysql_num_rows($result);
          return  $total; //($result);
        }
?>
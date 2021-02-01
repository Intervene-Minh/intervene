<?php
include('inc/connection.php');
$action=$_POST["action"];
$interveneIDs=[];
$TelUserID=[];
/* this function delete student from telpas  */
function deleteTelpasUser($student_id){

   mysql_query('DELETE  FROM `telpas_course_score_logs` WHERE `intervene_student_id` = '.$student_id);
   mysql_query('DELETE  FROM `telpas_speaking_completed` WHERE `intervene_student_id`='.$student_id);
   mysql_query('DELETE  FROM `telpas_student_course_audios` WHERE `intervene_student_id`='.$student_id);
   mysql_query('DELETE  FROM `telpas_student_speaking_grades` WHERE `intervene_student_id`='.$student_id);
   mysql_query('DELETE  FROM `telpas_student_writing_grades` WHERE `intervene_student_id` ='.$student_id);
   mysql_query('DELETE  FROM `Tel_CourseComplete` WHERE `intervene_student_id`='.$student_id);
   mysql_query('DELETE  FROM `Tel_UserDetails` WHERE `IntervenID`='.$student_id);
   mysql_query('DELETE  FROM `Telpas_course_users` WHERE `intervene_uuid`='.$student_id);
}

function _curlDelete($telUID,$deltype){



  // Here is the data we will be sending to the service
  $arr_data = array('telpasStu' => $telUID,'deltype'=>$deltype);
  $curl = curl_init();
  // We POST the data
  curl_setopt($curl, CURLOPT_POST, 1);
  // Set the url path we want to call
  curl_setopt($curl, CURLOPT_URL, 'http://ellpractice.com/intervene/moodle/delete_user.php');  
  // Make it so the data coming back is put into a string
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  // Insert the data
  curl_setopt($curl, CURLOPT_POSTFIELDS, $arr_data);
  // You can also bunch the above commands into an array if you choose using: curl_setopt_array
  // Send the request
  $result = curl_exec($curl);
  // Free up the resources $curl is using
  curl_close($curl);
  $res=json_decode($result);
  echo  $res->status;
}

/*----- delete student ---*/
if($action=="deleteStudent"){


    $student_id = $_POST["student_id"];
    $telUID = $_POST["telUID"];
    mysql_query('DELETE FROM students WHERE id = \''.$student_id.'\' ');
    mysql_query('DELETE FROM students_x_assesments WHERE student_id = \''.$student_id.'\' ');
    mysql_query('DELETE FROM teacher_x_assesments_x_students WHERE student_id = \''.$student_id.'\' ');
    deleteTelpasUser($student_id);
    _curlDelete($telUID,'single');

 
 }

  /*----- class delete ---*/
  if($action=="deleteClasses"){
    $class_id = $_POST["classes_id"];
    $students = mysql_query('SELECT id FROM `students` WHERE class_id = \''.$class_id.'\' '); 
        if (mysql_num_rows($students) > 0) {
            while ($row = mysql_fetch_assoc($students)) {
               mysql_query('DELETE FROM students_x_assesments WHERE student_id = \''.$row['id'].'\' ');
              mysql_query('DELETE FROM teacher_x_assesments_x_students WHERE student_id = \''.$row['id'].'\' ');
              /* delete telpas student*/
               deleteTelpasUser($row['id']);
                $interveneIDs[] = $row['id'];
            }
        }

        /* telpas user id */
        $ids =implode(',',$interveneIDs);
        $str="Select TelUserID  FROM `Tel_UserDetails` WHERE IntervenID IN ($ids)";
        $telpas_res = mysql_query($str);
        while ($Telrow     = mysql_fetch_assoc($telpas_res)){
           $TelUserID[] = $Telrow['TelUserID'];
        }
        $telids =implode(',',$TelUserID);

        mysql_query('DELETE FROM students WHERE class_id = \''.$class_id.'\' ');
        mysql_query('DELETE FROM classes WHERE id = \''.$class_id.'\' ');
        _curlDelete($telids,'multi');
  }
  
  /*-----  Demo class delete ---*/  
  if($action=="deletedemoClasses"){
    $class_id = $_POST["classes_id"];
    $students = mysql_query('SELECT id FROM `demo_students` WHERE class_id = \''.$class_id.'\' '); 
        if (mysql_num_rows($students) > 0) {
            while ($row = mysql_fetch_assoc($students)) {
               mysql_query('DELETE FROM demo_students_x_assesments WHERE student_id = \''.$row['id'].'\' ');
               mysql_query('DELETE FROM demo_teacher_x_assesments_x_students WHERE student_id = \''.$row['id'].'\' ');
            }
        }
        mysql_query('DELETE FROM demo_students WHERE class_id = \''.$class_id.'\' ');
        mysql_query('DELETE FROM demo_classes WHERE id = \''.$class_id.'\' ');
       echo 'true'; die;
  }
  
  /*----- delete student ---*/
if($action=="deletedemoStudent"){
    $student_id = $_POST["student_id"];
   mysql_query('DELETE FROM demo_students WHERE id = \''.$student_id.'\' ');
   mysql_query('DELETE FROM demo_students_x_assesments WHERE student_id = \''.$student_id.'\' ');
   mysql_query('DELETE FROM demo_teacher_x_assesments_x_students WHERE student_id = \''.$student_id.'\' ');
   echo 'true'; die;
  }
  

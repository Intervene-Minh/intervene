<?php
/***
@ Intervention
intervene

$tab_sessions='int_schools_x_sessions_log'; # intervenetion and homework_help
$tab_ses_stuents='int_slots_x_student_teacher'; # student list in  intervention
$tab_ses_quiz_answer='students_x_quiz'; # sesion student quiz answer

https://api.braincert.com/v2/schedule?apikey=WbMlO5sAx1fmV&title=schedule&timezone=33&date=2014-11-11&start_time=09:30 AM&end_time=10:30AM& currency=usd&ispaid=0&is_recurring=1&repeat=1&weekdays=1,2,3&end_classes_count=10&seat_attendees=10&record=1&format=xml
**/


 //print_r($_SERVER); die; 


# no result for old students > all quiz are deleted. 


$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
$error= 'Intervention Session create';
$today = date("Y-m-d H:i:s"); // 
$msg=array();
$msg_error=array();

include('inc/connection.php'); 
session_start();
  ob_start();      
global $base_url;
$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';


//include("header.php");

// if ($_SESSION['login_role'] != 0) { //not admin
//     header('Location: folder.php');
//     exit;
// }


////////////////Create new class.//
 //session detail . 
$getid=$_GET['ses_id'];
 $ses_row=mysql_fetch_assoc(mysql_query(" SELECT *
FROM `int_schools_x_sessions_log`
WHERE id=".$getid));
 //print_r($ses_row);die; 
 $start=$ses_row['ses_start_time'];
 $end_time=$ses_row['ses_end_time'];
//echo  $start , '<br/>';
 //echo  $start=date('h:i A', strtotime($start));
 //$get_class_id=99;
 // $sql=mysql_query(" UPDATE int_schools_x_sessions_log SET braincert_class='$get_class_id' WHERE id=".$getid);


//die; 
#############################################################

$board_api_url='';
 $board_api_key='BlOM11ettmLhEMiRqRui';
$arr=array();
$ses_id=$getid;
$arr['title']=$title='Intervention_'.$getid;
 $arr['date_start']=date('Y-m-d', strtotime($ses_row['ses_start_time']));// 2019-02-15  //$date_start='2019-02-15';
$arr['start_time']=date('h:i A', strtotime($ses_row['ses_start_time']));     //$start_time='09:30 AM';
 $arr['end_time']=date('h:i A', strtotime($ses_row['ses_end_time']));  //$end_time='10:20 AM';



$arr['currency']='usd';
$arr['ispaid']=1; //1

$arr['is_recurring']=1;
$arr['repeat']=1;
$arr['weekdays']='1,2,3';

$arr['end_classes_count']=3;
$arr['seat_attendees']=5;
$arr['record']=1;
 //print_r($arr); die;

 $get_data=get_braincert_class($arr,$ses_id=$getid,$type='Intervention');
 echo 'Brains Classs-<br/>';
  print_r($get_data);  die; 

///////////////////
function get_braincert_class($arr,$ses_id,$type='Intervention'){


$url ="https://api.braincert.com/v2/schedule?apikey=BlOM11ettmLhEMiRqRui&title=".$arr['title']."&timezone=33&date=".$arr['date_start']."&start_time=".$arr['start_time']."&end_time=".$arr['end_time']."& currency=".$arr['currency']."&ispaid=".$arr['ispaid']."&is_recurring=".$arr['is_recurring']."&repeat=".$arr['repeat']."&weekdays=1,2,3&end_classes_count=".$arr['end_classes_count']."&seat_attendees=".$arr['seat_attendees']."&record=".$arr['record']."&format=json";

$class_url = str_replace(" ", '%20', $url);
$ch = curl_init();
$curlConfig = array(
    CURLOPT_URL            => $class_url,
    //CURLOPT_HTTPHEADER => $headers,
    CURLOPT_RETURNTRANSFER => true,
   
);


curl_setopt_array($ch, $curlConfig);
$result= curl_exec($ch);
// print_r($result); //print_r($data);  die; 
$data=json_decode($result);
curl_close($ch);

return $data;

}

/////////Get student URL:://///////
 # get student url 
$student_url="https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id=194809&userId=1200&userName=Teacher&isTeacher=1&courseName=education
&lessonName=tester&lessonTime=60&isRecord=0";

//echo $student_url; 
 function get_student_board_url($clss_id,$student_id){
 	//

 	$url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id='.$clss_id.'&userId='.$student_id.'&userName=Teacher&isTeacher=0&courseName=education
&lessonName=tester&lessonTime=60&isRecord=0&format=json';

$data = file_get_contents($url22);
 return $data; 
 }

 $stu_url=get_student_board_url($clss_id=194847,$student_id=210);
 echo '=======Student url-<br/>';
 print_r($stu_url);



?>
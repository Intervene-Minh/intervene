<?php
/***
@ Intervention
intervene

$tab_sessions='int_schools_x_sessions_log'; # intervenetion and homework_help
$tab_ses_stuents='int_slots_x_student_teacher'; # student list in  intervention
$tab_ses_quiz_answer='students_x_quiz'; # sesion student quiz answer

https://api.braincert.com/v2/schedule?apikey=WbMlO5sAx1fmV&title=schedule&timezone=33&date=2014-11-11&start_time=09:30 AM&end_time=10:30AM& currency=usd&ispaid=0&is_recurring=1&repeat=1&weekdays=1,2,3&end_classes_count=10&seat_attendees=10&record=1&format=xml
**/





# no result for old students > all quiz are deleted. 


$district_wise_school_list="SELECT s.district_id, count( s.SchoolId ) AS totsc, d.id, d.district_name
FROM `schools` s
LEFT JOIN loc_district d ON s.district_id = d.id
WHERE s.district_id >0
GROUP BY s.district_id
ORDER BY d.district_name";


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
 


#############################################################

$board_api_url='';
 $board_api_key='BlOM11ettmLhEMiRqRui';
$arr=array();
$ses_id=99;
$arr['title']=$title='Intervention101';
 $arr['date_start']=$date_start='2019-02-15';
$arr['start_time']=$start_time='09:30 AM';
 $arr['end_time']=$end_time='10:20 AM';



$arr['currency']='usd';
$arr['ispaid']=1; //1

$arr['is_recurring']=1;
$arr['repeat']=1;
$arr['weekdays']='1,2,3';

$arr['end_classes_count']=3;
$arr['seat_attendees']=5;
$arr['record']=1;
  //print_r($arr); die;

//$get_data=get_braincert_class($arr,$ses_id=99,$type='Intervention');
//echo 'Brains Clas-Objecti<br/>';
 // print_r($get_data); // die; 

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
$student_url="https://api.braincert.com/v2/getclasslaunch?apikey=WbMlO5sAx1fmV&class_id=34315&userId=19&userName=Teacher&isTeacher=1&courseName=education
&lessonName=tester&lessonTime=60&isRecord=0";
echo $student_url; 

?>
<?php

echo  'XXXXXXXX'; die;
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
 $board_api_url='';
 $board_api_key='BlOM11ettmLhEMiRqRui';
$get_class_url='https://api.braincert.com/v2/schedule?apikey=WbMlO5sAx1fmV&title=schedule&timezone=33&date=2014-11-11&start_time=09:30 AM&end_time=10:30AM& currency=usd&ispaid=0&is_recurring=1&repeat=1&weekdays=1,2,3&end_classes_count=10&seat_attendees=10&record=1&format=xml';

echo  $get_class_url;

?>
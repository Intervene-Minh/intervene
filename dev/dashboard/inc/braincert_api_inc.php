<?php
/**
braincert_api_inc.php 

**/
############################################

/**
get_braincert_class
**/

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
/***Student URL**/

function get_student_board_url($clss_id,$student_id){
 	//

 	$url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id='.$clss_id.'&userId='.$student_id.'&userName=Teacher&isTeacher=1&courseName=education
&lessonName=tester&lessonTime=60&isRecord=0&format=json';

$data = file_get_contents($url22);
 return $data; 
 }

//  Tutor board url 
 function get_tutor_board_url($clss_id,$student_id){
 	//

 	$url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id='.$clss_id.'&userId='.$student_id.'&userName=Teacher&isTeacher=1&courseName=education
&lessonName=tester&lessonTime=60&isRecord=0&format=json';

$data = file_get_contents($url22);
 return $data; 
 }



 //$stu_url=get_student_board_url($clss_id=194847,$student_id=210);
 //echo '=======Student url-<br/>';
 //print_r($stu_url);


?>
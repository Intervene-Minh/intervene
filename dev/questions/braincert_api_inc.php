<?php
/**
braincert_api_inc.php 

**/
############################################
/**Remove Class**/

function remove_brain_class($class_id){

 	$post = [
    'cid' => $class_id,
    'format' => 'json',
    //'gender'   => 1,
];

// $ch = curl_init('http://www.example.com');
$ch = curl_init('https://api.braincert.com/v2/removeclass?apikey=BlOM11ettmLhEMiRqRui&format=xml');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// execute!
$response = curl_exec($ch);

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response
//var_dump($response);
 return $response; 

 }


/**
get_braincert_class

- 3 start recording automatically when class starts and disable instructor from managing the recording button. Recording will be produced at the end of class time.
-isPrivateChat == only tutor to stuent chat
**/

function get_braincert_class($arr,$ses_id,$type='Intervention'){


$urlStop="https://api.braincert.com/v2/schedule?apikey=BlOM11ettmLhEMiRqRui&title=".$arr['title']."&timezone=16&date=".$arr['date_start']."&start_time=".$arr['start_time']."&end_time=".$arr['end_time']."& currency=".$arr['currency']."&ispaid=".$arr['ispaid']."&is_recurring=".$arr['is_recurring']."&repeat=".$arr['repeat']."&weekdays=1,2,3&end_classes_count=".$arr['end_classes_count']."&seat_attendees=".$arr['seat_attendees']."&record=".$arr['record']."&format=json";


$url ="https://api.braincert.com/v2/schedule?apikey=BlOM11ettmLhEMiRqRui&title=".$arr['title']."&timezone=16&date=".$arr['date_start']."&start_time=".$arr['start_time']."&end_time=".$arr['end_time']."& currency=".$arr['currency']."&ispaid=".$arr['ispaid']."&is_recurring=".$arr['is_recurring']."&repeat=".$arr['repeat']."&weekdays=1,2,3&end_classes_count=".$arr['end_classes_count']."&seat_attendees=".$arr['seat_attendees']."&record=3&isPrivateChat=1&format=json";
 //echo  $url ; die; 


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

function get_student_board_url($clss_id,$student_id,$stu_name){
 	//
 	$stu_name=(!empty($stu_name))?$stu_name:'Student_'.$student_id;
 	 $lessonName='lessonName';
 	 $lessonTime=60;

 	$url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id='.$clss_id.'&userId='.$student_id.'&userName='.$stu_name.'&isTeacher=0&courseName=education
&lessonName='.$lessonName.'&lessonTime='.$lessonTime.'&isRecord=0&format=json';

$data = file_get_contents($url22);
 return $data; 
 }

 //$stu_url=get_student_board_url($clss_id=194847,$student_id=210);
 //echo '=======Student url-<br/>';
 //print_r($stu_url);
// Teacher Board URL.
function get_tutor_board_url($clss_id,$student_id,$stu_name){
 	//
 	$stu_name=(!empty($stu_name))?$stu_name:'tutor_'.$student_id;
 	 $lessonName='lessonName';
 	 $lessonTime=60;

 	$url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id='.$clss_id.'&userId='.$student_id.'&userName='.$stu_name.'&isTeacher=1&courseName=education
&lessonName='.$lessonName.'&lessonTime='.$lessonTime.'&isRecord=0&format=json';

$data = file_get_contents($url22);
 return $data; 
 }
 ////Get Observer UR/////observer////////
function get_observer_url($clss_id,$ob_id,$stu_name=''){
  //
 
  $stu_name='Observer_Tutor_'.$ob_id;
   $lessonName='lessonName';
   $lessonTime=60;

  $url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id='.$clss_id.'&userId='.$ob_id.'&userName='.$stu_name.'&isTeacher=1&courseName=education
&lessonName='.$lessonName.'&lessonTime='.$lessonTime.'&isRecord=0&format=json';

$data = file_get_contents($url22);
 return $data; 
 }

 // Student:observere///

 function get_observerStu_url($clss_id,$ob_id,$stu_name=''){
  //
 
  $stu_name='Observer_Student_'.$ob_id;
   $lessonName='lessonName';
   $lessonTime=60;

  $url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id='.$clss_id.'&userId='.$ob_id.'&userName='.$stu_name.'&isTeacher=0&courseName=education
&lessonName='.$lessonName.'&lessonTime='.$lessonTime.'&isRecord=0&format=json';

$data = file_get_contents($url22);
 return $data; 
 }



////////////////////

//  function s_get_tutor_board_urlxxx($clss_id,$student_id,$stu_name){
//  	//
//  	$stu_name=(!empty($stu_name))?$stu_name:'Tutor_'.$student_id;
//  	 $lessonName='lessonName';
//  	 $lessonTime=60;

//  	$url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id='.$clss_id.'&userId='.$student_id.'&userName='.$stu_name.'&isTeacher=1&courseName=education
// &lessonName='.$lessonName.'&lessonTime='.$lessonTime.'&isRecord=0&format=json';

// $data = file_get_contents($url22);
//  return $data; 
//  }


?>
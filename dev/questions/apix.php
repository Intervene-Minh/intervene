<?php 
//phpinfo(); die; 
error_reporting(-1);
    ini_set('display_errors', 1);
 $url1= 'https://api.braincert.com/v2/schedule?apikey=BlOM11ettmLhEMiRqRui&title=schedule&timezone=33&date=2019-02-03&start_time=09:30 AM&end_time=10:30AM& currency=usd&ispaid=0&is_recurring=1&repeat=1&weekdays=1,2,3&end_classes_count=5&seat_attendees=5&record=1&format=json';




$url22='https://api.braincert.com/v2/getclasslaunch?apikey=BlOM11ettmLhEMiRqRui&class_id=194863&userId=201&userName=Teacher&isTeacher=0&courseName=education
&lessonName=tester&lessonTime=60&isRecord=0&format=json';

$data = file_get_contents($url22);
print_r($data); die;


$url= str_replace(' ', '%20', $url22);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
$ret = curl_exec($ch);
print_r($ret); exit;
// returned json string will look like this: {"code":1,"data":"OK"}

?>
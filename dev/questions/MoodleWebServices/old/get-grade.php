<?php

$token = 'd9495caf1936f58a212f29817a797121';
$domainname = 'https://ellpractice.com/intervene/moodle';
$functionname = 'gradereport_user_get_grade_items';
require_once('cur-moodlel.php');
$curl = new curl;
$restformat = 'json'; 




$grade = array( 'courseid' => 5, 'user_id'=> 27);
$user_grades = array($grade);
$params = array('user_grades' => $user_grades);

//print_r($params);
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$Grade = json_decode($resp,true);


print_r($Grade)
?>
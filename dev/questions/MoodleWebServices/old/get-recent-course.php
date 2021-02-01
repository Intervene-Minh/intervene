<?php

$token = '3803989bf63a1a756aa8b865a6613fde';
$domainname = 'https://ellpractice.com/intervene/moodle';
$functionname = 'core_course_get_recent_courses';
require_once('cur-moodlel.php');
$curl = new curl;
$restformat = 'json'; 



//print_r($params);
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat);

$responsName = json_decode($resp,true);



print_r($responsName);
?>
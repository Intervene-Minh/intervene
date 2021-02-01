<?php

$token = '249876fc8a12e91afe66246a41ede339';
$domainname = 'https://ellpractice.com/intervene/moodle';
$functionname = 'core_enrol_get_users_courses';

require_once('cur-moodlel.php');

$curl = new curl;

$restformat = 'json'; 

$params = array('userid' =>$TelPasUserID,);

$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$respons = json_decode($resp,true);
?> 
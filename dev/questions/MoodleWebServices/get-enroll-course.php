<?php

$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
require_once($Directorypath.'cur-moodlel.php');
$token = '249876fc8a12e91afe66246a41ede339';
$domainname = $webServiceURl;
$functionname = 'core_enrol_get_users_courses';


$curl = new curl;

$restformat = 'json'; 

$params = array('userid' =>$TelPasUserID,);

$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$respons = json_decode($resp,true);
?> 
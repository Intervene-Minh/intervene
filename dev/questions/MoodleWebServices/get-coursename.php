<?php
$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
require_once($Directorypath.'cur-moodlel.php');
$token = '14432b87ba8ea3a4896dc7707d10e71d';
$domainname = $webServiceURl;
$functionname = 'core_course_get_courses_by_field';

$curl = new curl;
$restformat = 'json'; 

$params = array('field'=>'id','value'=>$couserID);

//print_r($params);
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$responsName = json_decode($resp,true);?>
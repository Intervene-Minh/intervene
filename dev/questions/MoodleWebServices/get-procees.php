<?php

$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
require_once($Directorypath.'cur-moodlel.php');

$token = 'fc031ccd9d287cdd293adde7e0ef546a';
$domainname = $webServiceURl;
$functionname = 'core_completion_get_activities_completion_status';

$curl = new curl;


$restformat = 'json'; 




$params = array(
	'courseid' =>5, 
	'userid' => 27
);



$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;


$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);


$respons = json_decode($resp,true); 



print_r($respons);

?>
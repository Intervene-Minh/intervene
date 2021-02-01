<?php

$token = 'fc031ccd9d287cdd293adde7e0ef546a';
$domainname = 'https://ellpractice.com/intervene/moodle';
$functionname = 'core_completion_get_activities_completion_status';

require_once('cur-moodlel.php');

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
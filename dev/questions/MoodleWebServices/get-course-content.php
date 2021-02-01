<?php
$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
require_once($Directorypath.'cur-moodlel.php');
$token = '02104727338432bcc247cce5338abb18';
$domainname = $webServiceURl;
$functionname = 'core_course_get_contents';

$curl = new curl;
$restformat = 'json'; 

$params = array('courseid' =>$couserID,);

$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$respons = json_decode($resp,true);
$i=1;

$Qustion=[];

foreach ($respons as  $subaaray) {


	foreach ($subaaray['modules'] as $value) {


   		$Qustion[$i]= $value['id'];
   		$instance[$value['id']] = $value['instance'];


   		$i++;
   		
   	}
   
   }  
 ?>   


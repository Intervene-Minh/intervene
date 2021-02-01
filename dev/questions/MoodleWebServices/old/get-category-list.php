<?php
$token = '1a8fd756cccee6fcc395a37b1f099dd4';
$domainname = 'https://ellpractice.com/intervene/moodle';
$functionname = 'core_course_get_categories';
require_once('cur-moodlel.php');
$curl = new curl;
$restformat = 'json'; 

$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, '');

$responCategory = json_decode($resp,true);
?>
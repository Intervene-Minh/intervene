<?php
		$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
		require_once($Directorypath.'cur-moodlel.php');
		$token = '1a8fd756cccee6fcc395a37b1f099dd4';
		$domainname = $webServiceURl;
		$functionname = 'core_course_get_categories';

		$curl = new curl;
		$restformat = 'json'; 

		$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

		$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

		$resp = $curl->post($serverurl . $restformat, '');

		$responCategory = json_decode($resp,true);
?>
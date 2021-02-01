	<?php

				$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
				require_once($Directorypath.'cur-moodlel.php');
				$curl = new curl;

				$restformat = 'json'; 
				$domainname = $webServiceURl;

				$tokencourses_by_field = '14432b87ba8ea3a4896dc7707d10e71d';
				$functionnametokencourses_by_field = 'core_course_get_courses_by_field';

				$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $tokencourses_by_field . '&wsfunction='.$functionnametokencourses_by_field;
				$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
				$resp = $curl->post($serverurl . $restformat, '');
				$responsCourse = json_decode($resp,true);


	?>
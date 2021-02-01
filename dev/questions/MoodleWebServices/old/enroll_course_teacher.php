<?php
	
	require_once('cur-moodlel.php');
	$curl = new curl;
	$restformat = 'json'; 
	$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:''; //
	$domainname = 'https://ellpractice.com/intervene/moodle';
	/*TeacherID=120 teacher id*/
	//$TeacherID=578; // gulfam id

	$TeacherID=120;
	/* enrol token details*/
	$token="831c9d38c55c65b99a5dde1bc4677ae1";
	$function = 'enrol_manual_enrol_users';
	$enrolment = new stdClass();
	/* token details get course list*/
	$token_field = '14432b87ba8ea3a4896dc7707d10e71d';
	$functionn_field = 'core_course_get_courses_by_field';
	$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token_field . '&wsfunction='.$functionn_field;
	/* Hit Web Services for get course List*/
	$resp = $curl->post($serverurl . $restformat,'');
	$responsCourse = json_decode($resp,true);
	//print_r($responsCourse); die;

	foreach ($responsCourse['courses'] as $row) 
	{
		if($row['categoryid'] > 0)
		{
			
			$course_id = $row['id']; // course IDS


			$enrolment->courseid = $course_id; 
			/* Enrol student*/
			$enrolment->roleid = 4; //estudante(student) -> 5; moderador(teacher) -> 4; professor(editingteacher) -> 3;
			$enrolment->userid =$TeacherID;
			/* end enrol token details*/
			
			$enrolments = array($enrolment); // enrolment details array
			$params = array('enrolments' => $enrolments); // set parameter

			//print_r($params);
			
			$serverurl = $domainname . '/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$function; // end point url

			
			$respons = $curl->post($serverurl . $restformat, $params); // hit web url
			
			

		}
	}
?>
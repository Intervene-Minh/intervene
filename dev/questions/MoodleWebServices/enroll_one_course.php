<?php


	$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
	require_once($Directorypath.'cur-moodlel.php');
	$curl = new curl;
	$restformat = 'json'; 
	$domainname = $webServiceURl;
	$tokenenrol_users="831c9d38c55c65b99a5dde1bc4677ae1";
	$functionnametokenenrol_users = 'enrol_manual_enrol_users';

	if($categoryid > 0 AND $course_id > 0) {

		$couserID =$course_id;
		require_once('MoodleWebServices/get-course-content.php');
		$url=$respons[1]['modules'][0]['url'];
		$str="SELECT * FROM `Telpas_course_users`  WHERE `telpas_uuid`='".$TeluserID."'&&   `course_id`='".$course_id."' && `course_cat_id`='".$categoryid."' && `intervene_uuid`='".$InterUserID."'";

		$qr= mysql_query($str);

		$duplicate= mysql_num_rows($qr);

		if($duplicate >0){


			header("location:".$url);

		} else{



				$enrolment = new stdClass();
				/* Enrol student*/
				$enrolment->roleid = 5; //estudante(student) -> 5; moderador(teacher) -> 4; professor(editingteacher) -> 3;
				$enrolment->userid = $TeluserID; //$userID;
				$enrolment->courseid = $course_id; 
				$enrolments = array( $enrolment);
				$params = array('enrolments' => $enrolments);
				$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $tokenenrol_users . '&wsfunction='.$functionnametokenenrol_users;
				$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
				$respEn = $curl->post($serverurl . $restformat, $params);
				/* add enroll Course ID */
				$str="INSERT INTO `Telpas_course_users` SET `telpas_uuid`='".$TeluserID."',`course_id`='".$course_id."',`course_cat_id`='".$categoryid."',
				`intervene_uuid`='".$InterUserID."'";
				mysql_query($str);
			
				header("location:".$url);


		}
		
	
		
	}
?>
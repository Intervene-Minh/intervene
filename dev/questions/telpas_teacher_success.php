<?php
	require_once('inc/connection.php'); 
	$urltogo='telpas_student_progress.php?t=y';
	$redrict=0;
	require_once 'MoodleWebServices/get_courseListOnly.php';

	$teacher_id=$_REQUEST['id'];

	$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';

	require_once($Directorypath.'cur-moodlel.php');
	$curl = new curl;
	$restformat = 'json'; 
	$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:''; //
	$domainname = $webServiceURl;	

	/* enrol token details*/
	$token="831c9d38c55c65b99a5dde1bc4677ae1";
	$function = 'enrol_manual_enrol_users';
	$enrolment = new stdClass();
	/* token details get course list*/

	foreach ($responsCourse['courses'] as $c) {
		
		if($c['visible']==1 && $c['categoryid'] > 0){
			
			$course_id= $c['id'];
			$course_name=$c['fullname'];
			$str  ="SELECT Id,course_id FROM `telpas_enroll_teacher` WHERE  `course_id`= $course_id and `teacher_id`= $teacher_id";
			$qr = mysql_query($str);
			$numRows= mysql_num_rows($qr);
			if($numRows==0){
			
					$enrolment->courseid = $course_id; 
					/* Enrol student*/
					$enrolment->roleid = 4; //estudante(student) -> 5; moderador(teacher) -> 4; professor(editingteacher) -> 3;
					$enrolment->userid =$teacher_id;
					/* end enrol token details*/

					$enrolments = array($enrolment); // enrolment details array
					$params = array('enrolments' => $enrolments); // set parameter

					//print_r($params);

					$serverurl = $domainname . '/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$function; // end point url


					$respons = $curl->post($serverurl . $restformat, $params); // hit web url
					echo $str= "INSERT INTO `telpas_enroll_teacher`  SET `course_id`= $course_id , `course_name`= '".$course_name."', `status`=1, `teacher_id`= $teacher_id ";
					mysql_query($str);

			}

		}
		$redrict=1;
	}

if($redrict > 0 ){
echo "<script> window.location='".$urltogo."'</script>";	
}
?>
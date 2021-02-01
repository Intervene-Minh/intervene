<?php
$post = ['telpasID' =>$TelPasUserID,'course_id'=>$course_id];

$ch = curl_init('http://ec2-35-165-58-67.us-west-2.compute.amazonaws.com/dev/get_course_writtng.php');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

// execute!
$response = curl_exec($ch);

//print_r($response);
// close the connection, release resources used
curl_close($ch);

$res=json_decode($response,true);
foreach ($res as $k => $row) {



		$question_id=$row['content_id'];
		$question_answer=$row['response'];

		$str="SELECT * FROM `telpas_student_writing_grades` WHERE
		intervene_student_id='".$InterveneID."' AND `course_id`='".$course_id."' && `question_id` =$question_id"; 
		$qr=mysql_query($str);
		$num_rows = mysql_num_rows($qr);
		if($num_rows==0){

			$str=" INSERT INTO  `telpas_student_writing_grades` SET  intervene_student_id='".$InterveneID."', `telpas_student_id`= $TelPasUserID, `course_id`='".$course_id."', `question_id`=$question_id, `total_question` =$totalquestion, `attemp_question`=0, `question_answer`= '".$question_answer."'"; 
			mysql_query($str);

		}

}
$redirect=1;
?>
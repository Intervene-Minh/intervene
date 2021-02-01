<?php
/* INCLUDE CLEVER API FUNCTION FILE */
require_once $_SERVER['DOCUMENT_ROOT'].'/questions/clever/clever.ApiFunction.php';

/*GET CLVER DATA*/
$res = _getCleverData('https://api.clever.com/me');

/* get type of user */
$type = $res['data']['type'];

/*start switch case*/
switch ($type) {

		case "teacher": //  teacher case 

			/* get teacher email ID*/
			$teacher_email         = $res['data']['email'];

			$qr = mysql_query("SELECT id FROM users WHERE email='".$teacher_email."'");

			$count = mysql_num_rows($qr);
			if($count > 0 ){ // check duplicate user
				$res = mysql_fetch_assoc($qr);
				$teacher_id= $res['id']; // get exist teacher id
				_teacherLogin($teacher_id); 	/*teacher login */
			}
			else{

					/*redirct*/
					_redirect('warning-message.php');
			}

			break;

			case "student": //  student case 

				/* get teacher email ID*/
				$stuUID    	 = $res['data']['id'];
				$qr=mysql_query("SELECT id FROM students WHERE uid='".$stuUID."'");
				$count = mysql_num_rows($qr);

				if($count > 0 ){ // check duplicate user
					$res = mysql_fetch_assoc($qr);
					$student_id= $res['id']; // get exist student id
					_studentLogin($student_id); /*student login */
				}
				else{

						/*redirct*/
						_redirect('warning-message.php');
				}
				break;

				default:

				/*redirct*/
				_redirect('warning-message.php');

}
/*end  switch case*/
?>
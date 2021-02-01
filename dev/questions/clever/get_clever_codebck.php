<?php

/* INCLUDE CLEVER API FUNCTION FILE */
require_once $_SERVER['DOCUMENT_ROOT'].'/questions/clever/clever.ApiFunction.php';


/*GET CLVER DATA*/
$res = _getCleverData('https://api.clever.com/me');

/*
echo '<pre>';

print_r($res);
echo '</pre>';
die;
*/
/*DEMO SCHOOL ID*/
$school_id=68;
/* get type of user */
$type = $res['data']['type'];

/*start switch case*/

switch ($type) {

		case "teacher": //  teacher case 

			$teacher_clever_id     =   $res['data']['id'];
			$teacher_email         =   $res['data']['email'];
			$teacher_first_name    =   $res['data']['name']['first'];
			$teacher_last_name     =   $res['data']['name']['last'].' '.$res['data']['name']['middle'];

			$Tpass=md5(123456); // gen rate password in MD5 format
			$Tuname =$teacher_first_name.'123'; // set username 

			$qr = mysql_query("SELECT id ,count(id) as cnt FROM users WHERE teacher_clever_id='".$teacher_clever_id."' Group by id");

			$res = mysql_fetch_assoc($qr);

			if($res['cnt'] > 0 ){ // check duplicate user

				$teacher_id= $res['id']; // get exist teacher id
				_teacherLogin($teacher_id); 	/*teacher login */
			}
			else{ // insert user in data base

				$str="INSERT INTO `users` SET `is_subdmin`='no', `user_name`='".$Tuname."',`email`='".$teacher_email."', `password`='".$Tpass."',`teacher_clever_id`='".$teacher_clever_id."',`first_name`='".$teacher_first_name."',`last_name`='".$teacher_last_name."',`school`='".$school_id."',`role`=1,`status`=1,`date_registered`='".date("Y-m-d  h:i:s")."',`district_id`=0, 
						`master_school_id`=0,`q_remaining`=0";

						mysql_query($str);

						$teacher_id= mysql_insert_id(); // get teacher id

						_teacherLogin($teacher_id); 	/*teacher login */
		    		}

			break;

			case "student": //  student case 

				$stuUID    	 =   $res['data']['id'];
				$stuEmail    =   $res['data']['email'];
				$stuFName    =   $res['data']['name']['first'];
				$stuLName    =   $res['data']['name']['last'].' '.$res['data']['name']['middle'];
				$stuBdy      =   $res['data']['dob'];
				$StuPass	 =   base64_encode('020student'); // gen rate password in base64 format
				$StuUname    =  $stuFName.'.'.$stuLName;
				$stuname     =  strtolower(str_replace(" ","",$StuUname)); // set username 
				$res = mysql_fetch_assoc(mysql_query("SELECT id ,count(id) as cnt FROM students WHERE uid='".$stuUID."' Group by id"));

				if($res['cnt'] > 0 ){ // check duplicate user

					$student_id= $res['id']; // get exist student id
					_studentLogin($student_id); /*student login */
				}
				else{

				$str= "INSERT INTO `students` SET  `email`='".$stuEmail."',`first_name` ='".$stuFName."',  `last_name`  ='".$stuLName."', `student_bdy`='".$stuBdy."',  `username`  = '".$stuname."',  `password`  ='".$StuPass."',   `school_id`  ='".$school_id."',  `grade_level_id` ='0',   `roster_id` ='0', `uid` ='".$stuUID."',  `status` =1, `created` ='".date("Y-m-d  h:i:s")."'";

				mysql_query($str);
				$student_id =mysql_insert_id(); // get student id
				_studentLogin($student_id); /*student login */

				}
				break;

				default:

				echo 'Wrong User Type.';

}
/*end  switch case*/
?>
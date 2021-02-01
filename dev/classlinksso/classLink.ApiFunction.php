<?php

/*INCLUDE DATA BASE CONNECTION FILE*/
require_once $_SERVER['DOCUMENT_ROOT'].'/questions/inc/connection.php';

/*INCLUDE Key File FILE*/
require_once $_SERVER['DOCUMENT_ROOT'].'/questions/clever/clever.keys.php';

/* This function use for get keys*/
function _SetCleverKey($clinetID,$SecretKey)
{
	return base64_encode($clinetID.":".$SecretKey);
}

/*get claver auth token*/
function _getCleverToken($Clevercode,$redirect_uri){

		global $keys;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://clever.com/oauth/tokens',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => "code=$Clevercode&grant_type=authorization_code&redirect_uri=$redirect_uri",
		CURLOPT_HTTPHEADER => array("Authorization: Basic $keys","Content-Type: application/x-www-form-urlencoded"),));
		$response = curl_exec($curl);
		curl_close($curl);
		$d = json_decode($response);

		if(!empty($d->error)){ // check  error 

		 	$d->error_description;
		 }else{

		 	
		 	return $d->access_token;


		 }


}

/* get clever data */
function _getCleverData($url){

	global $token;
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => "$url",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	CURLOPT_HTTPHEADER => array(
	"Authorization: Bearer $token"
	),
	));

	$res = curl_exec($curl);
	curl_close($curl);
	$d = json_decode($res,true);
	return $d;

}	


/* get keys*/
$keys = _SetCleverKey($client_id,$secret_id);
/* code is set and not empty*/
if(isset($code) && !empty($code)){
	/*get clever token*/
	$token =_getCleverToken($code,$redirect_uri);
}
function _teacherLogin($teacher_id)
{

		global $siteURL;
		// SQL query to fetch information of registerd users and finds user match.
		$query = mysql_query("SELECT * FROM users WHERE id=$teacher_id LIMIT 1");
		$rows = mysql_num_rows($query);
		if ($rows == 1) {
			$row = mysql_fetch_assoc($query);
			$lastest_login = date('Y-m-d H:i:s');
			$query = mysql_query("UPDATE users SET latest_login='$lastest_login' WHERE id=$teacher_id");
			$_SESSION['login_id']=$row['id'];
			$_SESSION['login_user']=$row['user_name']; // Initializing Session
			$_SESSION['login_mail']=$row['email'];
			$_SESSION['login_role']=$row['role'];
			$_SESSION['login_status']=$row['status'];
			$_SESSION['ses_subdmin']=$row['is_subdmin']; // For multi-sub-admin User..
			header("location: $siteURL"); // Redirecting To Other Page
	}

}

/*student login function*/
function _studentLogin($student_id){

		global $siteURL;
		$redirectURL = $siteURL.'welcome.php';
		$student_id = mysql_query("SELECT * FROM `students` WHERE `id` = $student_id AND `status` = 1 ");
		if( mysql_num_rows($student_id) == 0 ) {

				$error = 'Your information is invalid!';
		} 
		else {

			$students = mysql_fetch_assoc($student_id);
			$_SESSION['student_id'] = $students['id'];
			$studi= $students['id'];
			$_SESSION['student_name'] = $students['first_name'];
			$_SESSION['last_name'] = $students['last_name'];
			$_SESSION['schools_id'] = $students['school_id'];
			$str="SELECT tch.teacher_id FROM `students_x_class` as stu INNER JOIN `class_x_teachers` AS tch ON stu.class_id=tch.class_id WHERE stu.student_id=$studi";
			$teachD= mysql_fetch_assoc(mysql_query($str));

			$_SESSION['teacher_id'] = $teachD['teacher_id'];
			header("Location: $redirectURL"); 

		}
}

/* redirect funtion*/
function _redirect($localion){

		$url="https://" . $_SERVER['SERVER_NAME'].'/'.$localion;
		echo "<script> window.location='".$url."';</script>";
}
?>
<?php

 $token = 'fcc6f9e980dbdef0496fac348966499f';
$domainname = 'https://ellpractice.com/intervene/moodle';
$functionname = 'core_user_create_users';

require_once('cur-moodlel.php');

$curl = new curl;
// REST RETURNED VALUES FORMAT

$restformat = 'json'; 


//Also possible in Moodle 2.2 and later: 'json'
                     //Setting it to 'json' will fail all calls on earlier Moodle version
//////// moodle_user_create_users ////////
/// PARAMETERS - NEED TO BE CHANGED IF YOU CALL A DIFFERENT FUNCTION
$userDetails = new stdClass();


$ppas   =    $_SESSION['student_name'].'A@1';
$uname  =   $_SESSION['student_name'].'_'.$_SESSION['student_id'].'13';


$userDetails->username  			= 	 	$uname;
$userDetails->password  			=  		$ppas;
$userDetails->firstname 			= 		$_SESSION['student_name'].'va';
$userDetails->lastname  			= 		$_SESSION['student_name'].'av';
$userDetails->email    				= 		$uname.'@aintervene.com';
$userDetails->auth     				= 		'manual';
$userDetails->idnumber 				= 		$_SESSION['student_id'].'2s';

$users = array($userDetails);

$params = array('users' => $users);

 $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;





$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);


$respons = json_decode($resp); 



echo $userID = $respons->id;
print_r($respons)

die;


/* check  signup*/
if($respons->errorcode== 'invalidparameter' ){

	$url= $domainname."/login-viaAPI-from-Inter.php?username=".$uname."&password=".$ppas."";
    header("location:".$url);


}

else{
if(!empty($userID)){
	$token="831c9d38c55c65b99a5dde1bc4677ae1";
	$functionname = 'enrol_manual_enrol_users';
	$enrolment = new stdClass();
    $enrolment->roleid = 5; //estudante(student) -> 5; moderador(teacher) -> 4; professor(editingteacher) -> 3;
    $enrolment->userid =$userID;
    $enrolment->courseid = 5; 
    $enrolments = array( $enrolment);
    $params = array('enrolments' => $enrolments);
    $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
    $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
    $respEn = $curl->post($serverurl . $restformat, $params);
    $url= $domainname."/login-viaAPI-from-Inter.php?username=".$uname."&password=".$ppas."";
    header("location:".$url);
}
else{

	 $url= $domainname."/login-viaAPI-from-Inter.php?username=".$uname."&password=".$ppas."";
     header("location:".$url);
}

}

 ?>
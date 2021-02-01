<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoload.php';
require 'data/database.php';

$app = new \Slim\Slim(array(
    'debug' => true
));
$app->get("/",function(){
print_r($_SESSION['tUser']);
});
$app->get('/lessons', function(){
		$db=new Database();
	$sql="";
	if(checkStudent()){
		$sql="select * from tutorSessions where StudentID=".$_SESSION['sUser']['studentID']." and IsActive=1";
	}
	if(checkTutor()){
		$sql="select * from tutorSessions where TutorID=".$_SESSION['tUser']['tutorID']." and IsActive=1";
	}
	if(!empty($sql)){
		echo $sql;
	$results=$db->Select($sql);
	foreach($results as $key=>$lesson){
		
		$sql_sessions="select * from PaymentRequests where SessionID=$lesson->ID";
		$lesson->sessions=$db->Select($sql_sessions);
		$sql_tutor="select t.firstName, t.lastName,p.* from tutors t join tutorProfiles p on t.id=p.TutorID where id=".$lesson->TutorID;
		$lesson->tutor=$db->SelectFirst($sql_tutor);
		$sql_tutor="select s.firstName, s.lastName,p.* from students s join studentProfiles p on s.id=p.StudentID where id=".$lesson->StudentID;
		$lesson->student=$db->SelectFirst($sql_tutor);
		$results[$key]=$lesson;
	}
	echo json_encode($results);
	}else{
	echo "[]";
	}
	
});
$app->get("/lessons/:id",function($id){
	$db=new Database();
	$sql="";
	if(!checkStudent()&&!checkTutor()){
		echo "{}";
		return;
	}
		$sql="select * from tutorSessions where id=$id";
		$lesson=$db->SelectFirst($sql);
			$sql_sessions="select * from PaymentRequests where SessionID=$lesson->ID";
		$lesson->sessions=$db->Select($sql_sessions);
		$sql_tutor="select t.firstName, t.lastName,p.* from tutors t join tutorProfiles p on t.id=p.TutorID where id=".$lesson->TutorID;
		$lesson->tutor=$db->SelectFirst($sql_tutor);
		$sql_tutor="select s.firstName, s.lastName,p.* from students s join studentProfiles p on s.id=p.StudentID where id=".$lesson->StudentID;
		$lesson->student=$db->SelectFirst($sql_tutor);
		echo json_encode($lesson);
		
});
$app->get("/lessons/:id/sessions",function($id){
	$db=new Database();
	$sql="";
	if(!checkStudent()&&!checkTutor()){
		echo "[]";
		return;
	}
	$sql="select * from PaymentRequests where SessionID=$id";
	$results=$db->Select($sql);
	echo json_encode($results);
	
});
$app->run();

function checkStudent(){
	if(isset($_SESSION['sUser'])){
		
		return true;
	}
	return false;
}
function checkTutor(){
	if(isset($_SESSION['tUser'])){
		return true;
	}
	return false;
}


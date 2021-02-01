<?php
session_start ();
error_reporting ( E_ALL );
ini_set ( 'display_errors', 1 );

require 'vendor/autoload.php';
require 'vendor/class.phpmailer.php';
require 'data/database.php';
require_once ("vendor/authorizenet/AuthorizeNet.php");
include_once(dirname(__FILE__)."/gf/GRAPIPHPClient-master/src/GeniusReferrals/ApiClientInterface.php");
require_once 'gf/GRAPIPHPClient-master/src/GeniusReferrals/GRPHPAPIClient.php';
use GeniusReferrals\GRPHPAPIClient;
require_once 'util.php';



class Authorize extends \Slim\Middleware
{
	public function call(){
		$app = $this->app;
		if(!isset($_SESSION['user'])){
			header("HTTP/1.1 403 Access denied");
			exit;
		}
		 $this->next->call();
	}
}

$app = new \Slim\Slim ( array (
		'debug' => true
) );
$app->add(new Authorize());

foreach(glob('routes/*.php') as $file) {
	include_once $file;
}

//require_once 'routes/invites.php';

//require_once 'routes/referrals.php';
//require_once 'routes/messages.php';
$app->get ( "/", function () {
	print_r ( $_SESSION ['tUser'] );
} );
$app->get("/gf/",function(){
	giveBonus("internship@p2g.org","100");
});
$app->get ( '/lessons', function () use($app) {
	$db = new Database ();
	$sql = "";
	if (checkStudent ()) {
		$sql = "select * from tutorSessions where StudentID=" . $_SESSION ['sUser'] ['studentID'] . " and IsActive=1";
	}
	if (checkTutor ()) {
		$sql = "select * from tutorSessions where TutorID=" . $_SESSION ['tUser'] ['tutorID'] . " and IsActive=1";
	}
	if (! empty ( $sql )) {
		$lesson = new stdClass ();
		$results = $db->Select ( $sql );
		$lesson->HoursUsed = "0";
		$lesson->pendingSessions = 0;
		foreach ( $results as $key => $lesson ) {

			$sql_sessions = "select * from PaymentRequests where SessionID=$lesson->ID order by Approved";
			$lesson->sessions = $db->Select ( $sql_sessions );
			foreach ( $lesson->sessions as $s ) {

				if ($s->StudentAccept == 1) {
					$lesson->HoursUsed += $s->HoursToPay * 1;
				} else if ($s->StudentAccept == 0) {
					$lesson->pendingSessions = 1;
				}
			}
			if (empty ( $lesson->HoursUsed )) {
				$lesson->HoursUsed = "0";
			}
			$sql_tutor = "select t.id,t.FirstName, t.LastName,p.* from tutors t join tutorProfiles p on t.id=p.TutorID where id=" . $lesson->TutorID;
			$lesson->tutor = $db->SelectFirst ( $sql_tutor );
			$sql_subject = "select * from subjects s join tutorSubjects ts on ts.SubjectID=s.id where ts.id=" . $lesson->SubjectID;
			$lesson->subject = $db->SelectFirst ( $sql_subject );
			$sql_tutor = "select s.id,s.firstName, s.lastName, s.Email,p.* from students s join studentProfiles p on s.id=p.StudentID where id=" . $lesson->StudentID;
			$lesson->student = $db->SelectFirst ( $sql_tutor );
			$sql_refund = "select * from refunds where lesson_id=" . $lesson->ID;
			$lesson->refund = $db->SelectFirst ( $sql_refund );
			$results [$key] = $lesson;
		}
		toJSON ( $app, $results );
	} else {
		toJSON ( $app, "[]" );
	}
} );

$app->get ( "/lessons/:id", function ($id) use($app) {
	$db = new Database ();
	$sql = "";
	if (! checkStudent () && ! checkTutor ()) {
		echo "{}";
		return;
	}

	$sql = "select * from tutorSessions where id=$id";
	$lesson = $db->SelectFirst ( $sql );
	$lesson->HoursUsed = 0;
	$lesson->pendingSessions = 0;
	$sql_sessions = "select * from PaymentRequests where SessionID=$lesson->ID order by Approved";
	$lesson->sessions = $db->Select ( $sql_sessions );
	foreach ( $lesson->sessions as $s ) {
		if ($s->StudentAccept == 1) {
			$lesson->HoursUsed += $s->HoursToPay * 1;
		} else if ($s->StudentAccept == 0) {
			$lesson->pendingSessions = 1;
		}
	}
	if (empty ( $lesson->HoursUsed )) {
		$lesson->HoursUsed = "0";
	}
	$sql_tutor = "select t.id,t.firstName, t.lastName, t.Email,p.* from tutors t join tutorProfiles p on t.id=p.TutorID where id=" . $lesson->TutorID;
	$lesson->tutor = $db->SelectFirst ( $sql_tutor );
	$sql_subject = "select * from subjects s join tutorSubjects ts on ts.SubjectID=s.id where ts.id=" . $lesson->SubjectID;

	$lesson->subject = $db->SelectFirst ( $sql_subject );
	$sql_tutor = "select s.id,s.firstName, s.lastName, s.Email,p.* from students s join studentProfiles p on s.id=p.StudentID where id=" . $lesson->StudentID;
	$lesson->student = $db->SelectFirst ( $sql_tutor );
	$sql_refund = "select * from refunds where lesson_id=" . $lesson->ID;
	$lesson->refund = $db->SelectFirst ( $sql_refund );
	toJSON ( $app, $lesson );
} );

$app->get ( "/lessons/:id/sessions", function ($id) use($app) {
	$db = new Database ();
	$sql = "";
	$sql = "select * from PaymentRequests where SessionID=$id";
	$results = $db->Select ( $sql );
	toJSON ( $app, $results );
} );
$app->get ( "/refunds", function () use($app) {
	$db = new Database ();
	$sql = "select * from refunds where status is not null";
	$results = $db->Select ( $sql );
	$refunds = array ();
	foreach ( $results as $r ) {
		$r->lesson = getLesson ( $r->lesson_id );
		$refunds [] = $r;
	}
	toJSON ( $app, $refunds );
} );
$app->get ( "/refunds/:id", function () use($app) {
} );
// update
$app->post ( '/refunds/:id', function ($id) use($app) {

	$db = new Database ();

	$refund = json_decode ( $app->request->getBody () );

	$update = "update refunds set status='" . $refund->status . "',hours=" . $refund->hours . ",total=" . $refund->total . ",updated=now() where id=" . $id;

	$db->update ( $update );
	if ($refund->status == "COMPLETED") {
		$update_lesson = "update  tutorSessions set isOpen=0 where id=" . $refund->lesson_id;
		$db->update ( $update_lesson );
	}
	$res = array (
			'msg' => 'SUCCESS'
	);

	toJSON ( $app, $res );
} );

$app->get ( "/sessions/rejects", function () use($app) {
	$db = new database ();
	$sql = "select * from PaymentRequests  where Approved = -1";

	$results = $db->Select ( $sql );
	$rejects = array ();
	foreach ( $results as $r ) {
		$r->lesson = getLesson ( $r->SessionID );
		$rejects [] = $r;
	}
	toJSON ( $app, $rejects );
} );
$app->get ( "/contactus", function () use($app) {
	$db = new database ();
	$sql = "select * from contactus";

	$results = $db->Select ( $sql );
	toJSON ( $app, $results );
} );
$app->get ( "/feedback", function () use($app) {
	$db = new database ();
	$sql = "select * from feedback";

	$results = $db->Select ( $sql );
	toJSON ( $app, $results );
} );

// refencences
$app->get ( "/references", function () use($app) {
	$db = new database ();
	$sql = "select * from `references` where IsActive=1";

	$results = $db->Select ( $sql );
	toJSON ( $app, $results );
} );
$app->post ( "/references", function () use($app) {
	$ref = json_decode ( $app->request->getBody () );
	$db = new database ();
	$sql = "insert into `references` (Title,IsActive) values('" . $ref->Title . "',1)";
	$results = $db->update ( $sql );
	$res = array (
			'msg' => 'SUCCESS'
	);

	toJSON ( $app, $res );
} );
$app->delete ( "/references/:id", function ($id) use($app) {
	$ref = json_decode ( $app->request->getBody () );
	$db = new database ();
	$sql = "update `references` set IsActive=0 where ID=" . $id;

	$results = $db->update ( $sql );
	$res = array (
			'msg' => 'SUCCESS'
	);

	toJSON ( $app, $res );
} );

$app->run ();

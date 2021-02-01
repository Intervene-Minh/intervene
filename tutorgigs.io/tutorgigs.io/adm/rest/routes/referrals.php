<?php
$app->group ( "/referrals", function () use ($app) {
	
	$app->get ( "/referrers", function () use ($app) {
		$sql = "select * from referrer";
		
		$db = new Database ();
		
		$results = $db->Select ( $sql );
		toJSON ( $app, $results );
	} );
	
	$app->get ( "/referrers/:id", function ($id) use ($app) {
		
		$sql = "select * from referrer where id=" . $id;
		$db = new Database ();
		
		$results = $db->Select ( $sql );
		toJSON ( $app, $results );
	} );
	$app->get ( "/referrers/:id/invites", function ($id) use ($app) {
		$sql = "select i.* from invites i join referrer r on i.ref_code=r.code where r.id = $id";
		$db = new Database ();
		
		$results = $db->Select ( $sql );
		toJSON ( $app, $results );
	} );
	$app->get ( "/referrers/:id/tutors", function ($id) use ($app) {
		$sql = "select t.* from tutors t join referrer r on t.RepID=r.code where r.id = $id";
		$db = new Database ();
		
		$results = $db->Select ( $sql );
		
		toJSON ( $app, $results );
	} );
	$app->get ( "/referrers/:id/students", function ($id) use ($app) {
		$sql = "select s.* from students s join referrer r on s.RepID=r.code where r.id = $id";
		$db = new Database ();
		
		$results = $db->Select ( $sql );
		
		toJSON ( $app, $results );
	} );
	$app->get ( "/payments(/:start_date(/:end_date))", function ($start_date = null, $end_date = null) use ($app) {
		
		if (empty ( $start_date )) {
			$start_date = date ( 'Y-m-01' );
		}
		if (empty ( $end_date )) {
			$end_date = date ( 'Y-m-t', strtotime ( $start_date ) );
		}
		$all = array ();
		$sql = "SELECT r.*, count(pr.ID) as total_student_transactions,sum((ts.Rate*pr.HoursToPay)) as total_student_payment FROM `PaymentRequests` pr join tutorSessions ts on ts.ID=pr.SessionID join students s on s.ID=ts.StudentID join referrer r on r.id=s.RepID 
			WHERE pr.StudentAccept=1 and pr.ApprovedOn>=date('" . $start_date . "') and pr.ApprovedOn<=date('" . $end_date . "') group by s.RepID";
		
		$sql_tutors = "SELECT r.*, count(pr.ID) as total_tutor_transactions,sum((ts.Rate*pr.HoursToPay)) as total_tutor_payment FROM `PaymentRequests` pr join tutorSessions ts on ts.ID=pr.SessionID join tutors t on t.ID=ts.TutorID join referrer r on r.id=t.RepID
			WHERE pr.StudentAccept=1 and pr.ApprovedOn>=date('" . $start_date . "') and pr.ApprovedOn<=date('" . $end_date . "') group by t.RepID";
		$db = new Database ();
		
		$results = $db->Select ( $sql );
		$t_results = $db->Select ( $sql_tutors );
		// $s=json_decode(json_encode($results),True)[0];
		// $t=json_decode(json_encode($t_results),true)[0];
		foreach ( $results as $val ) {
			$val->total_tutor_transactions = 0;
			$val->total_tutor_payment = 0;
			$all [$val->id] = $val;
		}
		
		foreach ( $t_results as $tval ) {
			
			if (! isset ( $all [$tval->id] )) {
				$tval->total_student_transactions = 0;
				$tval->total_student_payment = 0;
				$all [$tval->id] = $tval;
			} else {
				$all [$tval->id]->total_tutor_payment = $tval->total_tutor_payment;
				$all [$tval->id]->total_tutor_transactions = $tval->total_tutor_transactions;
			}
		}
		$r = array ();
		foreach ( $all as $val ) {
			$r [] = $val;
		}
		
		toJSON ( $app, $r );
	} );
} );
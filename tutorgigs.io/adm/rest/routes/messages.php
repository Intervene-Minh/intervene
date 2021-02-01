<?php
$app->group ( "/messages", function () use($app) {
	$app->get ( "/:type", function ($type) use($app) {
		$db = new Database ();
		$results = array ();
		
		if ($type == "students" || $type == "all") {
			$sql2 = "select ID, FirstName as first_name, LastName as last_name, Email as email, 'student' as type from students";
			$students = $db->Select ( $sql2 );
			foreach($students as $s){
				$results[]=$s;
			}
		}
		if ($type == "tutors" || $type == "all") {
			$sql = "select ID, FirstName as first_name, LastName as last_name, Email as email, 'tutor' as type from tutors";
			$tutors = $db->Select ( $sql );
			foreach($tutors as $s){
				$results[]=$s;
			}
		}
		toJSON ( $app, $results );
	} );
	
	$app->get ( "/students", function () use($app) {
		$sql = "select * from students";
		$db = new Database ();
		$results = $db->Select ( $sql );
		toJSON ( $app, $results );
	} );
	
	$app->post ( "/send", function () use($app) {
		// send message to all students selected

		$data = json_decode ( $app->request->getBody () );
		
		if (count ( $data->users ) > 0 && ! empty ( $data->body )) {
			foreach ( $data->users as $t ) {
				$type = "";
				$recType = "";
				if($t->type == "student") {
					$type = "3";
					$recType = "S";
				}
				if($t->type == "tutor") {
					$type = "2";
					$recType = "T";
				}
				$db = new Database ();
				$sql = "INSERT INTO `messages`(`MailBoxID`, `SenderID`, `RecipientID`, `Subject`, `Body`, `SentOn`,`SendType`, `RecType`)
				 VALUES (" . $t->ID . ",0," . $t->ID . ",'" . $data->subject . "','" . $data->body . "',NOW(),'A','" . $recType . "')";
				$db->update ( $sql );

				$notification = array(
					'notification_type' => "MESSAGE", 
					'notification_header' => $data->subject,
					'notification_body' => $data->body,
					'notification_from' => "A",
					'notification_to' => $recType,
					'notification_from_id' => 0,
					'notification_to_id' => $t->ID
				);
				addNotification($notification);


				// $d ['message'] = $data->body;
				// $d ['first_name'] = $t->first_name;
				// $d ['last_name'] = $t->last_name;
				// $d['type']=$t->type;

				$message_vars = array(
					"message_subject" => $data->subject,
					"message_body" => $data->body,
					"message_receiver" => $t->first_name
				);

				sendTemplateEmail ( $data->subject, $message_vars, $t->email, "default" );
			}
		}
		$res = array (
				'msg' => 'SUCCESS' 
		);
		
		toJSON ( $app, $res );
	} );
} );

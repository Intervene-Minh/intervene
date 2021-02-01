<?php

function checkStudent() {
	if (isset ( $_SESSION ['sUser'] )) {

		return true;
	}
	return false;
}
function checkTutor() {
	if (isset ( $_SESSION ['tUser'] )) {
		return true;
	}
	return false;
}
function checkAdmin() {
}
function generateVerifyCode() {
	return md5 ( uniqid ( rand (), true ) );
}
function toJSON($app, $content) {
	$response = $app->response;
	$response ['Content-Type'] = 'application/json';
	$response->body ( json_encode ( $content ) );
}
function getLesson($id) {
	$db = new Database ();
	$sql = "";
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
	return $lesson;
}
function sendTemplateEmail($subject,$vars,$email, $template="default"){
	if(!empty($template)){

		$body=file_get_contents(dirname(__FILE__)."/email_templates/".$template.".html");

		$vars['message_time'] = date("h:i:s a");
		$vars['message_date'] = date("Y-m-d");
		$vars['site_url'] = 'site_url';
		foreach((array) $vars as $key=>$val){
			$body=str_replace("{".$key."}", $val, $body);
		}

	return	sendEmail($subject, $body, $email);

}
return false;

}
function sendEmail($subject, $body, $email, $template=null) {



	$mail = new PHPMailer ();
	$mail->From = "tutoring@p2g.org";
	$mail->FromName = "P2G Admin";
	$mail->Subject = $subject;
	$mail->addAddress ( $email );
	$mail->AddReplyTo('tutoring@p2g.org', 'P2G Admin');
	$mail->isHTML ( true );
	$mail->Body = $body;
	$mail->AltBody = $body;
	//$mail->addEmbeddedImage(dirname(__FILE__)."/email_templates/img/p2g25b.jpg", "logo", "p2g25b.jpg");
	if (! $mail->send ()) {
		print_r($mail->ErrorInfo);
		die;
		return false;
	} else {
		return true;
	}
}
function addNotification($notification_data = array()) {
	if(!empty($notification_data)) {
		$body = substr($notification_data['notification_body'], 0, 25);
		$body = addslashes($body)." ...";

		$sql = "INSERT INTO notifications (
					notification_type,
					notification_header,
					notification_body,
					notification_from,
					notification_to,
					added_on,
					status,
					notification_from_id,
					notification_to_id
				)
				VALUES (
					'".$notification_data['notification_type']."',
					'".$notification_data['notification_header']."',
					'".$body."',
					'".$notification_data['notification_from']."',
					'".$notification_data['notification_to']."',
					NOW(),
					'0',
					'".$notification_data['notification_from_id']."',
					'".$notification_data['notification_to_id']."'
				)";
		$db = new Database();
		$db->update($sql);
	}
}

function giveBonus($email, $amount){

$grClient = new GeniusReferrals\GRPHPAPIClient('pathways2greatness@gmail.com', '3b824b5fa9e2856a49cb15c6e02690f539a9b17d');
//get advocate id
$jsonResponse = $grClient->getAdvocates('p2g-account-1', 1, 10, 'email::'.$email, null);
$aryResponse = json_decode($jsonResponse);
if(!empty($aryResponse) && $aryResponse->data->total >0){
	$advocate=$aryResponse->data->results[0];
	//print_r($advocate);
	//print_r($advocate->token);
	$arrParams = array(
        'bonus' => array(
            'advocate_token'       => $advocate->token, //the advocate who made the payment
            'reference'            => rand(1000000, 9999999), //A reference number, could be the payment id
            'amount_of_payments'   => 1,
            'payment_amount'       => $amount //the payment amount placed by the referred advocate
        )
    );
    //trying to give a bonus to the advocate's referrer
    $strResponse = $grClient->postBonuses('p2g-account-1', $arrParams);
    $intResponseCode = $grClient->getResponseCode();
    if($intResponseCode == 201 ||$intResponseCode == 204){
    //  echo "success";  // bonus given to the advocate's referrer
    }
    else{
        // there is not need to give a bonus to the advocate's referrer
			//	echo "fail";
			//	print_r($strResponse);
    }
}

}
?>

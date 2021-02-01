<?php

$app->group('/payments', function() use($app){

	$app->get('/',function() use($app){
		$payments=array();
		$db = new Database ();
		$select="select * from PaymentRequests where Approved=1";
		$results=$db->Select($select);

		foreach($results as $key=>$payment){

			$payment->lesson=getLesson($payment->SessionID);

			$payment->total=$payment->lesson->Rate*$payment->HoursToPay;
			$payments[]=$payment;

		}
		toJSON ( $app, $payments);
	});

	$app->put('/:id',function($id) use($app){
		$db = new Database ();
		$db->update("update PaymentRequests set Paid=1, PaidOn=NOW() where ID=".$id);
		$res = array (
				'msg' => 'SUCCESS'
		);
		$select="select * from PaymentRequests where ID=".$id;
		$payment=$db->SelectFirst($select);

		//foreach($results as $key=>$payment){

			$payment->lesson=getLesson($payment->SessionID);

			$payment->total=$payment->lesson->Rate*$payment->HoursToPay;
			//$payments[]=$payment;

	//	}
		giveBonus($payment->lesson->student->Email, $payment->total);
		giveBonus($payment->lesson->tutor->Email, $payment->total);


		toJSON ( $app, $res );
	});



});

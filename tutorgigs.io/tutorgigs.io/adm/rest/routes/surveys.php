<?php
$app->group ( "/surveys", function () use($app) {
	
	$app->get ( "/", function () use($app) {
		$sql = "select * from surveys ";
		$db = new Database ();
		
		$results = $db->Select ( $sql );
		toJSON ( $app, $results );
	} );
	$app->post ( "/", function () use($app) {
		$res = array (
				'msg' => 'SUCCESS' 
		);
		$data = json_decode ( $app->request->getBody () );
		if (! empty ( $data->link )) {
			$sql = "insert into surveys (link,audience) values('$data->link','$data->audience')";
			$db = new Database ();
			
			$results = $db->update ( $sql );
		}
		toJSON ( $app, $res );
	} );
	$app->delete ( "/:id", function ($id) use($app) {
		if (! empty ( $id )) {
			$sql = "delete from surveys where id=$id";
			$db = new Database ();
			$results = $db->update ( $sql );
		}
		$res = array (
				'msg' => 'SUCCESS' 
		);
	} );
} );

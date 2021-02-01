<?php

 $app->group ( "/subjects", function () use($app) {
	$app->get("/",function() use($app){
		echo "hello";
	});
	$app->get("/requests",function() use($app){
		$rsp=array();
		$db=new Database();
		$sql="select * from subjectRequests";
		$requests=$db->Select($sql);
		foreach($requests as $request){
			if($request->RequestorType=="T"){
				$sql="select * from tutors where ID=:id";
				$request->requestor=$db->SelectFirst($sql,array("id"=>$request->RequestorID));
			}
			if($request->RequestorType=="S"){
				$sql="select * from students where ID=".$request->RequestorID;
				$request->requestor=$db->SelectFirst($sql,array("id"=>$request->RequestorID));
			}
			$rsp[]=$request;
		}
		toJSON ( $app, $rsp );
	});
});
?>
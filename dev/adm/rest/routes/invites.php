<?php


include_once(dirname(__FILE__)."/../../classes/class.DevEnv.php");


$app->group ( "/invites", function () use($app) {


	$app->get("",function() use($app) {
		$db=new Database();
		$results=array();
		$sql="select * from invites";
		$invites = $db->Select ( $sql );
		foreach($invites as $i) {
			$results[]=$i;
		}
		toJSON ( $app, $results );
	});


	$app->delete("/:id",function($id) use($app) {
		$sql="delete from invites where id=:id";
		if(!empty($id)){
			$db=new Database();
			$db->update($sql,array("id"=>$id));
			
		}
		$res = array (
				'status' => 'SUCCESS'
		);
		toJSON ( $app, $res );
		
	});


	$app->post("/accept/:id", function($id) use($app) {
		$res = array (
				'status' => 'FAIL'
		);
		if(!empty($id)){
			$db=new Database();
			$select="select * from invites where id=:id";
			$result=$db->selectFirst($select,array('id'=>$id));
			if(!empty($result)) {
				//create invite_code
				if(empty($result->invite_code)) {
					$invite_code=generateVerifyCode();
				} else {
					$invite_code=$result->invite_code;
				}
				$sql="update invites set approved=1, approved_on=now(), invite_code=:invite_code where id=:id";
				$db->update($sql,array('invite_code'=>$invite_code,'id'=>$id));

				$link="";
				$subject="";
				$template=null;
				if(strtolower($result->type)=="tutor") {
					$link=$_SERVER['HTTP_HOST']."/tutorsignup.php?ref=".$invite_code;
					$subject="Welcome to P2G Lets gets started Tutoring!";
					$template="tutor_accept";
				} else {
					$link=$_SERVER['HTTP_HOST']."/studentsignup.php?ref=".$invite_code;
					$subject="Next Steps: Get started Learning with Pathways 2 Greatness";
					$template="student_accept";
				}
				$content=array();
				$content['first_name']=$result->first_name;
				$content['last_name']=$result->last_name;
				$content['link']=$link;

				if(!sendTemplateEmail($subject,$content,$result->email,$template)) {
					$res = array (
						'status' => 'FAIL','msg'=>'email failed'
					);
				} else {
					$res = array (
						'status' => 'SUCCESS'
					);
				}

				// send to test emails if in dev env
				if (DevEnv::is_in_dev_env()) {
					$arr_emails = DevEnv::arr_get_test_email_addresses();
					for($n=0;$n<count($arr_emails);$n++) {
						$email = $arr_emails[$n];
						sendTemplateEmail($subject,$content,$email,$template);
					}
				}
			}
		}
		toJSON ( $app, $res );
	});
});
?>

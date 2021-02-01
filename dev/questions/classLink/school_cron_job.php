<?php
require_once 'function.php';
$arrGrade=[];
$payInfoUpdate=[];
$dataInfo = serialize($payInfoUpdate);
$type="Paper-based assessments only";
$arrSchool=[];
$SchoolPass= md5(123456);

// SEELCT QUERY GET KEYS
$str ="SELECT * FROM `class_link_keys` WHERE isActive=1";

$qr=mysql_query($str);

while($row = mysql_fetch_assoc($qr)){

		$client_id=$row['client_id'];
		$client_secret= $row['client_secret'];
		$endpoint_url= $row['endpoint_url'];

		$oneRoster = new OneRoster($client_id, $client_secret);

		$SchoolArr =	_getRosterData($endpoint_url."ims/oneroster/v1p1/schools");
		
		foreach ($SchoolArr['orgs'] as $row) {

			$sourced_id=$row['sourcedId'];
			$schoolName=$row['name'];
			$school_name = preg_replace('/\s+/', '', $schoolName);
			$schoolMail=strtolower($school_name).'@intervene.io';
			 $str ="SELECT name,intervene_grade_id FROM `classlink_grade_x_terms` WHERE `intervene_grade_id` > 0 && `active`=1 && school_source_id='".$sourced_id."'";
			 $qr=mysql_query($str);
			while($row = mysql_fetch_assoc($qr)){
				$arrGrade[$sourced_id][] = array('geade_id'=>$row['intervene_grade_id'],'grade_name'=>$row['name']);
				$arrSchool[$sourced_id]= array('school_name'=>$schoolName,'school_source_id'=>$sourced_id,'school_email'=>$schoolMail);


				}
	
}
}


// INSERT SCHOOL IN DB
if(count($arrSchool) >0 ) {

foreach ($arrSchool as $key => $row) {

		$schoolName= $row['school_name'];
		$sourced_id=$row['school_source_id'];
		$schoolMail=$row['school_email'];
		//check duplicate school
		if(checkDup("schools","`sourced_id`= '".$sourced_id."'")==0){
			//INSERT QUERY
			$str="INSERT INTO `schools` SET `SchoolName`='".$schoolName."', `sourced_id`= '".$sourced_id."',`district_id`=0, `master_school_id`=0,`SchoolPass`='".$SchoolPass."',`SchoolMail`='".$schoolMail."', `status`=1, `school_classlink`=1";
				
				mysql_query($str);
				
				$schoolId = mysql_insert_id();
				$qty = count($arrGrade[$sourced_id]);
				$price = ($type == 'Paper-based assessments only') ? 325 : 500;
				$amount = (int) $qty * $price;

				//INSERT purchases DETAILS
			$str="INSERT INTO `purchases` SET `schoolID`=$schoolId, `email`='".$schoolMail."', `type`='".$type."', `amount`='".$amount."',`role`=0,`payInfo`=''";

				mysql_query($str);

				$purchaseId = mysql_insert_id();


				//get array grade data
	   	foreach ($arrGrade[$sourced_id] as $grade) {
			//INSERT purchases meta DETAILS
	   	 $str="INSERT INTO `purchasemeta` SET `purchaseId`= $purchaseId, `termId`='".$grade['geade_id']."'";

			mysql_query($str);
			//INSERT school permissions   DETAILS
			$str="INSERT INTO `school_permissions` SET `school_id`=$schoolId, `grade_level_id`='".$grade['geade_id']."',`grade_level_name`='".$grade['grade_name']."',`permission`='data_dash'";

			mysql_query($str);

			//telpas permission
		 	if(checkDup("tbl_telpas_access","user_id=$schoolId && role='school' && school_id=$schoolId")==0){

		 		mysql_query("INSERT INTO  tbl_telpas_access SET user_id='$schoolId',role='school',school_id=".$schoolId);
		 	 }
				//intervene permission
		 	 if(checkDup("user_intervention_access","user_id=$schoolId && role='school' && school_id=$schoolId")==0){

		 		mysql_query("INSERT INTO  user_intervention_access SET user_id='$schoolId',role='school',school_id=$schoolId");
		 	 }
		  }
		}
	}
}
?>
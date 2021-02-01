<?php

require_once 'function.php';

$str ="SELECT * FROM `class_link_keys` WHERE isActive=1";
$qr=mysql_query($str);

while($row = mysql_fetch_assoc($qr)){

		$client_id=$row['client_id'];
		$client_secret= $row['client_secret'];
		$endpoint_url= $row['endpoint_url'];

		$oneRoster = new OneRoster($client_id, $client_secret);

		$data =	_getRosterData($endpoint_url."ims/oneroster/v1p1/schools/3/classes/31763/teachers");




pre($data);


	/*	foreach ($School['orgs'] as $row) {

			if($row['type'] == 'school'){

				$sourced_id=$row['sourcedId'];
				$schoolName=$row['name'];
				$school_name = preg_replace('/\s+/', '', $schoolName);
				$schoolMail=strtolower($school_name).'@classInterven.io';
				//check duplicate school
				if(checkDup("schools","`sourced_id`= '".$sourced_id."'")==0){
					//INSERT QUERY*
				$str="INSERT INTO `schools` SET `SchoolName`='".$schoolName."', `sourced_id`= '".$sourced_id."',`district_id`=0, `master_school_id`=0,`SchoolPass`='".$SchoolPass."',`SchoolMail`='".$schoolMail."', `status`=1, `school_classlink`=1";
				 mysql_query($str);
		}
		


	}
}
*/
}
?>
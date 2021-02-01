<?php
require_once 'function.php';
$str ="SELECT * FROM `class_link_keys` WHERE isActive=1";
$qr=mysql_query($str);
$datetm = date('Y-m-d H:i:s');
while($row = mysql_fetch_assoc($qr)){

		$client_id=$row['client_id'];
		$client_secret= $row['client_secret'];
		$endpoint_url= $row['endpoint_url'];

		$oneRoster = new OneRoster($client_id, $client_secret);

		$teacher =	_getRosterData($endpoint_url."ims/oneroster/v1p1/teachers");

		$Tpass=md5(123456);

		foreach ($teacher['users'] as $row) {

				$sourced_id=$row['sourcedId'];
				$Tuname=$row['username'];
				$teacher_email=$row['email'];

				$teacher_first_name = $row['givenName'];

				$teacher_last_name= $row['middleName'];

				$school_source_ID= $row['orgs'][0]['sourcedId'];

				/*school intervene ID */
				$schoolArr= getfiled("schools","SchoolId","`sourced_id`= $school_source_ID && school_classlink=1");
				$School_interveneID=$schoolArr['SchoolId'];
				if(checkDup("users","`sourced_id`= '".$sourced_id."'")==0){
					/*INSERT QUERY*/

				$str="INSERT INTO `users` SET `is_subdmin`='no',`user_name`='".$Tuname."',`email`='".$teacher_email."', `password`='".$Tpass."',`first_name`='".$teacher_first_name."',`last_name`='".$teacher_last_name."',`school`='".$School_interveneID."',`sourced_id`=$sourced_id,`role`=1,`status`=1,`date_registered`='".$datetm."', `district_id`=0, `master_school_id`=0, `q_remaining`=0 ,`teacher_classlink`=1";

				 mysql_query($str);
			}
	}
}
?>
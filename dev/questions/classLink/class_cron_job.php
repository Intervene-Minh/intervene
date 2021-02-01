<?php

require_once 'function.php';

$str ="SELECT * FROM `class_link_keys` WHERE isActive=1";
$qr=mysql_query($str);

while($row = mysql_fetch_assoc($qr)){

		$client_id=$row['client_id'];
		$client_secret= $row['client_secret'];
		$endpoint_url= $row['endpoint_url'];

		$oneRoster = new OneRoster($client_id, $client_secret);

		$classes =	_getRosterData($endpoint_url."ims/oneroster/v1p1/classes");



		foreach ($classes['classes'] as $row) {

				$sourced_id=$row['sourcedId'];
				$className=$row['title'];
				$grade_id=$row['course']['sourcedId'];
				$school_id=$row['school']['sourcedId'];


				if(checkDup("classlink_class_x_classes","`sourced_id`= '".$sourced_id."'")==0){
					//INSERT QUERY
				 $str="INSERT INTO `classlink_class_x_classes` SET `name`='".$className."', `sourced_id`= '".$sourced_id."',`grade_sourcedId`='".$grade_id."', `school_sourcedId`='".$school_id."'";
				 mysql_query($str);
	
		}
}
}
?>
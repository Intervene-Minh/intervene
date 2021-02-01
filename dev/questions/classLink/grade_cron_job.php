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

		$gradesArr =	_getRosterData($endpoint_url."ims/oneroster/v1p1/courses");
		//pre($gradesArr);


		foreach ($gradesArr['courses'] as $row) {


				$sourced_id	        =		    $row['sourcedId'];
				$gradeName	        =			$row['title'];
				$school_source_ID   =           $row['org']['sourcedId'];
				if(checkDup("classlink_grade_x_terms","`sourced_id`='".$sourced_id."'")==0){

				//INSERT QUERY
					$str="INSERT INTO `classlink_grade_x_terms` SET `name`='".$gradeName."',`sourced_id`='".$sourced_id."',
					`school_source_id`='".$school_source_ID."'";
					mysql_query($str);

					

				
			}  
	}
}
?>
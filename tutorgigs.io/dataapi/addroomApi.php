<?php
include('config/connection.php');
extract($_REQUEST);




/*

$str="INSERT INTO `newrow_rooms` SET 
	`newrow_room_id`= '".$NewrowRoomId."', 
	`ses_tutoring_id`='".$intervene_ses_id."',
	`name`='".$roomName."',
`description` ='".$roomName."',
`tp_id`='".$intervene_ses_id."',
";

*/



echo $str="INSERT INTO `newrow_rooms` SET 
	`newrow_room_id`= 21, 
	`ses_tutoring_id`=12,
	`name`='testrooom',
`description` ='testrooom',
`tp_id`=23434,
";
    $query = mysql_query($str); 

?>
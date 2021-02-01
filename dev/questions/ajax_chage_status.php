<?php
require_once 'inc/connection.php';
extract($_REQUEST);

if($id > 0){

	 $str="UPDATE `classlink_grade_x_terms` SET `active`=$status WHERE `id`=".$id;
	mysql_query($str);
	echo mysql_affected_rows();

}

?>
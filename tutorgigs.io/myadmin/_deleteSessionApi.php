<?php
//require'curl.function.php';
extract($_REQUEST);
ini_set("date.timezone", "CST6CDT");
$con = new mysqli('localhost', 'mhl397', 'Developer2!','lonestaar');

if ($con ->connect_errno) {
  echo "Failed to connect to MySQL: " . $con ->connect_error;
  exit();
}
 

 //$seID=implode(',',array(80,85));
if(!empty($seID)){

		$str="DELETE FROM int_schools_x_sessions_log WHERE 
		drhomework_ref_id IN($seID) AND type='drhomework'AND Tutoring_client_id='Drhomework123456'";

		$con->query($str);

		$str="DELETE FROM dr_tutoring_info WHERE drhomework_ses_id IN($seID)'
		AND Tutoring_client_id='Drhomework123456'";
		$con->query($str);

		$ar['res']='Delete Data';
}
else{

	$ar['res']='Session ID Not Find';
}
echo json_encode($ar);
?>
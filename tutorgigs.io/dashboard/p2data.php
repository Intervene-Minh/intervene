<?php 
/****
$host = "localhost";
		$username = "";
		$password = "aE&ZidJX)8bl";
		$dbname = "ptwogorg_main";

			if (DevEnv::is_in_dev_env() && !DevEnv::is_in_test_env()&&!DevEnv::is_localhost()) {
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$username = "p2g_com_virtual";
		$password = "rspiullsa";
		$host = "199.241.142.237";
		$dbname = "p2g_com_virtual";

		$username = "p2g_test_dev_240";
		$password = "fmyaufpma";
		$host = "199.241.142.237";
		$dbname = "p2g_test_dev_com_virtual";
	} else {
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$host = "localhost";
		$username = "ptwogorg_prod";
		$password = "aE&ZidJX)8bl";
		$dbname = "ptwogorg_main";

	}
================
$host = "localhost";
		$username = "ptwogorg_prod";
		$password = "aE&ZidJX)8bl";
		$dbname = "ptwogorg_main";

**/





 $host = "localhost";
		$username = "ptwogorg_prod";
		$password = "aE&ZidJX)8bl";
		$dbname = "ptwogorg_main";

$con_1= @mysql_connect('localhost', 'ptwogorg_prod', 'aE&ZidJX)8bl');
//mysql_query('SET NAMES utf8');
$pdb=mysql_select_db('ptwogorg_main', $con_1);

if($pdb){
echo 'Connnected db';
}else{
	echo 'Not Connnected db';

}

 //$sql="SELECT * FROM `questions` WHERE `TestID` = 5";
 $sql=" SELECT * FROM `questions` WHERE `ID` =28";
 $res= mysql_fetch_assoc(mysql_query($sql));
   //print_r($res);




 ///////////MYSQLI//////////
 //$mysqli = new mysqli("localhost", "my_user", "my_password", "world");

 $mysqli  = new mysqli("localhost", "ptwogorg_prod", "aE&ZidJX)8bl", "ptwogorg_main");
 /* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}


 $query=" SELECT * FROM `questions` WHERE `ID` =29";
$result = $mysqli->query($query);

echo 'Test Data.====<br/>';

/* numeric array */
//$row = $result->fetch_array(MYSQLI_NUM);
$row = $result->fetch_array(MYSQLI_ASSOC);
 print_r($row);



?>
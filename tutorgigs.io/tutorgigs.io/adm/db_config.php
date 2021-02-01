<?php 

	// -- Connection info for MySQL database 
	include_once(dirname(__FILE__)."/classes/class.DevEnv.php");
	
	if (DevEnv::is_in_dev_env() && !DevEnv::is_in_test_env()) {
		$username = "p2g_com_virtual"; 
		$password = "rspiullsa"; 
		$host = "199.241.142.237"; 
		$dbname = "p2g_com_virtual"; 

		$username = "p2g_test_dev_240"; 
		$password = "fmyaufpma"; 
		$host = "199.241.142.237"; 
		$dbname = "p2g_test_dev_com_virtual";
	} else {
		$host = "localhost";
		$username = "intervenedevUser";
		$password = 'Te$btu$#4f56#';
		$dbname = "ptwogorg_main";

	}

	//$tServer="box833.bluehost.com";
	$tServer = $host;
	$tUser = $username;          
	$tPass = $password;
	$tDB = $dbname;
?>

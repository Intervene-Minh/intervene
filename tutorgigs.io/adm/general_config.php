<?php

	include_once(dirname(__FILE__)."/classes/class.DevEnv.php");

		
	// if in dev but not est dev
	if (DevEnv::is_in_dev_env() && !DevEnv::is_in_test_env()) {
		$site_name="http://".$_SERVER['HTTP_HOST']."/adm";
	} else {
		
		if(isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
		} else {
			header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			exit();
		}
		$site_name="https://".$_SERVER['HTTP_HOST']."/adm";
	}


	if ($_SERVER['HTTP_HOST'] == "p2g-dev.serverdatahost.com" || $_SERVER['HTTP_HOST'] == "p2g-test-dev.serverdatahost.com") {
	}
	error_reporting(E_ALL & ~E_NOTICE);
	

?>

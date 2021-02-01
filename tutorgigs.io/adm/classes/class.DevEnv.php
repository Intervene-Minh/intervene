<?php



// checks for when we are in dev env
class DevEnv {

	public static function get_return_back_url() {
		if (self::is_in_dev_env() && !self::is_in_test_env()) {
			$url = "http://".$_SERVER['HTTP_HOST'];
		} else {
			$url = "https://".$_SERVER['HTTP_HOST'];
		}
		return $url;
	}

	public static function set_error_reporting() {
		error_reporting(E_ALL & ~E_NOTICE);
	}

	public static function is_in_test_env() {

		if ($_SERVER['HTTP_HOST'] == "test.p2g.org") {
			return true;
		}
		return false;
	}


	// for all tests
	public static function is_in_dev_env() {

		// try catch seems to fix issue of crashing on test env
		try {
			$hostname = php_uname('n');
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		//print "hostname is $hostname\n";
		/*
		print "in here\n";
		exit(0);
		*/
		//self::set_error_reporting();
		/*
		print "in here\n";
		exit(0);
		*/

		if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == "p2g-dev.serverdatahost.com" || $_SERVER['HTTP_HOST'] == "test.p2g.org" || $_SERVER['HTTP_HOST'] == "p2g-test-dev.serverdatahost.com")) {
			return true;
		} else if ($hostname == "agammemnon") {
			return true;
		}
			
		return false;
	}

	public static function arr_get_test_email_addresses() {

		$arr = Array();
		if(self::is_in_dev_env()) {
		}

		$arr[] = "dino.bartolome@gmail.com";
		
		return $arr;
	}
}
